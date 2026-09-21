<?php
	if (!isset($block) || !is_array($block)) return;

	if (display_block_preview_image($block)) return;

	$block_classes = new BlockClasses($block);

	$allowed_blocks = [
		"hiyield/image-content-column"
	];

	// Determine if the image should be first or last
	$image_order_field = get_field("block_image_content_image_order");
	$image_first = ($image_order_field) ? false : true;

	// Get the image aspect ratio
	$image_aspect_ratio = get_field("block_image_content_aspect_ratio");

	// Map aspect ratios to your registered image sizes
	$aspect_to_size = [
		"aspect-1" => "hy-square-640",
		"aspect-w-16 aspect-h-9" => "hy-16-9",
		"aspect-w-9 aspect-h-16" => "hy-9-16",
		"aspect-w-4 aspect-h-5" => "hy-4-5",
		"aspect-w-5 aspect-h-3" => "hy-5-3",
		"aspect-w-3 aspect-h-2" => "hy-3-2-600",
	];

	$image_size = isset($aspect_to_size[$image_aspect_ratio]) ? $aspect_to_size[$image_aspect_ratio] : "full";

	// get image fit
	$image_fit = get_field("block_image_content_object_fit");
	// Get the image field (returns ID)
	$image_field = get_field("block_image_content_image");

	// Get the image break out setting
	$image_break_out = get_field("block_image_content_image_break_out");
	$mobile_order = get_field("block_image_content_image_mobile_order");

	$template = [
		[
			"hiyield/image-content-column", [
				"template" => [
					[
						"core/heading",
						[
							"placeholder" => "Add heading here...",
							"textAlign" => "left",
							"level" => 2,
							"fontSize" => "text-hy-5xl"
						]
					],
					[
						"core/paragraph",
						[
							"placeholder" => "Add content here...",
							"textAlign" => "left"
						]
					],
					[
						"hiyield/buttons",
						[
							"data" => [
								"block_buttons_buttons_layout" => "0",
								"_block_buttons_buttons_layout" => "block_buttons_buttons_layout_key",
								"block_buttons_button_repeater" => 1,
								"_block_buttons_button_repeater" => "block_buttons_button_repeater_key"
							]
						]
					]
				]
			]
		]
	];
?>

<section id="<?php echo($block["id"]); ?>" class="relative flex <?php echo($block_classes); ?>">
	<?php do_action("hy_block_start", $block); ?>	

	<div class="grid grid-design container <?php echo($block_classes->items); ?> <?php echo($image_break_out ? " !gap-0" : ""); ?>">
		<div class="image-column col-span-full lg:col-span-6 flex
			<?php echo($image_aspect_ratio); ?> 
			<?php if(!$image_first) echo( "lg:!order-last lg:col-start-7"); ?>
			<?php 
				// If the image should bleed, add the mx-break-out class on mobile.
				if($image_break_out) echo(" mx-break-out"); 
				if($image_break_out && $image_first) echo(" lg:!mr-0 lg:ml-break-out");
				if($image_break_out && !$image_first) echo(" lg:!ml-0 lg:mr-break-out");
				echo($mobile_order ? ' order-first lg:order-none' : ' order-last lg:order-none');

				//handle height 
				if(!$image_aspect_ratio) echo(" h-full");
			?>
		"	
		>
			<?php 
				$image_classes = "{$image_fit} object-center w-full h-full";
				if (!$image_break_out) {
					$image_classes .= " rounded-xl";
				}

				echo(wp_get_attachment_image(
					attachment_id: $image_field, 
					size: $image_size,
					attr: [
						"class" => $image_classes,
						"sizes"     => "(max-width: 480px) 90vw, 400px",
						"loading"   => "lazy",
						"decoding"  => "async",
					]
				)); 
			?>
		</div>
		<div class="content-column col-span-full lg:col-span-5 <?php if($image_first) echo('lg:col-start-7'); ?>">
			<InnerBlocks 
				class="<?php echo("w-full h-full prose"); ?>"
				template="<?php echo(esc_attr(json_encode($template))); ?>"
				allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
			/>
		</div>
	</div>
</section>


<?php if (is_admin()) : ?>
	<style>
		:where(.editor-styles-wrapper) .wp-block-hiyield-image-content .image-column img {
			height: 100% !important;
			width: 100% !important
		}
	</style>
<?php endif; ?>