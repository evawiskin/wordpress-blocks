<?php

    // Step 0. Enqueue necessary JS
    // This JS contain complete code to transform datasets of filters fields to JSON arguments which match WP_Query args to make _POST request directly in ajax without any pre-processors
	ajax_filter_enqueue_scripts();

    // Step 1. Make proper config file, everything from here will be populated in a lopp in filters-all template part
    $config = [
        "tax" => [
            [
                "slug" => "category", // slug of taxonomy
                "allow_empty" => true, // allow to show terms without posts
                "label_singular" => "Category",
                "label_plural" => false,
                "template" => "checkbox" // Might be checkbox (multi), radio (single), select (dropdown)
            ],
            [
                "slug" => "post_tag", // slug of taxonomy
                "allow_empty" => true, // allow to show terms without posts
                "label_singular" => "Tag",
                "label_plural" => false,
                "template" => "select"
            ]
        ],
        "meta" => [
            [
                "key" => "product_brand", // meta field key
                "label_singular" => "Keyword",
                "label_plural" => false,
                "values" => [
                    "sony" => "Sony", // array of values (because we can't populate it)
                    "casio" => "Casio"
                ],
                "compare" => "IN", // compare for WP query
                "template" => "select", // Might be buttons, select, dropdown (for multi-use)
                "allow_empty" => true, // show only relevant values (WIP)
                "template" => "checkbox"
            ]
        ],
        "orderby" => [
            "template" => "select",
            "values" => [
                [
                    "type" => "title",
                    "meta_key" => false, // In case of type="meta_value"
                    "asc_label" => "Title (asc)", // False to disable it
                    "desc_label" => "Title (desc)"
                ],
                [
                    "type" => "meta_value",
                    "meta_key" => "hello", // In case of type="meta_value"
                    "asc_label" => "Meta key (asc)", // False to disable it
                    "desc_label" => "Meta key (desc)"
                ]
            ]
        ]
    ];

	// Template part with result
    // This template part have all custom information and might be variable for different filters
	$template_part = "template-parts/filter/filters-result-case-study";

    // Step 2 - Render filters-all (filter bar template part wit ahh filters)
    get_template_part( "template-parts/filter/filters", "all" );

?>

<?php
    /* Step 3. Create div with ID "axax-filterable" and put here result template part.
    * This template part will be reloaded on ajax request and also this template part "listen" for all _POST incoming data and adjust cards in a loop.
    * Also in this template part was included pagination. It is because it should be reloaded on filtering since amount of pages might be different
    * !! Important to put template part in dataset because this is path to template part which will be loaded on ajax request
    */
?>
<div id="ajax-filterable" data-template-part="<?php echo($template_part); ?>">
    <?php get_template_part( "template-parts/filter/filters", "result" );?>
</div>