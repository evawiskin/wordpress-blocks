<?php
/**
* 	The template for displaying all pages
*	
* 	This is the template that displays all pages by default.
* 	Please note that this is the WordPress construct of pages
* 	and that other "pages" on your WordPress site may use a
* 	different template.
*	
* 	@link https://developer.wordpress.org/themes/basics/template-hierarchy/
*	
* 	@package WordPress
* 	@subpackage Hi Yield Wordpress Starter
* 	@since 1.0
*/

if ( post_password_required() ) {
	http_response_code(401);
}

get_header(); 

// Start Post Loop
while (have_posts()) { the_post();
	// Handle password protected page(s)
	if ( post_password_required() ) {
		get_template_part("template-parts/utility/utility", "password-protected-form");
	} else {
		the_content();
	}
}

get_footer(); 
