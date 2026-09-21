<?php
	if (!isset($args))
		$args = [];

	// Set default template args
	$args = array_merge([
		"post_id" => ""
	], $args);
	
	$post_id = $args["post_id"];

	//Assign the random or specific person...
	$person_override_id = get_field("post_cta_person_override", $post_id) ?: false;

	// If there's a person override, use that person's asterisk image - if they have one.
	if($person_override_id && $person_asterisk_image = get_field("cpt_person_asterisk_image", $person_override_id)){
		$asterisk_image_id = $person_asterisk_image;
	} else {

		$randomised_person = get_randomised_person();

		$asterisk_image_id = get_field("cpt_person_asterisk_image", $randomised_person);		
	}

	$post_cta_title = get_field("option_blog_settings_post_cta_title", "option") ?: "Let’s get started!";
	$post_cta_text = get_field("option_blog_settings_post_cta_text", "option") ?: "From startups to global enterprises, we love filler text and offering free consultations to find out what’s best for you.";
	$post_cta_link = get_field("option_blog_settings_post_cta_link", "option");
?>


<section class="relative pt-10 pb-[4.375rem] lg:pt-[5.625rem] lg:pb-[3.125rem] bg-forest-green-500">
	<div class="container grid lg:grid-cols-12 gap-4">
		<div class="w-36 h-36 lg:h-auto lg:w-auto lg:col-span-4">
			<?php
				echo(wp_get_attachment_image($asterisk_image_id, "full", false, ["class" => "w-full h-full object-contain"]));
			?>
		</div>
		<div class="lg:col-span-6 lg:col-start-6 flex items-center">
			<div class="prose max-w-[37.5rem] text-white">

				<?php
					if($post_cta_title)
						echo("<h3 class=\"theme-heading-big\">{$post_cta_title}</h3>");

					if($post_cta_text)
						echo("<p>{$post_cta_text}</p>");

					if(!$post_cta_link)
						return;

					$button_content = [
						"button_text"	=> $post_cta_link["title"] ?: "Let's chat",
						"button_link"	=> $post_cta_link["url"],
						"button_target"	=> $post_cta_link["target"]
					];

					get_template_part(
						"template-parts/partials/partial",
						"button",
						[
							"button_content"	=> $button_content,
							"button_classes"	=> "hy-button-primary",
							"icon_right"		=> "arrow-right"
						]
					);
				?>

			</div>
		</div>
	</div>
</section>
