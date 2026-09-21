<?php

	########################################################################################################################
	# ========================= ENQUEUE SCRIPTS ============================================================================
	########################################################################################################################

	function wordpress_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

		// Deregister old jQuery version that bundles with Wordpress:
		// wp_deregister_script("jquery");

		// Main Js enqueued in footer. All functions being run inside a window.onload event
		if($main_script_uri = get_theme_file_uri("assets/dist/js/main.js"))
			wp_enqueue_script("main", $main_script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);

		if($utilities_script_uri = get_theme_file_uri("assets/dist/js/utilities.js"))
		wp_enqueue_script("utilities", $utilities_script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);

		if($mobile_menu_uri = get_theme_file_uri("assets/dist/js/mobile-menu.js"))
			wp_enqueue_script("mobile_menu", $mobile_menu_uri, [], $build_version, false);	

		// Remove WP Embed
		wp_deregister_script("wp-embed");

	}

	// Enqueue Basic Cookie Consent Script
	// https://github.com/orestbida/cookieconsent
	function cookie_consent_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

		$cookie_popup_settings = json_encode(
			[
				"enabled" => get_field("option_cookie_popup_enabled", "options"),
				"title" => get_field("option_cookie_popup_title", "options"),
				"description" => get_field("option_cookie_popup_description", "options"),
				"chooseOptionsLinkText" => get_field("option_cookie_popup_choose_options_link_text", "options"),
				"optionsTitle" => get_field("option_cookie_popup_options_title", "options"),
				"optionsHeading" => get_field("option_cookie_popup_options_heading", "options"),
				"optionsDescription" => get_field("option_cookie_popup_options_description", "options"),
				"optionsPrivacyPolicyLink" => get_field("option_cookie_popup_options_privacy_policy_link", "options"),
				"contactHeading" => get_field("option_cookie_popup_contact_heading", "options"),
				"contactDescription" => get_field("option_cookie_popup_contact_description", "options"),
				"contactLink" => get_field("option_cookie_popup_contact_link", "options"),
				"necessaryTitle" => get_field("option_cookie_popup_necessary_title", "options"),
				"necessaryDescription" => get_field("option_cookie_popup_necessary_description", "options"),
				"analyticsTitle" => get_field("option_cookie_popup_analytics_title", "options"),
				"analyticsDescription" => get_field("option_cookie_popup_analytics_description", "options"),
				"analyticsTable" => get_repeater_field_content_as_array("option_cookie_popup_analytics_table", "options"),
				"targetingTitle" => get_field("option_cookie_popup_targeting_title", "options"),
				"targetingDescription" => get_field("option_cookie_popup_targeting_description", "options"),
				"targetingTable" => get_repeater_field_content_as_array("option_cookie_popup_targeting_table", "options")
			]
		);
		wp_add_inline_script("cookie-consent-init", "window.cookiePopupSettings = {$cookie_popup_settings}", "before");

		if($cookie_consent_style_uri = get_theme_file_uri("assets/dist/vendor/css/cookieconsent.css"))
			wp_enqueue_style("cookie-consent", $cookie_consent_style_uri, [], $build_version, false);
	}

	// Enqueue JS Scripts for admin panel
	function admin_enqueue_js_script($hook) {
		if ('post.php' !== $hook) {
			return;
		}
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
		if($admin_script_uri = get_theme_file_uri("assets/dist/js/admin.js"))
			wp_enqueue_script("admin-js", $admin_script_uri, [], $build_version, false);

		if ($acf_svg_preview_script_uri = get_theme_file_uri("assets/dist/js/acf-svg-preview.js")){
			wp_enqueue_script(
				"acf-svg-preview", 
				$acf_svg_preview_script_uri, 
				[],
				$build_version, 
				false
			);

			// Generate the SVG preview files
			$svg_mapping = generate_svg_mapping();

			wp_localize_script( "acf-svg-preview", "svgPreviewData", array(
				"svgMapping" => $svg_mapping,
			));
		}

		// Register custom text formats in Gutenberg
		if ($register_custom_text_formats_uri = get_theme_file_uri("assets/dist/js/custom-text-formats.js")) {
			wp_enqueue_script(
				"custom-text-formats", 
				$register_custom_text_formats_uri, 
				["wp-rich-text", "wp-editor", "wp-element"], 
				$build_version, 
				false
			);
		}
	}
	add_action("admin_enqueue_scripts", "admin_enqueue_js_script");
	
	// Enqueue Swiper.js files (called only where needed)
	function swiper_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

		if($swiper_script_uri = get_theme_file_uri("assets/dist/vendor/js/swiper.js"))
			wp_enqueue_script("swiper", $swiper_script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);

		if($swiper_script_init_uri = get_theme_file_uri("assets/dist/js/swiper.js"))
			wp_enqueue_script("swiper-init", $swiper_script_init_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);

		if($swiper_style_uri = get_theme_file_uri("assets/dist/vendor/css/swiper.css"))
			wp_enqueue_style("swiper", $swiper_style_uri, [], $build_version, "all");
	}

	// Enqueue Scroll Magic files (called only where needed)
	function scroll_magic_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

		if($scroll_magic_script_uri = get_theme_file_uri("assets/dist/vendor/js/scroll-magic.js"))
			wp_enqueue_script("scroll-magic", $scroll_magic_script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);

		if(defined('WP_DEBUG') && true === WP_DEBUG) {
			if($scroll_magic_debug_script_uri = get_theme_file_uri("assets/dist/vendor/js/scroll-magic-debug.js"))
				wp_enqueue_script("scroll-magic-debug", $scroll_magic_debug_script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);
		}

	}

	// Enqueue tabs.js file (called only where needed)
	function tabs_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
		if($tabs_script_uri = get_theme_file_uri("assets/dist/js/tabs.js"))
			wp_enqueue_script("tabs", $tabs_script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);

	}

	// Enqueue Ajax filter file (called only where needed)
	function ajax_filter_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
		if($ajax_filter_js_uri = get_theme_file_uri("assets/dist/js/ajax-filter.js"))
			wp_enqueue_script("ajax_filter", $ajax_filter_js_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);
	}

	// Enqueue Lottie Script (called only where needed)
	function lottie_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

		if($script_uri = get_theme_file_uri("assets/dist/vendor/js/lottie-player.js"))
			wp_enqueue_script("lottie-player", $script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);
	}

	// Enqueue Gated Access Download Hero JS (called only where needed)
	function gated_access_download_hero_enqueue_scripts() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
		if($script_uri = get_theme_file_uri("assets/dist/js/gated-access-download-hero.js")) {
			wp_enqueue_script("gated-access-download-hero", $script_uri, [], $build_version, ["in_footer" => true, "strategy" => "defer"]);
			wp_localize_script("gated-access-download-hero", "gatedAccessData", [
				"ajaxUrl" => admin_url("admin-ajax.php"),
			]);
		}
	}

	// Called only where needed
	function accordions_enqueue_scripts() {

		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

		//enqueue accordions JS
		if($accordions_script_uri = get_theme_file_uri("assets/dist/js/accordions.js"))
			wp_enqueue_script("custom-accordions", $accordions_script_uri, [], $build_version, false);

	}


	########################################################################################################################
	# ========================= ENQUEUE STYLES =============================================================================
	########################################################################################################################

	add_filter("wp_resource_hints", function($urls, $relation_type) {
		if ($relation_type === "preconnect") {
			$urls[] = ["href" => "https://use.typekit.net", "crossorigin" => "anonymous"];
			$urls[] = ["href" => "https://p.typekit.net", "crossorigin" => "anonymous"];
		}
		return $urls;
	}, 10, 2);

	// Load TypeKit async so it doesn't block rendering — font-display: swap handles FOUT
	add_filter("style_loader_tag", function($tag, $handle) {
		if ($handle !== "typekit") return $tag;
		$href = esc_url("https://use.typekit.net/xdc4ddg.css");
		return "<link rel='preload' as='style' href='{$href}' onload=\"this.onload=null;this.rel='stylesheet'\">\n"
			. "<noscript><link rel='stylesheet' href='{$href}'></noscript>\n";
	}, 10, 2);

	function wordpress_enqueue_styles() {
		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";


		// Typescript Fonts
		wp_enqueue_style("typekit", "https://use.typekit.net/xdc4ddg.css", [], null, "all");


		// Enqueue Main Style:
		wp_enqueue_style("theme", get_theme_file_uri("assets/dist/css/style.css"), [], $build_version, "all");
	}

	########################################################################################################################
	# ========================= ENQUEUE ACTIONS ============================================================================
	########################################################################################################################

	/* Frontend Enqueue Hook: */
	function wordpress_enqueue_theme() {

		// Setup Cookie Consent JS + CSS
		cookie_consent_enqueue_scripts();

		// Setup Enqueue Scripts
		wordpress_enqueue_scripts();

		// Setup Enqueue Styles
		wordpress_enqueue_styles();

	}
	add_action("wp_enqueue_scripts", "wordpress_enqueue_theme");

	// Dequeue WordPress core block styles — runs at priority 100 to guarantee it fires after
	// WP core enqueues at priority 1 and any theme/plugin enqueues at priority 10
	add_action("wp_enqueue_scripts", function() {
		wp_dequeue_style("wp-block-library");
		wp_dequeue_style("wp-block-library-theme");
	}, 100);

	/* Header & Footer Hooks: */

	// Clean Comments Callback --- https://www.phpweb.info/wordpress/remove-html-comments
	function callback($buffer){ 

		// Strip HTML Comments
		$buffer = preg_replace("/<!--(.|s)*?-->/", "", $buffer);

		// Strip Mulitple Linebreaks
		$buffer = preg_replace("/[\n\r][\n\r]+/", PHP_EOL, $buffer);

		// Strip Mulitple Spaces
		$buffer = preg_replace("/[\s\s]+/", " ", $buffer);

		// Strip Tabs
		$buffer = preg_replace("/[\t\t]+/", " ", $buffer);

		// Return Result
		return $buffer;

	}

	// Content Buffers:
	function buffer_comments_start(){ 
		ob_start("callback"); 
	}
	function buffer_comments_end(){ 
		ob_end_flush(); 
	}

	// Apply Buffers
	// add_action("get_header", "buffer_comments_start");
	// add_action("wp_footer", "buffer_comments_end");

	// WP Block Editor Scripts
	function enqueue_block_editor_scripts() {

		$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

		// Typescript Fonts
		wp_enqueue_style("typekit", "https://use.typekit.net/xdc4ddg.css", [], null, "all");

		// Google Maps API
		if(
			($google_maps_loader_script_uri = get_theme_file_uri("assets/dist/js/google-maps.js")) && 
			($maps_api_key = get_field("option_api_keys_google_maps_key", "options"))
		){
			wp_enqueue_script("google-maps", $google_maps_loader_script_uri, [], $build_version, false);
			wp_enqueue_script("google-maps-api", "https://maps.googleapis.com/maps/api/js?key={$maps_api_key}&callback=initMaps", ["google-maps"], $build_version, ["in_footer" => true, "strategy" => "defer"]);
		}
		
		wp_enqueue_script("theme-block-editor-js", get_theme_file_uri("assets/dist/js/block-editor-scripts.js"), ["wp-blocks", "wp-dom-ready", "wp-edit-post"], '1.0', ["in_footer" => true, "strategy" => "defer"]);
		wp_enqueue_script("theme-block-editor-hooks-js", get_theme_file_uri("assets/dist/js/block-editor-hooks.js"), ["wp-element", "wp-i18n", "wp-editor", "wp-hooks"], "1.0", ["in_footer" => true, "strategy" => "defer"]);

	}
	add_action("enqueue_block_editor_assets", "enqueue_block_editor_scripts");

// Purposefully Leaving PHP Tag Open


