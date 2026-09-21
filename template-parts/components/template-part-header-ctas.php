<?php    
	$args = array_merge(
        [
            "wrapper_classes" => null,
        ],
        $args
    );
    $wrapper_classes = $args["wrapper_classes"];
?>
<div class="<?php echo($wrapper_classes); ?>">
    <?php
        if (have_rows("option_header_ctas", "option")): 
            while (have_rows("option_header_ctas", "option")): the_row();

                // Initialise variable state
                $icon = get_sub_field("option_header_ctas_icon");

                $link = get_sub_field("option_header_ctas_link") ?: [];
                if (!is_array($link)) continue;

                // Merge expected keys
                $link = array_merge([
                    "title" => "",
                    "url" => "",
                    "target" => "_self"
                ], $link);

                $link_content = [
                    "button_text" => $link["title"],
                    "button_target" => $link["target"],
                    "button_link" => $link["url"]
                ];

                $button_args = [
                    "button_content" => $link_content,
                    "aria_label" => get_sub_field("option_header_ctas_link")["title"],
                    "button_classes" => get_sub_field("option_header_ctas_style") ?: "hy-button-primary",
                ];

                if ($icon) {
                    $button_args["icon_right"] = $icon;
                }

                get_template_part("template-parts/partials/partial", "button", $button_args);

            endwhile;
        endif;
    ?>
</div>