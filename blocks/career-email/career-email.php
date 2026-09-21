<?php 
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

	$arrow_svg = get_svg_icon("hand-drawn-arrow", "hiyield-icons");
	$image = get_field("block_career_email_image_select");

	// Show block preview image
	if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/careers-email-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<div class="container grid grid-cols-12 gap-x-5 gap-y-10 md:flex-row relative pt-16 items-start">
    <div class="px-4 py-6 md:py-8 md:px-16 bg-white bg-opacity-10 col-span-12 lg:col-span-7 rounded-md">
		<InnerBlocks  
			class="prose container !no-underline"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
		/>
	</div>
	<!-- right side --> 
	<div class="flex flex-col md:flex-row mt-24 md:mt-40 items-end md:items-start gap-x-16 w-full justify-end col-span-12 lg:col-span-5">
		<div class="relative">
			<div class="absolute -translate-x-full -translate-y-full -top-2.5 pl-6 p-2.5 w-44 sm:pl-0">
				<div class="text-white font-semibold flex flex-col md:flex-row pb-4 relative w-max">
					Crystal wants to work
					<br /> with you, too!
					<div class="text-electric-green-500 w-14 h-8 absolute right-2 md:-right-10 -bottom-6 md:bottom-0">
						<?php echo($arrow_svg); ?>
					</div>
				</div>
			</div>
			<div class="w-48">
				<?php
					echo(
						wp_get_attachment_image(
							$image,
							"full",
							false,
							["class" => "w-full h-auto"]
						)	
					);
				?>
			</div>
		</div>
	</div>
</div>