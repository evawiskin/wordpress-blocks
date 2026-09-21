<?php 
    if (!isset($block)) {
        $block = [];
    }
    $block_classes = new BlockClasses($block);
    $image_id = get_field("block_featured_image_image_select") ?: [];
    
    // Add placeholder in the editor
    if (empty($image_id)) {
        if (is_admin()) {
            get_template_part("template-parts/utility/block-admin-message", null, [
                "message" => "Select an image to begin"
            ]);
        }
        return;
    }
?>
<figure class="<?php echo($block_classes); ?>">
    <?php do_action("hy_block_start", $block); ?>
    <?php 
        if ($caption = get_field("block_featured_image_image_caption")) :
            $caption_icon = get_field("block_featured_image_caption_icon") ?: "";
            $icon = get_svg_icon($caption_icon);
            ?>
                <figcaption class="flex items-center gap-2 mb-4 text-sm font-semibold">
                    <?php
                        if ($icon) :
                    ?>
                        <span class="block shrink-0 size-4 text-electric-green-500"><?php echo($icon); ?></span>
                    <?php
                        endif;
                        echo($caption); 
                    ?>
                </figcaption>
            <?php
        endif;

        echo(wp_get_attachment_image($image_id, "full", false, [
            "class" => "rounded-t-xl"
        ]));
    ?>
</figure>