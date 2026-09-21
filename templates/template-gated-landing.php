<?php
/**
 * Template Name: Gated Landing
 */

$header_args = [
    "header_partial" => "template-parts/global/header/template-part-gated-landing-page-header",
];

get_header(args: $header_args);

while (have_posts()) {
    the_post();
    the_content();
}

$footer_args = [
    "footer_partial"         => "template-parts/global/footer/template-part-gated-landing-page-footer",
    "include_cookie_consent" => true, // Can be false
    "include_carbon_checker" => true, // Can be false
];

get_footer(args: $footer_args);
