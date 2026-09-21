<?php
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	if($countup_uri = get_theme_file_uri("assets/dist/vendor/js/countUp.js"))
		wp_enqueue_script("countup-js", $countup_uri, [], $build_version, true);

	if($countup_init_uri = get_theme_file_uri("assets/dist/js/init-countup.js"))
		wp_enqueue_script("countup-js-init", $countup_init_uri, [], $build_version, true);

	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here"
			]
		]
	];

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/statistics-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section class="py-8 lg:pt-24 lg:pb-32 <?php if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<div class="container">
		<InnerBlocks 
			class="prose-narrow mb-6 lg:mb-10" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>

		<!-- Statistics repeater start -->
		<?php if (have_rows("block_statistics_repeater")) : ?>
				<div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
					<?php while(have_rows("block_statistics_repeater")) : the_row(); 
						$statistic_number_prefix = get_sub_field("block_statistics_repeater_number_prefix");
						$statistic_number = get_sub_field("block_statistics_repeater_number");
						$statistic_number_suffix = get_sub_field("block_statistics_repeater_number_suffix");
						$statistic_label = get_sub_field("block_statistics_repeater_label");
					?>
						
						<div class="flex items-center flex-col justify-center">
							<span class="heading-primary theme-heading-huge text-electric-green-500">
								<?php 
									if($statistic_number_prefix) echo("<span>{$statistic_number_prefix}</span>");
									echo("<span class=\"countup-item\">{$statistic_number}</span>");
									if($statistic_number_suffix) echo("<span>{$statistic_number_suffix}</span>");
								?>
							</span>
							
							<span class="heading-secondary theme-heading-tiny">
								<?php echo($statistic_label); ?>
							</span>
						</div>
						
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
			<!-- Statistics repeater end -->
	</div>
</section>