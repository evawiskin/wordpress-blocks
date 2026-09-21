<?php

$block_id      = set_block_id($block);
$block_classes = new BlockClasses($block);

$is_preview = isset($block["data"]["is_preview"]) && $block["data"]["is_preview"];

$posts_per_page = (int) (get_field("block_resources_listing_posts_per_page") ?: 3);

$paged = (get_query_var("paged")) ? get_query_var("paged") : 1;

$resources_query = new WP_Query([
    "post_type"      => "resource",
    "post_status"    => "publish",
    "posts_per_page" => $is_preview ? 1 : $posts_per_page,
    "paged"          => $paged,
    "orderby"        => "date",
    "order"          => "DESC",
]);

$bg_colour_map = [
    "forest-green-500"    => "bg-forest-green-500",
    "forest-green-600"    => "bg-forest-green-600",
    "deep-ocean-blue-500" => "bg-deep-ocean-blue-500",
    "sky-blue-500"        => "bg-sky-blue-500",
    "electric-green-500"  => "bg-electric-green-500",
    "sunshine-yellow-500" => "bg-sunshine-yellow-500",
    "flamingo-pink-500"   => "bg-flamingo-pink-500",
    "purple-500"          => "bg-purple-500",
    "black"               => "bg-black",
    "white"               => "bg-white",
    "neutral-500"         => "bg-neutral-500",
    "gretter-50"          => "bg-gretter-50",
    "gretter-100"         => "bg-gretter-100",
];

$text_colour_map = [
    "white"               => "text-white",
    "black"               => "text-black",
    "forest-green-500"    => "text-forest-green-500",
    "forest-green-600"    => "text-forest-green-600",
    "deep-ocean-blue-500" => "text-deep-ocean-blue-500",
    "electric-green-500"  => "text-electric-green-500",
];


$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
if ($modals_uri = get_theme_file_uri("assets/dist/js/modals.js")) {
    wp_enqueue_script("custom-modals", $modals_uri, [], $build_version, true);
}
if ($script_uri = get_theme_file_uri("assets/dist/js/resources-listing.js")) {
    wp_enqueue_script("resources-listing", $script_uri, ["custom-modals"], $build_version, true);
}

?>

<section
    id="<?php echo $block_id; ?>"
    class="<?php echo $block_classes; ?>"
>
    <div class="container max-w-[1280px] mx-auto px-7 lg:px-8 flex flex-col gap-[90px]">

        <?php if ($resources_query->have_posts()) : ?>

            <?php $index = 0; while ($resources_query->have_posts()) : $resources_query->the_post();

                $resource_id  = get_the_ID();
                $title        = get_the_title();
                $subtitle           = get_field("cpt_resource_subtitle", $resource_id);
                $description_intro  = get_field("cpt_resource_description_intro", $resource_id);
                $description        = get_field("cpt_resource_description", $resource_id);
                $cover_image  = get_field("cpt_resource_cover_image", $resource_id);
                $accent_key   = get_field("cpt_resource_accent_colour", $resource_id) ?: "forest-green-500";
                $text_key     = get_field("cpt_resource_text_colour", $resource_id) ?: "white";
                $button_text  = get_field("cpt_resource_button_text", $resource_id) ?: "Download free guide";
                $resource_gform_id = get_field("cpt_resource_gform_id", $resource_id);
                $resource_url = get_permalink();
                $modal_id     = "resource-download-modal-{$resource_id}";

                $card_bg   = $bg_colour_map[$accent_key]   ?? "bg-forest-green-500";
                $card_text = $text_colour_map[$text_key]   ?? "text-white";

                $cover_image_url = $cover_image ? wp_get_attachment_image_url($cover_image, "large") : "";
                $cover_image_alt = $cover_image ? get_post_meta($cover_image, "_wp_attachment_image_alt", true) : "";

                // Alternating layout: even index = card left, odd = card right
                $card_first = ($index % 2 === 0);
            ?>

                <div class="flex flex-col lg:flex-row gap-6 items-center <?php echo $card_first ? "" : "lg:flex-row-reverse"; ?>">

                    <!-- Coloured resource card -->
                    <div class="<?php echo esc_attr($card_bg); ?> <?php echo esc_attr($card_text); ?> rounded-xl shadow-[0_7px_7.6px_rgba(0,80,80,0.1)] flex flex-col justify-between gap-8 w-full lg:w-5/12 overflow-hidden lg:overflow-visible">
                        <div class="flex flex-col gap-4 px-10 pt-10">
                            <?php if ($title) : ?>
                                <p class="font-athletics font-extrabold text-5xl leading-[1.2] mb-0"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>
                            <?php if ($subtitle) : ?>
                                <p class="font-area-normal font-bold text-lg mb-0"><?php echo esc_html($subtitle); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($cover_image_url) : ?>
                            <div class="flex-1 flex items-end">
                                <img
                                    src="<?php echo esc_url($cover_image_url); ?>"
                                    alt="<?php echo esc_attr($cover_image_alt); ?>"
                                    class="w-[calc(100%+40px)] max-w-none -mx-5 -mb-5 object-contain object-bottom"
                                    loading="lazy"
                                    style="clip-path: <?php echo $card_first ? 'polygon(20px 0, 100% 0, 100% calc(100% - 20px), 20px calc(100% - 20px))' : 'polygon(0 0, calc(100% - 20px) 0, calc(100% - 20px) calc(100% - 20px), 0 calc(100% - 20px))'; ?>;"
                                />
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Text and actions column -->
                    <div class="flex flex-col  flex-1 <?php echo $card_first ? "lg:pl-20" : "lg:pr-20"; ?>">

                        <?php if ($description_intro) : ?>
                            <p class="text-forest-green-600 text-lg font-extrabold leading-[1.75]"><?php echo nl2br(esc_html($description_intro)); ?></p>
                        <?php endif; ?>

                        <?php if ($description) : ?>
                            <div class="prose prose-forest-green text-forest-green-600 text-base leading-[1.75]">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex w-full flex-col sm:flex-row flex-wrap gap-4 mt-8">
                            <?php if ($resource_gform_id) : ?>
                                <button
                                    type="button"
                                    class="hy-button-primary w-full sm:w-auto justify-center"
                                    onclick="triggerResourceModal('<?php echo esc_js($modal_id); ?>')"
                                    aria-haspopup="dialog"
                                >
                                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 4v12m0 0-4-4m4 4 4-4M4 20h16"/></svg>
                                    <span><?php echo esc_html($button_text); ?></span>
                                </button>
                            <?php endif; ?>

                            <a href="<?php echo esc_url($resource_url); ?>" class="hy-button-outline-forest w-full sm:w-auto justify-center">
                                <span>Learn more</span>
                                <svg class="size-3 shrink-0" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12.9397 6.5C12.9397 6.625 12.9159 6.74722 12.8683 6.86667C12.8207 6.98611 12.7506 7.09306 12.6579 7.1875L7.34522 12.6875C7.16008 12.8958 6.93603 13 6.67308 13C6.41013 13 6.18474 12.8958 5.99692 12.6875C5.8091 12.4931 5.71518 12.2639 5.71518 12C5.71518 11.7361 5.8091 11.5069 5.99692 11.3125L9.67959 7.47917L0.945823 7.47917C0.676471 7.47917 0.451499 7.3842 0.270907 7.19427C0.0903018 7.00433 -2.72248e-07 6.77169 -2.84283e-07 6.49635C-2.96319e-07 6.22101 0.0913957 5.98958 0.274188 5.80208C0.45698 5.61458 0.680858 5.52083 0.945823 5.52083L9.67959 5.52083L5.99692 1.6875C5.8091 1.49306 5.71518 1.26389 5.71518 1C5.71518 0.73611 5.80804 0.5 5.99374 0.291667C6.17944 0.0972221 6.40416 -2.79935e-07 6.66789 -2.91463e-07C6.93162 -3.02991e-07 7.1574 0.097222 7.34522 0.291667L12.6579 5.8125C12.7518 5.90972 12.8223 6.01799 12.8692 6.13731C12.9162 6.25663 12.9397 6.37753 12.9397 6.5Z" fill="currentColor"/></svg>
                            </a>
                        </div>

                    </div>

                </div>

                <?php if ($resource_gform_id) : ?>
                    <div
                        id="<?php echo esc_attr($modal_id); ?>"
                        data-effect="fade"
                        class="resource-download-modal modal fixed inset-0 z-30 flex items-center justify-center w-full h-full overflow-hidden transition-all backdrop-blur-md bg-black/60 duration-150 opacity-0 invisible"
                        role="dialog"
                        aria-modal="true"
                        aria-label="<?php echo esc_attr("Download: " . $title); ?>"
                        tabindex="-1"
                    >
                        <!-- Click outside to close -->
                        <div class="absolute inset-0 z-10 modal-dismiss" aria-hidden="true"></div>

                        <!-- Modal panel -->
                        <div class="relative z-20 bg-forest-green-600 text-white rounded-2xl shadow-2xl w-[90vw] max-w-[90vw] mx-auto p-8 lg:p-12 max-h-[70dvh] overflow-y-auto">

                            <button
                                type="button"
                                class="modal-dismiss absolute top-6 right-6 text-white hover:text-white transition-colors"
                                aria-label="Close"
                            >
                                <svg class="size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </button>

                            <p class="font-athletics font-extrabold text-3xl lg:text-4xl text-electric-green-500 mb-4">Grab your FREE copy of <?php echo esc_html($title); ?></p>

                            <p class="font-area-normal font-semibold text-base mb-6 text-white">Ready to get started? Just pop your details in the form below, and we will send the PDF straight to your inbox.</p>
                            <div class="gated-access-form">
                                <?php echo do_shortcode("[gravityform id=\"" . intval($resource_gform_id) . "\" title=\"false\" description=\"false\" ajax=\"true\"]"); ?>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

            <?php $index++; endwhile; wp_reset_postdata(); ?>

            <?php if ($resources_query->max_num_pages > 1) : ?>
                <div class="resources-pagination border-t border-forest-green-100 pt-5 flex justify-center">
                    <?php echo str_replace("class='page-numbers'", "class='page-numbers list-unset'", paginate_links([
                        "base"      => trailingslashit(get_post_type_archive_link("resource")) . "%_%",
                        "format"    => "page/%#%/",
                        "current"   => $paged,
                        "total"     => $resources_query->max_num_pages,
                        "show_all"  => true,
                        "prev_text" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>',
                        "next_text" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>',
                        "type"      => "list",
                    ])); ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <p class="text-forest-green-500">No resources found.</p>
        <?php endif; ?>

    </div>
</section>
