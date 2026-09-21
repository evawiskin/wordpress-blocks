<?php
if ((!isset($block)) || !is_array($block))
    return;

    // Render block preview image.
    if (display_block_preview_image($block)) return;

    $block_id = set_block_id($block);

    /* Get the gutenberg block classes */
    $block_classes = new BlockClasses($block);

    $icon_size_classes = get_field("block_icon_icon_size") ?: "size-8";
    $icon_file = get_field("block_icon_icon_select");

    // Ascertain if there is a background class, apply padding if so
    $has_padding = $block_classes->background_color ? "p-1.5" : "";
    $has_border = get_field("block_icon_show_border") ? "p-1.5 border-2 border-[#356869]" : "";
    $icon_classes = trim("{$icon_size_classes} {$has_padding} {$has_border} rounded-lg");

    if($icon_file){
        $icon = get_svg_icon($icon_file, "icon-selector-icons") ;
    } else {
        $icon = false;
    }

    if(!$icon) {
        return;
    }
?>

    <div id="<?php echo($block_id); ?>" class=" flex items-center shrink-0 <?php echo($block_classes . " " . $icon_classes); ?>"
        data-size="<?php echo($icon_size_classes); ?>"
    >
        <span class="inline-block size-full">
            <?php echo($icon); ?>
        </span>
    </div>

    <?php if(is_admin()) : ?>
        <style>
            :where(.editor-styles-wrapper) .wp-block-hiyield-icon {
                /* Ensure that the icon block does not render incorrectly in admin */ 
                background-color: transparent !important;
            }
        </style>
    <?php endif; 