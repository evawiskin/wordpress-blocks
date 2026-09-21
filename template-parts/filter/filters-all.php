<?php

    if( !isset($args["config"]) ) {
        return;
    }

    $config = $args["config"];
    $template_part = $args["template_part"];
    $active_filters = $args["active_filters"];
    $filters = [];
    $sorting_filter = [];
    $label_plural = "";
    $search = false;

    if(array_key_exists("search", $config)) {
        
        $filters[] = [
            "type" => "search",
            "template" => "search",
            "active_filters" => $active_filters
        ];

    }

    // STEP 1. Taxonomies - iterate through the taxonomies to get the terms for each taxonomy slug
    if( array_key_exists("tax", $config) ) {

        $tax_filters = $config["tax"];
        foreach($tax_filters as $tax_filter) {

            if(!isset($tax_filter["tax_slug"])) {
                continue;
            }

            $tax_slugs = is_array($tax_filter["tax_slug"]) ? $tax_filter["tax_slug"] : [$tax_filter["tax_slug"]];
            $template = $tax_filter["template"];
            $label = isset($tax_filter["label"]) ? $tax_filter["label"] : true;
            $hide_empty = isset($tax_filter["hide_empty"]) ? $tax_filter["hide_empty"] : true;

            $tax_label = "";
            if(count($tax_slugs) == 1) {
                $tax = get_tax($tax_slugs);
                $tax_label = $tax->label;
            }

            //if a custom label has been defined get that, otherwise fall back to tax label.
            $label_singular = isset($tax_filter["label_singular"]) ? $tax_filter["label_singular"] : $tax_label;
            $label_plural = isset($tax_filter["label_plural"]) ? $tax_filter["label_plural"] : $tax_label;

            foreach($tax_slugs as $tax_slug){
                $terms_by_tax_slug[$tax_slug] = get_terms([
                    "taxonomy"	=> $tax_slug,
                    "hide_empty"	=> $hide_empty
                ]);
                //so find the terms that exist on this specific post type with custom function
                $existing_terms = get_terms_by_post_type($post_type, $tax_slug);
                foreach($existing_terms as $existing_term) {
                    $existing_terms_by_tax_slug[$tax_slug][] = $existing_term->slug;
                }
                // remove items from $terms_by_tax_slug[$tax_slug] if not in $existing_terms_by_tax_slug[$tax_slug]
                $terms_by_tax_slug[$tax_slug] = array_filter($terms_by_tax_slug[$tax_slug], function($term) use ($existing_terms_by_tax_slug, $tax_slug) {
                    if(array_key_exists($tax_slug, $existing_terms_by_tax_slug))
                        return in_array($term->slug, $existing_terms_by_tax_slug[$tax_slug]);
                });
                // if $terms_by_tax_slug[$tax_slug] has no items, remove it from the array
                if (count($terms_by_tax_slug[$tax_slug]) == 0) {
                    unset($terms_by_tax_slug[$tax_slug]);
                }
            }

            if(count($terms_by_tax_slug) == 0) {
                continue;
            }

            $options = [];
            foreach($terms_by_tax_slug as $tax_slug => $terms) {
                foreach($terms as $term) {
                    $options[$tax_slug][$term->slug] = $term->name;
                }
            }

            //add it to our array of filters
            $filters[] = [
                "class"         => false,
                "type"          => "taxonomy",
                "options_by_slug" => $options,
                "label" 	    => ($label) ? $label_singular : false,
                "all_label"		=> "All {$label_plural}",
                "filter_uid"   	=> "tax_" . uniqid(),
                "template"      => $template,
                "active_filters" => $active_filters
            ];
        }
    }

    // STEP 2. Meta - iterate through the meta filters to get the terms for each meta key
    if (array_key_exists("meta", $config) ) {
        $meta_filters = $config["meta"];
        foreach($meta_filters as $meta_filter) {
    
            if(!isset($meta_filter["key"])) {
                continue;
            }
    
            $key = $meta_filter["key"];
            $template = $meta_filter["template"];
    
            //if a custom label has been defined get that, otherwise fall back to met key as label.
            $label_singular = isset($meta_filter["label_singular"]) ? $meta_filter["label_singular"] : $key;
            $label_plural = isset($meta_filter["label_plural"]) ? $meta_filter["label_plural"] : $key;
    
            // Loop through values to create 
            $values = $meta_filter["values"];
            $options = [];
            
            // Collect options to standartized array for futher pushing to the filter item
            foreach($values as $meta_value => $meta_title) {
                $options[$key][$meta_value] = $meta_title;
            }

            $compare = $meta_filter["compare"];
    
            //add it to our array of filters
            $filters[] = [
                "class"         => false,
                "type"          => "meta",
                "options_by_slug" => $options,
                "compare"       => $compare,
                "label" 	    => $label_singular,
                "all_label"		=> "All {$label_plural}",
                "filter_uid"   	=> $key . "_" . uniqid(),
                "template"      => $template,
                "active_filters" => $active_filters
            ];
        }
    }

    // STEP 3. Sorting filters
    if(isset($config["orderby"])) {

        $sorting_filters = $config["orderby"]["values"];
        $template = $config["orderby"]["template"];
        $label = isset($config["orderby"]["label"]) ? $config["orderby"]["label"] : "";
        $sort_options = [];

        foreach($sorting_filters as $sorting_filter) {

            if(!isset($sorting_filter["type"])) {
                continue;
            }
        
            $key = $sorting_filter["type"];

            if( $key == "meta_value" ) {
                $meta_key = "&meta_key=" . $sorting_filter["meta_key"];
            } else {
                $meta_key = "";
            }
        
            //if a custom label has been defined get that, otherwise set to false
            $desc_label = isset($sorting_filter["desc_label"]) ? $sorting_filter["desc_label"] : false;
            $asc_label = isset($sorting_filter["asc_label"]) ? $sorting_filter["asc_label"] : false;
            
            if($desc_label)
                $sort_options["orderby"]["orderby={$key}&order=desc{$meta_key}"] = $desc_label;
        
            if($asc_label)
                $sort_options["orderby"]["orderby={$key}&order=asc{$meta_key}"] = $asc_label;

        }

        if(isset($active_filters["orderby"]) && isset($active_filters["order"]))
            $active_filters["orderby"] = "orderby={$active_filters["orderby"]}&order={$active_filters["order"]}";
            

        // create same array of args as other filters but separately to put it into other part of the page
        $sorting_filter = [
            "class"         => "filter-sorting",
            "type"          => "orderby",
            "options_by_slug" => $sort_options,
            "label" 	    => $label,
            "filter_uid"   	=> "orderby_" . uniqid(),
            "template"      => $template,
            "active_filters" => $active_filters
        ];

    }

?>

<form id="ajax-filter" class="flex flex-col gap-4 lg:gap-8 shrink-0 justify-center" onsubmit="process_form_submit(event, 'ajax-filterable');">
    <?php

        // Load sort by
        if($sorting_filter)
            get_template_part("template-parts/filter/filters", "group", $sorting_filter);

        // All filters
        foreach($filters as $filter)
            get_template_part("template-parts/filter/filters", "group", $filter);

    ?>
</form>