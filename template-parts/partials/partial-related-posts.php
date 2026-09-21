<?php
	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here"
			]
		]
	];

	$post_id = get_the_ID();
	$category = get_the_terms($post_id, "category");
	$category_id = false;

	if($category && is_array($category))
		$category_id = $category[0]->term_id;

	$related_posts_title = get_field("option_blog_settings_related_posts_title", "option") ?: "More like this";

    $related_posts = get_field("post_related_posts", $post_id);
?>

<section class="inside-container-lg">
	<div class="container">

        <div class="prose mb-12">
			<?php
				if($related_posts_title)
					echo("<h3 class=\"theme-heading-medium text-forest-green-500\">{$related_posts_title}");
			?>
        </div>

		<div class="grid grid-cols-12 gap-8">
			<?php 
				if($related_posts) :
					$args = [
						"post_type" => "post",
						"posts_per_page" => 3,
						"post__in" => $related_posts,
						"fields" => "ids"
					];
				elseif($category_id) :
					$args = [
						"post_type" => "post",
						"posts_per_page" => 3,
						"tax_query" => [
							[
								"taxonomy" => "category",
								"field" => "term_id",
								"terms" => $category_id
							]
						],
						"fields" => "ids",
						"post__not_in" => [$post_id]
					];
				else:
					$args = [
						"post_type" => "post",
						"posts_per_page" => 3,
						"post__not_in" => [$post_id],
						"fields" => "ids"
					];
				endif;

				$related_post_query = new WP_Query( $args );
			
				if($related_post_query->have_posts()):
					while($related_post_query->have_posts()): $related_post_query->the_post();
						$card_args = [
							"post_id" => $related_post_query->post,
							"aspect_ratio" => "aspect-h-1 aspect-w-1"
						];
				?>
					<div class="col-span-12 md:col-span-6 lg:col-span-4 flex">
						<?php get_template_part("template-parts/cards/template-part", "post-card-compact", $card_args); ?>
					</div>
				<?php
					endwhile; 
				endif;
			?>
		</div>
	</div>
</section>