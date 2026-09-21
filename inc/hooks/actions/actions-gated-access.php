<?php
	// Walk all blocks in post content recursively, including inner blocks.
	function hy_gated_access_get_all_blocks($content) {
		$all   = [];
		$stack = parse_blocks($content);
		while (!empty($stack)) {
			$block = array_shift($stack);
			$all[] = $block;
			if (!empty($block["innerBlocks"])) {
				$stack = array_merge($block["innerBlocks"], $stack);
			}
		}
		return $all;
	}


	// Find the redirect page ID for a given GF form ID.
	// Checks wp_options first, falls back to scanning post content (self-healing cache).
	function hy_gated_access_find_redirect_page($form_id) {
		$mappings         = get_option("hy_gated_access_mappings", []);
		$redirect_page_id = (int) ($mappings[$form_id] ?? 0);

		if ($redirect_page_id) {
			return $redirect_page_id;
		}

		// Cache miss — scan published posts for a gated-access-form block with this form ID.
		global $wpdb;
		$post_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts}
				 WHERE post_status = 'publish'
				   AND post_content LIKE %s",
				"%hiyield/gated-access-form%"
			)
		);

		foreach ($post_ids as $post_id) {
			$post = get_post((int) $post_id);
			if (!$post) continue;

			foreach (hy_gated_access_get_all_blocks($post->post_content) as $block) {
				if ($block["blockName"] !== "hiyield/gated-access-form") continue;

				$data    = $block["attrs"]["data"] ?? [];
				$fid     = (int) ($data["block_gated_access_form_gform_id"]      ?? 0);
				$page_id = (int) ($data["block_gated_access_form_redirect_page"] ?? 0);

				if ($fid && $page_id) {
					$mappings[$fid] = $page_id;
				}

				if ($fid === $form_id) {
					$redirect_page_id = $page_id;
				}
			}
		}

		if (!empty($mappings)) {
			update_option("hy_gated_access_mappings", $mappings, false);
		}

		return $redirect_page_id;
	}


	// Keep the wp_options mapping up to date when a post containing the block is saved.
	function hy_gated_access_update_mappings($post_id, $post) {
		if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) return;
		if (wp_is_post_revision($post_id))                return;
		if (!($post instanceof WP_Post))                  return;
		if ($post->post_status !== "publish")             return;
		if (!has_block("hiyield/gated-access-form", $post->post_content)) return;

		$mappings = get_option("hy_gated_access_mappings", []);

		foreach (hy_gated_access_get_all_blocks($post->post_content) as $block) {
			if ($block["blockName"] !== "hiyield/gated-access-form") continue;

			$data    = $block["attrs"]["data"] ?? [];
			$fid     = (int) ($data["block_gated_access_form_gform_id"]      ?? 0);
			$page_id = (int) ($data["block_gated_access_form_redirect_page"] ?? 0);

			if ($fid && $page_id) {
				$mappings[$fid] = $page_id;
			}
		}

		update_option("hy_gated_access_mappings", $mappings, false);
	}
	add_action("save_post", "hy_gated_access_update_mappings", 10, 2);


	// Remove stale mappings when a post is deleted or trashed.
	function hy_gated_access_cleanup_mappings($post_id) {
		$post     = get_post($post_id);
		$mappings = get_option("hy_gated_access_mappings", []);
		$original = count($mappings);

		// Remove mappings where this post is the redirect destination.
		$mappings = array_filter($mappings, fn($page_id) => $page_id !== $post_id);

		// Remove mappings for any form IDs hosted on this post.
		if ($post instanceof WP_Post && has_block("hiyield/gated-access-form", $post->post_content)) {
			foreach (hy_gated_access_get_all_blocks($post->post_content) as $block) {
				if ($block["blockName"] !== "hiyield/gated-access-form") continue;
				$fid = (int) ($block["attrs"]["data"]["block_gated_access_form_gform_id"] ?? 0);
				if ($fid) unset($mappings[$fid]);
			}
		}

		if (count($mappings) !== $original) {
			update_option("hy_gated_access_mappings", $mappings, false);
		}
	}
	add_action("delete_post", "hy_gated_access_cleanup_mappings");
	add_action("trash_post",  "hy_gated_access_cleanup_mappings");


	// Intercept GF confirmation for the legacy page-based gating flow.
	function hy_gated_access_handle_confirmation($confirmation, $form, $entry, $ajax) {
		$form_id = (int) $form["id"];

		$redirect_page_id = hy_gated_access_find_redirect_page($form_id);

		if (!$redirect_page_id) {
			return $confirmation;
		}

		$redirect_post = get_post($redirect_page_id);
		if (!($redirect_post instanceof WP_Post) || $redirect_post->post_status !== "publish") {
			return $confirmation;
		}

		$redirect_url = get_permalink($redirect_page_id);
		if (!$redirect_url) {
			return $confirmation;
		}

		$token = wp_hash(uniqid("hy_gated_", true) . $form_id . $redirect_page_id);
		set_transient("hy_gated_access_" . $token, $redirect_page_id, DAY_IN_SECONDS);

		return ["redirect" => add_query_arg("access", $token, $redirect_url)];
	}
	add_filter("gform_confirmation", "hy_gated_access_handle_confirmation", 20, 4);


	// Read the download file URL directly from the page's block attributes.
	function hy_gated_access_get_file_url($page_id) {
		$post = get_post($page_id);
		if (!($post instanceof WP_Post) || !has_block("hiyield/gated-access-download-hero", $post->post_content)) {
			return "";
		}

		foreach (hy_gated_access_get_all_blocks($post->post_content) as $block) {
			if ($block["blockName"] !== "hiyield/gated-access-download-hero") continue;

			$raw = $block["attrs"]["data"]["block_gated_access_download_hero_file"] ?? "";
			if (!$raw) continue;

			// ACF may store either the attachment ID or the URL depending on version.
			if (filter_var($raw, FILTER_VALIDATE_URL)) {
				return esc_url_raw($raw);
			}

			$url = wp_get_attachment_url((int) $raw);
			if ($url) return esc_url_raw($url);
		}

		return "";
	}


	// Set Referrer-Policy: no-referrer on download pages to prevent token leakage
	// via the Referer header to any third-party assets on the page.
	function hy_gated_access_set_referrer_policy() {
		if (!is_page()) return;
		$post = get_queried_object();
		if (!($post instanceof WP_Post)) return;
		if (!has_block("hiyield/gated-access-download-hero", $post->post_content)) return;
		header("Referrer-Policy: no-referrer");
	}
	add_action("send_headers", "hy_gated_access_set_referrer_policy");


	/**
	 * AJAX: verify an access token and return the download URL.
	 * Tokens are single-use — deleted on successful verification.
	 * Token is received via POST body to avoid server log exposure.
	 *
	 * @param string token   — from the URL fragment #access=TOKEN
	 * @param int    page_id — scopes token to prevent cross-page reuse
	 */
	function hy_verify_gated_access() {
		$token   = sanitize_text_field(wp_unslash($_POST["token"]   ?? ""));
		$page_id = (int) ($_POST["page_id"] ?? 0);

		if (!$token || !$page_id) {
			wp_send_json_error(["valid" => false]);
		}

		$stored_page_id = (int) get_transient("hy_gated_access_" . $token);

		if ($stored_page_id !== $page_id) {
			wp_send_json_error(["valid" => false]);
		}

		$file_url = hy_gated_access_get_file_url($page_id);

		if (!$file_url) {
			wp_send_json_error(["valid" => false]);
		}

		delete_transient("hy_gated_access_" . $token);

		wp_send_json_success([
			"valid"        => true,
			"download_url" => $file_url,
		]);
	}
	add_action("wp_ajax_hy_verify_gated_access",        "hy_verify_gated_access");
	add_action("wp_ajax_nopriv_hy_verify_gated_access", "hy_verify_gated_access");




