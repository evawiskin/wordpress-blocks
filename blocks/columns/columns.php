<?php
	$allowed_blocks = [
		"hiyield/column"
	];

	$block_data = $block;
	$block_classes = ($block_data && array_key_exists("className", $block_data)) ? $block_data["className"] : "";
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

	$block_spacing = get_field("block_columns_spacing");
	$block_spacing_classes = ($block_spacing) ? "inside-container-{$block_spacing}" : "inside-container-lg";
	$block_has_margin = get_field("block_columns_margin");
	$section_heading = get_field("block_columns_heading");

	$template = [];
	$column_count = (get_field("block_columns_count")) ? get_field("block_columns_count") : "2";
	for($i = 0; $i < $column_count; $i++) {
		$template[] = ["hiyield/column"];
	}

	$gap = (get_field("block_columns_gap")) ? get_field("block_columns_gap") : "sm";
	$align_items = (get_field("block_columns_align_items")) ? get_field("block_columns_align_items") : "flex-start";

	switch($gap) {
		case "sm":
			$gap = "6";
			$mobile_gap = "4";
			break;
		case "md":
			$gap = "10";
			$mobile_gap = "6";
			break;
		case "lg":
			$gap = "16";
			$mobile_gap = "8";
			break;
		case "xl":
			$gap = "24";
			$mobile_gap = "12";
			break;
	}


	$bg_img = get_field("block_columns_background_image");
	$bg_img_has_filter = get_field("block_columns_add_background_image_filter");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/columns-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section class="relative <?php echo("{$block_classes} {$block_spacing_classes} "); if($block_has_margin) echo("m-4 lg:m-8 2xl:m-12 "); if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<?php if(array_key_exists("anchor", $block_data))
		get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	
	<?php if($bg_img): ?>
		<div class="absolute inset-0">
			<?php echo(wp_get_attachment_image($bg_img, "full", false, ["class" => "w-full h-full object-cover"])); ?>
			<?php if($bg_img_has_filter): ?>
				<div class="absolute inset-0 <?php echo("bg-{$block_bg}"); ?> opacity-50 pointer-events-none"></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="relative container">
		<?php if($section_heading): ?>
			<div class="text-center mb-8">
				<h3><?php echo($section_heading); ?></h3>
			</div>
		<?php endif; ?>
		<InnerBlocks 
			class="grid <?php echo("grid-cols-1 lg:grid-cols-{$column_count} gap-{$mobile_gap} md:gap-{$gap} items-{$align_items}"); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>
	</div>
</section>