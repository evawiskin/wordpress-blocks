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

    $block_data = $block;
    $block_id = $block_data["id"];
    $block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";

    //enqueue swiper
	swiper_enqueue_scripts();

    $testimonial_select = get_field("block_hear_it_from_us_testimonial_select");

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/hear-it-from-us-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;
?>
<div 
    class="
        inside-container-xl 
        <?php echo($block_bg ? "bg-$block_bg" : ""); ?>
    "
>
    <div class="container grid grid-cols-12 gap-y-11 gap-x-6">
        <div class="col-span-12 flex justify-between">
            <InnerBlocks  
                class="prose !mb-0"
                template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
                allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
            />
            <!-- carousel navigation goes here --> 
            <div class="flex gap-x-5 text-white mb-5">
                <!-- Navigation - Prev -->
                <div class="w-8 md:w-16 h-full z-10 flex justify-center items-center transition-all ">
                    <button
                        id="swiper-prev-<?php echo($block_id); ?>"
                        class="swiper-nav-button disabled:opacity-40"
                    >
                        <span class="block w-8 h-8 relative right-px">
                            <?php echo(get_svg_icon("arrow-left")); ?>
                        </span>
                    </button>
                </div>
                <!-- Navigation - Next -->
                <div class="w-8 md:w-16 h-full z-10 flex justify-center items-center transition-all">
                    <button
                        id="swiper-next-<?php echo($block_id); ?>"
                        class="swiper-nav-button disabled:opacity-40"
                    >
                        <span class="block w-8 h-8 relative left-px">
                            <?php echo(get_svg_icon("arrow-right")); ?>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row-start-2 col-span-12">
			<div
            class="swiper !overflow-x-clip !overflow-y-visible"
            id="<?php echo($block_id); ?>"
				data-pagination="true"
				data-navigation="true"
				data-scrollbar="false"
				data-mobilecol="1"
				data-mobilegap="50"
				data-tabletcol="2"
				data-tabletgap="40"
				data-desktopcol="3"
				data-desktopgap="40"
				data-slides-count="<?php echo($testimonial_select ? count($testimonial_select) : -1);  ?>"

			>
				<div class="swiper-wrapper col-span-12">
                   <?php 
                        $args = [
                            "post_type"     => "testimonial"
                        ];

                        if($testimonial_select && !is_null($testimonial_select))
                            $args["post__in"] = $testimonial_select;

                        $query = new WP_Query($args);

                        while($query->have_posts()):
                            $query->the_post();

                            $testimonial_id = get_the_ID();
                            $testimonial_content = get_field("cpt_testimonial_content", $testimonial_id);
                            $testimonial_author = get_the_title($testimonial_id);
                            $speech_bubble_svg = get_svg_icon("triangle", "hiyield-icons");
                    ?>
                        <div 
                            class="
                                swiper-slide col-span-4 text-deep-ocean-blue-500 
                                bg-white px-6 py-8 rounded font-bold relative !h-auto self-stretch
                            "
                        >
                            <div 
                                class="text-white absolute left-1/2 -bottom-5"
                            >
                                <div class="flex gap-x-5 relative">
                                    <div class="rotate-[30deg]">
                                        <?php echo($speech_bubble_svg); ?>
                                    </div>
                                </div>
                            </div>
                            <span class="flex flex-col gap-y-2">
                                <div class="mb-4"><?php echo($testimonial_content); ?></div>
                                <div class="w-full flex justify-between pb-8">
                                     <?php 
                                        echo($testimonial_author); 

                                        if(has_post_thumbnail($testimonial_id)): 
                                    ?>
                                            <div class="w-20 h-20 -bottom-8 absolute right-6">
                                                <?php  
                                                    echo(
                                                        wp_get_attachment_image(
                                                            get_post_thumbnail_id($testimonial_id),
                                                            "thumbnail",
                                                            false
                                                        )
                                                    ) 
                                                ?>
                                            </div>
                                    <?php endif; ?>
                                </div>
                            </span>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
    </div>
</div>
