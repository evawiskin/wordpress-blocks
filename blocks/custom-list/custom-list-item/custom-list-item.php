<?php
if ((!isset($block)) || !is_array($block)) {
    return;
}

$block_id = set_block_id($block);
$block_classes = new BlockClasses($block);

// Get the gap field
$gap_class = get_field('block_custom_list_item_gap') ?: 'gap-4';

$template = [
    [
        "hiyield/icon",
        [
            "block_icon_icon_select" => "check.svg"
        ]
    ],
    [
        "core/paragraph",
        [
            "placeholder" => "List item text...",
            "style" => [
                "spacing" => [
                    "margin" => [
                        "bottom" => "0",
                        "top" => "0"
                    ]
                ]
            ]
        ]
    ]
];
?>
<li id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($block_classes); ?>">
    <InnerBlocks class="flex flex-nowrap items-center <?php echo esc_attr($gap_class); ?>" template="<?php echo esc_attr(wp_json_encode($template)); ?>" templateLock="all"/>
</li>
