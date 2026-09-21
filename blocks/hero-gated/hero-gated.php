<?php

if ((!isset($block)) || !is_array($block)) return;
if (display_block_preview_image($block)) return;

$block_id = set_block_id($block);
$block_classes = new BlockClasses($block);

$template = esc_attr(wp_json_encode([
    [
        "hiyield/columns2",
        [
            "data" => [
                "block_columns_count" => "2",
                "_block_columns_count" => "block_columns_count_key",
                "background_media_background_type" => "0",
                "_background_media_background_type" => "background_media_background_type_key",
                "background_media_desktop_image_id" => "",
                "_background_media_desktop_image_id" => "background_media_desktop_image_id_key",
                "background_media_mobile_image_id" => "",
                "_background_media_mobile_image_id" => "background_media_mobile_image_id_key"
            ],
            "mode" => "preview",
            "alignText" => "left",
            "alignContent" => "top"
        ],
        [ // Inner columns
            [
                "hiyield/column2",
                [
                    "data" => [
                        "block_column_span" => "",
                        "_block_column_span" => "block_column_span_key",
                        "block_column_start" => "",
                        "_block_column_start" => "block_column_start_key"
                    ],
                    "mode" => "preview",
                    "alignText" => "left",
                    "alignContent" => "center"
                ],
                [ // Column content
                    [
                        "core/heading",
                        [
                            "level" => 2,
                            "placeholder" => "Column Heading",
                            "fontSize" => "small-heading",
                            "content" => "<strong>From prototype to product with purpose: A digital product roadmap for founders</strong>"
                        ]
                    ],
                    [
                        "core/paragraph",
                        [
                            "placeholder" => "Column content goes here. You can add text, images, buttons, and more.",
                            "style" => [
                                "spacing" => [
                                    "margin" => [
                                        "bottom" => "var:preset|spacing|40px"
                                    ]
                                ]
                            ],
                            "fontSize" => "text-hy-lg",
                            "content" => "Stop guessing and start building. Get the guide to navigating the journey from bright idea to trusted platform."
                        ]
                    ],
                    [
                        "hiyield/buttons",
                        [
                            "data" => [
                                "block_buttons_buttons_layout" => "0",
                                "_block_buttons_buttons_layout" => "block_buttons_buttons_layout_key",
                                "block_buttons_button_repeater_key_0_block_buttons_button_type" => "link",
                                "_block_buttons_button_repeater_key_0_block_buttons_button_type" => "block_buttons_button_type_key",
                                "block_buttons_button_repeater_key_0_block_buttons_button_link" => [
                                    "title" => "ABC",
                                    "url" => "#abc",
                                    "target" => ""
                                ],
                                "_block_buttons_button_repeater_key_0_block_buttons_button_link" => "block_buttons_button_link_key",
                                "block_buttons_button_repeater_key_0_block_buttons_button_style" => "hy-button-primary",
                                "_block_buttons_button_repeater_key_0_block_buttons_button_style" => "block_buttons_button_style_key",
                                "block_buttons_button_repeater_key_0_block_buttons_button_icon_right" => "0",
                                "_block_buttons_button_repeater_key_0_block_buttons_button_icon_right" => "block_buttons_button_icon_right_key",
                                "block_buttons_button_repeater_key_0_block_buttons_button_icon_left" => "0",
                                "_block_buttons_button_repeater_key_0_block_buttons_button_icon_left" => "block_buttons_button_icon_left_key",
                                "block_buttons_button_repeater_key" => 1,
                                "_block_buttons_button_repeater_key" => "block_buttons_button_repeater_key"
                            ],
                            "mode" => "preview"
                        ]
                    ]
                ]
            ],
            [
                "hiyield/column2",
                [
                    "data" => [
                        "block_column_span" => "5",
                        "_block_column_span" => "block_column_span_key",
                        "block_column_start" => "8",
                        "_block_column_start" => "block_column_start_key"
                    ],
                    "mode" => "preview",
                    "alignText" => "left",
                    "alignContent" => "center"
                ],
                [
                    [
                        "hiyield/featured-image",
                        [
                            "data" => [
                                "block_featured_image_image_select" => 10445,
                                "_block_featured_image_image_select" => "block_featured_image_image_select_key"
                            ],
                            "mode" => "preview",
                            "alignText" => "left"
                        ]
                    ]
                ]
            ]
        ]
    ]
]));

?>

<section
    id="<?php echo($block_id); ?>"
    class="<?php echo($block_classes); ?>"
>
    <div class="container">
        <InnerBlocks
            template="<?php echo($template); ?>"
            templateLock="all"
        />
    </div>
</section>
