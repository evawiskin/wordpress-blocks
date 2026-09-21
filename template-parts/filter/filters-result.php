<?php

	global $wp_query;

    if(!isset($args)) {
        $args = [];
    }

	$current_args = $wp_query->query_vars;

	// Step 1. Define Basic posts query parameters
	// Might be CMSable
	$query_args = [
		"post_type" => "post",
		"post_status"	=> "publish",
		"posts_per_page" => get_option("posts_per_page") ?: 12
	];

	// collect parameters from $args
	if(isset($args["query_args"])) {
		$query_args = array_merge($query_args, $args["query_args"]);
	}

	// Collect parameters from _POST
	if($_POST) {
		$query_args = array_merge($query_args, $_POST, $current_args);
	} else {
		$query_args["ignore_sticky_posts"] = 1;
	}

	// Append page number to query if accessing particular page directly
	if (isset($_GET["pn"])) {
		$query_args["paged"] = $_GET["pn"];
	}

	// Append page number if use /page/n construction;
	$page = get_query_var("paged");
	if($page) {
		$query_args["paged"] = $page;
	}

	$is_filtered = false;
	// Check if filter applied (search and tax query, exclude sorting)
	if (array_key_exists("tax_query", $query_args) || array_key_exists("s", $query_args) ) {
		$is_filtered = true;
	}

	// Get trending articles within current query
	$trending_posts_args = array_merge($query_args, [
		"meta_key" => "post_views_count",
		"orderby" => "meta_value_num",
		"order" => "DESC",
		"fields" => "ids",
		"posts_per_page" => 4,
	]);

	$trending_post_ids = get_posts($trending_posts_args);

	// Prevent repeating posts in main loop (only for non-filtered state)
	if(!$is_filtered) {
		$query_args["post__not_in"] = 
		isset($query_args["post__not_in"]) ? 
		array_merge($trending_post_ids, $query_args["post__not_in"]) : 
		$trending_post_ids;
	}

	// Modify main query
	query_posts( $query_args );

	// Get pagination information after query modifying
	$page_num = $wp_query->query_vars["paged"] ?: 1;
	$pages_count = $wp_query->max_num_pages;

	$page_title = get_field("post_filter_archive_title", "options") ?: "A collection of articles & insights from Hiyield";

	$results_template = "template-parts/filter/filters-result";

	$filter_args = [
        "config" => [
            "search" => [
                "template" => "search"
            ],
            "tax" => [
                [
                    "tax_slug" => ["category", "industry", "client"],
                    "label" => false,
                    "template" => "checkbox"
                ]
            ]
        ],
        "template_part" => $results_template,
        "active_filters" => $query_args
    ];

	$order_args = [
		"config" => [
			"orderby" => [
				"template" => "toggle",
				"label" => "Sort by date",
				"values" => [
					[
						"type" => "date",
						"meta_key" => false, // In case of type="meta_value"
						"desc_label" => "Latest news",
						"asc_label" => "Oldest News" // False to disable it
					]
				]
			]
		],
		"template_part" => $results_template,
		"active_filters" => $query_args
	];

?>

<div class="w-full flex flex-col lg:flex-row gap-8">
    <div class="w-full lg:w-2/3">

		<section class="w-full mx-auto">

			<div class="flex justify-between items-center">

				<?php if(!$is_filtered): ?>
					<h1 class="theme-heading-big text-forest-green-500 mb-16">
						<?php echo($page_title); ?>
					</h1>
				<?php endif; ?>

			</div>

			<!-- Trending Posts loop -->
			<?php if (!$is_filtered && count($trending_post_ids)) : ?>
				<div class="pb-14 lg:pb-28 mb-8 border-b-2 border-forest-green-500">
					<div class="flex gap-6 mb-5 text-forest-green-600 lg:mb-10 items-center">
						<?php if ($trending_icon = get_svg_icon("trending-up-square")) : ?>
							<span class="w-8 h-8 block"><?php echo($trending_icon) ?></span>
						<?php endif; ?>
						<h2 class="text-3xl">Trending</h2>
					</div>
					<div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:gap-x-5 lg:gap-y-10">
						<?php 
							foreach ($trending_post_ids as $index => $post_id) {
								get_template_part(
									"template-parts/cards/template-part", 
									"post-card-compact", 
									[
										"post_id" => $post_id,
										"card_number" => $index + 1,
									]
								);
							}
						?>
					</div>
				</div>
			<?php endif; ?>

			<div class="flex flex-row justify-between item-center mb-8">

				<div>
					<?php if($is_filtered): ?>
						<h2 class="theme-heading-small text-forest-green-500">
							Results
						</h2>
					<?php endif; ?>
				</div>

				<?php
					get_template_part( "template-parts/filter/filters", "all", $order_args );
				?>
			</div>

			<div class="mobile-filters-wrapper"></div>

			<!-- Main Post loop -->
			<?php if(have_posts()): ?>

				<div class="w-full grid grid-cols-1 gap-8 lg:gap-y-16">
					<?php
						while ( have_posts() ) : the_post();

							get_template_part("template-parts/cards/template-part", "post-card");
							
						endwhile; 
						// End of the loop
					?>
				</div>

				<?php
					$pagination_args = [
						"current_page"	=> $page_num,
						"max_num_pages"	=> $pages_count
					];
					get_template_part( "template-parts/filter/filters", "pagination", $pagination_args );
				?>

			<?php else: ?>

				<div>
					<p>No posts found</p>
				</div>

			<?php endif; ?>

		</section>

	</div>

	<aside class="relative w-full lg:w-1/3">
		<div class="sticky sticky-prevent top-16">

			<div class="desktop-filters-wrapper">
				<?php get_template_part( "template-parts/filter/filters", "all", $filter_args ); ?>
			</div>

			<?php get_template_part("template-parts/partials/partial", "post-newsletter"); ?>
		</div>
	</aside>

</div>