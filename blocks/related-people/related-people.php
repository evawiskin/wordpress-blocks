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
	$team = get_the_terms($post_id, "team");
	$team_id = false;

	if($team && is_array($team))
		$team_id = $team[0]->term_id;

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

	$related_people = get_field("block_related_people_related_people");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/related-people-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section class="inside-container-lg relative <?php if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	
	<div class="relative container">
		<InnerBlocks 
			class="prose mb-16" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>

		<div class="grid grid-cols-12 gap-4">
			<?php 
				if($related_people) :
					$args = [
						"post_type" => "person",
						"posts_per_page" => 3,
						"post__in" => $related_people,
						"fields" => "ids"
					];
				elseif($team_id):
					$args = [
						"post_type" => "person",
						"posts_per_page" => 3,
						"tax_query" => [
							[
								"taxonomy" => "team",
								"field" => "term_id",
								"terms" => $team_id
							]
						],
						"post__not_in" => [$post_id],
						"fields" => "ids"
					];
				endif;

				$related_person_query = new WP_Query( $args );

				$count = $related_person_query->post_count;

			
				if($related_person_query->have_posts()): $i = 1;
					while($related_person_query->have_posts()): $related_person_query->the_post();

						$card_args = [
							"post_id" => $related_person_query->post,
							"flex_direction" => "flex-row"
						];
				?>
					<div class="col-span-12 md:col-span-6 lg:col-span-4 <?php if(($count == 2) && ($i == 2)) echo(" lg:col-start-6") ?>">
						<?php get_template_part("template-parts/cards/template-part", "person-card", $card_args); ?>
					</div>
				<?php
					$i++; endwhile; 
				endif;
			?>
		</div>
	</div>
</section>