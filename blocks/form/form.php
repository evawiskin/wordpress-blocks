<?php

	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph"
	];

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
				"placeholder" => "Paragraph text goes here, click the plus button to choose a inner block to add to this container.",
			]
		]
	];

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/form-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;

	// Base classes
	$block_spacing = get_field("block_form_spacing");
	$block_spacing_classes = ($block_spacing && $block_spacing !== "none") ? "inside-container-{$block_spacing}" : ($block_spacing === "none" ? "" : "inside-container-xl");
	$block_classes = new BlockClasses($block, "relative overflow-hidden");
?>
<section class="<?php echo("{$block_classes} {$block_spacing_classes}"); ?>">
	<?php if(array_key_exists("anchor", $block))
		get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>	
	<div class="container">
		<InnerBlocks 
			class="prose mb-8" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>

		<div class="max-w-screen-md">
		<!-- Render form -->
		<?php
			// Define Form Shortcode
			$form_id = get_field('block_form_select_form');
			$form_shorctode = "[gravityform id=" . $form_id . "]";
			
			// Output form
			echo do_shortcode($form_shorctode);
		?>
		</div>
	</div>
</section>