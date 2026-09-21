<?php
	$args = array_merge(
		[
			"accreditation_id" => false
		],
		$args ?? []
	);

	$accreditation_id = $args["accreditation_id"];
	if(!$accreditation_id && is_admin()):
		echo("Please specify a accreditation_id for this card.");
		return;
	endif;

	$country = get_field("cpt_accreditation_country", $accreditation_id);
	$year    = get_field("cpt_accreditation_year", $accreditation_id);
	$image   = get_field("cpt_accreditation_image", $accreditation_id);
?>
<div class="swiper-slide !h-auto">
	<div class="<?php if(!is_admin()) echo("h-full "); ?> relative p-3 flex flex-col gap-3 items-start">
		<div class="flex items-center overflow-hidden w-full">
			<?php
				echo(wp_get_attachment_image(
					attachment_id: $image,
					size: "medium",
					attr: ["class" => "w-full max-h-[7.5rem] !object-contain object-left"]
				));
			?>
		</div>

		<h3 class="font-bold text-base">
			<?php echo(esc_html(get_the_title($accreditation_id))); ?>
		</h3>

		<?php if (!empty($country) || !empty($year)): ?>
			<span class="py-1 px-3 text-xs rounded-2xl font-bold text-white bg-[#F6FFFC1A]">
				<?php echo(esc_html(implode(", ", array_filter([$country, $year])))); ?>
			</span>
		<?php endif; ?>
	</div>
</div>
