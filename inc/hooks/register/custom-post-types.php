<?php

	/**
	 * Registering Custom Post Types
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_post_type/
	*/

	/**
	 * Using Custom Post Types
	 *
	 * @link https://developer.wordpress.org/plugins/post-types/working-with-custom-post-types/
	 */

	function get_custom_post_type_labels(string $singular, string $plural){
		return([
			"name"                  => _x( $plural, "Post type general name", "textdomain" ),
			"singular_name"         => _x( "{$singular}", "Post type singular name", "textdomain" ),
			"menu_name"             => _x( $plural, "Admin Menu text", "textdomain" ),
			"name_admin_bar"        => _x( "{$singular}", "Add New on Toolbar", "textdomain" ),
			"add_new"               => __( "Add New", "textdomain" ),
			"add_new_item"          => __( "Add New {$singular}", "textdomain" ),
			"new_item"              => __( "New {$singular}", "textdomain" ),
			"edit_item"             => __( "Edit {$singular}", "textdomain" ),
			"view_item"             => __( "View {$singular}", "textdomain" ),
			"all_items"             => __( "All {$plural}", "textdomain" ),
			"search_items"          => __( "Search {$plural}", "textdomain" ),
			"parent_item_colon"     => __( "Parent {$plural}:", "textdomain" ),
			"not_found"             => __( "No {$plural} found.", "textdomain" ),
			"not_found_in_trash"    => __( "No {$plural} found in Trash.", "textdomain" ),
			"featured_image"        => _x( "{$singular} Cover Image", "Overrides the \"Featured Image\" phrase for this post type. Added in 4.3", "textdomain" ),
			"set_featured_image"    => _x( "Set cover image", "Overrides the \"Set featured image\" phrase for this post type. Added in 4.3", "textdomain" ),
			"remove_featured_image" => _x( "Remove cover image", "Overrides the \"Remove featured image\" phrase for this post type. Added in 4.3", "textdomain" ),
			"use_featured_image"    => _x( "Use as cover image", "Overrides the \"Use as featured image\" phrase for this post type. Added in 4.3", "textdomain" ),
			"archives"              => _x( "{$singular} archives", "The post type archive label used in nav menus. Default \"Post Archives\". Added in 4.4", "textdomain" ),
			"insert_into_item"      => _x( "Insert into {$singular}", "Overrides the \"Insert into post\"/\"Insert into page\" phrase (used when inserting media into a post). Added in 4.4", "textdomain" ),
			"uploaded_to_this_item" => _x( "Uploaded to this {$singular}", "Overrides the \"Uploaded to this post\"/\"Uploaded to this page\" phrase (used when viewing media attached to a post). Added in 4.4", "textdomain" ),
			"filter_items_list"     => _x( "Filter {$plural} list", "Screen reader text for the filter links heading on the post type listing screen. Default \"Filter posts list\"/\"Filter pages list\". Added in 4.4", "textdomain" ),
			"items_list_navigation" => _x( "{$plural} list navigation", "Screen reader text for the pagination heading on the post type listing screen. Default \"Posts list navigation\"/\"Pages list navigation\". Added in 4.4", "textdomain" ),
			"items_list"            => _x( "{$plural} list", "Screen reader text for the items list heading on the post type listing screen. Default \"Posts list\"/\"Pages list\". Added in 4.4", "textdomain" )
		]);
	}

	function accreditation_init(){
		$args = [
			"labels" 				=> get_custom_post_type_labels("Accreditation", "Accreditations"),
			"description" 			=> __("Description."),
			"public" 				=> false,
			"publicly_queryable" 	=> false,
			"show_ui" 				=> true,
			"show_in_menu" 			=> true,
			"menu_icon" 			=> "dashicons-awards",
			"query_var" 			=> true,
			"rewrite" 				=> ["slug" => false, "with_front" => false],
			"capability_type" 		=> "post",
			"has_archive" 			=> false,
			"hierarchical" 			=> false,
			"menu_position" 		=> null,
			"show_in_rest" 			=> true,
			"edit_in_rest" 			=> true,
			"rest_base" 			=> "api/accreditations",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"taxonomies"			=> [],
			"supports" 				=> ["title", "custom-fields", "revisions"]
		];

		register_post_type("accreditation", $args, "normal");
	}

	// FAQ Custom Post Type Init
	function faq_init() {
		$args = [
			"labels" 				=> get_custom_post_type_labels("FAQ", "FAQs"),
			"description" 			=> __("Description."),
			"public" 				=> true,
			"publicly_queryable" 	=> false,
			"show_ui" 				=> true,
			"show_in_menu" 			=> true,
			"menu_icon" 			=> "dashicons-editor-help",
			"query_var" 			=> true,
			"rewrite" 				=> ["slug" => false, "with_front" => false],
			"capability_type" 		=> "post",
			"has_archive" 			=> false,
			"hierarchical" 			=> true,
			"menu_position" 		=> null,
			"show_in_rest" 			=> true,
			"edit_in_rest" 			=> true,
			"rest_base" 			=> "api/faqs",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"taxonomies"			=> ["faq_subject"],
			"supports" 				=> ["title", "custom-fields"]
		];

		register_post_type("faq", $args, "normal");
	}

	function job_init(){
		$args = [
			"labels" 				=> get_custom_post_type_labels("Job", "Jobs"),
			"description" 			=> __("Description."),
			"public" 				=> true,
			"publicly_queryable" 	=> true,
			"show_ui" 				=> true,
			"show_in_menu" 			=> true,
			"menu_icon" 			=> "dashicons-businessman",
			"query_var" 			=> true,
			"rewrite" 				=> ["slug" => "careers", "with_front" => false],
			"capability_type" 		=> "post",
			"has_archive"           => get_post_type_archive_page_slug("job"),
			"hierarchical" 			=> false,
			"menu_position" 		=> null,
			"show_in_rest" 			=> true,
			"edit_in_rest" 			=> true,
			"rest_base" 			=> "api/jobs",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"taxonomies"			=> [],
			"supports" 				=> ["title", "editor", "custom-fields", "revisions", "thumbnail"],
			"template"				=> [
				[
					"hiyield/container",
					[
						"backgroundColor" => "gretter-50",
						"textColor" => "forest-green-600",
						"align" => "center",
						"data" => [
							"container_settings_width" => "max-w-[50.5rem]"
						]
					]
				]
			]
		];

		register_post_type("job", $args, "normal");
	}

	// Person Custom Post Type
	function person_init(){
		$args = [
			"labels"					=> get_custom_post_type_labels("Person", "People"),
			"description"				=> __("Description."),
			"public"					=> true,
			"publicly_queryable"		=> true,
			"show_ui"					=> true,
			"show-in_menu"				=> true,
			"menu_icon"					=> "dashicons-groups",
			"query_var"					=> true,
			"rewrite"                   => [ "slug" => false, "with_front" => false ],
			"capability_type"           => "post",
			"has_archive"           	=> false,
			"hierarchical"              => false,
			"menu_position"             => null,
			"show_in_rest" 				=> true,
			"edit_in_rest" 				=> true,
			"rest_base"                 => "api/people",
			"rest_controller_class"     => "WP_REST_Posts_Controller",
			"taxonomies"				=> ["team"],
			"supports"                  => ["title", "editor", "custom-fields", "revisions", "thumbnail"]
		];

		register_post_type("person", $args, "normal"); 
	}

	// Testimonial Custom Post Type Init
	function testimonial_init() {
		$args = [
			"labels" 				=> get_custom_post_type_labels("Testimonial", "Testimonials"),
			"description" 			=> __("Description."),
			"public" 				=> false,
			"publicly_queryable" 	=> false,
			"show_ui" 				=> true,
			"show_in_menu" 			=> true,
			"menu_icon" 			=> "dashicons-format-quote",
			"query_var" 			=> true,
			"rewrite" 				=> ["slug" => false, "with_front" => false],
			"capability_type" 		=> "post",
			"has_archive" 			=> false,
			"hierarchical" 			=> false,
			"menu_position" 		=> null,
			"show_in_rest" 			=> true,
			"edit_in_rest" 			=> true,
			"rest_base" 			=> "api/testimonials",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"taxonomies"				=> ["category", "client", "industry"],
			"supports" 				=> ["title", "custom-fields", "revisions", "thumbnail"]
		];

		register_post_type("testimonial", $args, "normal");
	}

	// Service Custom Post Type Init
	function service_init(){
		$args = [
			"labels" 				=> get_custom_post_type_labels("Service", "Services"),
			"description" 			=> __("Description."),
			"public" 				=> true,
			"publicly_queryable" 	=> true,
			"show_ui" 				=> true,
			"show_in_menu" 			=> true,
			"menu_icon" 			=> "dashicons-money-alt",
			"query_var" 			=> true,
			"rewrite" 				=> ["slug" => "services", "with_front" => false], // Edge-case: Use plural slug to match archive page name and improve SEO
			"capability_type" 		=> "post",
			"has_archive"           => get_post_type_archive_page_slug("service"),
			"hierarchical" 			=> false,
			"menu_position" 		=> null,
			"show_in_rest" 			=> true,
			"edit_in_rest" 			=> true,
			"rest_base" 			=> "api/services",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"taxonomies"				=> [],
			"supports" 				=> ["title", "custom-fields", "editor", "revisions", "thumbnail"]
		];

		register_post_type("service", $args, "normal");

	}

	// Work Custom Post Type Init
	function work_init(){
		$args = [
			"labels" 				=> get_custom_post_type_labels("Work", "Work"),
			"description" 			=> __("Description."),
			"public" 				=> true,
			"publicly_queryable" 	=> true,
			"show_ui" 				=> true,
			"show_in_menu" 			=> true,
			"menu_icon" 			=> "dashicons-portfolio",
			"query_var" 			=> true,
			"rewrite" 				=> ["slug" => false, "with_front" => false],
			"capability_type" 		=> "post",
			"has_archive"           => get_post_type_archive_page_slug("work"),
			"hierarchical" 			=> false,
			"menu_position" 		=> null,
			"show_in_rest" 			=> true,
			"edit_in_rest" 			=> true,
			"rest_base" 			=> "api/work",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"taxonomies"			=> ["client", "industry", "service"],
			"supports" 				=> ["title", "editor", "custom-fields", "revisions", "thumbnail"]
		];

		register_post_type("work", $args, "normal");
	}

	// Resource Custom Post Type Init
	function resource_init(){
		$args = [
			"labels" 				=> get_custom_post_type_labels("Resource", "Resources"),
			"description" 			=> __("Description."),
			"public" 				=> true,
			"publicly_queryable" 	=> true,
			"show_ui" 				=> true,
			"show_in_menu" 			=> true,
			"menu_icon" 			=> "dashicons-media-document",
			"query_var" 			=> true,
			"rewrite" 				=> ["slug" => "resources", "with_front" => false],
			"capability_type" 		=> "post",
			"has_archive"           => get_post_type_archive_page_slug("resource") ?: "resources",
			"hierarchical" 			=> false,
			"menu_position" 		=> null,
			"show_in_rest" 			=> true,
			"edit_in_rest" 			=> true,
			"rest_base" 			=> "api/resources",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"taxonomies"			=> [],
			"supports" 				=> ["title", "editor", "custom-fields", "revisions", "thumbnail"]
		];

		register_post_type("resource", $args, "normal");
	}

	// Add Accreditation CPT
	add_action("init", "accreditation_init");

	// Add FAQ CPT
	add_action("init", "faq_init");

	// Add job CPT
	add_action("init", "job_init");

	// Add Person CPT
	add_action("init", "person_init");

	// Add Service CPT
	add_action("init", "service_init");

	// Add Testimonial CPT
	add_action("init", "testimonial_init");

	// Add Work CPT
	add_action("init", "work_init");

	// Add Resource CPT
	add_action("init", "resource_init");


// Purposefully Leaving PHP Tag Open

