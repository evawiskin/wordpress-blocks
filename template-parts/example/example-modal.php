<?php

/*
	Example Modal Use.
	Simply pull this template part into your template file (eg front-page.php) to undertstand how this functionality can be used.
*/

//Pulls in script 
$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

if($modals_script_uri = get_theme_file_uri("assets/dist/js/modals.js"))
	wp_enqueue_script("custom-modals", $modals_script_uri, [], $build_version, false);

/*
	Each modal on a page should have a unique ID, and a corresponding get_template_part(),
	which references the same modal ID. In this case #example-modal-1 and #example-modal-2.
*/
?>

<div class="container outside-container">
	<button onclick="triggerModal('#example-modal-1')">
		trigger example modal 1
	</button>

	<button onclick="triggerModal('#example-modal-2')">
		trigger example modal 2
	</button>
</div>

<?php
	// As you can see these are differentiated between by the ID in the 'modal_id' param.
	// It is also possible to determine what 'type' of modal it is. Say you wanted to have a modal for a
	// notification and another for a contact form, you can create the different kinds of modal. In the 
	// get template part there is a switch statement which pulls in the right modal content based on the type.
	// You can also determine 'modal_persistent' which prevents clicking the overlay behind dismissing the modal (it will need a button to close it).
	
	get_template_part("template-parts/modals/template-part", "modal", ["modal_type" => "generic-modal", "modal_id" => "example-modal-1"]); 
	get_template_part("template-parts/modals/template-part", "modal", ["modal_type" => "contact-modal", "modal_id" => "example-modal-2", "modal_persistent" => true]); 
