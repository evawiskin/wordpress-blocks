<?php
	// Specify Args
	$args = array_merge(
		[
			"post_id" 		 => false,
			"allow_click" 	 => true,
			"flex_direction" => "flex-col", // Will become lg:flex-col lg:flex-row lg:flex-col-reverse lg:flex-row-reverse
			"name"			 => "",
			"job_title"		 => "",
			"profile_image"  => ""
		], 
		$args ?? []
	);

	// Ensure we've got at least the post_id
	$post_id = $args["post_id"];
	if(!$post_id) {
		if(is_admin())
			echo("Please specify a post_id for this card.");
		return;
	}

	// Grab our fields
	$name = get_field("cpt_person_name", $post_id) ?: false;
	$job_title = get_field("cpt_person_job_title", $post_id) ?: false;
	$profile_image = get_field("cpt_person_profile_image", $post_id) ?: false;
	$fun_profile_image = get_field("cpt_person_fun_profile_image", $post_id) ?: false;
	$display_modal = get_field("cpt_person_display_modal", $post_id) ?: false;
	$linkedin_url = get_field("cpt_person_linkedin_url", $post_id) ?: false;


	if($args["flex_direction"] == "flex-row"):
		$name_size = "text-xl";
		$job_title_size = "text-base";
	else:
		$name_size = "text-sm";
		$job_title_size = "text-xs";
	endif;

	$person_likes_arr = [];
	$person_dislikes_arr = [];
	
	while(have_rows("cpt_person_likes_repeater", $post_id)):
		the_row();
		$person_likes_arr[] = get_sub_field("cpt_person_like", $post_id);
	endwhile;

	while(have_rows("cpt_person_dislikes_repeater", $post_id)):
		the_row();
		$person_dislikes_arr[] = get_sub_field("cpt_person_dislike", $post_id);
	endwhile;

	if($post_id === "join_the_team") {
		$name = $args["name"];
		$job_title = $args["job_title"];
		$profile_image = $args["profile_image"];
	}

	// Manage post terms
	$post_terms_team = get_the_terms($post_id, "team");
	if(!is_array($post_terms_team))
		$post_terms_team = [];

	// Manage image classes
	$bottom_image_classes = "
		absolute inset-0
		w-full h-full block-editor:w-full block-editor:h-full
		object-cover object-center scale-[1.01] transform
	";
	$top_image_classes = $bottom_image_classes . (
		($fun_profile_image)
			? "transition-opacity duration-300 group-hover:opacity-0" 
			: "transition-transform duration-300 transform group-hover:scale-105"
	);
?>

<div class="flex justify-center <?php if (isset($args["wrapper_class"])) echo($args["wrapper_class"]) ?>">

	<!--- Actual Group --->
	<?php if($display_modal && $args["allow_click"]): ?>
		<button 
			class="
				group flex flex-1 rounded-lg overflow-hidden items-stretch justify-normal text-left 
				<?php echo($args["flex_direction"]); ?> person-has-modal
			"
			onclick="triggerModal('#profile-modal')"
			data-name="<?php echo(htmlspecialchars($name, ENT_QUOTES, 'UTF-8')); ?>"
			data-job="<?php echo(htmlspecialchars($job_title, ENT_QUOTES, 'UTF-8')); ?>"
			data-image="<?php echo(wp_get_attachment_image_url($profile_image, "large")); ?>"
			data-image-alt="<?php echo(htmlspecialchars(get_post_meta($profile_image, '_wp_attachment_image_alt', true) ?: $name, ENT_QUOTES, 'UTF-8')); ?>"
			data-image-fun="<?php echo(wp_get_attachment_image_url($fun_profile_image, "large")); ?>"
			data-image-fun-alt="<?php echo(htmlspecialchars(get_post_meta($fun_profile_image, '_wp_attachment_image_alt', true) ?: $name, ENT_QUOTES, 'UTF-8')); ?>"
			data-description="<?php echo(htmlspecialchars(get_field("cpt_person_bio", $post_id), ENT_QUOTES, 'UTF-8')); ?>"
			data-linked-in="<?php echo($linkedin_url); ?>"
			data-likes="
			<?php 
					echo(htmlspecialchars(json_encode([
						"likes" 		=> $person_likes_arr,
						"dislikes"		=> $person_dislikes_arr
					]), ENT_QUOTES, 'UTF-8')
					);
				?>
			"
		>
	<?php elseif($args["allow_click"] && !$display_modal): ?>
		<a
			href="<?php echo(get_permalink($post_id)); ?>"
			class="
				hy-card group flex flex-1 rounded-lg overflow-hidden
				<?php echo($args["flex_direction"]); ?>
			"
		>
	<?php else: ?>
		<div 
			class="
				group flex flex-1 rounded-lg overflow-hidden
				<?php echo($args["flex_direction"]); ?>
			"
		>
	<?php endif; ?>

		<!--- Left / Top Column --->
	<?php 
		if($args["flex_direction"] == "flex-row")
			echo("<div class=\"w-32 sm:w-44 rounded-lg bg-gretter-50\">");
	?>
			<div 
				class="
					flex-none relative overflow-hidden
					aspect-w-1 aspect-h-1
				"
			>
				<?php 
					// First let's get that "fun" alternative image
					if($fun_profile_image)
						echo(
							wp_get_attachment_image($fun_profile_image, "large", false, ["class" => $bottom_image_classes])
						);

					// Next let's get that standard image
					if($profile_image)
						echo(wp_get_attachment_image($profile_image, "large", false, ["class" => $top_image_classes]));
					else
						echo(the_post_thumbnail_fallback("large", ["class" => $top_image_classes]));
				?>
			</div>
	<?php 
		if($args["flex_direction"] == "flex-row")
			echo("</div>");
	?>

		<!--- Right / Bottom Column --->
		<div 
			class="
				flex-1 h-auto p-4 w-full
				flex flex-col
				text-forest-green-500 bg-gretter-50 
				<?php if($args["flex_direction"] == "flex-row") echo("justify-center"); ?>
			"
		>
			<h2 class="font-extrabold mb-4 <?php echo($name_size); ?>">
				<?php echo($name); ?>
			</h2>
			<div class="flex justify-between items-center flex-wrap">
				<p class="font-semibold mr-1 flex-1 <?php echo($job_title_size); ?>">
					<?php echo($job_title); ?>
				</p>
				<?php if($arrow_icon = get_svg_icon("arrow-right")): ?>
					<span class="w-6 h-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex-shrink-0">
						<?php echo($arrow_icon); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>

	<?php if($display_modal && $args["allow_click"]): ?>
	</a>
	<?php elseif($args["allow_click"]): ?>
		</a>
	<?php else: ?>
		</div>
	<?php endif; ?>
</div>