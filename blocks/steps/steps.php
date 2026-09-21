<?php
	$template = [
        [
			"hiyield/step"
		],
		[
			"hiyield/step"
		],
		[
			"hiyield/step"
		]
	];

    // Enqueue tabs js file
    tabs_enqueue_scripts();

	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"hiyield/step"
	];

    $block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

	$block_spacing = get_field("block_steps_spacing");
	$block_spacing_classes = ($block_spacing) ? "inside-container-{$block_spacing}" : "inside-container-lg";

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/steps-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section
    class="<?php echo("{$block_spacing_classes} "); if($block_bg) echo(" bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">

    <!-- Tabs Body -->
	<div class="container py-8 prose">
        <InnerBlocks
            class="grid grid-cols-1 lg:grid-cols-3 gap-8"
            allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
            template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
        />
    </div>
</section>