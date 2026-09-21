<?php
/**
* 	The template for displaying all pages
*	
* 	This is the template that displays all pages by default.
* 	Please note that this is the WordPress construct of pages
* 	and that other "pages" on your WordPress site may use a
* 	different template.
*	
* 	@link https://developer.wordpress.org/themes/basics/template-hierarchy/
*	
* 	@package WordPress
* 	@subpackage Hi Yield Wordpress Starter
* 	@since 1.0
*/

get_header();

$term = isset($service_term) ? $service_term : get_queried_object();
$term_id = $term->term_id;
$term_content_post_id = get_field("taxonomy_service_content", "service_{$term_id}");

if($term_content_post_id){
	$term_content_post_query_args = [
		"p" => $term_content_post_id,
		"post_type" => "service"
	];
	$term_content_post_query = new WP_Query($term_content_post_query_args);

	while ($term_content_post_query->have_posts()) { 
		$term_content_post_query->the_post();
		the_content();
	}
}

get_footer(); 
