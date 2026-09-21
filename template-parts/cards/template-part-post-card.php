<?php
	if (!isset($args))
		$args = [];

	$args = array_merge([
		"post_id" => get_the_ID()
	], $args);

	$post_id = $args["post_id"];
	$post = get_post($post_id);
	$author_id = $post->post_author;
?>
<a href="<?php echo(get_the_permalink($post_id)) ?>" class="group hy-card bg-gretter-100 rounded-md p-6 flex flex-col-reverse grid-cols-3 gap-9 md:grid text-forest-green-500">

	<div class="col-span-2">

		<!-- Author Details -->
		<div class="mb-6">
			<?php
				get_template_part(
					"template-parts/components/template-part", 
					"author-details",
					[
						"author_id" => $author_id,
						"post_id" => $post_id,
						"text_color" => "text-forest-green-500"
					]
				)
			?>
		</div>

		<!-- Post Title -->
		<h2 class="text-3xl mb-4 group-hover:text-flamingo-pink-500 transition-colors duration-200"><?php echo(get_the_title()) ?></h2>

		<!-- Excerpt -->
		<?php if ($excerpt = get_the_excerpt_first_sentences($post_id, 2)) : ?>
			<p class="text-forest-green-500 mb-4 font-semibold"><?php echo($excerpt); ?></p>
		<?php endif; ?>

		<!-- Post Categories -->
		<?php 
			get_template_part(
				"template-parts/components/template-part",
				"term-tags",
				[
					"post_id" => $post_id,
				]
			)
		?>
	</div>

	<!-- Post Thumbnail -->
	<div class="h-fit aspect-w-3 aspect-h-2 rounded-md overflow-hidden md:aspect-w-1 md:aspect-h-1">
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
				<div class="w-full h-full flex items-center justify-center bg-neutral-300 text-gretter-50">
					<span class="w-1/2 h-auto block group-hover:scale-105 transition-transform duration-200">
						<?php echo($img_placeholder) ?>
					</span>
				</div>
			<?php 
				endif; 
			?>
	</div>
</a>