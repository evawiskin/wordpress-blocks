<?php

	// Enqueue swiper.js scripts
	swiper_enqueue_scripts();

    // Setting bg from Gutenberg UI (ID is important for swiper but might be any unique ID, just comfortable thing to use block's ID)
	$block_id = "CAROUSELID";

    // every block with class = swiper and unique ID will be considered as swiper carousel and auto initialised.
    // If we want to have pagination we need to set attr data-pagination="true" in main tag and also have the empty div with following id: id="swiper-pagination-CAROUSELID"
    // If we want to have navigation we need to set attr data-navigation="true" in main tag and also have the control elements with following ids:
    // "swiper-prev-CAROUSELID" and "swiper-next-CAROUSELID"
    // If ID will be the same, these controls will be auto attached to CAROUSELID swiper instance
?>



		<!-- Carousel -->
		<div class="">
			<div
				class="swiper !overflow-visible"
				id="<?php echo $block_id; ?>"
				data-pagination="true"
				data-navigation="true"
				data-scrollbar="false"
				data-mobilecol="1"
				data-mobilegap="50"
				data-tabletcol="2"
				data-tabletgap="40"
				data-desktopcol="3"
				data-desktopgap="40"
				data-slides-count="<?php // Number of slides, might be static or calculate dynamically ?>"

			>
				<div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <!-- Slide content -->
                    </div>
				</div>
			</div>
		</div>
		
		<!-- Controls -->
		<div class="w-full flex justify-center items-center">

			<!-- Navigation - Prev -->
			<div class="flex justify-center items-center">
				<button
					id="swiper-prev-<?php echo($block["id"]); ?>"
					class="swiper-nav-button"
				>
					<?php if ($chevron_left = get_theme_file_path("/assets/dist/imgs/feather-icons/arrow-left.svg")) : ?>
						<span class="block w-6 h-6">
							<?php echo(file_get_contents($chevron_left)); ?>
						</span>
					<?php endif; ?>
				</button>
			</div>

			<!-- Pagination -->
			<div
				class="!static flex justify-center z-20"
				id="swiper-pagination-<?php echo $block_id; ?>"
			></div>


			<!-- Navigation - Next -->
			<div class="flex justify-center items-center">
				<button
					id="swiper-next-<?php echo($block["id"]); ?>"
					class="swiper-nav-button"
				>
					<?php if ($chevron_right = get_theme_file_path("/assets/dist/imgs/feather-icons/arrow-right.svg")): ?>
						<span class="block w-6 h-6">
							<?php echo(file_get_contents($chevron_right)); ?>
						</span>
					<?php endif; ?>
				</button>
			</div>

		</div>

	</div>
</section>