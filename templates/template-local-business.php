<?php
	// Template Name: Local Business

    // Get the selected location from the dropdown
    $selected_location_index = get_field("location_schema_location"); 
    $locations = get_field("option_company_info_schema", "options"); 
    $selected_location = isset($locations[$selected_location_index]) ? $locations[$selected_location_index] : []; 

    // Custom JSON-LD Schema for Local Business
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "url" => site_url(),
        "name" => $selected_location["option_company_info_schema_name"] ?? get_the_title(), 
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $selected_location["option_company_info_schema_address_street"] ?? "", 
            "addressLocality" => $selected_location["option_company_info_schema_address_locality"] ?? "", 
            "addressRegion" => $selected_location["option_company_info_schema_address_region"] ?? "", 
            "postalCode" => $selected_location["option_company_info_schema_address_postal"] ?? "",
            "addressCountry" => $selected_location["option_company_info_schema_address_country"] ?? "United Kingdom"
        ],
        "telephone" => $selected_location["option_company_info_schema_telephone"] ?? "",
        "email" => $selected_location["option_company_info_schema_email"] ?? "", 
        "openingHours" => $selected_location["option_company_info_schema_opening_hours"] ?? "", // Added opening hours
        "image" => $selected_location["option_company_info_schema_image"] ?? "", // Added image
        "description" => $selected_location["option_company_info_schema_description"] ?? "", // Added description
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => $selected_location["option_company_info_schema_rating_value"] ?? "0", // Added rating value
            "ratingCount" => $selected_location["option_company_info_schema_rating_count"] ?? "0" // Added rating count
        ],
    ];

    // Get the header
    get_header(); 

    // Output the schema
    echo('<script type="application/ld+json">' . json_encode($schema) . '</script>');

    // Start Post Loop
    while (have_posts()) { 
        the_post();
        the_content();
    }
    
    get_footer(); 
?>