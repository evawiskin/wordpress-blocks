<?php
// Simple, consistent args handling using defaults + array_merge + extract.
if (!isset($args) || !is_array($args)) {
    $args = [];
}

$args = array_merge(
    [
        "hover_class" => "hover:text-electric-green-500",
    ],
    $args
);
// Extract to local variables (e.g. $hover_class)
extract($args);

if (have_rows("option_company_info_socials", "options")) : ?>
    <ul id="social-options" class="flex flex-wrap items-center mb-6 list-unset">
        <?php while (have_rows("option_company_info_socials", "options")) : the_row();

            $social_url = get_sub_field("option_company_info_sub_social_url");
            $social_icon = get_sub_field("option_company_info_sub_social_icon");

            if (!$social_url || !$social_icon || !is_array($social_icon)) {
                continue;
            }
        ?>

            <li class="flex items-center mr-8 last:mr-0">
                <a class="block w-6 my-auto <?php echo(esc_attr($hover_class)); ?> after:content-none"
                    aria-label="<?php echo(array_key_exists("label", $social_icon) ? esc_html($social_icon["label"]) . " Social Link" : "Social Link"); ?>"
                    href="<?php echo(esc_url($social_url)); ?>"
                    target="_blank"
                >
                    <?php if (array_key_exists("value", $social_icon) && array_key_exists("label", $social_icon)) : ?>
                        <span class="sr-only"><?php echo(esc_html($social_icon["label"]) . " Social Link"); ?></span>
                        <span aria-hidden="true">
                            <?php
                            // Get Image File
                            $social_icon_file = get_theme_file_path("/assets/dist/imgs/socials/{$social_icon["value"]}");
                            if (file_exists($social_icon_file)) {
                                echo(file_get_contents($social_icon_file));
                            }
                            ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>

        <?php endwhile; ?>
    </ul>
<?php endif; ?>

