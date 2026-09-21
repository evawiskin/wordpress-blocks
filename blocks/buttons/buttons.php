<?php
	if ((!isset($block)) || !is_array($block))
		return;

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/buttons-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;

	// Base classes
	$block_classes = new BlockClasses($block, "flex gap-4");

	// Add placeholder in editor
	if (!have_rows("block_buttons_button_repeater_key") && is_admin()) :
?>
	<div class="container inside-container-xl bg-forest-green-700 text-gray-200 text-center">
		<p>Add a button to get started</p>
	</div>
<?php
		return;
	elseif (!have_rows("block_buttons_button_repeater_key")) :
		return;
	endif;

	// Set button layout class
	if (get_field("block_buttons_buttons_layout")) {
		$button_layout_class = "flex-col";
	} else {
		$button_layout_class = "flex-wrap";
	}
?>

<div class="<?php echo($block_classes . " " . $button_layout_class); ?>">
	<?php
		while (have_rows("block_buttons_button_repeater_key")) { the_row();
			$button_type = get_sub_field("block_buttons_button_type") ?: "link";

			if (($button_type == "link" && $button_link = get_sub_field("block_buttons_button_link")) && is_array($button_link)):
				// Force keys
				$button_link = array_merge([
					"url" => "#",
					"target" => "_self",
					"title" => "Click here"
				], $button_link);

				// Set button args
				$button_args = [
					"button_content" => [
						"button_link" => $button_link["url"],
						"button_target" => $button_link["target"],
						"button_text" => $button_link["title"]
					],
					"button_classes" => get_sub_field("block_buttons_button_style") ?: "hy-button-primary"
				];
			endif;

			if ($button_type == "schedule_modal"):
				// Enqueue JS
				$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

				if($modals_script_uri = get_theme_file_uri("assets/dist/js/modals.js"))
					wp_enqueue_script("custom-modals", $modals_script_uri, [], $build_version, false);

				if($contact_form_script_uri = get_theme_file_uri("assets/dist/js/main-contact-form.js"))
					wp_enqueue_script("main-contact-form", $contact_form_script_uri, [], $build_version, false);

				// Set button args
				$button_args = [
					"button_type" => "schedule_modal",
					"button_content" => [
						"button_text" => get_sub_field("block_buttons_button_text") ?: "Insert Link"
					],
					"button_classes" => get_sub_field("block_buttons_button_style") ?: "hy-button-primary",
				];
			endif;

			if ($button_type == "hubspot_chat"):
				// Set button args
				$button_args = [
					"button_type" => "hubspot_chat",
					"button_content" => [
						"button_text" => get_sub_field("block_buttons_button_text") ?: "Chat with us"
					],
					"button_classes" => get_sub_field("block_buttons_button_style") ?: "hy-button-primary",
					"button_onclick" => "if(window.HubSpotConversations && window.HubSpotConversations.widget){window.HubSpotConversations.widget.open();}else{console.warn('HubSpot chat not loaded yet');}"
				];
			endif;

			// Set left icon
			if ($icon_left = get_sub_field("block_buttons_button_icon_left"))
				$button_args["icon_left"] = str_replace(".svg", "", $icon_left);

			// Set right icon
			if ($icon_right = get_sub_field("block_buttons_button_icon_right"))
				$button_args["icon_right"] = str_replace(".svg", "", $icon_right);

			//Get template
			get_template_part("template-parts/partials/partial", "button", $button_args);
		}
	?>
</div>