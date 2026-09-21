<?php

	/**
	 * Action Hooks
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_action/
	 * @link https://developer.wordpress.org/plugins/hooks/actions/
	 */

	/* Admin Actions */

		// Alter Login Styles
		function change_login_styles() {
			// Add Logo and Script Block
			?>
				<script>      
					document.addEventListener(
						"DOMContentLoaded", 
						() => {
							// Get Logo Elements
							const customLogoElement = document.getElementById("custom-logo");
							const logoElement = document.getElementById("login").querySelector("h1");

							// Clear Element and Append Child
							logoElement.innerHTML = "";
							logoElement.appendChild(customLogoElement);

							// Show Element
							customLogoElement.style.display = "block";
						}
					);
				</script>
				<div
					id="custom-logo"
					style="
						width: 100%;
						max-width: 100vw;
						margin-left: auto;
						margin-right: auto;
						margin-bottom: 2rem;
					"
				>	
					<img
						alt="<?php echo(esc_attr(get_bloginfo('name'))); ?>"
						src="<?php echo(wp_get_attachment_url(get_field("option_company_info_logo", "options"))); ?>"
						style="
							width: 10rem;
							margin-left: auto;
							margin-right: auto;
						"
					/>
				</div>
			<?php
		}
		

		function force_hide_login_logo() { ?>
			<style type="text/css">
					body.login div#login h1 a {
					background-image: none;
					background-size: 0 0;
					height: 0;
					margin: 0 auto 0;
					width: 0;
					}
					body.login{
						background: linear-gradient(135deg,#3db783 0,#3db7bd 50%,#a3ffea 100%);
					}

					body.login #login #backtoblog a, body.login #login #nav a {
						color: white;
					}

					body.login #login #backtoblog a:hover, body.login #login #nav a:hover {
						color: #fb64a4;
						transition: all 0.1s ease-in-out;
					}

					body form#loginform, body form#lostpasswordform, body form#resetpassform {
						background: none;
						border: none;
						border-radius: none;
						box-shadow: none;
						color: white;
					}

					body.login .forgetmenot label:hover,
					body.login .forgetmenot input:checked ~ label {
						color: #fb64a4;
					}

					body.login #wp-submit {
						background: #465480;
						border: none;
						border-radius: 0;
						box-shadow: none;
						color: white;
						text-shadow: none;
						transition: all 0.1s ease-in-out;
					}
					body.login #wp-submit:hover {
						background: #fb64a4;
					}
					body.login .language-switcher {
						display: none;
					}
					
			</style>
		<?php }
		

		// Alter Admin Styles
		function change_admin_styles() {
			$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
			// Get Admin CSS
			$admin_css_uri = get_theme_file_uri("assets/dist/css/admin.css");
			// Get Core CSS
			$core_css_uri = get_theme_file_uri("assets/dist/css/style.css");

			wp_enqueue_style(
				"core-styles",
				$core_css_uri,
				[],
				$build_version
			);
			wp_enqueue_style(
				"admin-styles",
				$admin_css_uri,
				[],
				$build_version
			);
		}
		

		// Alter Admin Menus
		// Credit: https://www.interserver.net/tips/kb/how-to-remove-tabs-from-the-wordpress-administrator-dashboard/
		function change_admin_menus(){
			remove_menu_page("edit-comments.php"); // Comments
			remove_submenu_page("edit.php", "edit-tags.php?taxonomy=post_tag"); // The "tags" sub-menu of the "posts" menu
		}		

	/* Column Actions (See Custom Filters for the Column Filters) */

		// Event Row Inputting ACF Fields
		function example_custom_column ($column, $post_id) {
			switch($column) {
				case("example_date"):
					echo("The Date Is: " . get_field("example_date", $post_id));
					break;

				case("example_time"):
					echo("The Time Is: " . get_field("example_time", $post_id));
					break;
			}
		}


	// Adding Post Type Archives to Reading Settings
	function create_cms_page_for_post_type_archives() {
		// Get all the post types that have archives
		$post_types = get_post_types(
			[
				"public" => true,
				"_builtin" => false,
				"has_archive" => true
			],
			"objects"		
		);
		// add any post types to skip to this array
		$skip_post_types = [];
		// Loop through each post type
		foreach($post_types as $post_type) {
			// Skip post types that are in the skip list
			if (in_array($post_type->name, $skip_post_types))
				continue;
			// generate underscored post type name
			$post_type_underscored = str_replace("-", "_", $post_type->name);
			// Create Setting ID
			$setting_id = "page_for_{$post_type_underscored}";
			// Parse the current setting's value
			$setting_value = is_numeric(get_option($setting_id)) 
				? intval(get_option($setting_id, 0)) 
				: 0;
			// Register Post Setting to Reading WP Group
			register_setting(
				"reading",
				$setting_id,
				[
					"type" => "string", 
					"sanitize_callback" => "sanitize_text_field"
				]
			);
			// Add Archive Page to Reading Settings
			add_settings_field(
				$setting_id, 
				"{$post_type->labels->menu_name} Post Archive Page:", 
				"settings_archive_page_select_callback", 
				"reading",
				"default",
				["setting_id" => $setting_id]
			);
			// Create page if it hasn't got one already
			if(!$setting_value) {
				// Create a new page
				$page_id = wp_insert_post(
					[
						"post_title" => "{$post_type->labels->menu_name} Post Archive",
						"post_status" => "publish",
						"post_type" => "page",
						"post_name" => sanitize_title($post_type->labels->menu_name),
						"post_content" => "This is the {$post_type->labels->menu_name} post archive page."
					]
				);
				// Update option regardless to maintain the existing archive pages assignment whenever reading settings are saved
				update_option($setting_id, $page_id);					
			}
		}
	}

	/* 
		Throws a 404 for taxonomy archives that just don't need them
	*/
		
	function kill_taxonomy_archives($query) {
	
		if (is_admin())return;
	
		if (
			is_category() ||
			is_tax("client")||
			is_tax("phase")||
			is_tax("team")
		)
			$query->set_404();
		
	}


	// As we are caching some queries to reduce the number of database calls, we need to clear the cache when a post is updated
	function clear_query_cache_for_post($post_id) {
		// skip if this is an autosave
		if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
			return;

		// clear the post name cache for this post id in case it's been changed
		delete_transient("post_name_{$post_id}");
	}

		
	/* Cleanup Actions */
		//Removes actions that embed bloat in DOM
		remove_action( "admin_print_styles", "print_emoji_styles" );
		remove_action( "wp_head", "print_emoji_detection_script", 7 );
		remove_action( "admin_print_scripts", "print_emoji_detection_script" );
		remove_action( "wp_print_styles", "print_emoji_styles" );

	/* Admin Actions */
		add_action("login_head", "change_login_styles" );
		add_action("login_enqueue_scripts", "force_hide_login_logo");
		add_action("enqueue_block_editor_assets", "change_admin_styles");
		add_action("admin_menu", "change_admin_menus");
		add_action("admin_init", "create_cms_page_for_post_type_archives");
		add_action("save_post", "clear_query_cache_for_post");

	/* Column Actions (See Custom Filters for the Column Filters) */
		// add_action ( "manage_example_posts_custom_column", "example_custom_column", 10, 2 );

	add_action("pre_get_posts", "kill_taxonomy_archives");

	function resource_archive_posts_per_page($query) {
		if (!is_admin() && $query->is_main_query() && $query->is_post_type_archive("resource")) {
			$posts_per_page = (int) get_option("resources_listing_posts_per_page", 3);
			$query->set("posts_per_page", $posts_per_page);
		}
	}
	add_action("pre_get_posts", "resource_archive_posts_per_page");

	// Keep resources_listing_posts_per_page in sync whenever the archive page is saved.
	// This ensures pre_get_posts always reads the current value, not the previous render's value.
	function resource_archive_sync_posts_per_page($post_id, $post) {
		if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) return;
		if (wp_is_post_revision($post_id)) return;
		if (!($post instanceof WP_Post) || $post->post_status !== "publish") return;
		if (!has_block("hiyield/resources-listing", $post->post_content)) return;

		foreach (parse_blocks($post->post_content) as $block) {
			if ($block["blockName"] !== "hiyield/resources-listing") continue;
			$value = (int) ($block["attrs"]["data"]["block_resources_listing_posts_per_page"] ?? 3);
			update_option("resources_listing_posts_per_page", $value ?: 3, false);
			break;
		}
	}
	add_action("save_post", "resource_archive_sync_posts_per_page", 10, 2);
	

// Purposefully Leaving PHP Tag Open


