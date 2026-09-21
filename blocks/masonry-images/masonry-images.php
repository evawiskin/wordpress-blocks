<?php 
    if (!isset($block)) {
        $block = [];
    }
    $block_classes = new BlockClasses($block);
    $image_ids = get_field("block_masonry_images_images_select") ?: [];
    
    // Add placeholder in the editor
    if (empty($image_ids)) {
        if (is_admin()) {
            get_template_part("template-parts/utility/block-admin-message", null, [
                "message" => "Select images to begin"
            ]);
        }
        return;
    }
?>
<div class="flex gap-6 flex-wrap <?php echo($block_classes); ?>">
    <?php do_action("hy_block_start", $block); ?>
    <?php 
        foreach($image_ids as $index => $image_id) :

            // Calculation for masonry 60/40 layout
            $count = $index + 1; 
            $is_start_of_60_40_row = ($count % 4 === 1 || $count % 4 === 2);
            $mod4 = $count % 4;
            if ($mod4 === 1 || $mod4 === 2) {
                $width = ($mod4 === 1) ? "w-[calc(60%-0.75rem)]" : "w-[calc(40%-0.75rem)]";
            } else {
                $width = ($mod4 === 3) ? "w-[calc(40%-0.75rem)]" : "w-[calc(60%-0.75rem)]";
            }
        ?>
            <figure class="image-span-full-in-editor overflow-hidden rounded-xl h-40 <?php echo($width); ?>">
                <?php 
                    echo(wp_get_attachment_image($image_id, "large", false, [
                        "class" => "size-full object-cover object-center"
                    ]));
                ?>
            </figure>
        <?php
        endforeach;
    ?>
</div>