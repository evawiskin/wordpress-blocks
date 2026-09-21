<?php

    if(!isset($args)) {
        $args = [];
    }

    $args = array_merge(
        [
            "name" => "for your enquiry",
			"confirmation" => "",
			"show_send_another_button" => true
        ],
        $args
    );

	$name = $args["name"];
	$confirmation = $args["confirmation"];
	$show_send_another_button = $args["show_send_another_button"];
	$confirmation = wp_strip_all_tags($confirmation);

	if (empty($confirmation)) {
		$confirmation = "Our team will be in touch in 3 working days.";
	}

	$phone = get_field("option_company_info_sales_number", "option");

	if ($arrow_right_icon = get_svg_icon("arrow-right")) {
		$arrow_right = "<span class=\"block w-4 h-4\">{$arrow_right_icon}</span>";
	}

?>
<div id="contact_form_confirmation" class="prose">

	<?php if($check_icon = get_svg_icon("check")): ?>

		<div class="h-12 w-12 mb-6 text-electric-green-500">
			<span class="w-6 h-6"><?php echo($check_icon); ?></span>
		</div>

	<?php endif; ?>
	
		<h3 class="theme-heading-tiny">Thanks<?php echo(" {$name}");?>,</h3>

		<p class="font-semibold">
			<?php echo($confirmation);?>
		</p>

		<p class="font-semibold ">
			Need to talk sooner?
			<a class="hover:!text-electric-green-500 duration-300" href="tel:<?php echo(str_replace(" ", "", $phone)); ?>">Give us a call</a>
		</p>

	<?php
		if ($show_send_another_button) {
			$button_content = [
				"button_text"	=> "Send another"
			];

			get_template_part(
				"template-parts/partials/partial",
				"button",
				[
					"button_content"	=> $button_content,
					"button_onclick"	=> "window.location.reload()",
					"button_classes"	=> "hy-button-secondary",
					"button_id"			=>	"show-contact-form",
					"icon_right"		=> "arrow-right",
				]
			);
		}
	?>	
</div>