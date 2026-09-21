<?php
	if ((!isset($block)) || !is_array($block))
		return;

	// Render block preview image.
	if (display_block_preview_image($block)) return;

	// Block Fields
	$testimonial_accent_colour = get_field("block_testimonial_block_accent_colour") ?: "current";
	$testimonial_id = get_field("block_testimonial_block_testimonial") ?: false;
		
	// Testimonial Fields
	if(!$testimonial_id)
		return;

	$testimonial_name = get_field("cpt_testimonial_attestant_name", $testimonial_id) ?: false;
	$testimonial_content = get_field("cpt_testimonial_content", $testimonial_id) ?: false;
	$testimonial_thumbnail_id = get_post_thumbnail_id($testimonial_id);
	$testimonial_location = get_field("cpt_testimonial_attestant_location", $testimonial_id) ?: false;

	// get the client terms for the testimonial
	$testimonial_clients = get_the_terms($testimonial_id, "client");
	$testimonial_client_name = "";
	// get the first client name
	if($testimonial_clients)
		$testimonial_client_name = $testimonial_clients[0]->name;

	$block_classes = new BlockClasses($block);
?>

<section class="relative <?php echo($block_classes); ?>">
	<?php do_action("hy_block_start", $block); ?>
		<div class="container">
			<div class="relative flex flex-col lg:flex-row items-center gap-10 lg:gap-14 justify-between <?php if(!$testimonial_thumbnail_id) echo ' max-w-[800px] mx-auto'; ?>">
			<?php if($testimonial_thumbnail_id): ?>
				<div class="max-w-1/2 lg:max-w-72  mx-auto flex-shrink-0">
					<?php echo(wp_get_attachment_image($testimonial_thumbnail_id, "large", false, ["class" => "w-full h-full"])); ?>
				</div>
			<?php endif; ?>
			<div class="relative flex-1 flex flex-col items-center justify-center <?php if($testimonial_thumbnail_id) echo ' max-w-[800px]'; ?>">
				<!-- Top left quote -->
				<div class="flex flex-col lg:flex-row items-start w-full">
					<span class="lg:absolute lg:-top-6 mb-7 -left-16 text-<?php echo($testimonial_accent_colour); ?> h-12 w-12 block">
						<?php echo(get_svg_icon("speech-mark", "hiyield-icons")); ?>
					</span>
					<?php if ($testimonial_content) : ?>
						<blockquote class="text-xl font-bold text-current">
							<?php echo($testimonial_content) ?>
						</blockquote>
					<?php endif; ?>
				</div>
				<?php if ($testimonial_name): ?>
					<div class="mt-6 w-full">
						<p>
							<span rel="author"><?php echo($testimonial_name); ?></span>
							<?php if($testimonial_client_name): ?>
								<span>at <?php echo($testimonial_client_name); ?></span>
							<?php endif; ?>
							<?php if($testimonial_location && $testimonial_location !== $testimonial_client_name): ?>
								<span>, <?php echo($testimonial_location); ?></span>
							<?php endif; ?>
						</p>
					</div>
				<?php endif; ?>
				<!-- Bottom right quote -->
				<span class="self-end lg:absolute -bottom-6 -right-6 text-<?php echo($testimonial_accent_colour); ?> h-12 w-12 rotate-180 block">
					<?php echo(get_svg_icon("speech-mark", "hiyield-icons")); ?>
				</span>
			</div>
		</div>
	</div>
</section>