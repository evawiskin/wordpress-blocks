<?php
	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here",
				"fontSize" => "medium-heading"
			]
		],
		[
			"core/paragraph",
			[
				"placeholder" => "Paragraph text goes here, click the plus button to choose a inner block to add to this container."
			]
		]
	];

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

	$block_has_light_background = get_field("block_locations_has_light_background");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/container-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<section class="relative inside-container-xl <?php if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>
	<div class="relative container">
		<InnerBlocks 
			class="prose max-w-[50.375rem]" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>

		<?php if( have_rows("option_company_info_locations", "option") ): ?>
			<div class="grid md:grid-cols-3 gap-y-12 gap-x-6 mt-[3.75rem] font-semibold">
				<?php while( have_rows("option_company_info_locations", "option") ) : the_row(); ?>
					<div class="flex gap-8 text-black">
						<?php 
							if($asterisk = get_svg_icon("asterisk", "hiyield-icons")):
								$location_color_dark_bg = get_sub_field("option_company_info_locations_location_color");
								$location_color_light_bg = get_sub_field("option_company_info_locations_location_color_light_bg");
								$location_link = get_sub_field("option_company_info_locations_location_link");
								$location_name = get_sub_field("option_company_info_locations_location_name");
								$location_name_classes = "text-2xl text-forest-green-500 font-bold";

								$location_color = ($block_has_light_background && ($location_color_light_bg != "transparent")) ? $location_color_light_bg : $location_color_dark_bg;

								$asterisk_color = $location_color ? "text-{$location_color}" : "text-forest-green-600";

								
						?>
								<span class="w-12 h-12 <?php echo($asterisk_color); ?>">
									<?php echo($asterisk); ?>
								</span>
						<?php endif; ?>

						<div>
							<?php 
								if($location_link): ?>
									<a href="<?php echo($location_link); ?>" class="<?php echo($location_name_classes);?> hover:text-electric-green-500 transition-colors">
							<?php else: ?>
									<p class="<?php echo($location_name_classes); ?>">
							<?php endif; ?>
										<?php echo($location_name); ?>
							<?php if($location_link): ?>
									</a>
							<?php else: ?>
									</p>
							<?php endif;
							
								if($location_address = get_sub_field("option_company_info_locations_location_address"))
									echo("<p class=\"mt-2\">{$location_address}</p>");
							?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>