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

	// enqueue accordions JS
	if($accordions_script_uri = get_theme_file_uri("assets/dist/js/accordions.js"))
		wp_enqueue_script("custom-accordions", $accordions_script_uri, [], $build_version, false);

	/* Get the gutenberg block classes */
	$block_classes = new BlockClasses($block);

	$image = get_field("block_hero_accordion_image");
	$image_mobile = get_field("block_hero_accordion_image_mobile");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/hero-accordion-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<section class="relative py-16 <?php echo($block_classes); ?>">

	<?php if(array_key_exists("anchor", $block))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>	

	<div class="container relative grid grid-cols-1 lg:grid-cols-2 gap-x-16 <?php if(!is_admin()) echo("h-full"); ?>">

		<div class="w-full lg:w-4/5 order-1 lg:order-0 mt-12 lg:mt-0 flex flex-col justify-end">
			
			<div>
				<InnerBlocks  
					class="prose"
					template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
					allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
				/>

				<?php if(have_rows("block_hero_accordion_repeater")): ?>
					<div class="mt-12 lg:mt-20">
						<?php
							$accordions = get_field("block_hero_accordion_repeater");
							$accordion_count = 0;
							foreach($accordions as $accordion) {
								//pass in the data and get the partial
								$args = [
										"accordion_title" => $accordion["block_hero_accordion_repeater_title"], 
										"accordion_content" => $accordion["block_hero_accordion_repeater_desc"], 
										"accordion_count" => $accordion_count
									];
								get_template_part("template-parts/partials/partial", "accordion", $args);
								$accordion_count++;
							}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class=" order-0 lg:order-1">
			
			<?php if($image): ?>
				<div class="hidden lg:block sticky top-28 right-0 w-full h-fit">	
					<?php echo(wp_get_attachment_image($image, "large", false, ["class" => "w-auto h-full max-h-[calc(100vh_-_7rem)] min-h-[600px] ml-auto"])); ?>
				</div>
			<?php endif; ?>

			<?php if($image_mobile): ?>
				<div class="block lg:hidden w-full">	
					<?php echo(wp_get_attachment_image($image_mobile, "large", false, ["class" => "w-full h-full"])); ?>
				</div>
			<?php endif; ?>
		</div>

	</div>

</section>