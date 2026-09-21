<?php


	/**
	 * Returns a template part using WordPress
	 * get_template_part function. Useful for embedding templates
	 * with a fetch call rather than rebuilding elements in js.
	 *
	 * @param string A string must be passed in via javascript
	 * 
	 * @return HTML 
	 */ 

	function ajax_get_template_part(){
		if(
			array_key_exists("template_part", $_GET) &&
			($template_part_path = get_theme_file_path($_GET["template_part"]))
		)
			get_template_part($template_part_path);
			die();

		die();
	}



	// Work blog Filter
	function ajax_block_work_filter() {

		if (!isset($_GET["tax_categories"])) {
			echo(json_encode(
				[
					"content" => "<p class='text-current text-lg font-bold text-center'>Nothing to show, please remove some filters.</p>"
				]
			));
			die();
		}
			
		// Get the category slug(s) and corresponding taxonomy from the query string
		$tax_categories = sanitize_text_field($_GET["tax_categories"]);
	
		// Split the comma-separated string into an array of category slugs
		$tax_category_slugs = explode(",", $tax_categories);
	

		$tax_query = ["relation" => "OR"];
	
		foreach ($tax_category_slugs as $tax_slug) {

			if (!$tax_slug || !isset(explode("_", $tax_slug)[0]))
				continue;

			// Taxonomy and term from string and append to tax query
			$taxonomy = explode("_", $tax_slug)[0];
			$slug = str_replace("{$taxonomy}_", "", $tax_slug);
			$tax_query[] = [
				"taxonomy" => $taxonomy,
				"terms" => [$slug],
				"field" => "slug"
			];
		}

		// Set template args
		$template_args = [
			"bg_color" => "white",
		];
		if (count($tax_query) > 1)
			$template_args["tax_query"] = $tax_query;

		ob_start();
			
			get_template_part(
				"template-parts/partials/partial",
				"work-cards",
				$template_args
			);
	
		$content = ob_get_clean();
	
		echo(json_encode(
			[
				"content" => $content ?: "<p class='text-current text-lg font-bold text-center'>Nothing to show, please remove some filters.</p>"
			]
		));
	
		die();
	}

	// Load more Work posts (infinite scroll)
	function ajax_load_more_work() {
		// Sanitize and retrieve ajax data variables
		$paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
		$posts_per_page = 6;

		// Generate cache key based on paged and other query arguments
		$cache_key = "work_posts_page_" . $paged;
		$cached_response = get_transient($cache_key);

		// If we have a cached response, return it instead of getting post data
		if ($cached_response !== false) {
			wp_send_json_success($cached_response);
		}

		// Build query arguments
		$args = [
			"post_type"      => "work",
			"posts_per_page" => $posts_per_page,
			"paged"          => $paged,
			"post_status"         => "publish",
			"fields"         => "ids",
			"meta_query" => [
                        "relation" => "OR",
                        [
                            "key" => "cpt_work_hide_from_archive",
                            "compare" => "NOT EXISTS",
                            "value" => 0
                        ],
                        [
                            "key" => "cpt_work_hide_from_archive",
                            "compare" => "==",
                            "value" => 0
                        ]
                    ]
			];

		$query = new WP_Query($args);
		$num_posts = 0;

		if ($query->have_posts()) {
			ob_start();
			while($query->have_posts()): $query->the_post();
				$num_posts++;

				get_template_part(
					"template-parts/cards/template-part",
					"work-card",
					[
						"post_id" => get_the_ID()
					]
				);
			endwhile;

			$posts_html = callback(ob_get_clean());

			// Create the cache
			$cache = [
				'content' => $posts_html,
				'current_page' => $paged,
				'num_posts' => $num_posts,
				'max_pages' => $query->max_num_pages
			];

			// Cache the posts for 1 hour
			set_transient($cache_key, $cache, HOUR_IN_SECONDS);

			// Return success response
			wp_send_json_success($cache);
		} else {
			// No more posts
			wp_send_json_success([
				'content' => '',
				'current_page' => $paged,
				'num_posts' => 0,
				'max_pages' => $query->max_num_pages
			]);
		}

		die();
		}

	// Post Filter
	function ajax_filter_posts() {

		global $wp_query;

		$_POST = json_decode(file_get_contents('php://input'), true);
		
		if( !isset($_POST["template_part"]) ) {
			return;
		} else {
			$template_part = $_POST["template_part"];
			get_template_part( $template_part , "", $_POST );
		}

		die();
	}

	// Load modal content from template folder
	function ajax_load_modal_content() {

		if (!isset($_GET["post_id"]) || !isset($_GET["template"]))
			die();

		$post_id = $_GET["post_id"];
		$template = $_GET["template"];

		get_template_part("template-parts/modals/modal-content/modal-content", $template, ["post_id" => $post_id]);
		die();
	}
	
	
	add_action("wp_ajax_ajax_get_template_part", "ajax_get_template_part");
	add_action("wp_ajax_nopriv_ajax_get_template_part", "ajax_get_template_part");

	// Filter posts
	add_action("wp_ajax_ajax_filter_posts", "ajax_filter_posts");
	add_action("wp_ajax_nopriv_ajax_filter_posts", "ajax_filter_posts");

	// Filter for Work filtering block
	add_action("wp_ajax_ajax_block_work_filter", "ajax_block_work_filter");
	add_action("wp_ajax_nopriv_ajax_block_work_filter", "ajax_block_work_filter");

	// Load more Work posts
	add_action('wp_ajax_nopriv_ajax_load_more_work', 'ajax_load_more_work');
	add_action('wp_ajax_ajax_load_more_work', 'ajax_load_more_work');

	// Load modal content
	add_action("wp_ajax_ajax_load_modal_content", "ajax_load_modal_content");
	add_action("wp_ajax_nopriv_ajax_load_modal_content", "ajax_load_modal_content");