<?php

	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"hiyield/content-slider-item"
	];

    $block_data = $block;
    $block_id = $block["id"];

    //enqueue swiper
	swiper_enqueue_scripts();

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/content-slider-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;
?>
<section class="pt-4 lg:py-16">

    <div class="container">

        <div
            class="w-full swiper relative"
            id="<?php echo($block_id); ?>"
            data-pagination="false"
            data-navigation="true"
            data-scrollbar="false"
            data-mobilecol="1"
            data-mobilegap="20"
            data-tabletcol="1"
            data-tabletgap="20"
            data-desktopcol="1"
            data-desktopgap="20"
        >

            <InnerBlocks  
                class="swiper-wrapper z-10"
                allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
            />

            <!-- Navigation --> 
            <div class="absolute w-20 lg:w-24 h-12 right-4 lg:right-8 top-4 lg:top-6 z-20 text-white flex flex-row justify-between">

                <!-- Navigation - Prev -->
                <div class="w-10 h-full z-10 flex justify-center items-center transition-all">
                    <button
                        id="swiper-prev-<?php echo($block_id); ?>"
                        class="swiper-nav-button disabled:opacity-40"
                    >
                        <span class="block w-8 h-8 lg:w-10 lg:h-10 relative right-px rotate-180">
                            <?php echo(get_svg_icon("arrow-right-bold", "hiyield-icons")); ?>
                        </span>
                    </button>
                </div>

                <!-- Navigation - Next -->
                <div class="w-12 h-full z-10 flex justify-center items-center transition-all">
                    <button
                        id="swiper-next-<?php echo($block_id); ?>"
                        class="swiper-nav-button disabled:opacity-40"
                    >
                        <span class="block w-8 h-8 lg:w-10 lg:h-10 relative left-px">
                            <?php echo(get_svg_icon("arrow-right-bold", "hiyield-icons")); ?>
                        </span>
                    </button>
                </div>

            </div>

        </div>

    </div>

</section>