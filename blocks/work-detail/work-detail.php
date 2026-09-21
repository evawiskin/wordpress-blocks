<?php
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
				"placeholder" => "Paragraph text goes here, click the plus button to choose a inner block to add to this container."
			]
		]
	];

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	$work_id = get_the_ID();

	$company_name = get_the_title($work_id);

	$company_size = get_field("cpt_work_company_size", $work_id);
	$budget = get_field("cpt_work_budget", $work_id);
	$website_url = get_field("cpt_work_url", $work_id);
	$website_text = str_replace(["https://", "http://", "www."], "", $website_url);

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/work-detail-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section class="relative inside-container-xl <?php if($block_text_center) echo("text-center "); if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>">
	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	
	<div class="container grid grid-cols-12 gap-4 items-center">
		<div class="col-span-12 lg:col-span-7 lg:col-start-2">
			<InnerBlocks 
				class="prose-narrow <?php if($block_text_center) echo("text-center "); ?>" 
				template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			/>
		</div>

		<div class="col-span-12 lg:col-span-3 lg:col-start-10 font-semibold text-base">

			<?php 
				$services = get_the_terms($work_id, "service");
				if($services) :
			?>
				<span class="block mb-2">Services</span>
				<div class="mb-8 text-forest-green-500">
					<?php
						$service_names = [];
						foreach($services as $service) :
							$service_names[] = $service->name;
						endforeach;
						$service_names = implode(", ", $service_names);

						echo($service_names);
					?>
				</div>
			<?php endif; ?>


			<?php if($company_size) : ?>
				<span class="block mb-2">Company Size</span>
				<p class="text-forest-green-500"><?php echo($company_size); ?></p>
			<?php endif; ?>

			<?php 
				$industries = get_the_terms($work_id, "industry");
				if($industries) :
			?>
				<span class="block mb-2">Industry</span>
				<div class="mb-8 text-forest-green-500">
					<?php
						$industry_names = [];
						foreach($industries as $industry) :
							$industry_names[] = $industry->name;
						endforeach;
						$industry_names = implode(", ", $industry_names);

						echo($industry_names);
					?>
				</div>
			<?php endif; ?>


			<?php if($budget) : ?>
				<span class="block mb-2">Range</span>
				<p class="text-forest-green-500"><?php echo($budget); ?></p>
			<?php endif; ?>

			<?php if($website_url) : ?>
				<span class="block mb-2">Website</span>
				<a
					href="<?php echo($website_url); ?>"
					target="_blank"
					aria-label="<?php echo($company_name); ?> Website"
					class="flex items-center gap-1 text-forest-green-500 hover:text-black transition-colors font-bold"
				>
					<?php echo($website_text); ?>
					<?php if ($external_link_icon = get_svg_icon("external-link")) : ?>
						<span class="w-5 h-5"><?php echo($external_link_icon) ?></span>
					<?php endif; ?>
				</a>
			<?php endif; ?>

		</div>
	</div>
</section>