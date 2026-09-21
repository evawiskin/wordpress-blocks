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
			"post-banner", 
			[
				"post_id" => $post_id,
				"post_type" => get_post_type($post_id),
				"post_type_name" => "Blog"
			]
		); 

		get_template_part("template-parts/partials/partial", "reading-progress", ["progress_type" => "horizontal_mobile"]);
	?>

	<div id="reading-progress-container" class="container grid lg:grid-cols-12">
		<div class="lg:col-span-4 lg:pr-16 xl:pr-28">
			<div class="sticky sticky-prevent top-8 mt-8 lg:mt-16 lg:pb-16 space-y-8">
				<?php
					get_template_part("template-parts/partials/partial", "table-of-contents", ["post_id" => $post_id]);

					get_template_part("template-parts/partials/partial", "reading-progress", ["progress_type" => "horizontal"]);

					$args = [
						"icons" => ["twitter", "facebook", "linkedin"],
						"icon_classes" => "text-forest-green-500",
						"wrapper_classes" => "gap-x-8 justify-between"
					];

					get_template_part("template-parts/partials/partial", "post-share-buttons", $args);
				
					get_template_part("template-parts/partials/partial", "post-newsletter");
				?>
			</div>
		</div>

		<div class="w-full lg:col-span-8 lg:col-start-5 prose py-12 lg:py-16">
			<?php
				// Start Post Loop
				while (have_posts()) { 
					the_post();
					set_post_views(get_the_ID());
						the_content();
					}
				?>
		</div>
	</div>

	<div>
		<?php 
			$args = [
				"post_id" => $post_id
			];

			get_template_part("template-parts/partials/partial", "related-posts", $args);

			get_template_part("template-parts/partials/partial", "post-get-started", $args);
		?>
	</div>
	
</article>

<?php 
get_footer(); 
