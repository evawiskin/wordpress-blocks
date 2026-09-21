<?php 
	if (!isset($args))
		$args = [];
	
	$args = array_merge([
		"post_id" => get_the_ID(),
		"card_number" => 0,
		"aspect_ratio" => "aspect-h-2 aspect-w-3"
	], $args);

	$post_id = $args["post_id"];
	$post = get_post($post_id);
	$card_number = ($args["card_number"] && $args["card_number"] < 10) ? "0{$args["card_number"]}" : $args["card_number"];
	$aspect_ratio = $args["aspect_ratio"];
?>
<?php
	$outer_classes = ['flex', 'flex-1'];
	if ($card_number) {
		$outer_classes[] = 'md:pl-7';
	}
?>
<div class="<?php echo esc_attr(implode(' ', $outer_classes)); ?>">
	<div class="relative bg-gretter-100 text-forest-green-500 rounded-md outline outline-transparent outline-offset-4 focus-within:outline-forest-green-500 flex-1">

		<!-- Card Number -->
		<?php if ($card_number) : ?>
				<span class="text-flamingo-pink-500 text-5xl font-bold font-athletics absolute z-10 mt-2.5 -translate-x-1/4 sm:text-4xl xl:text-5xl">
					<?php echo($card_number); ?>
				</span>
		<?php endif; ?>

		<div class="group">
			<a href="<?php echo(get_the_permalink($post_id)) ?>" class="card">

				<!-- Post Thumbnail -->
				<div class="max-h-48 rounded-t-md overflow-hidden <?php echo($aspect_ratio); ?>">
					<?php 
						if (has_post_thumbnail($post_id)) : 
							echo(
								get_the_post_thumbnail(
									$post_id, 
									"large", 
									[
										"class" => "w-full h-full object-cover group-hover:scale-105 transition-transform duration-200",
										"srcset" => wp_get_attachment_image_srcset(get_post_thumbnail_id($post_id), "large"),
										"sizes" => "(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
									]
								)
						);
						elseif ($img_placeholder = get_svg_icon("image")) : 
					?>
						<div class="w-full h-auto flex items-center justify-center bg-neutral-300 text-gretter-50">
							<span class="w-1/2 h-auto block group-hover:scale-105 transition-transform duration-200">
								<?php echo($img_placeholder) ?>
							</span>
						</div>
					<?php 
						endif; 
					?>
				</div>
				<div class="p-4">
					<!-- Author, Date Posted and Read Time -->
					<div class="pb-2">
						<?php 
							get_template_part(
								"template-parts/components/template-part",
								"author-details",
								[
									"author_id" => $post->post_author,
									"post_id" => $post_id,
									"show_avatar" => false,
									"text_color" => "text-forest-green-500"
								]
							);
						?>
					</div>
						
					<!-- Post title -->
					<h3 class="text-2xl group-hover:text-flamingo-pink-500 transition-colors duration-200">
						<?php 
							$title_words = explode(" ", get_the_title($post_id));

							$last_word = array_pop($title_words);

							$remaining_title = implode(" ", $title_words);

							echo($remaining_title);
						?>
							<div class="inline-block"><?php echo($last_word); ?>

						<?php if ($arrow_icon = get_svg_icon("arrow-right")): ?>
								<span class="inline-block w-5 h-5 -mb-1 text-flamingo-pink-500 group-hover:translate-x-1.5 transition-transform duration-200">
									<?php echo($arrow_icon); ?>
								</span>
						<?php 
							endif;
						?>
							</div>
					</h3>
				</div>
			</a>
		</div>
	</div>
</div>