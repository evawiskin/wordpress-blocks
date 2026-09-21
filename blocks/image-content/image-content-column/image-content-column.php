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

    $template = [
        [
            "core/heading",
            [
                "level" => 3,
                "placeholder" => "Heading Goes Here",
                "fontSize" => "text-hy-5xl"
            ],
        ],
        [
            "core/paragraph",
            [
                "placeholder" => "Enter your paragraph text here...",
                "fontSize" => "text-hy-lg"
            ]
        ]
    ];

?>

<div class="flex h-full <?php echo($block_classes); ?>">
	<InnerBlocks 
		class="column prose w-full"
		template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
	/>
</div>