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
    
    // Check if in admin
    $is_admin = is_admin();

    // Get link
    $link = get_field("block_card_link");

    // Template
    $template = [
        [
            "hiyield/icon-content",
            [
                "data" => [
                    "block_icon_content_gap" => "gap-2"
                ],
                "template" => [
                    [
                        "core/heading",
                        [
                            "level" => 3,
                            "fontSize" => "micro-heading",
                            "style" => [
                                "spacing" => [
                                    "margin" => [
                                        "bottom" => "0"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "hiyield/icon",
                        [
                            "data" => [
                                "block_icon_icon_select_key" => "arrow-right.svg",
                                "block_icon_icon_size_key" => "size-4"
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            "core/paragraph",
            [
                "placeholder" => "Card content goes here...",
                "style" => [
                    "typography" => [
                        "fontWeight" => "600"
                    ]
                ]
            ]
        ]
    ];

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
        'relative',
        'hy-card',
        $block_classes,
        $is_link ? 'group' : '',
        $is_admin ? 'pointer-events-none' : ''
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

    <?php if ($image_id = get_field("block_card_image")) : ?>
        <div class="aspect-w-12 aspect-h-7 overflow-hidden mb-5 rounded-xl">
            <figure class="group-hover:scale-105 transition-transform duration-200">
                <?php 
                    echo(wp_get_attachment_image($image_id, "large", false, [
                        "class" => "size-full object-cover object-center"
                    ]));
                ?>
            </figure>
        </div>
    <?php elseif ($is_admin) : ?>
        <div class="relative aspect-w-12 aspect-h-7 bg-gray-50 border border-dashed border-gray-400 mb-4">
            <span class="absolute left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2 size-10 text-gray-400">
                <?php echo(get_svg_icon("image")); ?>
            </span>
        </div>
    <?php endif; ?>
    
    <InnerBlocks 
        template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
        allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
    />
</<?php echo $wrapper_tag; ?>>

<style>
    <?php if ($is_link) : ?>
    /* Underline headings on hover when card is a link */
    #<?php echo esc_attr($block_id); ?>:hover h1,
    #<?php echo esc_attr($block_id); ?>:hover h2,
    #<?php echo esc_attr($block_id); ?>:hover h3,
    #<?php echo esc_attr($block_id); ?>:hover h4,
    #<?php echo esc_attr($block_id); ?>:hover h5,
    #<?php echo esc_attr($block_id); ?>:hover h6 {
        text-decoration: underline;
    }

    <?php endif; ?>
</style>

<?php if ($is_link && $is_admin) : // click prevention ?>
<script>
(function() {
    const card = document.getElementById('<?php echo esc_js($block_id); ?>');
    if (card && card.tagName === 'A') {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            return false;
        });
    }
})();
</script>
<?php endif; ?>