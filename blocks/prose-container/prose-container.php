<?php
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph",
		"core/list",
		"core/image",
		"core/buttons",
		"gravityforms/form",
		"core/embed",
		"hiyield/form",
		"hiyield/statistics",
		"hiyield/buttons"
	];


	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	$block_spacing = get_field("block_prose_container_spacing");
	$block_spacing_classes = ($block_spacing) ? "inside-container-{$block_spacing}" : "inside-container-lg";

	$has_asterisk = get_field("block_prose_container_has_asterisk"); 

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/prose-container-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section class="<?php echo("{$block_spacing_classes} "); if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?> relative overflow-hidden">
	<div class="relative container">
		<?php if(array_key_exists("anchor", $block_data))
				get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
		?>
		<InnerBlocks 
			class="prose mx-auto max-w-[50.5rem] z-10 relative
					<?php
						if($block_text_center) echo("text-center ");
					?>
				" 
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>
	</div>
	<?php if($has_asterisk): ?>
		<div class="absolute top-1/4 md:top-1/2 h-[220%] -translate-y-1/2 -right-[30%] w-1/2 z-0 text-forest-green-600">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 184 191" class="h-full">
				<path fill="currentColor" d="M184 69.24h-56.469l17.445-53.01L94.398 0 76.941 53.01 31.263 20.25 0 62.732l45.679 32.76L0 128.268l31.263 42.484 45.678-32.774L94.398 191l50.578-16.23-17.445-53.01H184V69.24Z"/>
			</svg>
		</div>
	<?php endif; ?>
</section>