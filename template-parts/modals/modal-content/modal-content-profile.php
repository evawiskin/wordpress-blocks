<?php 
	if (!isset($args["post_id"]))
		return;

	$post_id = $args["post_id"];
	$teams = get_the_terms($post_id, "team");
	$is_leadership_role = false;

	foreach ($teams as $team) {
		if (get_field("taxonomy_team_is_leadership_role", "term_{$team->term_id}")) {
			$is_leadership_role = true;
			break;
		}
	}
?>
<div class="max-w-5xl px-10 pt-32 pb-24 mx-auto inside-container w-full h-full justify-between items-center gap-24 md:flex">
	<div class="mb-14 md:w-1/2 md:mb-0 md:-rotate-[5deg]">
		<?php 
			get_template_part(
				"template-parts/cards/template-part",
				"person-card",
				[
					"post_id" 		 => $post_id,
					"width_classes"	 => "w-96",
					"height_classes" => "h-60 md:h-96",
					"allow_click" => $is_leadership_role
				]
			)
		?>
	</div>
	<div class="max-h-[80vh] pb-24 md:pb-0 overflow-auto md:w-1/2">
		<?php 
			if ($bio = get_field("cpt_person_bio", $post_id)) : 
		?>
			<p class="mb-11"><?php echo($bio) ?></p>
		<?php 
			endif;
			
			if ($is_leadership_role) {
				get_template_part(
					"template-parts/partials/partial",
					"button",
					[
						"button_content" => [
							"button_link" => get_the_permalink($post_id),
							"button_text" => "Read more"
						],
						"button_classes" => "hy-button-secondary has-chevron-right text-white mb-14 hover:text-black"
					]
				);
			}

			// Likes
			if (have_rows("cpt_person_likes_repeater", $post_id)) :
		?>
				<ul>
		<?php 
				while (have_rows("cpt_person_likes_repeater", $post_id)) : the_row();
					if ($like = get_sub_field("cpt_person_like")) :
		?>
					<li class="flex items-center gap-3.5 text-white mb-7">
						<?php if ($heart_icon = get_svg_icon("heart")) : ?>
							<span class="block shrink-0 w-7 h-7 text-electric-green-500"><?php echo($heart_icon); ?></span>
							<span><?php echo($like) ?></span>
						<?php endif; ?>
					</li>
		<?php
					endif;
				endwhile; 
		?>
				</ul>
		<?php
				endif;

			// Dislikes
			if (have_rows("cpt_person_dislikes_repeater", $post_id)) :
		?>
				<ul class="mb-10">
		<?php 
				while (have_rows("cpt_person_dislikes_repeater", $post_id)) : the_row();
					if ($dislike = get_sub_field("cpt_person_dislike")) :
		?>
					<li class="flex items-center gap-3.5 text-white mb-7">
						<?php if ($heart_cross_icon = get_svg_icon("heart-cross")) : ?>
							<span class="block shrink-0 w-7 h-7 text-electric-green-500"><?php echo($heart_cross_icon); ?></span>	
							<span><?php echo($dislike) ?></span>
						<?php endif; ?>
					</li>
		<?php
					endif;
				endwhile; 
		?>
				</ul>
		<?php
			endif;

			// Linkedin button
			if ($linkedin_link = get_field("cpt_person_linkedin_url", $post_id)) :
		?>
				<a 
					href="<?php echo($linkedin_link) ?>" 
					target="_blank" 
					class="hy-button-outline flex gap-2.5 items-center"
					aria-label="Go to <?php echo(get_the_title($post_id)) ?>'s Linkedin profile"
				>
					<?php if ($linkedin_icon = get_svg_icon("linkedin-square", "feather-icons-social")) : ?>
						<span class="w-5 h-5 block"><?php echo($linkedin_icon) ?></span>
					<?php endif; ?>
					<span class="-mb-1">Go to profile</span>
					<?php if ($new_tab_icon = get_svg_icon("external-link")) : ?>
						<span class="w-5 h-5 block"><?php echo($new_tab_icon) ?></span>
					<?php endif; ?>
				</a>
		<?php
			endif;
		?>
	</div>
</div>