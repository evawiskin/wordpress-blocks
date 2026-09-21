<?php
	get_header(); 

	$post_id = get_the_ID();
?>

<article class="bg-gretter-50 text-forest-green-600 ">
	<?php
		// Get the post banner
		get_template_part(
			"template-parts/partials/partial", 
			"job-banner", 
			[
				"post_id" => $post_id,
				"post_type" => get_post_type($post_id),
				"post_type_name" => "Careers"
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
