<?php 
	//counter which is required for unique accordion ids
	//and aria labelling inside the partial
	$accordion_count = 0;

	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here"
			]
		],
		[
			"core/paragraph",
			[
				"placeholder" => "Paragraph text goes here"
			]
		]
	];
	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph"
	];

	//get script build constant from wp-config
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	$terms_heading_color = get_field("block_industries_headings_color") ?: "electric-green-500";
	$terms_link_color = get_field("block_industries_links_color") ?: "white";
	$asterisk_color = get_field("block_industries_asterisk_color") ?: "electric-green-500";

	// Get terms from selection
	$terms_selector = get_field("block_industries_terms_select");

	// Check if empty
	if($terms_selector) {
		$terms = [];
		foreach ($terms_selector as $key => $term)
			$terms[$key] = get_term_by( "id", $term, "industry" );
	} else {
		// If ACF field is empty - then show all terms (except terms without posts)
		$terms = get_terms([
			"taxonomy" => "industry",
			"hide_empty" => true
		]);
	}

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/industries-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section 
	class="
		inside-container-lg overflow-x-clip relative overflow-y-hidden
		<?php 
			if($block_text_center) 
				echo("text-center "); 
			if($block_bg) 
				echo("bg-{$block_bg} "); 
			if($block_text) 
				echo(" text-{$block_text}"); 
		?>
	"
>

	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	

	<div class="absolute top-1/4 md:top-1/2 -translate-y-1/2 -right-[25%] w-2/5 z-0 opacity-20 <?php echo("text-{$asterisk_color}"); ?>">
		<?php echo(get_svg_icon("hiyield-asterisk", "hiyield-icons")); ?>
	</div>

	<div class="container relative z-10">

		<InnerBlocks  
			class="prose"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
		/>

		<?php if($terms): ?>
			<div class="mt-12 columns-1 sm:columns-2 lg:columns-3 gap-x-0 sm:gap-x-6 lg:gap-x-12">
				<?php foreach($terms as $term):
					
					// Look how many posts might be shown for this term
					$max_posts = get_field("tax_industry_max_items_to_show", $term);
					
					// If not specified - make indefinite;
					if(!$max_posts) {
						$max_posts = -1;
					}

					// Make WP Query
					$args = [
						"post_type" => "work",
						"numberposts" => $max_posts,
						"tax_query" => [
							[
								"taxonomy" => "industry",
								"field" => "id",
								"terms" => $term->term_id
							]
						]
					];
					$posts = get_posts($args);
					
				?>
					<div class="flex flex-col break-inside-avoid mb-16 outline-none" tabindex="0">
					<h3 class="theme-heading-mini mb-4 flex items-center <?php echo("text-{$terms_heading_color}"); ?>" tabindex="0";>
    					<?php echo($term->name); ?>
					</h3>
						<div class="flex flex-col <?php echo("text-{$terms_link_color}"); ?>">

							<?php foreach($posts as $post): 

									if($hide_single = get_field("cpt_work_hide_single_page", $post->ID))
										$link = get_field("cpt_work_url", $post->ID);
									else
										$link = get_permalink($post);
								?>
								<a
								class="group flex items-center space-x-1 mb-2 semibold opacity-100 transition-opacity duration-200
								focus-within:opacity-60 
								hover:opacity-60"
								href="<?php echo($link); ?>"
								tabindex="0"
									<?php if($hide_single): ?>
										target="_blank"
									<?php endif; ?>
								>
									<span><?php echo(get_the_title($post)); ?></span>
									<span class="h-full w-4 ml-1 mt-1 transition-all ease-linear duration-200 group-focus-within:translate-x-2 group-hover:translate-x-2"><?php echo(get_svg_icon("arrow-right")); ?></span>
							</a>

							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
	</div>
</section>