<?php
	// Block & Container Classes
	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_align = ($block_data && array_key_exists("alignText", $block_data) ? $block_data["alignText"] : "");
	$block_align = ($block_data && array_key_exists("align", $block_data)) ? $block_data["align"] : "";

	// create the block & container classes
	$block_classes = "";
	$container_classes = "";
	if($block_text_align) $block_classes .= "text-{$block_text_align} ";
	if($block_bg) $block_classes .= "bg-{$block_bg} ";
	if($block_text) $block_classes .= "text-{$block_text} ";
	if($block_align) {
		if($block_align == "center")
			$container_classes .= "items-center ";
		else if($block_align == "right")
			$container_classes .= "items-end ";
		else
			$container_classes .= "items-start ";
	}

	// Block Fields
	$embed_code = get_field("block_review_widget_code") ?: false;
	$show_fallback = get_field("block_review_widget_use_fallback") ?: false;
	$fallback_type = get_field("block_review_widget_fallback") ?: false;
	$fallback_link = get_field("block_review_widget_fallback_link") ?: false;
	$feedback_image_id = get_field("block_review_widget_fallback_image") ?: false;

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/reviews-widget-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section class="relative <?php echo($block_classes); ?>">
	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>
	<div class="relative container flex flex-col <?php echo($container_classes); ?>">
		<?php if($show_fallback == false && $embed_code) : ?>
			<?php echo($embed_code); ?>
		<?php endif; ?>
		<?php 
			if($show_fallback):
				if($fallback_link): 
			?>
				<a title="Check out our <?php if($fallback_type !== "image")echo(ucfirst($fallback_type)); ?> reviews" href="<?php echo($fallback_link); ?>" target="_blank">
			<?php 
				endif;
				if($fallback_type == "image"):
					echo(wp_get_attachment_image($feedback_image_id, "medium", false, ["class" => "w-full h-full object-contain"]));
				elseif($fallback_type == "clutch"):
			?>
				<div class="flex gap-4">
					<span class="text-electric-green-600 w-11 h-12">
						<?php echo(get_svg_icon("clutch-icon-light", "hiyield-icons")); ?>
					</span>
					<div>
						<div class="flex gap-px text-electric-green-600">
							<span class="w-6 h-6"><?php echo(get_svg_icon("star-filled", "hiyield-icons")); ?></span>
							<span class="w-6 h-6"><?php echo(get_svg_icon("star-filled", "hiyield-icons")); ?></span>
							<span class="w-6 h-6"><?php echo(get_svg_icon("star-filled", "hiyield-icons")); ?></span>
							<span class="w-6 h-6"><?php echo(get_svg_icon("star-filled", "hiyield-icons")); ?></span>
							<span class="w-6 h-6"><?php echo(get_svg_icon("star-filled", "hiyield-icons")); ?></span>
						</div>
						<span class="text-sm font-bold">Top rated agency</span>
					</div>
					</span>
				</div>
			<?php 
				endif;
				if($fallback_link): 
			?>
				</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>