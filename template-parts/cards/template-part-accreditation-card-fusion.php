<?php
	$args = array_merge(
		[
			"accreditation_id" => false
		],
		$args ?? []
	);

	// Ensure we"ve got at least the post_id
	$accreditation_id = $args["accreditation_id"];
	if(!$accreditation_id && is_admin()):
		echo("Please specify a accreditation_id for this card.");
		return;
	endif;

	$country = get_field("cpt_accreditation_country", $accreditation_id);
	$year = get_field("cpt_accreditation_year", $accreditation_id);
	$name = get_field("cpt_accreditation_name", $accreditation_id);
	$description = get_field("cpt_accreditation_description", $accreditation_id);
	$image = get_field("cpt_accreditation_image", $accreditation_id);
?>
<div class="swiper-slide !h-auto">
	<div class="<?php if(!is_admin()) echo("h-full "); ?> relative rounded-xl border-2 border-[#DDEDEE] p-4">
		<!-- start of accreditation image -->
		<div class="flex flex-col items-center lg:items-start gap-[0.7188rem]">
			<div class="flex justify-center items-center overflow-hidden">
				<?php 
					echo(wp_get_attachment_image(
						attachment_id: $image, 
						size: "medium", 
						attr: ["class" => "w-full max-h-[5.625rem] !object-contain"]
					)); 
				?>
			</div>

			<h3 class="text-center lg:text-left font-bold leading-tight lg:leading-snug">
				<?php echo(get_the_title($accreditation_id)); ?>
			</h3>

			<div class="flex flex-wrap items-center gap-2">
				<?php if (!empty($country) && !empty($year)): ?>
					<span class="py-1 px-3 text-xs rounded-2xl font-bold text-forest-green-500 bg-[#DFEEEB]">
						<?php echo("$country, $year"); ?>
					</span>
				<?php endif; ?>

				<?php if (!empty($name)): ?>
					<p class="!m-0 text-xs font-bold text-forest-green-500">
						<?php echo($name); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
