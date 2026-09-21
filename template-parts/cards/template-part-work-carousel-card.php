<?php 
    if(!isset($args))
        $args = [];

    $args = array_merge(
        [
					"post_id" => get_the_ID(),
					"custom_cursor" => false,
					"card_background_color" => "gretter-50"
        ],
        $args
    );

	$post_id = $args["post_id"];
	$show_custom_cursor = $args["custom_cursor"];
	$card_background_color = $args["card_background_color"];

	if($show_custom_cursor) {
		$classes = "md:cursor-none";
	} else {
		$classes = "";
	}

	$image = get_field("cpt_work_card_image", $post_id);
	$image_2 = get_field("cpt_work_card_image_2", $post_id);
	$image_3 = get_field("cpt_work_card_image_3", $post_id);

	$queried_object = $wp_query->get_queried_object();
	$post_type = $queried_object ? $queried_object->post_type : get_post_type($post_id);
	
	if($post_type == "service" || $post_type == "work") {
		$card_background_color = "bg-gretter-50";
	}

	switch ($card_background_color):
		case in_array($card_background_color, ["white", "gretter-50", "sunshine-yellow-500"]):
			$arrow_color = "text-forest-green-600";
			$work_name_color = "text-forest-green-600";
			$work_title_color = "text-black";
			$tags_color = "bg-gretter-50";
			break;
		case in_array($card_background_color, [
			"electric-green-500", "forest-green-500", "forest-green-600", 
			"deep-ocean-blue-500", "flamingo-pink-500", "purple-800",
			"black"]):
			$arrow_color = "text-black";
			$work_name_color = "text-black";
			$work_title_color = "text-black";
			$tags_color = "bg-white";
			break;
		default:
			$arrow_color = "text-black";
			$work_name_color = "text-black";
			$work_title_color = "text-black";
			$tags_color = "bg-gretter-50";
			break;
		endswitch;
			

	// Add arrow to end of text in title
	$title = append_icon_to_text(
		get_field("cpt_work_title", $post_id),
		"arrow-right",
		"w-6 h-6 inline-block {$arrow_color} transition-all duration-50 group-hover:translate-x-2 group-hover:text-electric-green-500"
	);
?>

<a href="<?php echo(get_the_permalink($post_id)) ?>" class="w-full h-full group hy-card flex items-stretch select-none text-forest-green-600">
	<div class="flex flex-col gap-6 mx-6 bg-gretter-100 rounded shadow-lg <?php echo($classes); ?>">
		<!-- Post Thumbnail -->
		<div class="scale-110 aspect-w-3 aspect-h-2">
			<?php if ($image) : ?>
					<div class="flex items-center h-full gap-6">
						<div <?php if ($image_2 || $image_3) : ?> class="w-2/5 h-full" <?php endif; ?>>
							<?php echo(wp_get_attachment_image($image, "large", false, ["class" => "w-full h-full object-contain self-center"])); ?>
						</div>
						<?php if ($image_2 || $image_3) : ?>
							<div class="h-full flex flex-col justify-center gap-6 w-3/5">
								<?php if ($image_2) : ?>
									<div class="rounded-lg overflow-hidden h-2/3 shadow-lg3">
										<?php echo(wp_get_attachment_image($image_2, "medium", false, ["class" => "w-full h-full object-cover object-center"])); ?>
									</div>
								<?php endif; ?>
								
								<?php if ($image_3) : ?>
									<div class="rounded-lg overflow-hidden h-1/3">
										<?php echo(wp_get_attachment_image($image_3, "medium", false, ["class" => "w-full h-full object-cover object-center"])); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif;?>
					</div>

			<?php elseif ($img_placeholder = get_svg_icon("image")) : ?>
				<div class="w-full h-full flex items-center justify-center bg-white text-white rounded">
					<span class="w-1/2 h-auto block group-hover:scale-105 transition-transform duration-200">
						<?php echo($img_placeholder) ?>
					</span>
				</div>
			<?php endif; ?>
		</div>

		<div class="flex flex-col w-full self-center p-8 relative">
			<div class="w-full">
				<!-- Work Name -->
				<span class="text-lg font-black block !mb-1 transition-colors duration-200 no-underline font-athletics">
					<?php echo(get_the_title($post_id)) ?>
				</span>

				<!-- Work Title -->
				<?php if ($title): ?>
					<p class="theme-heading-tiny !font-bold mb-2 no-underline leading-[1.2em] font-area-normal">
						<?php echo($title); ?>
					</p>
				<?php endif; ?>
			</div>

			<div class="absolute z-10 flex flex-col justify-between rounded-b-[4px] w-full h-full p-8 left-0 top-0 opacity-0 transition-opacity duration-200 group-hover:opacity-100 bg-gretter-100">

				<!-- Highlight Statistic -->
				<?php if ($highlight_statistic = get_field("cpt_work_highlight_statistic", $post_id)) : ?>
					<p class="text-xl mb-8 xl:mb-12 !leading-tight !font-semibold"><?php echo($highlight_statistic); ?></p>
				<?php endif; ?>

				<!-- Work Services -->
				<?php 
					get_template_part(
						"template-parts/components/template-part",
						"term-tags",
						[
							"post_id" => $post_id,
							"taxonomy" => "service",
							"character_threshold_desktop" => 15,
							"character_threshold_mobile" => 20,
							"term_class" => "{$tags_color} py-2 px-3 rounded font-bold text-forest-green-600 text-xs"
						]
					)
				?>
			</div>
		</div>
	</div>
</a>