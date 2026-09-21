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

	$block_data = $block;
    $block_id = $block["id"];
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	// Get terms from selection
	$override_global = get_field("block_our_process_override");

    if($override_global) {
        $prefix = "block";
        $postfix = $block_id;
    } else {
        $prefix = "option";
        $postfix = "options";
    }

	$processes = get_field("{$prefix}_our_process_repeater", $postfix );

    if(!$processes) {
        return;
    }

    // enqueue swiper
	swiper_enqueue_scripts();

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/our-process-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;
?>
<section class="inside-container-xl relative overflow-hidden <?php if($block_text_center) echo("text-center "); if($block_bg) echo("bg-{$block_bg} "); if($block_text) echo(" text-{$block_text}"); ?>">

	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	


	<div class="container z-10">

		<InnerBlocks  
			class="prose"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
		/>

        <!-- Processes carousel -->
        <div class="flex items-start justify-start w-5/6 lg:w-3/4">
            <div class="swiper w-full flex flex-col justify-start gap-y-8 !overflow-visible"

                id="<?php echo($block_id); ?>"
                data-pagination="false"
                data-navigation="false"
                data-scrollbar="false"
                data-mobilecol="1"
                data-mobilegap="0"
                data-tabletcol="2"
                data-tabletgap="0"
                data-desktopcol="2"
                data-desktopgap="0"
            >

                <div class="swiper-wrapper has-cursor">

                    <!-- Custom Cursor -->
			        <?php get_template_part( "template-parts/partials/partial-cursor", "", ["text" => "Drag"] ); ?>

                    <?php
                    $process_count = count($processes);

                    foreach($processes as $key => $process):

                        $color = $process["{$prefix}_our_process_repeater_color"];
                        $icon = $process["{$prefix}_our_process_repeater_icon"];
                        $heading = $process["{$prefix}_our_process_repeater_heading"];
                        $desc = $process["{$prefix}_our_process_repeater_desc"];

                    ?>
                        <div class="swiper-slide select-none">
                            
                            <div class="flex w-full flex-col pr-4">

                                <!-- Heading -->
                                <div class="flex flex-row items-center gap-x-4 mb-4 text-<?php echo($block_text); ?>">

                                    <div class="svg-wrapper flex justify-center items-center w-10 h-10 shrink-0 text-<?php echo($color); ?>">
                                        <?php if($icon): ?>
                                            <?php echo( get_svg_icon($icon) ); ?>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="theme-heading-tiny font-normal <?php if($block_text) echo(" text-{$block_text}"); ?>">
                                        <?php echo($heading); ?>
                                    </h3>

                                    <!-- Timeline -->
                                    <?php if( $process_count !== ($key+1) ): // Check if last element ?>
                                        <div class="w-full relative mr-6 ml-12 text-<?php echo($block_text); ?>">
                                            <div class="w-2 h-2 rotate-45 bg-white absolute left-0 top-1/2 -translate-y-1/2"></div>
                                            <div class="h-[2px] w-full bg-white"></div>
                                            <div class="w-2 h-2 rotate-45 bg-white absolute right-0 top-1/2 -translate-y-1/2"></div>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <!-- Description -->
                                <div class="flex w-3/4 flex-row text-<?php echo($block_text); ?>">
                                    <?php echo($desc); ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
		</div>
	</div>
</section>