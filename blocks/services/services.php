<?php 
	//counter which is required for unique accordion ids
	//and aria labelling inside the partial
	$accordion_count = 0;

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

	//get script build constant from wp-config
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	$card_bg = get_field("block_services_card_bg_color") ?: "forest-green-500";

	// Show block preview image
	if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/services-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;

	if(!have_rows("block_services_terms_repeater")) {
		return;
	}

	// Get terms from repeater
	$terms_repeater = get_field("block_services_terms_repeater");

	$terms_count = count($terms_repeater);
	$columns = "";

	switch($terms_count) {
		case 2:
			$columns = "md:grid-cols-2";
			break;
		case 3:
			$columns = "lg:grid-cols-3";
			break;
		case ($terms_count > 3):
			$columns = "md:grid-cols-2 lg:grid-cols-3";
			break;

	}
?>

<section class="inside-container-xl relative overflow-x-clip <?php if($block_text_center) echo("text-center "); if($block_bg) echo("bg-{$block_bg} "); if($block_text) echo(" text-{$block_text}"); ?>">

	<?php 
		if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	

	<div class="container relative z-10">
		<?php if(get_field("block_services_asterisk")): ?>
			<div class="hidden md:block absolute md:top-1/4 lg:-top-[60%] md:-right-[25%] lg:-right-[40%] md:w-1/2 lg:w-3/5 text-electric-green-500 opacity-20 pointer-events-none -z-10">
				<?php echo(get_svg_icon("hiyield-asterisk", "hiyield-icons")); ?>
			</div>
		<?php endif; ?>

		<InnerBlocks  
			class="prose"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
		/>

		<?php if($terms_repeater): ?>
			<div class="mt-12 grid gap-6 grid-cols-1 <?php echo($columns); ?>">

				<?php foreach($terms_repeater as $terms_item):

					$term = $terms_item["block_services_terms_select"] ?: false;

					if(!$term) continue;

					$is_custom_link = $terms_item["block_services_terms_use_custom_link"];
					$custom_link = $terms_item["block_services_terms_custom_link"];

					$card_color = get_field("tax_service_card_color", $term) ?: "electric-green-500";
					$card_icon = get_field("tax_service_card_icon", $term);

					$link = get_term_link($term);

					if($is_custom_link && $custom_link) {
						$link = $custom_link["url"];
					}

					$args = [
						"bg_color" => $card_bg,
						"lead_color" => $card_color,
						"text_color" => $block_text,
						"top_icon" => $card_icon,
						"help_icon" => "arrow-right",
						"heading" => $term->name,
						"link" => $link,
						"content" => $term->description
					];

					get_template_part("template-parts/cards/template-part", "service-card", $args);
			
				endforeach; ?>
			</div>

		<?php endif; ?>
		
	</div>
</section>