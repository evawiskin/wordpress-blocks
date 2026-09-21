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

get_header(); 

$post_id = get_the_ID();
?>

<article class="bg-gretter-50 text-forest-green-600 ">
	
	<?php 
		// Get the post banner
		get_template_part(
			"template-parts/partials/partial", 
			"work-banner", 
			[
				"post_id" => $post_id,
				"post_type" => get_post_type($post_id),
				"post_type_name" => "Work"
			]
		); 
	?>

	<div class="prose">
		<?php
			// Start Post Loop
			while (have_posts()) { 
				the_post();
				set_post_views(get_the_ID());
				the_content();
			}
		?>
	</div>
</article>

<?php 
get_footer(); 
