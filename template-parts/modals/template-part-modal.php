<?php

	//Pulls in script 
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	if($modals_script_uri = get_theme_file_uri("assets/dist/js/modals.js"))
		wp_enqueue_script("custom-modals", $modals_script_uri, [], $build_version, false);

	/* $args are required! */
	if (!$args)
		return;

	/* 
		The type of the modal can be passed in, this allows for using the same modal
		wrapper/partial, but then in a switch statement you can render different modal content.
		The modal can also be made persistent which prevents the overlay from dismissing the modal.
		If you use the modal this way you will need to define a .modal-dismiss element somewhere else
		or the modal will be uncloseable!!
	*/
	$defaults = [
		"modal_type" => null,
		"modal_id" => null,
		"modal_persistent" => false,
		"modal_effect" => "fade"
	];

	$args = wp_parse_args($args, $defaults);

	/* modal id is of course required */
	if (!$args["modal_id"])
		return;

	/* obtain modal arguments */
	$modal_content = $args["modal_type"];
	$modal_id = $args["modal_id"];
	$modal_persistent = $args["modal_persistent"];
	$modal_effect = $args["modal_effect"];

	/*
		this here just allows you to set $dev to true, which means the modal loads open
		helpful for when you are working on the content of a modal so you don't have to reopen
		it on page load! 
	*/
	$dev = false;
	if ($dev)
		$hidden_classes = "";
	else
		$hidden_classes = "duration-300 opacity-0 invisible";


	switch($modal_effect) {
		case "fade":
			$hidden_classes = "duration-150 opacity-0 invisible";
			break;
		case "slide-right":
			$hidden_classes = "duration-300 translate-x-full";
			break;
		case "slide-left":
			$hidden_classes = "duration-300 -translate-x-full";
			break;
	}

		

?>


<div id="<?php echo($modal_id); ?>"
	data-effect="<?php echo($modal_effect); ?>"
	class="modal fixed inset-0 flex items-center justify-center w-full h-full overflow-hidden transition-all backdrop-blur-md bg-black/60 z-60 <?php echo("{$hidden_classes}"); ?>"
	role="dialog" aria-modal="true"
	tabindex="-1"
>	

	<?php if($modal_persistent == false): // persistent modals won't close when clicking outside the modal ?>
		<!-- hidden div which initiates modal closure -->
		<div class="absolute inset-0 z-10 w-full h-full modal-dismiss" aria-hidden="true"></div>
	<?php endif; ?>
		
	<?php
		switch ($modal_content) {
			/* 
				Add your different modals here 
			*/
			case "contact-modal":
				get_template_part("template-parts/modals/template-part-modal", "contact");
				break;

			case "instagram-modal":
				get_template_part("template-parts/modals/template-part-modal", "instagram");
				break;

			case "profile-modal":
					get_template_part("template-parts/modals/template-part-modal", "profile");
					break;

			case "schedule-call-modal":
				get_template_part("template-parts/modals/template-part-modal", "schedule-call");
				break;
				
			default:
				get_template_part("template-parts/modals/template-part-modal", "generic");
				break;
		}
	?>
</div>
