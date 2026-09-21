<?php

	/**
	 * Registering Custom Taxonomy
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
	 */

	// Custom Taxonomy Utility Functions
	function get_custom_taxonomy_labels(string $singular, string $plural){
		return([
			"name"                       => _x($plural, "taxonomy general name", "textdomain"),
			"singular_name"              => _x($singular, "taxonomy singular name", "textdomain"),
			"search_items"               => __("Search {$plural}", "textdomain"),
			"popular_items"              => __("Popular {$plural}", "textdomain"),
			"all_items"                  => __("All {$plural}", "textdomain"),
			"parent_item"                => null,
			"parent_item_colon"          => null,
			"edit_item"                  => __("Edit {$singular}", "textdomain"),
			"update_item"                => __("Update {$singular}", "textdomain"),
			"add_new_item"               => __("Add New {$singular}", "textdomain"),
			"new_item_name"              => __("New Example {$singular}", "textdomain"),
			"separate_items_with_commas" => __("Separate {$plural} with commas", "textdomain"),
			"add_or_remove_items"        => __("Add or remove {$plural}", "textdomain"),
			"choose_from_most_used"      => __("Choose from the most used {$plural}", "textdomain"),
			"not_found"                  => __("No {$plural} found.", "textdomain"),
			"menu_name"                  => __($plural, "textdomain")
		]);
	}

	/**
	 * Using Custom Taxonomy
	 *
	 * @link https://developer.wordpress.org/plugins/taxonomies/working-with-custom-taxonomies/
	 */
	function example_taxonomies_init() {

		// Taxonomy Arguments
		$args = [
			"hierarchical"          => true,
			"labels"                => get_custom_taxonomy_labels("Example", "Examples"),
			"show_ui"               => true,
			"show_admin_column"     => true,
			"show_in_rest"			=> true,
			"update_count_callback" => "_update_post_term_count",
			"query_var"             => true,
			"rewrite"               => [ "slug" => false, "with_front" => false ]
		];

		// Register Tax ( Taxonomy Slug, Post Type Assignment, Arguments )
		register_taxonomy( "example_category", "example", $args );
	}

	// Add Example Taxonomy
	// add_action( "init", "example_taxonomies_init" );



	// Add FAQ Subject Taxonomy
	function faq_subject_init() {

		// Taxonomy Arguments
		$args = [
			"hierarchical"          => true,
			"labels"                => get_custom_taxonomy_labels("FAQ Subject", "FAQ Subjects"),
			"show_ui"               => true,
			"show_admin_column"     => true,
			"show_in_rest"			=> true,
			"update_count_callback" => "_update_post_term_count",
			"query_var"             => true,
			"rewrite"               => [ "slug" => false, "with_front" => false ]
		];

		// Register Tax ( Taxonomy Slug, Post Type Assignment, Arguments )
		register_taxonomy( "faq_subject", "faq_subject", $args );
	}

	// Add Person Team Taxonomy
	function team_init() {

		// Taxonomy Arguments
		$args = [
			"hierarchical"          => true,
			"labels"                => get_custom_taxonomy_labels("Team", "Team"),
			"show_ui"               => true,
			"show_admin_column"     => true,
			"show_in_rest"			=> true,
			"update_count_callback" => "_update_post_term_count",
			"query_var"             => true,
			"rewrite"               => [ "slug" => false, "with_front" => false ]
		];

		// Register Tax ( Taxonomy Slug, Post Type Assignment, Arguments )
		register_taxonomy( "team", ["person"], $args );
	}

	function client_init(){

		// Taxonomy Arguments
		$args = [
			"hierarchical"          => true,
			"labels"                => get_custom_taxonomy_labels("Client", "Clients"),
			"show_ui"               => true,
			"show_admin_column"     => true,
			"show_in_rest"			=> true,
			"update_count_callback" => "_update_post_term_count",
			"query_var"             => true,
			"rewrite"               => [ "slug" => false, "with_front" => false ]
		];

		// Register Tax ( Taxonomy Slug, Post Type Assignment, Arguments )
		register_taxonomy("client", ["post", "testimonial", "work"], $args );
	}

	function industry_init(){

		// Taxonomy Arguments
		$args = [
			"hierarchical"          => true,
			"labels"                => get_custom_taxonomy_labels("Industry", "Industries"),
			"show_ui"               => true,
			"show_admin_column"     => true,
			"show_in_rest"			=> true,
			"update_count_callback" => "_update_post_term_count",
			"query_var"             => true,
			"rewrite"               => [ "slug" => false, "with_front" => false ]
		];

		// Register Tax ( Taxonomy Slug, Post Type Assignment, Arguments )
		register_taxonomy("industry", ["post", "testimonial", "work"], $args );
	}

	function service_tax_init(){
		// Taxonomy Arguments
		$args = [
			"hierarchical"          => true,
			"labels"                => get_custom_taxonomy_labels("Service", "Services"),
			"show_ui"               => true,
			"show_admin_column"     => true,
			"show_in_rest"			=> true,
			"update_count_callback" => "_update_post_term_count",
			"query_var"             => true,
			"rewrite"               => [ "slug" => false, "with_front" => false ]
		];

		// Register Tax ( Taxonomy Slug, Post Type Assignment, Arguments )
		register_taxonomy("service", ["post", "service", "testimonial", "work"], $args );
	}

	function phase_init(){
		// Taxonomy Arguments
		$args = [
			"hierarchical"          => true,
			"labels"                => get_custom_taxonomy_labels("Phase", "Phases"),
			"show_ui"               => true,
			"show_admin_column"     => true,
			"show_in_rest"			=> true,
			"update_count_callback" => "_update_post_term_count",
			"query_var"             => true,
			"rewrite"               => [ "slug" => false, "with_front" => false ]
		];

		// Register Tax ( Taxonomy Slug, Post Type Assignment, Arguments )
		register_taxonomy("phase", ["service"], $args );
	}


	// Add FAQ Subject Taxonomy
	add_action( "init", "faq_subject_init" );

	// Add Person Team Taxonomy
	add_action( "init", "team_init" );

	// Add Client Taxonomy
	add_action( "init", "client_init" );

	// Add Industry Taxonomy
	add_action( "init", "industry_init" );

	// Add Service Taxonomy
	add_action( "init", "service_tax_init" );

	// Add Phase (for Service CPT) Taxonomy
	add_action( "init", "phase_init" );



// Purposefully Leaving PHP Tag Open


