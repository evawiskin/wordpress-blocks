<?php
	$allowed_blocks = ["core/heading"];
	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"textColor" => "white",
				"fontSize" => "big-heading",
				"placeholder" => "Heading Goes Here"
			]
		],
	];

	$has_asterisk = get_field("block_custom_heading_asterisk") ?: false;
	$asterisk_position_classes = "-top-1.5 -left-1.5";
	if(get_field("block_custom_heading_asterisk_append") == true)
		$asterisk_position_classes = "top-0 -right-1.5";

	$asterisk_position_classes .= ($has_asterisk && get_field("block_custom_heading_hide_asterisk_on_mobile")) ? " hidden md:block" : " block";

	$icon_position = "prepend";
	if(get_field("block_custom_heading_icon_append") == true)
		$icon_position = "append";
	$icon = get_field("block_custom_heading_icon") ?: false;
	$icon_colour = get_field("block_custom_heading_icon_colour") ?: "current";
	$icon_classes = "text-{$icon_colour}";
	$icon_size = get_field("block_custom_heading_icon_size") ?: "lg";
	switch($icon_size){
		case "sm":
			$icon_classes .= " h-6 w-6";
			break;
		case "md":
			$icon_classes .= " h-8 w-8";
			break;
		default:
			$icon_classes .= " h-10 w-10";
			break;
	}
	$hidden_on_mobile = get_field("block_custom_heading_hide_on_mobile");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/custom-heading-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<!-- Custom Heading -->
<div class="<?php echo($hidden_on_mobile ? "hidden md:flex" : "flex") ?> items-center gap-3 mb-8 last:mb-0">
	<?php if($icon && $icon_position == "prepend"): ?>
		<div class="svg-wrapper <?php echo($icon_classes); ?>">
			<?php echo(get_svg_icon($icon)); ?>
		</div>
	<?php endif; ?>
	<div class="relative">
		<?php if($has_asterisk): ?>
			<div class="absolute <?php echo($asterisk_position_classes); ?> svg-wrapper w-4 h-4 text-electric-green-500">
				<?php echo(get_svg_icon("asterisk", "hiyield-icons")); ?>
			</div>
		<?php endif; ?>
		<InnerBlocks  
			class="custom-heading relative"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>
	</div>
	<?php if($icon && $icon_position == "append"): ?>
		<div class="svg-wrapper <?php echo($icon_classes); ?>">
			<?php echo(get_svg_icon($icon)); ?>
		</div>
	<?php endif; ?>
</div>