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

	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph",
		"hiyield/custom-heading"
	];

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

	$selected_work = get_field("block_selected_work_select_work");

	$archive_page = get_post_type_archive_link("work");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/selected-work-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section class="relative inside-container-lg <?php if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	
	<div class="container">
		<InnerBlocks 
			class="prose mb-10" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>

		<div class="flex flex-col gap-12 lg:gap-24 xl:-mx-8 mx-0">
			<?php
				$args = [
					"post_type" => "work",
					"post__in" => $selected_work,
					"posts_per_page" => -1,
					"orderby" => "post__in"
				];
				
				$work_query = new WP_Query($args);

				if ($work_query->have_posts()) { 
					$count = 1;
					while($work_query->have_posts()) {
						$work_query->the_post();
						if(is_admin() && !wp_doing_ajax()) {
							echo(get_the_title());
							continue;
						}
						get_template_part(
							"template-parts/cards/template-part", 
							"work-card",
							[
								"post_id" => get_the_ID(),
								"post_count" => $count
							]
						);
						$count++; 
					}
				}
				wp_reset_postdata();
			?>
		</div>

		<div class="text-center mt-14 mb-6">
			<?php
				$button_content = [
					"button_text"	=> "View all our work",
					"button_link"	=> $archive_page
				];

				get_template_part(
					"template-parts/partials/partial",
					"button",
					[
						"button_content"	=> $button_content,
						"button_classes"	=> "hy-button-outline",
						"icon_right"		=> "arrow-right"
					]
				);
			?>
		</div>
	</div>
</section>