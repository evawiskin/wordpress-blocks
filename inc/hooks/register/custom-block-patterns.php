<?php 
    // Remove Default Patterns
    remove_theme_support("core-block-patterns");

    // Register Layout Category
    register_block_pattern_category("layout", ["label" => "Layout"]);

    // Register Custom Patterns

    register_block_pattern("hiyield/image-right-content-left", [
        "title" => "Image Right & Content Left",
        "description" => "A custom block that is split in two halves with a image on one side and content on the other",
        "categories" => ["layout"],
        "content" => "
            <!-- wp:hiyield/columns {\"name\":\"hiyield/columns\",\"data\":{\"block_columns_spacing\":\"lg\",\"_block_columns_spacing\":\"block_columns_spacing_key\",\"block_columns_heading\":\"\",\"_block_columns_heading\":\"block_columns_heading_key\",\"block_columns_margin\":\"0\",\"_block_columns_margin\":\"block_columns_margin_key\",\"block_columns_count\":\"2\",\"_block_columns_count\":\"block_columns_count_key\",\"block_columns_gap\":\"sm\",\"_block_columns_gap\":\"block_columns_gap_key\",\"block_columns_align_items\":\"flex-start\",\"_block_columns_align_items\":\"block_columns_align_items_key\",\"block_columns_background_image\":\"\",\"_block_columns_background_image\":\"block_columns_background_image_key\",\"block_columns_add_background_image_filter\":\"0\",\"_block_columns_add_background_image_filter\":\"block_columns_add_background_image_filter_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\",\"backgroundColor\":\"\",\"textColor\":\"\"} -->
            <!-- wp:hiyield/column {\"name\":\"hiyield/column\",\"data\":{\"block_column_span\":\"1\",\"_block_column_span\":\"block_column_span_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:heading {\"className\":\"wp-block-heading\"} -->
            <h2 class=\"wp-block-heading\">Heading goes here...</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Curabitur at lacus ac velit ornare lobortis. Nunc nec neque nulla sit amet est. Curabitur vestibulum aliquam leo nullam accumsan lorem in dui.</p>
            <!-- /wp:paragraph -->
            <!-- /wp:hiyield/column -->
            
            <!-- wp:hiyield/column {\"name\":\"hiyield/column\",\"data\":{\"block_column_span\":\"1\",\"_block_column_span\":\"block_column_span_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:hiyield/image-wrapper {\"name\":\"hiyield/image-wrapper\",\"mode\":\"preview\"} -->
            <!-- wp:image {\"id\":3083,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
            <figure class=\"wp-block-image size-large\"><img src=\"https://hiyield.hiyieldwordpress.co.uk/wp-content/uploads/2023/09/1-3-1-1024x1024.webp\" alt=\"\" class=\"wp-image-3083\"/></figure>
            <!-- /wp:image -->
            <!-- /wp:hiyield/image-wrapper -->
            <!-- /wp:hiyield/column -->
            <!-- /wp:hiyield/columns -->
        "
    ]);

    register_block_pattern("hiyield/image-left-content-right", [
        "title" => "Image Left & Content Right",
        "description" => "A custom block that is split in two halves with a image on one side and content on the other",
        "categories" => ["layout"],
        "content" => "
            <!-- wp:hiyield/columns {\"name\":\"hiyield/columns\",\"data\":{\"block_columns_spacing\":\"lg\",\"_block_columns_spacing\":\"block_columns_spacing_key\",\"block_columns_heading\":\"\",\"_block_columns_heading\":\"block_columns_heading_key\",\"block_columns_margin\":\"0\",\"_block_columns_margin\":\"block_columns_margin_key\",\"block_columns_count\":\"2\",\"_block_columns_count\":\"block_columns_count_key\",\"block_columns_gap\":\"sm\",\"_block_columns_gap\":\"block_columns_gap_key\",\"block_columns_align_items\":\"flex-start\",\"_block_columns_align_items\":\"block_columns_align_items_key\",\"block_columns_background_image\":\"\",\"_block_columns_background_image\":\"block_columns_background_image_key\",\"block_columns_add_background_image_filter\":\"0\",\"_block_columns_add_background_image_filter\":\"block_columns_add_background_image_filter_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\",\"backgroundColor\":\"\",\"textColor\":\"\"} -->
            <!-- wp:hiyield/column {\"name\":\"hiyield/column\",\"data\":{\"block_column_span\":\"1\",\"_block_column_span\":\"block_column_span_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:hiyield/image-wrapper {\"name\":\"hiyield/image-wrapper\",\"mode\":\"preview\"} -->
            <!-- wp:image {\"id\":3083,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
            <figure class=\"wp-block-image size-large\"><img src=\"https://hiyield.hiyieldwordpress.co.uk/wp-content/uploads/2023/09/1-3-1-1024x1024.webp\" alt=\"\" class=\"wp-image-3083\"/></figure>
            <!-- /wp:image -->
            <!-- /wp:hiyield/image-wrapper -->
            <!-- /wp:hiyield/column -->

            <!-- wp:hiyield/column {\"name\":\"hiyield/column\",\"data\":{\"block_column_span\":\"1\",\"_block_column_span\":\"block_column_span_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:heading {\"className\":\"wp-block-heading\"} -->
            <h2 class=\"wp-block-heading\">Heading goes here...</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Curabitur at lacus ac velit ornare lobortis. Nunc nec neque nulla sit amet est. Curabitur vestibulum aliquam leo nullam accumsan lorem in dui.</p>
            <!-- /wp:paragraph -->
            <!-- /wp:hiyield/column -->

            <!-- /wp:hiyield/columns -->
        "
    ]);

    register_block_pattern("hiyield/three-columns", [
        "title" => "Three Columns",
        "description" => "A custom block that is split in three columns with text content in each",
        "categories" => ["layout"],
        "content" => "
            <!-- wp:hiyield/columns {\"name\":\"hiyield/columns\",\"data\":{\"block_columns_spacing_key\":\"lg\",\"block_columns_heading_key\":\"\",\"block_columns_margin_key\":\"0\",\"block_columns_count_key\":\"3\",\"block_columns_gap_key\":\"sm\",\"block_columns_align_items_key\":\"flex-start\",\"block_columns_background_image_key\":\"\",\"block_columns_add_background_image_filter_key\":\"0\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:hiyield/column {\"name\":\"hiyield/column\",\"data\":{\"block_column_span\":\"1\",\"_block_column_span\":\"block_column_span_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:heading {\"className\":\"wp-block-heading\"} -->
            <h2 class=\"wp-block-heading\">Heading goes here...</h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p>Curabitur at lacus ac velit ornare lobortis. Nunc nec neque nulla sit amet est. Curabitur vestibulum aliquam leo nullam accumsan lorem in dui.</p>
            <!-- /wp:paragraph -->
            <!-- /wp:hiyield/column -->

            <!-- wp:hiyield/column {\"name\":\"hiyield/column\",\"data\":{\"block_column_span\":\"1\",\"_block_column_span\":\"block_column_span_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:heading {\"className\":\"wp-block-heading\"} -->
            <h2 class=\"wp-block-heading\">Heading goes here...</h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p>Curabitur at lacus ac velit ornare lobortis. Nunc nec neque nulla sit amet est. Curabitur vestibulum aliquam leo nullam accumsan lorem in dui.</p>
            <!-- /wp:paragraph -->
            <!-- /wp:hiyield/column -->

            <!-- wp:hiyield/column {\"name\":\"hiyield/column\",\"data\":{\"block_column_span\":\"1\",\"_block_column_span\":\"block_column_span_key\"},\"mode\":\"preview\",\"alignText\":\"left\",\"alignContent\":\"top\"} -->
            <!-- wp:heading {\"className\":\"wp-block-heading\"} -->
            <h2 class=\"wp-block-heading\">Heading goes here...</h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p>Curabitur at lacus ac velit ornare lobortis. Nunc nec neque nulla sit amet est. Curabitur vestibulum aliquam leo nullam accumsan lorem in dui.</p>
            <!-- /wp:paragraph -->
            <!-- /wp:hiyield/column -->
            <!-- /wp:hiyield/columns -->
        "
    ]);

    register_block_pattern("hiyield/statistics", [
        "title" => "Statistics",
        "description" => "A prepopulated statistics block",
        "categories" => ["layout"],
        "content" => "
            <!-- wp:hiyield/statistics {\"name\":\"hiyield/statistics\",\"data\":{\"block_statistics_repeater_0_block_statistics_repeater_number_prefix\":\"\",\"_block_statistics_repeater_0_block_statistics_repeater_number_prefix\":\"block_statistics_repeater_number_prefix_key\",\"block_statistics_repeater_0_block_statistics_repeater_number\":\"14\",\"_block_statistics_repeater_0_block_statistics_repeater_number\":\"block_statistics_repeater_number_key\",\"block_statistics_repeater_0_block_statistics_repeater_number_suffix\":\"\",\"_block_statistics_repeater_0_block_statistics_repeater_number_suffix\":\"block_statistics_repeater_number_suffix_key\",\"block_statistics_repeater_0_block_statistics_repeater_label\":\"Start ups launched\",\"_block_statistics_repeater_0_block_statistics_repeater_label\":\"block_statistics_repeater_label_key\",\"block_statistics_repeater_1_block_statistics_repeater_number_prefix\":\"\",\"_block_statistics_repeater_1_block_statistics_repeater_number_prefix\":\"block_statistics_repeater_number_prefix_key\",\"block_statistics_repeater_1_block_statistics_repeater_number\":\"105\",\"_block_statistics_repeater_1_block_statistics_repeater_number\":\"block_statistics_repeater_number_key\",\"block_statistics_repeater_1_block_statistics_repeater_number_suffix\":\"\",\"_block_statistics_repeater_1_block_statistics_repeater_number_suffix\":\"block_statistics_repeater_number_suffix_key\",\"block_statistics_repeater_1_block_statistics_repeater_label\":\"Projects completed\",\"_block_statistics_repeater_1_block_statistics_repeater_label\":\"block_statistics_repeater_label_key\",\"block_statistics_repeater_2_block_statistics_repeater_number_prefix\":\"\",\"_block_statistics_repeater_2_block_statistics_repeater_number_prefix\":\"block_statistics_repeater_number_prefix_key\",\"block_statistics_repeater_2_block_statistics_repeater_number\":\"2870\",\"_block_statistics_repeater_2_block_statistics_repeater_number\":\"block_statistics_repeater_number_key\",\"block_statistics_repeater_2_block_statistics_repeater_number_suffix\":\"\",\"_block_statistics_repeater_2_block_statistics_repeater_number_suffix\":\"block_statistics_repeater_number_suffix_key\",\"block_statistics_repeater_2_block_statistics_repeater_label\":\"Trees planted\",\"_block_statistics_repeater_2_block_statistics_repeater_label\":\"block_statistics_repeater_label_key\",\"block_statistics_repeater\":3,\"_block_statistics_repeater\":\"block_statistics_repeater_key\"},\"mode\":\"preview\",\"textColor\":\"white\"} -->
            <!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"placeholder\":\"Heading Goes Here\",\"className\":\"wp-block-heading\",\"fontSize\":\"micro-heading\"} -->
            <h3 class=\"wp-block-heading has-text-align-center has-micro-heading-font-size\">From ideas to execution: our proven results.</h3>
            <!-- /wp:heading -->
            <!-- /wp:hiyield/statistics -->
        "
    ]);