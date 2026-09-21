<?php
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	if($contact_form_script_uri = get_theme_file_uri("assets/dist/js/main-contact-form.js"))
		wp_enqueue_script("main-contact-form", $contact_form_script_uri, [], $build_version, false);

	// Enqueue JS
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
	if($modals_script_uri = get_theme_file_uri("assets/dist/js/modals.js"))
		wp_enqueue_script("custom-modals", $modals_script_uri, [], $build_version, false);

	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here",
				"fontWeight" => "extrabold",
				
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
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/contact-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;

	// Base classes
	$block_classes = new BlockClasses($block, "relative outside-container");

	$show = "contact";

	if( (isset($_GET["show"])) && $_GET["show"] == "brief" ) {
		$show = "brief";
	}

	$post_id = get_the_ID();

	// Create brief form shortcode
	$brief_form_shortcode = get_field("block_contact_select_brief_form");
?>
<section id="contact-brief-forms" data-show="<?php echo($show); ?>" class="<?php echo($block_classes); ?>">
	<?php if(array_key_exists("anchor", $block))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>	
	<div class="relative container">
		<div class="grid grid-cols-12 gap-4">
			<div id="contact-form" class="prose col-span-12 py-20 text-forest-green-500">
				<div id="contact_block" class="grid grid-cols-12 gap-x-4 gap-y-6 lg:gap-y-14 mb-14">
					<div class="col-span-full lg:col-span-6 mb-8 lg:mb-0">
						<InnerBlocks 
							class="prose" 
							template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
						/>
					</div>
					<div class="col-span-full lg:col-span-6 flex lg:justify-end">
						<?php 
							if($schedule_call_image = get_field("block_contact_schedule_call_image"))
								echo wp_get_attachment_image( $schedule_call_image, "large", false, ["class" => "w-full max-w-48 object-contain max-w-md"] );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
		if (!is_admin())
			get_template_part("template-parts/modals/template-part", "modal", ["modal_type" => "schedule-call-modal", "modal_id" => "schedule-call-modal"]); 
	?>
</section>