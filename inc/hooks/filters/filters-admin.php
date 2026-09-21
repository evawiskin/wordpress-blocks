<?php

	/**
	 * Filter Hooks
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_filter/
	 * @link https://developer.wordpress.org/plugins/hooks/filters/
	 */
	

	/* Block Filters */
	
		// Restrict Which Blocks Are Available
		function restrict_allowed_blocks($allowed_block_types) {
			$get_all_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
			$all_block_types = array_keys($get_all_blocks);
			$all_non_core_block_types = array_filter($all_block_types, function($block_type) {
				return(strpos($block_type, "core/") !== 0); // all blocks that don't start with "core/"
			});
			// Add core blocks here to enable them
			$allowed_core_blocks_types = [
				"core/block",
				"core/heading",
				"core/paragraph",
				"core/list",
				"core/list-item",
				"core/image",
				"core/spacer",
				"core/embed",
				"core/video",
				"core/quote",
				"core/table",
				"core/separator",
			];
			$allowed_block_types = array_merge($allowed_core_blocks_types, $all_non_core_block_types);
			return($allowed_block_types);
		}

		// Add Custom Block Categories
		function add_custom_block_categories($block_categories) {
			
			// Add Layout category
			$block_categories[] = [
				"slug" => "custom-layout",
				"title" => "Layout",
				"icon" => NULL
			];
			

			// Add Hero category
			$block_categories[] = [
				"slug" => "content-hero",
				"title" => "Content",
				"icon" => NULL
			];

			// Add Content elements
			$block_categories[] = [
				"slug" => "elements",
				"title" => "Elements",
				"icon" => NULL
			];

			return($block_categories);
		}

		// Add containers around core blocks
		// function core_block_containers( $block_content, $block ) {
		// 	$core_blocks_to_contain = [
		// 		"core/heading",
		// 		"core/paragraph",
		// 		"core/list",
		// 		"core/buttons",
		// 		"core/embed",
		// 		"core/video",
		// 		"core/quote",
		// 		"core/image",
		// 		"core/table"
		// 	];

		// 	if (
		// 		in_array($block["blockName"], $core_blocks_to_contain) && is_singular("post")
			
		// 	) {
		// 		return("<div class=\"container\">{$block_content}</div>");
		// 	}
		// 	return $block_content;
		// }

		// auto add an anchor to heading blocks if they don't have one
		function add_anchor_to_heading_blocks($block_content, $block) {
			// check if heading block and if it has no id
			if ($block["blockName"] == "core/heading" && strpos($block_content, "id=") === false) {
				// get the heading element and the text
				preg_match("#<(h[1-6].*?)>(.*?)</h[1-6]>#", $block_content, $matches);
				if($matches){
					$h_element = $matches[1];
					$title = $matches[2];
					// create an id from the text
					$id = sanitize_title_with_dashes($title);
					// append the id to the heading element
					$block_content = str_replace("<$h_element>", "<$h_element id='$id'>", $block_content);
				}
			}
			return $block_content;
		}

	/* User Filters */

	// Hide ACF Menu for None-Admin 
	function acf_admin_menu_role_hide() {
		// Catch Broken User
		if(!($user = wp_get_current_user()))
			return false;
		return in_array("administrator", $user->roles);
	}

	// Disallow Role Change to a Higher Rank
	function deny_same_level_role_change($all_roles) {
		// Catch Broken User
		if(!($user = wp_get_current_user()))
			return [];

		// Return everything for Administrators
		if(in_array("administrator", $user->roles)) 
			return $all_roles;

		if(in_array("content-administrator", $user->roles)){
			unset($all_roles["administrator"]);
			return $all_roles;
		}

		// Return filtered versions for everything else. 
		// This could be done with smaller array_merge s and a larger quantity of in_array checks.
		// As both approaches have similar pros and cons the approach taken is the one deemed most readable.
		$allowed_roles = [];

		// Editors get basically everything but not administrator
		if(in_array("editor", $user->roles)) 
			$allowed_roles = array_merge(
				$allowed_roles,
				[
					"editor" => $all_roles["editor"],
					"author" => $all_roles["author"],
					"subscriber" => $all_roles["subscriber"],
					"contributor" => $all_roles["contributor"]
				]
			);

		// Authors don't get Editor
		if(in_array("author", $user->roles)) 
			$allowed_roles = array_merge(
				$allowed_roles,
				[
					"author" => $all_roles["author"],
					"subscriber" => $all_roles["subscriber"],
					"contributor" => $all_roles["contributor"]
				]
			);

		// Plebeian Roles don't get much
		if(
			in_array("subscriber", $user->roles) ||
			in_array("contributor", $user->roles)
		) 
			$allowed_roles = array_merge(
				$allowed_roles,
				[
					"subscriber" => $all_roles["subscriber"],
					"contributor" => $all_roles["contributor"]
				]
			);

		// Flip and then Chuck back the allowed roles
		return array_reverse($allowed_roles);
	}

	/* Column Filters (See Custom Actions for the Column Actions) */

		// Example Post Type Column Filter
		function filter_example_columns($old_columns) {
			$new_columns = [
				"example_date" => __( "Example Date" ),
				"example_time" => __( "Example Time" )
			];

			return array_merge($old_columns, $new_columns);
		}

	/* WYSIWYG Filters */

		// Alter WYSIWYG Toolbars
		function wysiwyg_toolbar_alterations($toolbars) {
			// Set Sidebar Toolbar Level 1
			$toolbars["Sidebar"][1] = [
				"formatselect",
				"bold",
				"italic",
				"wp_adv"
			];

			// Set Sidebar Toolbar Level 2
			$toolbars["Sidebar"][2] = [
				"styleselect",
				"undo",
				"redo",
				"bullist",
				"numlist"
			];

			// Set Sidebar Toolbar Level 3
			$toolbars["Sidebar"][3] = [
				"forecolor",
				"strikethrough",
				"link",
				"unlink",
				"pastetext",
				"removeformat"
			];

			// Set Sidebar Toolbar Level 4
			$toolbars["Sidebar"][4] = [
				"alignleft",
				"aligncenter",
				"alignright",
				"outdent",
				"indent"
			];

			return $toolbars;
		}

		// Add WYSIWYG Styles
		function wysiwyg_format_styles($init_array) {
			// Tag Formats
			$formats = [
				"paragraph_large" => [
					"block"   => "p",
					"attributes" => [
						"class" => "text-lg"
					]
				],
				"paragraph_base" => [
					"block"   => "p",
					"attributes" => [
						"class" => "text-base"
					]
				],
				"paragraph_small" => [
					"block"   => "p",
					"attributes" => [
						"class" => "text-sm"
					]
				],

				"heading_large" => [
					"block" => "h2",
					"attributes" => [
						"class" => "text-heading-lg"
					]
				],
				"heading_base" => [
					"block" => "h3",
					"attributes" => [
						"class" => "text-heading-base"
					]
				],
				"heading_small" => [
					"block" => "h4",
					"attributes" => [
						"class" => "text-heading-sm"
					]
				],

				"pre" => [
					"block" => "pre",  
					"attributes" => [
						"class" => "text-system-red"
					]
				]
			];

			// Extra Formats
			$style_formats = [   
				[
					"title" => "Extra",
					"items" => [
						[  
							"title"   => "Example Circle",  
							"inline"  => "span",  
							"classes" => "",
							"wrapper" => true
						]
					]
				]
			]; 

			// Set the Body to have the right classes
			$init_array["body_class"] = "wysiwyg-defaults";

			// Set our CSS into the WYSIWYGs to aid Output
			$init_array["content_css"] = get_theme_file_uri("assets/dist/css/style.css");

			// JSON Encode and Insert Formats
			$init_array["formats"] = json_encode($formats);

			// JSON Encode and Insert Style Formats
			$init_array["style_formats"] = json_encode($style_formats);  

			// Toggle to show the other WYSIWYG Rows on load
			// We could instead show them the toggle as an option
			$init_array["wordpress_adv_hidden"] = false;

			// Toggle to ensure that all text pasted into the WYSIWYG is plain
			// We could instead show them the toggle as an option
			$init_array["paste_as_text"] = true;

			// JSON Encode and Redefine Block Formats
			// First item gets used as a default so best to do paragraph base first
			$init_array["block_formats"] = implode(
				"",
				[
					"Paragraph Base=paragraph_base;",
					"Paragraph Large=paragraph_large;",
					"Paragraph Small=paragraph_small;",

					"Heading Large=heading_large;",
					"Heading Base=heading_base;",
					"Heading Small=heading_small;",

					"Preformatted=pre;"
				]
			);

			// Return Init Array
			return $init_array; 
		}

	/* Enqueue Filters */

		// Font Tag Filter
		function theme_loader_link_tag_filter($html, $handle, $href) {
			// Check for a Woff Tag
			if(strpos($handle, "-woff-font")) {
				return "<link id=\"$handle\" href=\"$href\" rel=\"preload\" as=\"font\" type=\"font/woff\" crossorigin=\"anonymous\"/>";
			}

			// Check for a Woff2 Tag
			if(strpos($handle, "-woff2-font")) {
				return "<link id=\"$handle\" href=\"$href\" rel=\"preload\" as=\"font\" type=\"font/woff2\" crossorigin=\"anonymous\"/>";
			}

			// Check for a OTF Tag
			if(strpos($handle, "-otf-font")) {
				return "<link id=\"$handle\" href=\"$href\" rel=\"preload\" as=\"font\" type=\"font/otf\" crossorigin=\"anonymous\"/>";
			}

			// Check for a TTF Tag
			if(strpos($handle, "-tff-font")) {
				return "<link id=\"$handle\" href=\"$href\" rel=\"preload\" as=\"font\" type=\"font/tff\" crossorigin=\"anonymous\"/>";
			}

			// Return Default HTML
			return ($html);
		}

	/* Image Filters */
	
		/**
		 * Limit the file size for images upload
		 *
		 * @param $file
		 * @return mixed
		 */
		function restrict_image_upload_size($file){
			//3 MB
			$max_allowed_size = 3000 * 1024;

			if ($file["size"] > $max_allowed_size)
				$file["error"] = "Please reduce the size of your image to 3 Mb or less before uploading it.";

			return $file;
		}

		// Replace WP's default class that gets overwritten when using the class attribute in wp_get_attachment_img
		function replace_default_attachment_image_classes($html, $attachment_id, $size) {
			$html = str_replace("class=\"", "class=\"attachment-{$size} size-{$size} ", $html);
			return $html;
		}

		// replace any uploaded svg fill colour with current color before its saved in WP
		function replace_svg_colour_on_upload($file) {
			// check if file is an SVG, if not then skip and return as normal
			$file_is_svg = (strpos($file["type"], "svg") !== false);
			if($file_is_svg && isset($file["tmp_name"]) && file_exists($file["tmp_name"])) {
				// get the SVG contents from the temp file
				$file_contents = file_get_contents($file["tmp_name"]);
				// replace any fill colour with current color
				$file_contents = preg_replace('/fill=\"(?!none)[^"]*\"/', 'fill="currentColor"', $file_contents);
				// save the file back to the tmp file
				file_put_contents($file["tmp_name"], $file_contents);
			}
			return $file;
		}

	/* Query Filters */

		// LIKE Query
		function query_post_title( $where, $wp_query ) {
			// Get WPDB Instance
			global $wpdb;

			// Set Search Title
			$search_title = $wp_query->get( "post_title_like" );

			// Check Search Title
			if( $search_title ) {
				// Search Like String
				$search_like = esc_sql( $wpdb->esc_like( $search_title ) );

				// Append to Where
				$where .= " AND LOWER($wpdb->posts.post_title) LIKE \"%" . strtolower($search_like) . "%\"";
			}

			// Return Where
			return $where;
		}

	/* Menu Filters */

		// Filter Item Class Nav Argument
		function menu_args_add_item_class(array $classes, WP_POST $item, object $args){
			if(isset($args->item_class))
				$classes[] = $args->item_class;
			return $classes;
		}

	/* Excerpt Filters */

		//trim excerpt length
		function trim_excerpt_length() {
			return 30;
		}
		// add_filter("excerpt_length", "trim_excerpt_length");
		
		//alter end of excerpt
		function alter_excerpt_read_more() {
			return "...";
		}	

	/* Redirect Filters */

		// Disable author archive redirection to prevent username reveal
		function disable_author_archive_redirection($redirect_url, $requested_url) {
			if (is_author()) {
				global $wp_query;
				$wp_query->set_404();
				status_header(404);
				get_template_part(404); exit();
			}
			return $redirect_url;
		}

	/* State Filters */

		// Edit the Page State to include Post Archive Tag
		function page_state_add_archive_pages($states) {
			// Only add the state for pages
			if (get_post_type() != "page")
				return $states;
			// Get the post type for the current archive page - returns false if not an archive page
			if($post_type = get_archive_page_post_type(get_the_ID())) {
				$post_type_menu_name_label = get_post_type_object($post_type)->labels->menu_name;
				$states[] = "{$post_type_menu_name_label} Archive Page";
			}
			return $states;
		}
	
	/* Yoast Filters */

		// If archive page, check if there is a page for archive and use that title instead
		function use_page_for_archive_title_on_archive($title) {
			if (is_archive()){
				$post_type_underscored = str_replace("-", "_", get_post_type());
				if(($post_id = get_option("page_for_{$post_type_underscored}")))
					$title["title"] = get_post_meta($post_id, "_yoast_wpseo_title", true);
			}
			return $title;
		}

		// If archive page then make sure we're not doing no-index
		function archive_noindex_change($robots) {
			if (is_archive()){
				$robots = "index, follow";
			}
			return $robots;
		}

	// filter the Gravity Forms button type
	function form_submit_button_icon( $button_input, $form ) {
		$button_arrow_icon = get_svg_icon("arrow-right");
		$button_text = $form["button"]["text"];
		preg_match( "/<input([^\/>]*)(\s\/)*>/", $button_input, $button_match );
		$button_attributes = $button_match[1];

		return "<button {$button_attributes}><span class=\"-mt-1\">{$button_text}</span><span class=\"block w-3.5 h-3.5 md:w-5 md:h-5 my-auto\">{$button_arrow_icon}</span></button>";
	}

	// Add custom confirmation to Gravity Forms
	function custom_confirmation($confirmation, $form, $entry, $ajax) {

		// Check if the confirmation is a message and tweak it only if it is
		if(!isset($form["confirmation"]["type"])) {
			return($confirmation);
		} else {
			if($form["confirmation"]["type"] !== "message") {
				return($confirmation);
			}
		}

		// Gravity forms function https://docs.gravityforms.com/rgar/
		$name = rgar($entry, "1");
		$args = [
			"name" => $name,
			"confirmation" => $confirmation,
			"show_send_another_button" => rgar($form, "hide_send_another") ? false : true
		];
		ob_start();
			get_template_part("template-parts/partials/partial", "contact-confirmation", $args);
			echo("<script>contactFormConfirmation()</script>");
		$confirmation = ob_get_clean();
		return($confirmation);
	}

	// Add custom UI + save filter for custom Gform setting
	function custom_form_confirmation_button_setting($settings, $form) {
		$settings["Custom Settings"]["hide_send_another"] = "
			<tr>
				<td>
					<input 
						type=\"checkbox\" 
						name=\"hide_send_another\" 
						id=\"hide_send_another\" value=\"1\" " 
						. checked(rgar($form, "hide_send_another"), "1", false) . 
					"/>
					<label for=\"hide_send_another\">Hide re-send button in confirmation message?</label>
				</td>
			</tr>
		";
		return $settings;
	}
	add_filter("gform_form_settings", "custom_form_confirmation_button_setting", 10, 2);
	add_filter("gform_pre_form_settings_save", function($form) {
		$form["hide_send_another"] = rgpost("hide_send_another");
		return $form;
	});

	//Add Links to Anchors inside wp_nav_menu
	function anchor_classes($classes, $item, $args){
		if (isset($args->anchor_class)) {
			$classes["class"] = $args->anchor_class;
		}
		return $classes;
	}

	add_filter("nav_menu_link_attributes", "anchor_classes", 1, 3);

	/* Request Filters */

	// Add the staging user/pass to ajax requests to prevent 401 errors
	function add_staging_auth_to_ajax_requests($args, $url) {
		if (strpos($url, admin_url("admin-ajax.php")) === 0) {
			$args["headers"]["Authorization"] = "Basic " . base64_encode("Hiyield1:Hiyield1");
		}
		return $args;
	}

	// Ensure json files (lottie) can be uploaded to media library
	function add_json_mime_type($mimes) {
		$mimes["json"] = "text/plain";
		return $mimes;
	}

	/* Category Filters */

		// Exclude Uncategorized from get_categories() and get_terms()
		function exclude_uncategorized_category($args, $taxonomies) {
			// Static cache to prevent infinite loop
			static $uncategorized_id = null;
			static $is_fetching = false;
			
			// Only apply on frontend
			if (is_admin() || $is_fetching) {
				return $args;
			}
			
			// Only apply to category taxonomy
			if (in_array("category", (array) $taxonomies)) {
				// Get the Uncategorized category ID (with recursion protection)
				if ($uncategorized_id === null) {
					$is_fetching = true;
					$uncategorized = get_category_by_slug("uncategorized");
					$is_fetching = false;
					
					if ($uncategorized) {
						$uncategorized_id = $uncategorized->term_id;
					}
				}
				
				if ($uncategorized_id) {
					// Initialize exclude array if it doesn't exist
					if (!isset($args["exclude"])) {
						$args["exclude"] = array();
					} elseif (!is_array($args["exclude"])) {
						$args["exclude"] = array($args["exclude"]);
					}
					
					// Add Uncategorized to exclude list if not already there
					if (!in_array($uncategorized_id, $args["exclude"])) {
						$args["exclude"][] = $uncategorized_id;
					}
				}
			}
			
			return $args;
		}

		// Exclude Uncategorized from wp_list_categories()
		function exclude_uncategorized_from_widget($args) {
			// Static cache to prevent infinite loop
			static $uncategorized_id = null;
			static $is_fetching = false;
			
			// Only apply on frontend
			if (is_admin() || $is_fetching) {
				return $args;
			}
			
			// Get the Uncategorized category ID (with recursion protection)
			if ($uncategorized_id === null) {
				$is_fetching = true;
				$uncategorized = get_category_by_slug("uncategorized");
				$is_fetching = false;
				
				if ($uncategorized) {
					$uncategorized_id = $uncategorized->term_id;
				}
			}
			
			if ($uncategorized_id) {
				if (!isset($args["exclude"])) {
					$args["exclude"] = $uncategorized_id;
				} else {
					$args["exclude"] .= "," . $uncategorized_id;
				}
			}
			
			return $args;
		}

		// Exclude Uncategorized from get_the_category_list()
		function exclude_uncategorized_from_post_categories($categories, $post_id) {
			// Static cache to prevent infinite loop
			static $uncategorized_id = null;
			static $is_fetching = false;
			
			// Only apply on frontend
			if (is_admin() || $is_fetching) {
				return $categories;
			}
			
			// Get the Uncategorized category ID (with recursion protection)
			if ($uncategorized_id === null) {
				$is_fetching = true;
				$uncategorized = get_category_by_slug("uncategorized");
				$is_fetching = false;
				
				if ($uncategorized) {
					$uncategorized_id = $uncategorized->term_id;
				}
			}
			
			if ($uncategorized_id && is_array($categories)) {
				$categories = array_filter($categories, function($category) use ($uncategorized_id) {
					return $category->term_id !== $uncategorized_id;
				});
			}
			
			return $categories;
		}

		add_filter("upload_mimes", "add_json_mime_type");

	/* Request Filters */
		add_filter("http_request_args", "add_staging_auth_to_ajax_requests", 10, 2);

	/* Block Filters */
		add_filter("allowed_block_types_all", "restrict_allowed_blocks");
		add_filter("block_categories_all", "add_custom_block_categories");
		// add_filter("render_block", "core_block_containers", 10, 2 );
		add_filter("render_block", "add_anchor_to_heading_blocks", 10, 2 );
	
	/* User Filters */
		add_filter("acf/settings/show_admin", "acf_admin_menu_role_hide");
		add_filter( "editable_roles", "deny_same_level_role_change" );

	/* Column Filters (See Custom Actions for the Column Actions) */
		// add_filter( "manage_example_posts_columns", "filter_example_columns" );

	/* WYSIWYG Filters */
		add_filter("acf/fields/wysiwyg/toolbars", "wysiwyg_toolbar_alterations");
		add_filter("tiny_mce_before_init", "wysiwyg_format_styles");

	/* Enqueue Filters */
		add_filter("style_loader_tag", "theme_loader_link_tag_filter", 10, 3);

	/* Image Filters */
		// add_filter("wp_handle_upload_prefilter", "restrict_image_upload_size", 20);
		add_filter("wp_get_attachment_image", "replace_default_attachment_image_classes", 10, 3);
		// add_filter("wp_handle_upload_prefilter", "replace_svg_colour_on_upload", 21);

	/* Menu Filters */
		add_filter("nav_menu_css_class", "menu_args_add_item_class", 10, 3);

	/* Excerpt Filters */
		// add_filter("excerpt_length", "trim_excerpt_length");
		// add_filter("excerpt_more", "alter_excerpt_read_more");

	/* Redirect Filters */
		add_filter("redirect_canonical", "disable_author_archive_redirection", 10, 2);

	/* State Filters */
		add_filter("display_post_states", "page_state_add_archive_pages");

	/* Yoast Filters */
		add_filter( "document_title_parts", "use_page_for_archive_title_on_archive" );
		add_filter( "wpseo_robots", "archive_noindex_change" );

	/* Cleanup Filters */
		//Removes filters that embed bloat in DOM
		remove_filter( "wp_mail", "wp_staticize_emoji_for_email" );
		remove_filter( "the_content_feed", "wp_staticize_emoji" );
		remove_filter( "comment_text_rss", "wp_staticize_emoji" );


	/* Add icon to Gravity Forms submit button */ 
	add_filter("gform_submit_button", "form_submit_button_icon", 10, 2);

	// Add custom confirmation to Gravity Forms
	add_filter("gform_confirmation", "custom_confirmation", 10, 4);

	/* Category Filters */
		add_filter("get_terms_args", "exclude_uncategorized_category", 10, 2);
		add_filter("widget_categories_args", "exclude_uncategorized_from_widget");
		add_filter("widget_categories_dropdown_args", "exclude_uncategorized_from_widget");
		add_filter("get_the_categories", "exclude_uncategorized_from_post_categories", 10, 2);

// Purposefully Leaving PHP Tag Open

