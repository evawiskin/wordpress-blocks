<?php

	/**
	 * Filtering ACF
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf-load_field/
	 */

	/* Field Filters */
		// Dynamically Populate List with Nav Menu Options
		function menu_select_choices( $field ){
			// Check Choices Exist
			if(array_key_exists("choices", $field)){
				// Get WP Menus
				$wp_menus = wp_get_nav_menus();

				// Clear all Prev Menus as a Precaution
				$field["choices"][""] = "Select a menu...";
				
				// Loop Over Menus
				foreach ($wp_menus as $menu) {
					// Add to Array
					$field["choices"][$menu->slug] = $menu->name;
				}
			}

			// Return Data
			return($field);
		}
		

		// Dynamically Populate WP Forms Shortcodes
		function wp_forms_shortcode_select_choices( $field ){
			// Check Choices Exist
			if(array_key_exists("choices", $field)){
				// Clear all Prev Forms as a Precaution
				$field["choices"] = [];

				// Get WP Forms Post Args
				$args = [
					"post_type" 	 => "wpforms",
					"post_status" 	 => "publish",
					"posts_per_page" => -1,
				];
				
				// Make Forms Query
				$forms_query = new WP_Query($args);

				// Loop Over Forms
				while ( $forms_query->have_posts() ) {
					// Get Post
					$forms_query->the_post();

					// Get Form Content Object
					$form_content = json_decode(get_post()->post_content);

					// Check Content
					if($form_content && $form_content->id){
						// Add to Array
						$field["choices"]["[wpforms id=\"{$form_content->id}\"]"] = get_the_title();
					}
				}

				// Reset Postdata
				wp_reset_postdata();
			}

			// Return Data
			return($field);
		}

		// Dynamically Populate Gravity Forms into ACF Selects with IDs
		function gravity_forms_id_select_choices($field) {

			if ( ! class_exists( "GFAPI" ) ) {
				return $field;
			}

			// Check Choices Exist
			if(array_key_exists("choices", $field)) {

				$field["choices"] = [];

				//get forms list
				$forms = GFAPI::get_forms();

				//add a blank option
				$field["choices"][""] = "Select a form...";

				//iterate through all the forms and add as choices
				foreach ($forms as $form) {
					$id = $form["id"];
					$title = $form["title"];
					$field["choices"]["{$id}"] = $title;
				}
			}

			// Return the data in the field
			return($field);
		}

		// Dynamically Populate Gravity Forms into ACF Selects with Shortcodes
		function gravity_forms_shortcode_select_choices($field) {

			// Check Choices Exist
			if(key_exists("choices", $field)){

				// Clear all Prev Forms as a Precaution
				$field["choices"] = [];

				//if no Gravity Forms API then we have a problem
				if (!class_exists("GFAPI"))
					return($field);	

				//get all forms
				$all_forms = GFAPI::get_forms(); // Method to get all forms

				//add a blank option
				$field["choices"][""] = "Select a form...";

				//iterate through all the forms and add as choices
				foreach ($all_forms as $form) {
					$field["choices"]["[gravityform id=\"{$form["id"]}\" title=\"false\" ajax=\"true\"]"] = ucwords(str_replace("-", " ", $form["title"]));
				}
			}

			// Return the data in the field
			return($field);
		}



		// Dynamically Populate Forminator Shortcodes
		// if we wanna use forminator anywhere else later
		function forminator_shortcode_select_choices($field) {

			// Check Choices Exist
			if(array_key_exists("choices", $field)){

				// Clear all Prev Forms as a Precaution
				$field["choices"] = [];

				//if no forminator API then we have a problem
				if (!class_exists("Forminator_API"))
					return($field);	

				//get all forms
				$all_forms = Forminator_API::get_forms(); // Method to get all forms

				//add a blank option
				$field["choices"][""] = "Select a form...";

				//iterate through all the forms and add as choices
				foreach ($all_forms as $form) {
					$field["choices"]["[forminator_form id=\"{$form->id}\"]"] = ucwords(str_replace("-", " ", $form->name));
				}
			}

			// Return the data in the field
			return($field);
		}

		// Dynamically Populate Post Types
		function post_types_select_choices($field) {

			// Check Choices Exist
			if(key_exists("choices", $field)){

				// Clear all Prev Forms as a Precaution
				$field["choices"] = [];

				// Only get post types which are public
				$args = [
					"public"   => true
				];
				$output = "names";
				
				// get post types, add to the array to remove from the field
				$choices = array_diff(get_post_types($args, $output), ["attachment"]);

				//add a blank option
				$field["choices"][""] = "Select post type...";

				//iterate through all the forms and add as choices
				foreach( $choices as $choice ) {
					$field["choices"][ $choice ] = $choice;	
				}
			}

			// Return the data in the field
			return($field);
		}

		// Dynamically populate colors selects fields (from theme.json)
		function colors_select_choices($field){
			//clear out the default choices
			$field["choices"] = [];

			// Get color from theme.json
			$colors = get_theme_color_palette();

			//add the colors to the choices
			if($colors){
				foreach($colors as $color){
					$name = $color["name"];
					$value = $color["slug"];
					$field["choices"][$value] = $name;
				}
			}
			return($field);
		}

		// Dynamically populate colors selects fields (from theme.json)
		function populate_parent_services_ids($field){
			//clear out the default choices
			$field["choices"] = [];

			// Get only parent terms
			$terms = get_terms( [ "taxonomy" => "service", "parent" => 0, "hide_empty" => false ] );

			//add the terms to the choices
			if($terms){
				foreach($terms as $term){
					$name = $term->name;
					$value = $term->term_id;
					$field["choices"][$value] = $name;
				}
			}
			return($field);
		}

		function button_select_choices($field){
			//clear out the default choices
			$field["choices"] = [];

			$button_styles = [
				[
					"name" => "Primary",
					"slug" => "hy-button-primary"
				],
				[
					"name" => "Secondary (Pink)",
					"slug" => "hy-button-secondary"
				],
				[
					"name" => "Tertiary (Yellow)",
					"slug" => "hy-button-tertiary"
				],
				[
					"name" => "Outline",
					"slug" => "hy-button-outline"
				],
				[
					"name" => "Outline Neutral",
					"slug" => "hy-button-outline-neutral"
				]
			];

			//add the colors to the choices
			if($button_styles){
				foreach($button_styles as $style){
					$name = $style["name"];
					$value = $style["slug"];
					$field["choices"][$value] = $name;
				}
			}
			return($field);
		}

		//getting the theme icons
		function populate_theme_icon_select($field){
			//clear out the default choices
			$field["choices"] = [false => "None"];

			//the the array of key value pairs
			$icons = get_files_as_key_name_pair("/assets/dist/imgs/feather-icons/", "svg");

			//add the icons to the choices
			if($icons){
				foreach($icons as $key => $value){
					$field["choices"][$key] = $value;
				}
			}

			//return the field
			return($field);
		}

		//getting the theme custom icons
		function populate_theme_custom_icon_select($field){
			//clear out the default choices
			$field["choices"] = [false => "None"];

			//the the array of key value pairs
			$icons = get_files_as_key_name_pair("/assets/dist/imgs/icon-selector-icons/", "svg");

			//add the icons to the choices
			if($icons){
				foreach($icons as $key => $value){
					$field["choices"][$key] = $value;
				}
			}

			//return the field
			return($field);
		}

		//getting the social icons
		function populate_social_icon_select($field){
			//clear out the default choices
			$field["choices"] = [];

			//the the array of key value pairs
			$icons = get_files_as_key_name_pair("/assets/dist/imgs/socials/", "svg");

			//add the icons to the choices
			if($icons){
				foreach($icons as $key => $value){
					$field["choices"][$key] = $value;
				}
			}

			//return the field
			return($field);
		}

	/* Gutenberg Block Filters */
		// Block Filter For Non-ACF Stuff
		function acf_allowed_block_types( $allowed_block_types ) {

			if ((get_post_type() !== "post")) {
				// Var Declaration
				$allowed_block_types = [];

				// Loop over ACF Blocks
				foreach(acf_get_block_types() as $acf_block){
					// Add to Array
					$allowed_block_types[] = $acf_block["name"];
				}

			} else {
				$allowed_block_types = [
					"core/heading",
					"core/image",
					"core/list",
					"core/paragraph",
					"core/separator"
				];
			}
			// Return New Array
			return($allowed_block_types);
		}
		

	/* ACF Block Filters  */

		// Addition Categories Added
		function acf_additional_categories( $categories ){
			// Merge New Categories with Default
			$new_categories = array_merge(
				$categories,
				[
					[
						"slug"  => "hero",
						"title" => "Hero Blocks",
					],
					[
						"slug"  => "content",
						"title" => "Content Blocks",
					]
				]
			);

			// Return New Array
			return ($new_categories);
		}
		

		// Block Filter for Gutten Styles
		function remove_guten_wrapper_styles($settings) {
			$settings["styles"][0] = [];
			return($settings);
		}
		

		// Block Filter for Experimental Patterns
		function remove_guten_block_patterns($settings) {
			$settings["__experimentalBlockPatterns"] = [];
			$settings["__experimentalBlockPatternCategories"] = [];
			return($settings);
		}

		// Check block Visibility (using ACF field "block_is_visible")
		function check_block_visibility( $block_content, $block, $instance ) {
			$is_visible = true;
			if( array_key_exists("attrs", $block) ) {
				if( array_key_exists("data", $block["attrs"]) ) {
					if( array_key_exists("block_is_visible", $block["attrs"]["data"]) ) {
						$is_visible = (bool)$block["attrs"]["data"]["block_is_visible"];
					}
				}
			}
			if( $is_visible == false ) {
				return;
			}
			return $block_content;
		}
		
	/* ACF Admin / Utility Filters */ 

		//Pull API key from options page
		function add_acf_google_map_api( $api ){
			$api["key"] = get_field("option_api_keys_google_maps_key", "options");
			return($api);
		}

		//Load ACF field groups for blocks and options pages
		function load_hy_acf_field_groups($paths) {
			$blocks 	= hy_get_blocks();
			$options	= hy_get_acf_field_groups();
			
			//clear out the default path where acf saves field groups
			$paths = [];

			foreach($blocks as $block) {
				$paths[] = get_theme_file_path("/blocks/{$block}");
			}

			foreach($options as $option){
				$paths[] = get_theme_file_path("/acf-json/{$option}");
			}

			return($paths);
		}

		// Dynamically populate schema locations from company info
		function populate_schema_locations($field) {
			// Clear out the default choices
			$field["choices"] = [];

			// Add empty option
			$field["choices"][""] = "Select a location...";

			// Get schema locations from options
			$schema_locations = get_field("option_company_info_schema", "options");

			if($schema_locations && is_array($schema_locations)) {
				foreach($schema_locations as $index => $location) {
					// Create a display name from the business name and address
					$business_name = !empty($location["option_company_info_schema_name"]) ? $location["option_company_info_schema_name"] : 'Location';
					$city = $location["option_company_info_schema_address_locality"];

					// Use index as value and combine business name + city as label
					$field["choices"][$index] = "{$business_name} - {$city}";
				}
			}

			return $field;
		}

		// Disable Yoast canonical URL for blog archive pages
		function disable_blog_canonical($canonical) {
			if (is_home() || is_archive() && !is_singular()) {
				return false;
			}
			return $canonical;
		}

		// Add custom canonical URL for blog archive pages (initial page load only)
		function add_custom_blog_canonical() {
			if (is_home() || (is_archive() && !is_singular())) {
				global $paged, $wp_query;
				$current_page = max(1, get_query_var("paged", 1));

				// Get the base archive URL (should be /blog/ for posts)
				$base_url = get_post_type_archive_link("post");
				if (!$base_url) {
					// Fallback for default blog: use home_url('/blog/')
					$base_url = home_url("/blog/");
				}

				if ($current_page === 1) {
					$canonical_url = trailingslashit($base_url);
				} else {
					// Append /page/{n}/ to the base URL
					$canonical_url = trailingslashit($base_url) . "page/" . $current_page . "/";
				}

				echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
				echo '<meta name="initial-page-num" content="' . esc_attr($current_page) . '" />' . "\n";
			}
		}

		/**
		 * 
		 * Populate theme icons. When used in a closure, this function will populate the choices of an ACF field with the icons in the theme's icon directory,
		 * with the option to define which directory to load, and to include an empty choice.
		 * 
		 * @param array $field The ACF field to populate
		 * @param string $icon_directory The directory in which the icons are stored
		 * @param bool $empty_choice Whether to include an empty choice in the field
		 * 
		 */
		function populate_icons($field, $icon_directory, $empty_choice = false){
			//clear out the default choices
			$field["choices"] = [];

			if(!$icon_directory)
				return($field);
			
			//the the array of key value pairs
			$icons = get_files_as_key_name_pair("/assets/dist/imgs/{$icon_directory}/", "svg");

			if($empty_choice){
				$field["choices"] = ["" => "None"];
			}

			$field["choices"] = array_merge($field["choices"], $icons);

			//return the field
			return($field);
		}

		// Get ACF field select choices for nav items
		function nav_item_select_choices($field) {

			// Force choices array to exist and be empty
			if ((!isset($field["choices"])) || !empty($field["choices"])) {
				$field["choices"] = [];
			}

			// Get WP Menus
			$wp_menus = wp_get_nav_menus();

			// Populate choices with nav items, including menu title
			foreach ($wp_menus as $wp_menu) {
				$nav_items = wp_get_nav_menu_items($wp_menu->term_id);
				
				if ($nav_items) {
					foreach ($nav_items as $nav_item) {
						$field["choices"][$nav_item->ID] = "{$nav_item->title} ({$wp_menu->name})";
					}
				}
			}

			return $field;
		}

		/**
		 * Container width choices for ACF fields. Allows for consistent container widths across the site.
		 * This makes sure that all direct children of a container have a max width.
		 * But also allows for flexibility to have full width elements inside a container if needed by applying !max-w-full on a child.
		 * @param array $field The ACF field to populate
		 * @return array The modified ACF field
		 */

		function container_width_choices($field) {
			$field["choices"] = [];
			$field["choices"][""] = "Full Design width";
			$field["choices"]["max-w-[31rem]"] = "496px";
			$field["choices"]["max-w-[37.5rem]"] = "600px";
			$field["choices"]["max-w-[40.875rem]"] = "654px";
			$field["choices"]["max-w-md"] = "768px";
			$field["choices"]["max-w-[50.5rem]"] = "808px";
			return($field);
		}



		add_filter("wpseo_canonical", "disable_blog_canonical", 20);
		add_action("wp_head", "add_custom_blog_canonical", 1);
		
		/* Field Filters */
		add_filter("acf/load_field/name=option_header_primary_menu", "menu_select_choices");
		add_filter("acf/load_field/name=option_header_mobile_menu", "menu_select_choices");
		add_filter("acf/load_field/name=option_header_secondary_menu", "menu_select_choices");

		// Footer Menus
		add_filter("acf/load_field/name=option_footer_menu_primary", "menu_select_choices");
		add_filter("acf/load_field/name=option_footer_menu_secondary", "menu_select_choices");
		add_filter("acf/load_field/name=option_footer_menu_quaternary", "menu_select_choices");
		
		add_filter("acf/load_field/name=posts_block_post_type", "post_types_select_choices");

		// Populate ACF select fields with social icons
		add_filter("acf/load_field/name=option_company_info_sub_social_icon", "populate_social_icon_select");

		// Populate ACF select fields with icons
		add_filter("acf/load_field/name=block_custom_list_icon", "populate_theme_icon_select");
		add_filter("acf/load_field/name=block_buttons_button_icon_right", "populate_theme_icon_select");
		add_filter("acf/load_field/name=block_buttons_button_icon_left", "populate_theme_icon_select");
		add_filter("acf/load_field/name=option_header_ctas_icon", "populate_theme_icon_select");		
		add_filter("acf/load_field/name=tax_service_card_icon", "populate_theme_icon_select");
		add_filter("acf/load_field/name=block_our_process_repeater_icon", "populate_theme_icon_select");
		add_filter("acf/load_field/name=option_our_process_repeater_icon", "populate_theme_icon_select");
		add_filter("acf/load_field/name=block_custom_heading_icon", "populate_theme_icon_select");
		add_filter("acf/load_field/name=tax_phase_icon", "populate_theme_icon_select");
		add_filter("acf/load_field/name=nav_item_icon", "populate_theme_icon_select");
		
		// Populate ACF select fields with custom icons
		add_filter("acf/load_field/name=block_step_icon", "populate_theme_custom_icon_select");
		

		// Pupoluate ACF select fields with colors from theme.json
		add_filter("acf/load_field/name=block_industries_headings_color", "colors_select_choices");
		add_filter("acf/load_field/name=block_industries_links_color", "colors_select_choices");
		add_filter("acf/load_field/name=block_industries_asterisk_color", "colors_select_choices");
		add_filter("acf/load_field/name=tax_service_card_color", "colors_select_choices");
		add_filter("acf/load_field/name=block_services_card_bg_color", "colors_select_choices");
		add_filter("acf/load_field/name=block_our_process_repeater_color", "colors_select_choices");
		add_filter("acf/load_field/name=option_our_process_repeater_color", "colors_select_choices");
		add_filter("acf/load_field/name=block_partners_testimonial_testimonial_background_colour", "colors_select_choices");
		add_filter("acf/load_field/name=block_partners_testimonial_testimonial_accent_colour", "colors_select_choices");
		add_filter("acf/load_field/name=block_testimonial_block_accent_colour", "colors_select_choices");
		add_filter("acf/load_field/name=block_custom_heading_icon_colour", "colors_select_choices");
		add_filter("acf/load_field/name=block_custom_list_icon_colour", "colors_select_choices");
		add_filter("acf/load_field/name=tax_phase_color", "colors_select_choices");
		add_filter("acf/load_field/name=block_testimonial_author_text_color", "colors_select_choices");
		add_filter("acf/load_field/name=option_company_info_locations_location_color", "colors_select_choices");
		add_filter("acf/load_field/name=option_company_info_locations_location_color_light_bg", "colors_select_choices");
		

		//Add Button Select Choices
		add_filter("acf/load_field/name=option_header_ctas_style", "button_select_choices");

		// Populate service taxonomy selects
		add_filter("acf/load_field/name=block_services_scroll_parent_service", "populate_parent_services_ids");
		
		// Populate Gravity Form Fields
		// We could possibly combine these?
		add_filter("acf/load_field/name=block_form_select_form", "gravity_forms_id_select_choices");
		add_filter("acf/load_field/name=block_contact_select_contact_form", "gravity_forms_shortcode_select_choices");
		add_filter("acf/load_field/name=block_contact_select_brief_form", "gravity_forms_shortcode_select_choices");
		add_filter("acf/load_field/name=cpt_resource_gform_id", "gravity_forms_id_select_choices");

		// Populate mega-menus trigger select field with nav menu item select choices
		add_filter("acf/load_field/name=option_header_megamenu_trigger_menu_item", "nav_item_select_choices");
		add_filter("acf/load_field/name=option_header_megamenu_nav_menus_select", "menu_select_choices");

		

		// Loading Container Widths
		add_filter("acf/load_field/name=container_settings_width", "container_width_choices");

		//block icon fields
		add_filter("acf/load_field/name=block_icon_icon_select", function($field){
			$icon_directory = "icon-selector-icons";
			return populate_icons($field, $icon_directory, true);
		});
		add_filter("acf/load_field/name=block_featured_image_caption_icon", function($field){
			$icon_directory = "feather-icons";
			return populate_icons($field, $icon_directory, true);
		});

	/* Gutenberg Block Filters */
		// we need to look at these 
		// add_filter( "block_categories_all", "acf_additional_categories" );
		// add_filter( "allowed_block_types_all", "acf_allowed_block_types" );
		// add_filter( "block_editor_settings_all", "remove_guten_wrapper_styles" );
		// add_filter( "block_editor_settings_all", "remove_guten_block_patterns", 11 );

	/* Gutenberg Block Front End Filters */
		add_filter( "render_block", "check_block_visibility", 10, 3 );

	/* ACF Admin / Utility Filters */ 
		add_filter("acf/settings/load_json", "load_hy_acf_field_groups", 1);
		add_filter("acf/fields/google_map/api", "add_acf_google_map_api");

		
		// Add the filter
		add_filter('acf/load_field/name=location_schema_location', 'populate_schema_locations');
		add_filter("acf/load_field/name=block_tile_border_color", "colors_select_choices");

	// Purposefully Leaving PHP Tag Open
