<?php

	$block_data = $block;
	$block_id = $block_data["id"];
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	if(!have_rows("block_image_slider_repeater")) {
		return;
	}
	$images = get_field("block_image_slider_repeater");

	//enqueue swiper
	swiper_enqueue_scripts();

?>

<section class="container grid <?php if($block_text_center) echo("text-center "); if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<?php if(array_key_exists("anchor", $block_data))
		get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	
		<div 
			class="swiper group max-w-full"
			id="<?php echo($block_id); ?>"
			data-pagination="true"
			data-navigation ="true"
			data-mobilecol="1"
			data-mobilegap="20"
			data-tabletcol="1"
			data-tabletgap="20"
			data-desktopcol="1"
			data-desktopgap="20"
		>

			<!-- Navigation - Prev -->
			<div class="absolute w-20 h-full z-10 top-0 left-0 flex justify-center items-center transition-all opacity-0 group-hover:opacity-100 invisible group-hover:visible">
				<div class="absolute inset-0 bg-gradient-to-l from-transparent to-black opacity-50"></div>
				<button
					id="swiper-prev-<?php echo($block_id); ?>"
					class="swiper-nav-button disabled:opacity-50"
				>
					<?php if($chevron_left = get_theme_file_path("/assets/dist/imgs/feather-icons/arrow-left.svg")): ?>
						<span class="block w-8 h-8 relative right-px text-white">
							<?php echo(file_get_contents($chevron_left)); ?>
						</span>
					<?php endif; ?>
				</button>
			</div>

			<div class="swiper-wrapper">

				<?php foreach($images as $image): ?>
					<div class="swiper-slide">
						<div class="w-full h-full">
							<?php 
								echo(
									wp_get_attachment_image(
										$image["block_image_slider_repeater_image"],
										"large",
										"",
										["class" => "w-full h-full object-cover"]
									)
								);
							?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Navigation - Next -->
			<div class="absolute w-20 h-full z-10 top-0 right-0 flex justify-center items-center transition-opacity opacity-0 group-hover:opacity-100 invisible group-hover:visible">
				<div class="absolute inset-0 bg-gradient-to-r from-transparent to-black opacity-60"></div>
				<button
					id="swiper-next-<?php echo($block_id); ?>"
					class="swiper-nav-button disabled:opacity-50"
				>
					<?php if($chevron_right = get_theme_file_path("/assets/dist/imgs/feather-icons/arrow-right.svg")): ?>
						<span class="block w-8 h-8 relative left-px text-white">
							<?php echo(file_get_contents($chevron_right)); ?>
						</span>
					<?php endif; ?>
				</button>
			</div>

		</div>

		<!-- Pagination and scrollbar-->
		<div>
			<div class="flex items-center justify-center">
				<div
					id="swiper-pagination-<?php echo($block_id); ?>"
					class="!static flex w-full justify-center gap-x-2 items-center mx-6 mt-3"
				>
				</div>
			</div>
		</div>

</section>