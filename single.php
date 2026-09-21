<?php
/**
* 	The template for displaying all single posts
*	
* 	@link https://developer.wordpress.org/themes/basics/template-hierarchy/
*	
* 	@package WordPress
* 	@subpackage Hi Yield Wordpress Starter
* 	@since 1.0
*/

// If viewing a single service - only render the content in this template when viewing in preview mode. Otherwise serve content from taxonomy-service.php
if (get_post_type() == "service" && !is_preview()) {
	$slug = get_post_field("post_name", get_post());
	$service_term = get_term_by("slug", $slug, "service");
	include_once(get_theme_file_path("taxonomy-service.php"));
	exit();
}

get_header(); ?>

<article class="prose">
	<?php
		// Start Post Loop
		while (have_posts()) { 
			the_post();
			set_post_views(get_the_ID());
			the_content();
		}
	?>
</article>

<?php 
get_footer(); 
