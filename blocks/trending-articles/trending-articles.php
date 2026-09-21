<?php 
    if ((!isset($block)) || !is_array($block)) {
        $block = [];
    }

    // Get posts
    $is_handpicked = get_field("block_trending_articles_choose_articles");
    $is_four_columns = get_field("block_trending_articles_column_amount");
    $trending_post_ids =
        $is_handpicked ? 
        get_field("block_trending_articles_articles_select") :
        get_posts([
            "post_status" => "publish",
            "meta_key" => "post_views_count",
            "orderby" => "meta_value_num",
            "order" => "DESC",
            "fields" => "ids",
            "posts_per_page" => 4
        ]);

    // Inner-blocks template
    $template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Add Heading Here...",
				"fontSize" => "medium-heading"
			]
		]
	];
    $allowed_blocks = get_allowed_blocks();

    // Concat block classes
    $block_class = new BlockClasses($block);
?>
<section class="<?php echo($block_class); ?>">
    <div class="container">

        <!-- Inner-blocks -->
        <InnerBlocks
            class="mb-10 lg:mb-16"
            template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
            allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
        />
        
        <!-- Editor Placeholder -->
        <?php if (empty($trending_post_ids) && is_admin()) : ?>
            <p class="py-20 text-lg text-center">
                <?php echo($is_handpicked ? "Choose articles to begin" : "You must publish at least one post to use this block"); ?>
            </p>
        <?php else : ?>

            <!-- Post Loop -->
            <h3 class="text-3xl flex gap-6 mb-5 lg:mb-10 items-center">
                <?php if ($trending_icon = get_svg_icon("trending-up-square")) : ?>
                    <span class="block size-8 shrink-0"><?php echo($trending_icon) ?></span>
                <?php endif; ?>
                Trending
            </h3>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 <?php echo($is_four_columns ? 'xl:grid-cols-4' : ''); ?> lg:gap-x-5 lg:gap-y-10">
                <?php 
                    foreach ($trending_post_ids as $index => $post_id) {
                        get_template_part(
                            "template-parts/cards/template-part", 
                            "post-card-compact", 
                            [
                                "post_id" => $post_id,
                                "card_number" => $index + 1,
                            ]
                        );
                    }
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>