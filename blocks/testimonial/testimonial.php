<?php 
	if ((!isset($block)) || !is_array($block))
		return;

	// Testimonial content
	$testimonial_id = get_field("block_testimonial_testimonial_select") ?: 0;
	$testimonial_author = get_field("cpt_testimonial_attestant_name", $testimonial_id);
	$testimonial_role = get_field("cpt_testimonial_attestant_role", $testimonial_id);
	$testimonial_company = get_field("cpt_testimonial_attestant_location", $testimonial_id);
	$testimonial_content = get_field("cpt_testimonial_content", $testimonial_id);
	$testimonial_thumbnail_id = get_post_thumbnail_id($testimonial_id);
	$testimonial_author_color = get_field("block_testimonial_author_text_color") ?: "forest-green-500";

	$show_border = get_field("block_testimonial_show_border");
	$show_avatar = get_field("block_testimonial_show_avatar");

	$author_data = [];

	if($testimonial_author) {
		$author_data[] = "<span rel='author'>{$testimonial_author}</span>";
	}

	if($testimonial_role) {
		$author_data[] = $testimonial_role;
	}

	if($testimonial_company) {
		$author_data[] = $testimonial_company;
	}

	if($show_avatar) {
		$author_data = implode("<br>", $author_data);
	} else {
		$author_data = "— " . implode(", ", $author_data);
	}

	// Colors
	$block_bg = array_key_exists("backgroundColor", $block) ? "bg-{$block["backgroundColor"]}" : "bg-transparent";
	$block_text = array_key_exists("textColor", $block) ? "text-{$block["textColor"]}" : "text-black";

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/inline-testimonial-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;

	// Output placeholder in editor
	if (!$testimonial_id && is_admin()) :
?>
		<div class="bg-forest-green-700 text-center py-24">
			<p>Select a testimonial to get started</p>
		</div>
<?php
		return;
	elseif (!$testimonial_id) :
		return;
	elseif (!$testimonial_author && !$testimonial_company && !$testimonial_content && is_admin()) :
?>
		<div class="bg-forest-green-700 text-center py-24">
			<p>This testimonial is missing content. Please add more content to this testimonial to use it.</p>
		</div>
<?php
		return;
	endif;
?>
<section class="<?php if (get_field("block_testimonial_spacing")) : ?> inside-container-md <?php endif; echo($block_bg) ?>">
	<div class="container">

		<div class="<?php if($show_border) echo("px-5 border-l-8 border-flamingo-pink-500"); ?>">

			<!-- Quote -->
			<?php if ($testimonial_content) : ?>
				<blockquote class="font-athletics theme-heading-tiny mb-8 <?php echo($block_text) ?>"><?php echo($testimonial_content) ?></blockquote>
			<?php endif; ?>


			<div class="flex flex-row gap-6 items-center">

				<?php if($show_avatar && $testimonial_thumbnail_id): ?>

					<div class="w-20 h-20 aspect-square shrink-0">
						<?php echo(wp_get_attachment_image($testimonial_thumbnail_id, "large", false, ["class" => "w-full h-full object-contain"])); ?>
					</div>

				<?php endif; ?>
				
				<!-- Author -->
				<p class="font-semibold text-<?php echo($testimonial_author_color); ?>">
					<?php echo($author_data); ?>
				</p>

			</div>

		</div>	
	</div>
</section>
