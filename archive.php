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

global $post;

// get the archive page if there is one
$has_archive_page = false;
$post_type = get_queried_object()->name;
if($post_type){
	$post_type_underscored = str_replace("-", "_", $post_type);
	$archive_page_id = get_option("page_for_{$post_type_underscored}");
	if($archive_page_id)
		$has_archive_page = true;
}

$blocks_before_archive = [];
$blocks_after_archive = [];
$reached_archive = false;

// get the blocks from the archive page
if($has_archive_page && $archive_page_id){
	$post = get_post($archive_page_id);
	$blocks = parse_blocks( $post->post_content );
	if($blocks)
		foreach($blocks as $block) {
			if($block["blockName"] == "hiyield/archive-placeholder"){
				$reached_archive = true;
				continue;
			}
			if(!$reached_archive)
				$blocks_before_archive[] = $block;
			else
				$blocks_after_archive[] = $block;
		}
}

// // Enqueue ajax filter js
// ajax_filter_enqueue_scripts();

// // Add filter config
// $filter_config = [
// 	"tax" => [
// 		[
// 			"slug" => "category", // slug of taxonomy
// 			"allow_empty" => true, // allow to show terms without posts
// 			"label_singular" => "Category",
// 			"label_plural" => "Select categories",
// 			"template" => "select" // Might be checkbox (multi), radio (single), select (dropdown)
// 		]
// 		],
// 	"orderby" => [
// 		"template" => "select",
// 		"values" => [
// 			[
// 				"type" => "date",
// 				"meta_key" => false, // In case of type="meta_value"
// 				"desc_label" => "Latest news",
// 				"asc_label" => "Oldest News" // False to disable it
// 			]
// 		]
// 	]
// ];

// // Template part with result
// $template_part = "template-parts/filter/filters-result";
// // Load template part with Filters & Sorting bar
// $args = [
// 	"config" => $filter_config,
// 	"template_part" => $template_part
// ];

get_header();

    // get the block before the content
	if($blocks_before_archive)
		foreach($blocks_before_archive as $block)
			echo(render_block($block));

		//right now I am thinking we can do the filtering component actually inside of a block rather than doing this?
		//each of the 'archive' pages are very different and don't strictly have filters
		// i am thinking we set the about page as the 'archive' page for people and then pull in the people block
		// services page for services and again there's a variety of blocks
		// work page for work and then there's a 'work' filters component.
	
    // get the block after the content
	if($blocks_after_archive)
		foreach($blocks_after_archive as $block)
			echo(render_block($block));

get_footer();
