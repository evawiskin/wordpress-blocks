<?php
	if ((!isset($block)) || !is_array($block))
		return;

	// Render block preview image.
	if (display_block_preview_image($block)) return;
	
	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here",
				"fontSize" => "text-hy-5xl"
			]
		],
		[
			"core/paragraph",
			[
				"placeholder" => "Paragraph text goes here, click the plus button to choose a inner block to add to this container.",
				"fontSize" => "text-hy-lg"
			]
		]
	];

	$allowed_blocks = get_allowed_blocks(["hiyield/timeline", "hiyield/tabs"]);
	$block_id = set_block_id($block);

	/* Get the gutenberg block classes */
	$block_classes = new BlockClasses($block);

	/* Get the custom block classes */
	$block_container_width = get_field(selector: "container_settings_width");
	$block_container_spacing = get_field("block_container_spacing");
	$block_spacing_class = $block_container_spacing ? "inside-container-{$block_container_spacing}" : "";

?>

<section id="<?php echo($block_id); ?>" class="relative w-full <?php echo(trim($block_classes . " " . $block_spacing_class)); ?>">
	<?php do_action("hy_block_start", $block); ?>	
	<div class="relative container flex <?php echo($block_classes->justify); ?>">
		<InnerBlocks 
			class="w-full prose <?php if($block_container_width) echo($block_container_width); ?>" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>
	</div>
</section>