<?php
	if (!isset($args))
		$args = [];

	// Set default args
	$args = array_merge([
		"button_type" => "link",
		"button_content" => [],
		"button_classes" => "hy-button-primary",
		"button_id"		=> "",
		"icon_left" => false,
		"icon_right" => false,
	], $args);

	$args["button_content"] = array_merge(
		[
			"button_text" 	=> "Insert Link",
			"button_target" => "_self",
			"button_link"  	=> "#"
		],
		$args["button_content"]
	);

	$button_type = $args["button_type"];
	$button_link = $args["button_content"]["button_link"];
	$button_target = $args["button_content"]["button_target"];
	$button_text = $args["button_content"]["button_text"];
	$button_class = $args["button_classes"];
	$icon_left = get_svg_icon($args["icon_left"]);
	$icon_right = get_svg_icon($args["icon_right"]);
	$post_id = get_the_ID();
	$icon_size = str_contains($button_class, "hy-button-link-sm") ? "size-2.5 md:size-4" : "size-5";

	// If button type is modal, open the schedule call modal
	if($button_type == "schedule_modal") {
		$args["button_onclick"] = "triggerModal('#schedule-call-modal', true, {$post_id}, 'schedule-call')";
		is_admin() ?: get_template_part("template-parts/modals/template-part", "modal", ["modal_type" => "schedule-call-modal", "modal_id" => "schedule-call-modal"]);
	}

	if(isset($args["button_onclick"])):
?>
	<button
		onclick="<?php echo esc_attr($args["button_onclick"]); ?>"
	
<?php else: ?>
	<a
		href="<?php echo esc_url($button_link); ?>"
<?php endif ?>

	<?php 
		if($args["button_id"] != "")
			echo("id=\"{$args["button_id"]}\"");
	?>

	<?php if ($button_target == "_blank") : ?>
		target="_blank" rel="noopener noreferrer"
	<?php endif; ?>
	class="<?php echo esc_attr($button_class); ?>"
>
	<!-- Icon Left -->
	<?php if ($icon_left) : ?>
		<span class="block my-auto <?php echo($icon_size); ?>"><?php echo($icon_left) ?></span>
	<?php endif; ?>

	<!-- Button Text -->
	<span><?php echo esc_html($button_text); ?></span>

	<!-- Icon Right -->
	<?php if ($icon_right) : ?>
		<span class="block my-auto <?php echo($icon_size); ?>"><?php echo($icon_right) ?></span>
	<?php endif; ?>

<?php 
	if(isset($args["button_onclick"]))
		echo("</button>");
	else
		echo("</a>");
?>
