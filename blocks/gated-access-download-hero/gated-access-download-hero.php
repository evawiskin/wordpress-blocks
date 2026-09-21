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
            "fontSize"  => "x-large",
            "placeholder" => "Your download is ready!"
        ]
    ],
    [
        "core/paragraph",
        [
            "placeholder" => "Thanks for completing the form..."
        ]
    ]
]));
$allowed_blocks = esc_attr(wp_json_encode(["core/heading", "core/paragraph"]));

$page_id        = get_the_ID();
$button_text    = get_field("block_gated_access_download_hero_button_text") ?: "Download Now";
$locked_message = get_field("block_gated_access_download_hero_locked_message") ?: "Please complete the form to access your download.";

gated_access_download_hero_enqueue_scripts();

?>

<section
    id="<?php echo($block_id); ?>"
    class="<?php echo($block_classes); ?>"
>
    <div class="container grid-design flex flex-col gap-8">
        <div class="col-span-full lg:col-span-9">
            <div class="gated-download-hero" data-page-id="<?php echo(esc_attr($page_id)); ?>">

                <InnerBlocks
                    template="<?php echo($template); ?>"
                    allowedBlocks="<?php echo($allowed_blocks); ?>"
                />

                <!-- Revealed by JS after access token is verified -->
                <div class="gated-download-hero__download" hidden>
                    <?php get_template_part("template-parts/partials/partial", "button", [
                        "button_classes" => "hy-button-primary gated-download-hero__btn",
                        "button_content" => [
                            "button_text"   => $button_text,
                            "button_link"   => "#",
                            "button_target" => "_blank",
                        ],
                    ]); ?>
                </div>

                <!-- Shown when no valid access token is present; hidden initially to prevent flash -->
                <div class="gated-download-hero__locked" hidden>
                    <p><?php echo(esc_html($locked_message)); ?></p>
                </div>

                <noscript>
                    <p><?php echo(esc_html($locked_message)); ?></p>
                </noscript>

            </div>
        </div>
    </div>
</section>
