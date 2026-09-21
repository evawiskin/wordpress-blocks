<?php
	if ((!isset($block)) || !is_array($block))
		return;

	// Render block preview image.
	if (display_block_preview_image($block)) return;

	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here",
				"fontSize" => "text-hy-5xl"
			]
		],
		[
			"core/paragraph",
			[
				"placeholder" => "Paragraph text goes here, click the plus button to choose an inner block to add to this container.",
				"fontSize" => "text-hy-lg"
			]
		]
	];

	// Testimonial content
	$testimonial_id = get_field("block_partners_testimonial_testimonial_select") ?: 0;
	$testimonial_author = get_field("cpt_testimonial_attestant_name", $testimonial_id);
	$testimonial_company = get_field("cpt_testimonial_attestant_location", $testimonial_id);
	$testimonial_content = get_field("cpt_testimonial_content", $testimonial_id);

	$speech_bubble_svg = get_svg_icon("speech-mark", "hiyield-icons");

	$testimonial_bg = get_field("block_partners_testimonial_testimonial_background_colour") ?: "purple-500";
	$testimonial_accent = get_field("block_partners_testimonial_testimonial_accent_colour") ?: "flamingo-pink-500";

	$block_id = set_block_id($block);

	/* Get the gutenberg block classes */
	$block_classes = new BlockClasses($block);

?>
<section id="<?php echo($block_id); ?>" class="relative w-full <?php echo($block_classes); ?>">
	<?php do_action("hy_block_start", $block); ?>
	<div class="relative container">
		<InnerBlocks 
			class="w-full prose mb-8 lg:mb-20" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>

		<div class="grid grid-cols-1 lg:grid-cols-2 gap-32 lg:gap-16">
			
			<?php if ( have_rows("block_partners_testimonial_repeater") ) : ?>
				<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 justify-center items-center">
					<?php 
						while( have_rows("block_partners_testimonial_repeater") ) : the_row();
							$partner_logo = get_sub_field("block_partners_testimonial_repeater_partner_image");
							?>
								<div class="w-full aspect-w-3 lg:aspect-w-2 aspect-h-1">
									<?php	
									echo(
										wp_get_attachment_image(
											$partner_logo, 
											"small", 
											false, 
											["class" => "m-auto max-h-12 max-w-36 md:max-w-auto lg:max-h-16 object-contain"]
										)
									);
									?>
								</div>
							<?php
						endwhile;	
					?>
				</div>
			<?php endif; ?>

			<div>
				<div class="relative p-8 px-12 bg-<?php echo($testimonial_bg); ?> text-white rounded">
					<?php if(has_post_thumbnail($testimonial_id)): ?>
						<div class="mb-6">
							<?php  
								echo(
									wp_get_attachment_image(
										get_post_thumbnail_id($testimonial_id),
										"thumbnail",
										false,
										["class" => "-mt-24 w-36 h-36"]
									)
								) 
							?>
						</div>
					<?php endif; ?>

					<div class="text-<?php echo($testimonial_accent); ?> h-12 w-12 mb-6">
						<?php echo($speech_bubble_svg); ?>
					</div>
					
					<!-- Quote -->
					<?php if ($testimonial_content) : ?>
						<blockquote class="px-4 font-athletics font-medium theme-heading-tiny <?php echo($block_text) ?>"><?php echo($testimonial_content) ?></blockquote>
					<?php endif; ?>

					

					<div class="flex items-end justify-between mb-4">
						<!-- Author/Company -->
						<?php if ($testimonial_author || $testimonial_company) : ?>
							<p class="pl-4 font-bold text-gretter-50 text-sm">
								<?php 
									if ($testimonial_author) : 
								?>
									<span rel="author"><?php echo($testimonial_author); ?></span><?php if ($testimonial_company) echo(", "); ?>
								<?php 
									endif;
									if ($testimonial_company)
										echo($testimonial_company);
								?>
							</p>
						<?php endif; ?>

						<div class="h-12 w-12 rotate-180 text-<?php echo($testimonial_accent); ?>">
							<?php echo($speech_bubble_svg); ?>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
</section>