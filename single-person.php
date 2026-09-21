<?php 
	get_header(); 
	get_template_part(
		"template-parts/partials/partial", 
		"person-banner",
		[
			"post_id" 				=> get_the_ID(),
			"post_type"				=> get_post_type(get_the_ID()),
			"post_type_name" 		=> "About" 
		]
	);

	// Start Post Loop
	while (have_posts()) { the_post();
		the_content();
	}
get_footer(); 

?>