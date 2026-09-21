<?php
	if (!isset($args))
		$args = [];

	$args = array_merge([
		"post_id" => get_the_ID(),
		"post_count" => 1
	], $args);

	$post_id = $args["post_id"];
	$count = $args["post_count"];

	$post = get_post($post_id);

	// Get images
	$image = get_field("cpt_work_card_image", $post_id);
	$image_2 = get_field("cpt_work_card_image_2", $post_id);
	$image_3 = get_field("cpt_work_card_image_3", $post_id);

	// Set classes dynamically
	$flex_direction = $count % 2 == 0 ? "lg:flex-row-reverse" : "lg:flex-row";
	$image_column_position = $count % 2 == 0 ? "left-0 lg:pl-4 lg:pl-12 lg:pr-5" : "justify-end right-0 lg:pr-4 lg:pr-12 lg:pl-5";
	$text_padding = $count % 2 == 0 ? "lg:pl-6" : "lg:pr-6";
	$text_order = "order-last";
	$image_1_dimensions = $image_2 || $image_3 ? "!object-contain !h-full !object-bottom" : "object-contain object-bottom max-w-2xl";
	$image_1_alignment = $count % 2 == 0 ? "mr-auto" : "ml-auto";

	// Add arrow to end of text in title
	$title = append_icon_to_text(
		get_field("cpt_work_title", $post_id),
		"arrow-right",
		"w-6 h-6 inline-block text-forest-green-500 transition-all duration-200 group-hover:translate-x-2 group-hover:text-flamingo-pink-500"
	);
?>

<a href="<?php echo(get_the_permalink($post_id)) ?>" class="group relative card pt-10 lg:py-10 text-forest-green-600">

	<div class="rounded mt-6 lg:mt-0 xl:mx-2 border-2 border-[#DDEDEE] bg-gretter-100">

		<div class="gap-6 px-4 flex flex-col md:px-8 lg:px-12 <?php echo($flex_direction) ?>">

			<!-- Text Section -->
			<div 
				class="
					flex flex-col pb-8 self-center lg:py-16 
					<?php 
						echo("{$text_order} {$text_padding}"); 
						if ($image) 
							echo(" lg:w-2/5"); 
						if(!$image && !$image_2 && !$image_3)
							echo(" py-4");
					?>
				"
			>

				<!-- Work Name -->
				<h2 class="heading-secondary text-base text-forest-green-500 mb-4 md:mb-8 group-hover:text-flamingo-pink-500 transition-colors duration-200 font-extrabold">
					<?php echo(get_the_title()) ?>
				</h2>

				<!-- Work Title -->
				<?php if ($title) : ?>
					<p class="text-3xl mb-6 font-extrabold text-forest-green-500">
						<?php echo($title); ?>
					</p>
				<?php endif; ?>

				<!-- Highlight Statistic -->
				<?php if ($highlight_statistic = get_field("cpt_work_highlight_statistic", $post_id)) : ?>
					<p class="theme-heading-tiny text-forest-green-500 mb-8 xl:mb-12 !leading-tight !font-semibold"><?php echo($highlight_statistic); ?></p>
				<?php endif; ?>

				<!-- Work Services -->
				<?php 
					get_template_part(
						"template-parts/components/template-part",
						"term-tags",
						[
							"post_id" => $post_id,
							"taxonomy" => "service",
							"character_threshold_desktop" => 30,
							"character_threshold_mobile" => 15
						]
					)
				?>
			</div>

			<!-- Work Images -->
			<?php if ($image) : ?>
				<div class="flex right-4 bottom-0 gap-6 h-full inset-y-0 -mt-10 lg:w-3/5 lg:absolute <?php echo($image_column_position); ?>">

					<!-- Main Image -->
					<div class="h-full flex items-end lg:overflow-y-hidden xl:block <?php echo($image_2 || $image_3 ? "w-1/2 2xl:w-1/3" : "w-full") ?>">
						<?php 
							echo(
								wp_get_attachment_image(
									$image, 
									"large", 
									false, 
									["class" => "h-full w-full {$image_1_dimensions} {$image_1_alignment}"]
								)
							); 
						?>
					</div>

					<!-- Additional Images -->
					<?php if ($image_2 || $image_3) : ?>

						<div class="relative flex flex-col gap-6 w-1/2 lg:-bottom-10">

							<!-- Image 2 -->
							<?php if ($image_2) : ?>
								<div class="rounded-lg overflow-hidden h-2/3 flex-col shadow-lg">
									<?php echo(wp_get_attachment_image($image_2, "medium", false, ["class" => "h-full w-full object-cover object-center"])); ?>
								</div>
							<?php endif; ?>

							<!-- Image 3 -->
							<?php if ($image_3) : ?>
								<div class="rounded-lg overflow-hidden h-1/3 flex-col shadow-lg">
									<?php echo(wp_get_attachment_image($image_3, "medium", false, ["class" => "h-full w-full object-cover object-center"])); ?>
								</div>
							<?php endif; ?>

						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

	</div>

</a>