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
				"placeholder" => "Paragraph text goes here, click the plus button to choose a inner block to add to this container."
			]
		]
	];

    $block_data = $block;
    $block_id = $block_data["id"];
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
    $card_bg = get_field("block_work_carousel_card_background");

    //enqueue swiper
	swiper_enqueue_scripts();

    $work_select = get_field("block_work_carousel_select");

    $args = [
        "post_type" => "work",
        "posts_per_page" => 5,
        "post__in" => $work_select,
        "meta_query" => [
        "relation" => "OR",
            [
                "key"       => "cpt_work_hide_from_archive",
                "value"     => 0,
                "compare"   => "NOT EXISTS"
            ],
            [
                "key"       => "cpt_work_hide_from_archive",
                "value"     => 0,
                "compare"   => "=="
            ]
        ]
    ];

    $work_query = new WP_Query($args);

    $counter = $work_query->found_posts;

    $slides_tablet = 2;
    $slides_desktop = 3;

    $classes = "";
    $show_custom_cursor = false;

    if($counter > $slides_desktop) {
        $classes = "has-cursor";
        $show_custom_cursor = true;
    }

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/work-carousel-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;

    $is_admin = is_admin();

    $arrow_colors = "";

    switch($block_bg){
        case "electric-green-500":
        case "forest-green-500":
        case "deep-ocean-blue-500":
        case "flamingo-pink-500":
        case "purple-800":
        case "forest-green-600":
        case "black":
            $arrow_colors = "text-white";
            break;
        case "gretter-50":
        case "sunshine-yellow-500":
        case "white":
            $arrow_colors = "text-forest-green-500";
            break;
        default: 
            $arrow_colors = "text-white";
    }

    // get the post type of the current page
    if($current_post_type = get_post_type(get_the_id()) === "service"){
        $arrow_colors = "text-forest-green-500";
    } 
?>

<div class="<?php echo($block_bg ? "bg-$block_bg" : 'bg-gretter-50'); ?> inside-container-lg overflow-hidden">
    <div class="container flex flex-col gap-y-10">

        <div class="flex flex-row justify-between items-center">

            <InnerBlocks 
                class="prose" 
                template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
            />

            <!-- Navigation --> 
            <div class="md:hidden w-20 lg:w-16 h-8 right-4 lg:right-8 top-4 lg:top-6 z-20 <?php echo($arrow_colors); ?> flex flex-row justify-between">

                <!-- Navigation - Prev -->
                <div class="w-8 h-full z-10 flex justify-center items-center transition-all">
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
                <div class="w-8 h-full z-10 flex justify-center items-center transition-all">
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
        <?php if(!$is_admin): ?>
        <div
            class="w-full swiper !overflow-visible"
            id="<?php echo($block_id); ?>"
            data-pagination="true"
            data-navigation="true"
            data-scrollbar="false"
            data-mobilecol="1"
            data-mobilegap="20"
            data-tabletcol="<?php echo($slides_tablet); ?>"
            data-tabletgap="30"
            data-desktopcol="<?php echo($slides_desktop); ?>"
            data-desktopgap="40"
        >
            <div class="swiper-wrapper <?php echo($classes); ?>">

                <!-- Custom Cursor -->
                <?php
                    if($show_custom_cursor) {
                        get_template_part( "template-parts/partials/partial-cursor", "", ["text" => "Drag"] );
                    }
                ?>

                <?php
                    while($work_query->have_posts()):
                        $work_query->the_post();
                ?>
                    <div class="swiper-slide !h-auto self-stretch">
                        <?php 
                            get_template_part(
                                "template-parts/cards/template-part", 
                                "work-carousel-card", 
                                [
                                    "custom_cursor" => $show_custom_cursor, 
                                    "card_background_color" => $card_bg
                                ]
                            ); 
                        ?>
                    </div>
                <?php endwhile; ?>
          </div>
        </div>
        <?php else: ?>
        <div style="padding: 1rem; background: #f0f0f0; border: 2px dashed #ccc; text-align: center; border-radius: 4px;">
            <p style="margin: 0; font-size: 0.875rem; color: #666;">Work carousel cards will display on the frontend</p>
        </div>
        <?php endif; ?>
    </div>
</div>