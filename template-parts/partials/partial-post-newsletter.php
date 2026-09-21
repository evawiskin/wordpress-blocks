<?php
    $newsletter_title = get_field("option_newsletter_title", "options");
    $newsletter_page_link = get_field("option_newsletter_page_link", "options");

    if (is_array($newsletter_page_link) && !empty($newsletter_page_link)):
        $newsletter_page_link = array_merge([
            "title" => "",
            "url" => "",
            "target" => "_self"
        ], $newsletter_page_link);
?>

<div class="my-8">
    <?php if ($newsletter_title) : ?>
        <div class="flex items-center gap-4 mb-4">
            <span class="flex items-center w-16 h-16 svg-wrapper">
                <?php if(file_exists($hand_drawn_arrow_svg = get_theme_file_path("/assets/dist/imgs/hiyield-icons/hand-drawn-arrow.svg"))): ?>
                    <?php 
                        $svg_code = file_get_contents($hand_drawn_arrow_svg);
                        echo($svg_code);
                    ?>
                <?php endif; ?>
            </span>

            <?php echo("<h4 class=\"text-forest-green-500 theme-heading-tiny\">" . esc_html($newsletter_title) . "</h4>"); ?>
        </div>
    <?php endif; 

    get_template_part("template-parts/partials/partial", "button", [
        "button_content" => [
            "button_text" => $newsletter_page_link["title"],
            "button_target" => $newsletter_page_link["target"],
            "button_link" => $newsletter_page_link["url"]
        ],
        "aria_label" => $newsletter_page_link["title"],
        "button_classes" => "hy-button-primary text-[1rem]",
        "icon_right" => "arrow-right",
    ]);
    ?>
</div>

<?php endif; ?>