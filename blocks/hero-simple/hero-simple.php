<?php

$block_id      = set_block_id($block);
$block_classes = new BlockClasses($block);

$template = [
    [
        "core/paragraph",
        [
            "placeholder" => "Eyebrow text e.g. Resources",
            "style"       => [
                "typography" => [
                    "fontStyle"  => "normal",
                    "fontWeight" => "600",
                ]
            ],
            "fontSize"    => "small",
            "textAlign"   => "center",
        ]
    ],
    [
        "core/heading",
        [
            "level"       => 1,
            "placeholder" => "Really awesome values-driven headline",
            "textAlign"   => "center",
            "fontSize"    => "huge-heading",
        ]
    ],
];

$allowed_blocks = [
    "core/paragraph",
    "core/heading",
    "hiyield/custom-heading",
];

?>

<section
    id="<?php echo $block_id; ?>"
    class="<?php echo $block_classes; ?>"
>
    <div class="container mx-auto px-7 lg:px-8">
        <div class="mx-auto flex flex-col gap-3 text-center [&_h1]:text-balance [&_h2]:text-balance [&_h3]:text-balance">
            <InnerBlocks
                template="<?php echo esc_attr(wp_json_encode($template)); ?>"
                allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
            />
        </div>
    </div>
</section>
