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
    $block_id = $block["id"];
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "forest-green-600";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "white";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	$slides = get_field("block_slider_captions_repeater");
    $slides_count = count($slides);

    if($slides_count > 2) {
        $initial_slide = 1;
    } else {
        $initial_slide = 0;
    }

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/slider-caption-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;
        
    if(!$slides) {
        return;
    }

    // enqueue swiper
	swiper_enqueue_scripts();

    $content = get_field("block_slider_captions_content");

    $banner_is_shown = get_field("block_slider_captions_banner_show");

?>

<section class="inside-container-xl relative overflow-hidden <?php if($block_text_center) echo("text-center "); if($block_bg) echo("bg-{$block_bg} "); if($block_text) echo(" text-{$block_text}"); ?>">

	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	


	<div class="container">

		<InnerBlocks  
			class="prose"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
		/>

        <!-- Processes carousel -->
        <div class="flex items-start justify-start w-full mt-8 lg:w-4/5 mx-auto z-10">
            <div class="swiper w-full flex flex-col justify-start gap-y-8 !overflow-visible opacity-0 invisible"

                id="<?php echo($block_id); ?>"
                data-pagination="false"
                data-navigation="false"
                data-scrollbar="false"
                data-mobilecol="1"
                data-mobilegap="0"
                data-tabletcol="1"
                data-tabletgap="0"
                data-desktopcol="1"
                data-desktopgap="0"
                data-slides-count="<?php echo($slides_count); ?>"
                data-loop="false"
                data-initial-slide="<?php echo($initial_slide); ?>"
            >

                <div class="swiper-wrapper has-cursor">

                    <!-- Custom Cursor -->
			        <?php get_template_part( "template-parts/partials/partial-cursor", "", ["text" => "Drag"] ); ?>

                    <?php
                    foreach($slides as $slide):

                        $image_id = $slide["block_slider_captions_repeater_image"];
                        $caption = $slide["block_slider_captions_repeater_caption"];

                    ?>
                        <div class="swiper-slide w-full group">
                            
                            <div class="flex flex-col gap-y-6 mr-4 md:mr-6 lg:mr-8">

                                <div class="w-full aspect-w-5 aspect-h-2 rounded overflow-hidden">
                                    <?php echo(wp_get_attachment_image($image_id, "full", false, ["class" => "object-cover w-full h-full"])); ?>
                                </div>

                                <?php if($caption): ?>
                                    <div class="caption transition-all duration-300 invisible opacity-0 group-[.swiper-slide-active]:opacity-100 group-[.swiper-slide-active]:visible">
                                        <!-- Description -->
                                        <div class="w-full sm:w-1/2 xl:w-3/4 flex flex-row gap-4 items-top lg:items-center text-<?php echo($block_text); ?>">

                                            <span class="flex w-5 h-5 relative lg:-top-0.5 shrink-0">
                                                <?php echo(get_svg_icon("arrow-up")); ?>
                                            </span>

                                            <span><?php echo($caption); ?></span>

                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
		</div>

        <!-- Content part -->
        <div class="w-full relative flex flex-col lg:flex-row items-start gap-8 justify-between mt-8 sm:mt-16">
            
            <div class="w-full md:w-3/5 prose font-semibold order-1 lg:order-0">
                <?php echo($content); ?>
            </div>

            <?php if($banner_is_shown):
                $banner_image = get_field("block_slider_captions_banner_image");
                $banner_content = get_field("block_slider_captions_banner_content");
            ?>
                <div class="w-full sm:w-1/2 lg:w-96 sm:-mt-48 md:-mt-40 z-20 bg-forest-green-500 rounded p-4 lg:p-8 shadow-lg flex flex-row justify-between items-center gap-x-8 lg:gap-x-12 order-0 lg:order-1 self-end lg:self-start">

                    <div class="w-1/3">
                        <div class="aspect-w-2 aspect-h-3 overflow-hidden">
                            <?php echo(wp_get_attachment_image($banner_image, "medium", false, ["class" => "object-contain w-full h-full"])); ?>
                        </div>
                    </div>

                    <div class="overlap-banner w-2/3">
                        <div class="text-md xl:text-lg leading-relax">
                            <?php echo($banner_content); ?>
                        </div>
                    </div>

                </div>
            <?php endif; ?>
        </div>




	</div>
</section>