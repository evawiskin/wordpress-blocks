<?php
	/* Init ACF Blocks */
	function hy_acf_blocks_load(){
		$blocks = hy_get_blocks();		
		//iterate through each block to register them
		foreach($blocks as $block) {
			//check if block has a block.json file and register if so
			if (file_exists(get_theme_file_path("/blocks/{$block}/block.json")))
				register_block_type(get_theme_file_path("/blocks/{$block}/block.json"));
		}
	}
	
	/* Init ACF options pages */
	function acf_options_pages_init() {

		// Check function exists.
		if(function_exists("acf_add_options_sub_page")) {

			// Add options page parent.
			$parent = acf_add_options_page([
				"page_title"  => "Theme Settings",
				"menu_title"  => "Settings",
				"menu_slug"	  => "hy-site-settings",
				"redirect"    => true,
				"capability" => "edit_posts"
			]);

			// Add options header sub page.
			acf_add_options_sub_page([
				"page_title"  => "Header Settings",
				"menu_title"  => "Header Settings",
				"menu_slug"	  => "hy-header-settings",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);

			// Add options footer sub page.
			acf_add_options_sub_page([
				"page_title"  => "Footer Settings",
				"menu_title"  => "Footer Settings",
				"menu_slug"	  => "hy-footer-settings",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);

			// Add options footer sub page.
			acf_add_options_sub_page([
				"page_title"  => "Our Process",
				"menu_title"  => "Our Process",
				"menu_slug"	  => "hy-our-process",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);
			
			// Add Company Settings sub page.
			acf_add_options_sub_page([
				"page_title"  => "Company Settings",
				"menu_title"  => "Company Settings",
				"menu_slug"	  => "hy-company-settings",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);

			// Add API Keys Settings Page
			acf_add_options_sub_page([
				"page_title"  => "API Keys Settings",
				"menu_title"  => "API Keys Settings",
				"menu_slug"	  => "hy-api-keys-settings",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);

			// Add API Keys Settings Page
			acf_add_options_sub_page([
				"page_title"  => "404 Not Found Settings",
				"menu_title"  => "404 Not Found Settings",
				"menu_slug"	  => "hy-not-found-settings",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);

			// Add Cookie Popup Settings Page
			acf_add_options_sub_page([
				"page_title"  => "Cookie Popup Settings",
				"menu_title"  => "Cookie Popup Settings",
				"menu_slug"	  => "hy-cookie-popup-settings",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);

			//Add Post type options pages
			acf_add_options_sub_page([
				"page_title"  => "Blog Settings",
				"menu_title"  => "Blog Settings",
				"menu_slug"	  => "hy-blog-settings",
				"parent_slug" => "edit.php",
				"capability" => "edit_posts"
			]);

			acf_add_options_sub_page([
				"page_title"  => "Resources Settings",
				"menu_title"  => "Resources Settings",
				"menu_slug"	  => "hy-resources-settings",
				"parent_slug" => $parent["menu_slug"],
				"capability" => "edit_posts"
			]);
		}
	}

	// Add fields to the team taxonomy for team members
	function add_fields_to_team_taxonomy() {

		if (!function_exists("acf_add_local_field_group")) 
			return;
		
		$args = [
			"key" => "taxonomy_team_field_group_key",
			"title" => "Team Attributes",
			"fields" => [
				[
					"key" => "taxonomy_team_is_leadership_role_key",
					"label" => "Is this a Leadership Role?",
					"name" => "taxonomy_team_is_leadership_role",
					"type" => "true_false"
				]
			],
			"location" => [
				[
					[
						"param" => "taxonomy",
						"operator" => "==",
						"value" => "team",
					]
				]
			],
			"position" => "normal"
		];

		acf_add_local_field_group($args);
	}

	/* Init ACF Blocks */
	add_action("init","hy_acf_blocks_load", 5);
	/* Init ACF options pages */
	add_action("acf/init", "acf_options_pages_init");
	// Add fields to the team taxonomy for team members
	add_action("acf/include_fields", "add_fields_to_team_taxonomy");


	/* Custom 404 Trigger if Person has a modal*/ 
	function person_404_trigger() {

		$post_type = get_post_type();

		if(!is_singular("person")) {
			return;
		}

		if( get_field("cpt_person_display_modal", get_the_ID()) ) {
			global $wp_query;
			$wp_query->set_404();
			status_header(404);
		}

	}
	add_action("wp", "person_404_trigger");

	/* Custom 404 Trigger if Work Item should not be seen */ 
	function work_404_trigger() {

		$post_type = get_post_type();

		if(!is_singular("work")) {
			return;
		}

		if(get_field("cpt_work_hide_single_page", get_the_ID()) ) {
			global $wp_query;
			$wp_query->set_404();
			status_header(404);
		}
	}
	add_action("wp", "work_404_trigger");



