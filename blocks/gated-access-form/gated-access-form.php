<?php

$block_id      = set_block_id($block);
$block_classes = new BlockClasses($block);

$template = esc_attr(wp_json_encode([
    [
        "core/heading",
        [
            "style" => [
                "elements" => [
                    "link" => [
                        "color" => [
                            "text" => "var:preset|color|electric-green-500"
                        ]
                    ]
                ]
            ],
            "textColor" => "electric-green-500",
            "fontSize" => "x-large",
            "placeholder" => "Grab your FREE ..."
        ]
    ],
    [
        "core/paragraph",
        [
            "style" => [
                "typography" => [
                    "fontStyle" => "normal",
                    "fontWeight" => "600"
                ]
            ],
            "placeholder" => "Ready to get started? ..."
        ]
    ]
]));
$allowed_blocks = esc_attr(wp_json_encode(["core/heading", "core/paragraph"]));

$post_gform_id = get_field("cpt_resource_gform_id", get_the_ID());

?>

<section
    id="<?php echo $block_id; ?>"
    class="<?php echo $block_classes; ?>"
>
    <div class="container grid-design flex flex-col gap-8">
        <div class="col-span-full lg:col-span-9">
            <InnerBlocks
                template="<?php echo $template; ?>"
                allowedBlocks="<?php echo $allowed_blocks; ?>"
            />

            <?php if ($post_gform_id) : ?>
                <div class="gated-access-form">
                    <?php echo do_shortcode("[gravityform id=\"" . intval($post_gform_id) . "\" title=\"false\" description=\"false\" ajax=\"false\"]"); ?>
                </div>
            <?php else : ?>
                <p class="text-sm opacity-60">No form selected. Set the <strong>Gated Download Form</strong> field in the Resource Details panel on this post.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
