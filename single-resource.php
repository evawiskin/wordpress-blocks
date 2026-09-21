<?php
/**
 * Template for displaying single resource posts.
 */

get_header(args: [
    "header_partial" => "template-parts/global/header/template-part-gated-landing-page-header",
]);

while (have_posts()) {
    the_post();
    the_content();
}

get_footer(args: [
    "footer_partial"         => "template-parts/global/footer/template-part-gated-landing-page-footer",
    "include_cookie_consent" => true,
    "include_carbon_checker" => true,
]);
