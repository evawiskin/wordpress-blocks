<?php
	$default_args = [
		"author_id" => 1,
		"post_id" => false,
		"show_avatar" => true,
		"link_avatar" => false,
		"show_date" => true,
		"show_name" => true, 
		"show_reading_duration" => true,
		"text_color" => false
	];

	//Parse incoming $args into an array and merge it with $default_args
	$args = array_merge($default_args, $args);

	//Obtain the author ID from the $args array
	$author_id = $args["author_id"];
	$user = get_user_by("id", $author_id);

	//If the author WP User Object is invalid, return
	if(!$user)
		return;

	//Ascertains whether the user has a linked person CPT post
	$linked_person = get_field("user_linked_person", "user_{$author_id}");
	$user_has_linked_person = $linked_person ?: false;

	// User has to have a linked person in order to show single
	$show_single = false;

	//Sets variables accordingly
	if($user_has_linked_person){
		$user_name = get_field("cpt_person_name", $linked_person->ID);
		$user_avatar = get_field("cpt_person_profile_image", $linked_person->ID);
		$user_display_modal = get_field("cpt_person_display_modal", $linked_person->ID);

		// Show single only if the person is set to not display modal
		$show_single = !$user_display_modal;

	} else {
		$user_name = $user->display_name;
	}

	//Post ID - Falls back to ID of the current post so will just work in the loop
	$post_id = $args["post_id"] ? $args["post_id"] : get_the_ID();

	//Text Color
	$text_color = $args["text_color"] ? $args["text_color"] : "text-white";

	global $post;

	$author_id = get_post_field("post_author", $post->ID);
	$author_name = get_the_author_meta('display_name', $author_id);

	$person_archive_link = isset($linked_person->ID) ? get_permalink($linked_person->ID) : false;
	$link_avatar = $args["link_avatar"] && $person_archive_link;

?>

<div class="text-xs flex flex-wrap items-center font-semibold <?php echo($text_color); ?>">
	<?php if($args["show_avatar"]): ?>
		<div class="mr-4">
			<?php 
				// Display avatar with or without link, depending on user settings
				if ($link_avatar && $show_single) : ?>
				<a href="<?php echo(esc_url($person_archive_link)); ?>" aria-label="View <?php echo(esc_attr($author_name)); ?>'s posts">
					<?php 
						if($user_has_linked_person && $user_avatar):
							echo(wp_get_attachment_image($user_avatar, "medium", false, ["class" => "rounded-full w-10 h-10"]));
						else:
							echo(get_avatar($user->ID, 40, args:["class" => "rounded-full w-10 h-10"]));
						endif;
					?>
				</a>
				<?php else : 
					if($user_has_linked_person && $user_avatar):
						echo(wp_get_attachment_image($user_avatar, "medium", false, ["class" => "rounded-full w-10 h-10"]));
					else:
						echo(get_avatar($user->ID, args:["class" => "rounded-full w-10 h-10"]));
					endif;
			endif; ?>
		</div>
	<?php endif; ?>
	<div class="flex flex-wrap flex-1 gap-y-1 items-center sm:gap-y-0">
		<?php if($args["show_name"]): ?>
			<div class="mr-2 flex flex-wrap items-center">
				<?php 
					// Display name with or without link, depending on user settings
					if ($link_avatar && $show_single) : ?>
                    <a href="<?php echo(esc_url($person_archive_link)); ?>" class="pr-2" rel="author">
                        <?php echo(esc_html($author_name)); ?>
                    </a>
                <?php else : ?>
                    <span class="pr-2" rel="author">
                        <?php echo(esc_html($author_name)); ?>
                    </span>
                <?php endif; ?>
				<span>
					<svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation">
						<circle cx="2" cy="2" r="2" fill="currentColor"/>
					</svg>
				</span>
			</div>
		<?php endif; ?>

		<?php if($args["show_date"]): ?>
			<div class="mr-2 flex flex-wrap items-center">
				<time class="pr-2" datetime="<?php echo(get_the_date("c")); ?>"><?php echo(get_the_date("j F Y", $post_id)); ?></time>
				<span>
					<svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation">
						<circle cx="2" cy="2" r="2" fill="currentColor"/>
					</svg>
				</span>
			</div>
		<?php endif; ?>

		<?php if($args["show_reading_duration"]): ?>
			<div class="mr-2 flex flex-wrap items-center">
				<span class="pr-2" aria-label="Estimated post reading time">
					<?php echo(calculate_reading_duration($post_id)); ?> min read
				</span>
			</div>
		<?php endif; ?>
	</div>
</div>