<?php

	if ((!isset($block)) || !is_array($block)) return;
	if (display_block_preview_image($block)) return;

	$block_id = set_block_id($block);
	$block_classes = new BlockClasses($block, "relative");
	$allowed_blocks = get_allowed_blocks();

	//Assign the random or specific person...
	$person_override = get_field("block_get_started_person_override");
	$has_person_override = $person_override ? true : false;

	// If there's a person override, use that person's asterisk image - if they have one.
	if($has_person_override && $person_asterisk_image = get_field("cpt_person_asterisk_image", $person_override->ID)){
		$asterisk_image_id = $person_asterisk_image;
	} else {
		$randomised_person = get_randomised_person();
		$asterisk_image_id = get_field("cpt_person_asterisk_image", $randomised_person);		
	}

	//Generic Block things 
	$template = [
		["core/heading", [
			"level" => 2,
			"placeholder" => "Let's get started!"
		]],
		["core/paragraph", [
			"placeholder" => "From startups to global enterprises, we love filler text and offering free consultations to find out what's best for you."
		]]
	];
	
	// Get the boxed style setting
	$is_boxed = get_field("block_get_started_boxed_style");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/get-started-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<section class="relative pt-10 pb-[4.375rem] lg:pt-[5.625rem] lg:pb-[3.125rem] <?php if($is_boxed) echo("bg-gretter-50 "); else echo($block_classes->background_color); ?>">
	<?php do_action("hy_block_start", $block); ?>
	<div class="container z-10 relative">
		<div class="grid lg:grid-cols-12 gap-4 <?php if($is_boxed) echo("{$block_classes->background_color} rounded-xl p-8"); ?>">
			<div class="w-36 h-36 lg:h-auto lg:w-auto lg:col-span-4">
				<?php 
					echo(wp_get_attachment_image($asterisk_image_id, "full", false, ["class" => "w-full h-full object-contain"]));
				?>
			</div>
			<div class="lg:col-span-6 lg:col-start-6 flex items-center">
				<InnerBlocks 
					class="prose max-w-[37.5rem] <?php echo($block_classes->text_color); ?>"
					allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"					
					template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
				/>
			</div>
		</div>
	</div>
</section>
