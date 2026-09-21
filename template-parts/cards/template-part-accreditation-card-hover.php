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

	$accreditation_year = get_field("cpt_accreditation_year", $accreditation_id);
	$accreditation_description = get_field("cpt_accreditation_description", $accreditation_id);
	$accreditation_image = get_field("cpt_accreditation_image", $accreditation_id);
?>
<div class="swiper-slide">
	<div class="group relative focus:outline-none focus-visible:ring-2 focus-visible:ring-forest-green-500 focus-visible:ring-offset-2 rounded" tabindex="0">
		<!-- start of accreditation image -->
		<div class="flex justify-center items-center lg:group-hover:opacity-0 lg:group-hover:invisible group-focus-within:opacity-0 group-focus-within:invisible transition-all h-20 sm:h-24 md:h-36 lg:h-40">
				<?php echo(wp_get_attachment_image($accreditation_image, "full", "", ["class" => "max-h-full object-contain"])); ?>
		</div>

		<!-- start of accreditation card -->
		<div class="absolute w-full h-full flex invisible top-0 left-0 opacity-0 lg:group-hover:visible lg:group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 transition-all flex-col">
			<!-- accreditation year -->
			<span class="text-xs">
				<?php echo($accreditation_year); ?>
			</span>
			<!-- accreditation description -->
			<span class="font-bold">
				<?php echo($accreditation_description); ?>
			</span>
		</div>
	</div>
</div>