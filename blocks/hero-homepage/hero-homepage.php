<?php 
	// Render block preview image.
	if (display_block_preview_image($block)) return;

	/* Get the gutenberg block classes */
	$block_classes = new BlockClasses($block);

	$template = [
		["hiyield/heading-carousel"],
		[
			"core/paragraph", 
			[
				"placeholder" => "Paragraph text goes here",
				"fontSize" => "xl",
				"textColor" => "white"
			]
		],
		["hiyield/buttons"]
	];

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/homepage-hero-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<section class="overflow-x-clip relative <?php echo($block_classes); ?>">

	<?php if(array_key_exists("anchor", $block))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>	

	<div class="absolute top-1/3 -right-[20%] w-1/2 z-0 text-electric-green-500 opacity-20 pointer-events-none">
		<?php echo(get_svg_icon("hiyield-asterisk", "hiyield-icons")); ?>
	</div>

	<div class="container relative z-10">
		<InnerBlocks  
			class="prose max-w-3xl"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>
	</div>
</section>