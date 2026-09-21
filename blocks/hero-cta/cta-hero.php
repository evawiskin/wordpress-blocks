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
		],
		[
			"hiyield/buttons"
		]
	];
	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph",
		"hiyield/buttons"
	];

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "white";

	$mime_type = get_field("block_cta_hero_media_type") ?: "image";
	$banner_image = get_field("block_cta_hero_image");
	$banner_lottie = get_field("block_cta_hero_lottie");

	if ($mime_type === 'lottie' && $banner_lottie) {
		lottie_enqueue_scripts();
	}

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/hero-cta-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<div class="relative overflow-hidden <?php if($block_bg) echo("bg-{$block_bg} "); if($block_text) echo(" text-{$block_text}"); ?>">
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 container">
		<div class="w-full my-14 flex flex-col gap-y-14 justify-center">
			<!-- left side of the banner --> 
			<InnerBlocks
				class="prose max-w-[31rem]"
				template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
				allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
			/>
		</div>

		<div class="w-full hidden lg:block">
				<div class="h-full relative lg:mr-break-out max-lg:!mr-0">
					<div 
						class="
							w-full h-full inset-0 bottom-0 flex justify-end 
							lg:mr-break-out max-lg:!mr-0 aspect-w-3 aspect-h-2
						"
					>
						<div class="object-cover">
						<?php 
							if ($mime_type === "lottie" && $banner_lottie) :
								?>
								<lottie-player
									src="<?php echo($banner_lottie); ?>"
									background="transparent"
									speed="<?php echo(get_field('block_cta_hero_lottie_animation_speed') ?? 1); ?>"
									loop
									autoplay>
								</lottie-player>
							<?php elseif ($mime_type === "image" && $banner_image) :
								echo(
									wp_get_attachment_image(
										$banner_image, 
										"large", 
										false, 
										["class" => "w-full h-full object-cover object-center"]
									)
								);
							endif; ?>
						</div>
					</div>
				</div>
			</div>

	
	<?php if (get_field("block_cta_hero_show_on_mobile")) :?>

		<!-- Banner Image Mobile -->
		<div class="w-full lg:hidden object-cover aspect-w-3 aspect-h-2">
		<?php 
			if ($mime_type === "lottie" && $banner_lottie) :
				?>
				<lottie-player
					src="<?php echo($banner_lottie); ?>"
					background="transparent"
					speed="<?php echo(get_field('block_cta_hero_lottie_animation_speed') ?? 1); ?>"
					loop
					autoplay>
				</lottie-player>
			<?php elseif ($mime_type === "image" && $banner_image) :
				echo(
					wp_get_attachment_image(
						$banner_image, 
						"large", 
						false, 
						["class" => "w-full h-full object-cover object-center"]
					)
				);
			endif; ?>
		</div>
	<?php endif; ?>
</div>
<?php 