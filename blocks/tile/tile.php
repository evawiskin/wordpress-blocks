<?php

    if ((!isset($block)) || !is_array($block)) {
        return;
    }

    // Render block preview image.
    if (display_block_preview_image($block)) {
        return;
    }

    $block_id = set_block_id($block);
    $allowed_blocks = get_allowed_blocks();

    // Use BlockClasses system
    $block_classes = new BlockClasses($block);

    // Get column index for display
    $column_index = get_field("block_tile_show_index");
    
    // Get theme border color for the index circle
    $border_color = get_field("block_tile_border_color");
    $border_color = $border_color ? $border_color : "white"; // Default to white theme color if not set

    // Get link
    $link = get_field("block_tile_link");

    // Template
    $template = [
        [
            "core/heading",
            [
                "placeholder" => "Tile Heading",
                "fontSize" => "text-hy-5xl"
            ]
        ],
        [
            "core/paragraph",
            [
                "placeholder" => "Column content goes here. You can add text, images, buttons, and more.",
                "fontSize" => "text-hy-lg"
            ]
        ]
    ];

    // In some situations we might want to modify the styling based on whether the block has a background color set.
    if ($block_classes->background_color) {
        $bg_classes = " rounded-2xl ";
    } else {
        $bg_classes = "";
    }

    // Build wrapper element attributes
    $is_link = $link && is_array($link) && !empty($link['url']);
    $wrapper_tag = $is_link ? 'a' : 'div';
    $wrapper_attrs = [];
    
    if ($is_link) {
        $wrapper_attrs['href'] = esc_url($link['url']);
        if (!empty($link['target'])) {
            $wrapper_attrs['target'] = esc_attr($link['target']);
        }
        if (!empty($link['title'])) {
            $wrapper_attrs['title'] = esc_attr($link['title']);
        }
    }
    
    // Build classes
    $wrapper_classes = [
        (!is_admin() ? 'relative' : ''),
        'hy-tile',
        !is_admin() ? 'h-full' : '',
        $bg_classes,
        $block_classes,
        $is_link ? 'hover:!bg-[rgba(256,256,256,0.05)] transition-colors duration-200' : ''
    ];
    $wrapper_classes = array_filter($wrapper_classes);
?>

<<?php echo $wrapper_tag; ?> 
    id="<?php echo esc_attr($block_id); ?>" 
    class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>"
    <?php foreach ($wrapper_attrs as $attr => $value) : ?>
        <?php echo $attr; ?>="<?php echo $value; ?>"
    <?php endforeach; ?>
>
    <?php if ($column_index && is_numeric($column_index) && $column_index > 0) : ?>
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 size-16 flex items-center justify-center rounded-full theme-heading-mini leading-none mb-0 text-electric-green-500 border-4 border-<?php echo esc_attr($border_color); ?> <?php echo($block_classes->background_color); ?>">
            <span class="relative -top-0.5 font-extrabold">
                <?php echo intval($column_index); ?>
            </span>
        </div>
    <?php endif; ?>
    
    <InnerBlocks 
        class=""
        template="<?php echo esc_attr(wp_json_encode($template)); ?>"
        allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
    />
</<?php echo $wrapper_tag; ?>>

<style>
    
    <?php if ($is_link) : ?>
        /* Underline headings on hover when tile is a link */
        #<?php echo esc_attr($block_id); ?>:hover h1,
        #<?php echo esc_attr($block_id); ?>:hover h2,
        #<?php echo esc_attr($block_id); ?>:hover h3,
        #<?php echo esc_attr($block_id); ?>:hover h4,
        #<?php echo esc_attr($block_id); ?>:hover h5,
        #<?php echo esc_attr($block_id); ?>:hover h6 {
            text-decoration: underline;
        }
        #<?php echo esc_attr($block_id); ?>:hover .hy-icon-content div[id*="icon_block"] svg,
        #<?php echo esc_attr($block_id); ?>:hover .hy-icon-content div[id*="icon_block"] svg * {
            fill: #00faa0 !important;
            color: #00faa0 !important;
        }
    <?php endif; ?>
</style>

<?php if ($is_link && is_admin()) : ?>
<script>
(function() {
    const tile = document.getElementById('<?php echo esc_js($block_id); ?>');
    if (tile && tile.tagName === 'A') {
        tile.addEventListener('click', function(e) {
            e.preventDefault();
            return false;
        });
    }
})();
</script>
<?php endif; ?>