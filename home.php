<?php

	/**
	*	The template for displaying the 'Posts' Archive Page.
	*
	* 	@link https://developer.wordpress.org/themes/template-files-section/post-template-files/#home-php
	*
	* 	@package WordPress
	* 	@subpackage Hi Yield Wordpress Starter
	* 	@since 1.0
	*/

	get_header();

	// Enqueue ajax filter js
	ajax_filter_enqueue_scripts();

    $query_args = [];
    $results_template = "template-parts/filter/filters-result";

    // Collect parameters from _GET
	if($_GET) {
		foreach($_GET as $key => $value) {
			if($key == "tax_query") {
				foreach($value as $tax_query_item) {
					$tax_query_item = json_decode(stripslashes(html_entity_decode($tax_query_item)), true);
					$query_args["tax_query"][] = [
						"taxonomy" => $tax_query_item["tax"],
						"field" => "slug",
						"terms" => $tax_query_item["terms"]
					];
				}
				continue;
			}
			$query_args[$key] = $value;
		}
	}

    $page_title = get_field("post_filter_archive_title", "options") ?: "A collection of articles & insights from Hiyield";

?>

<div class="relative overflow-clip bg-gretter-50  text-forest-green-600 py-20">
    <div class="absolute -top-[20%] -right-[20%] w-1/2 z-0 text-white opacity-20 pointer-events-none">
		<?php echo(get_svg_icon("hiyield-asterisk", "hiyield-icons")); ?>
	</div>
    <div class="container">

        <div id="ajax-filterable" data-template-part="<?php echo($results_template); ?>">
            <?php get_template_part($results_template, "", ["query_args" => $query_args]);?>
        </div>

    </div>
</div>

<?php
    // Output page content blocks if there are any
    $blog_page_object = get_option("page_for_posts");
    $post = get_post($blog_page_object);
    $blocks = parse_blocks($post->post_content);

    if($blocks)
		foreach($blocks as $block) {
			echo(render_block($block));
		}

	get_footer();
?>