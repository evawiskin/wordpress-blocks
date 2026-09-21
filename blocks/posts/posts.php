<?php 
	//Placeholder content when the block loads in the editor.
	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here"
			]
		],
		[
			"core/paragraph",
			[
				"placeholder" => "Paragraph text goes here"
			]
		]
	];
	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph"
	];

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

	$post_type = get_field("block_posts_post_type") ?: "post";
	$posts_per_page = get_field("block_posts_number_of_posts") ?: 3;
	$number_of_columns = get_field("block_posts_number_of_columns") ?: 3;
	$select_posts = get_field("block_posts_select_posts");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/posts-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<section class="relative inside-container <?php if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	
	<div class="container">
		<InnerBlocks  
			class="prose" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>

		<?php
			$args = [
				"post_type"      => $post_type,
				"posts_per_page" => $posts_per_page,
				"post__in" 		 => $select_posts
			];

			$query = new WP_Query($args);

			if ($query->have_posts()) :
		?>
			<div class="grid grid-cols-1 lg:grid-cols-<?php echo($number_of_columns); ?> gap-16">
				<?php while ($query->have_posts()): $query->the_post(); ?>
					<div class="col-span-1">
						<?php get_template_part( "template-parts/cards/template-part", "post-card" ); ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php
			else:
				echo("No posts found.");
			endif;

			wp_reset_postdata();
		?>

	</div>
</section>