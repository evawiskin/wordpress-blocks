<?php 

	//get script build constant from wp-config
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "forest-green-500";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "white";

	// Show block preview image
	if (get_field("is_preview")) :
	?>
			<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/phases-block.jpg")) ?>" width="100%">
	<?php
		return;
	endif;

	// Get terms from selection
	$terms = get_field("block_phases_terms_select");

	if(!$terms) {
		return;
	}
?>

<section class="inside-container-sm relative <?php if($block_bg) echo("bg-{$block_bg} "); if($block_text) echo(" text-{$block_text}"); ?>">

	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	

	<div class="container relative z-10">

		<div class="w-full flex flex-col gap-8">

			<?php foreach($terms as $term):
				
				$name = $term->name;
				$lead_color = get_field("tax_phase_color", $term) ?: "electric-green-500";
				$icon = get_field("tax_phase_icon", $term);
				$image = get_field("tax_phase_image", $term);
				$desc = get_field("tax_phase_long_desc", $term);
		
			?>
				<!-- Phase -->
				<div class="w-full flex flex-col gap-8">

					<!-- Phase header -->
					<div class="w-full flex flex-row gap-4 items-center">

						<?php if($icon): ?>
							<!-- Icon -->
							<div class="w-12 h-12 text-<?php echo($lead_color); ?>">
								<?php echo( get_svg_icon($icon) ); ?>
							</div>
						<?php endif; ?>

						<!-- Heading text -->
						<h3 class="theme-heading-small font-normal <?php if($block_text) echo(" text-{$block_text}"); ?>">
							<?php echo($name); ?>
						</h3>

					</div>

					<!-- Phase Body -->
					<div class="w-full flex items-stretch gap-4 text-<?php echo($block_text); ?>">
						
						<!-- Timeline -->
						<div class="w-12 grow relative flex flex-row justify-center">
							<div class="w-2 h-2 rotate-45 bg-white absolute top-0 left-1/2 -translate-x-1/2"></div>
							<div class="w-0.5 h-full bg-white"></div>
							<div class="w-2 h-2 rotate-45 bg-white absolute bottom-0 left-1/2 -translate-x-1/2"></div>
						</div>

						<!-- Content -->
						<div class="w-full grid lg:grid-cols-3 gap-8 mb-16 lg:mb-24 mr-8 lg:mr-0">

							<?php if($image): ?>
								<!-- Banner -->
								<div class="w-full">
									<div class="aspect-w-1 aspect-h-1 shadow-lg">
										<?php echo(wp_get_attachment_image($image, "large", false, ["class" => "w-full h-full object-cover rounded overflow-hidden"])); ?>
									</div>
								</div>
							<?php endif; ?>

							<!-- Description -->
							<div class="w-full prose mt-2 leading-relaxed font-normal <?php if($block_text) echo(" text-{$block_text}"); ?>">
								<?php echo($desc); ?>
							</div>

							<!-- Links -->
							<div class="w-full leading-relaxed font-normal lg:pl-24 ">

								<h3 class="theme-heading-tiny font-normal text-electric-green-500">Services during <?php echo($name); ?></h3>

								<!-- Links array -->
								<div class="flex flex-col gap-y-4 mt-4 <?php if($block_text) echo(" text-{$block_text}"); ?>">

									<?php
									$args = [
										"post_type"      => "service",
										"posts_per_page" => -1,
										"tax_query" => [
											[
												"taxonomy" => "phase",
												"field" => "id",
												"terms" => $term->term_id
											]
										],
									];
						
									$posts = get_posts($args);

									foreach($posts as $post):
										$service_link = get_permalink($post);
										$service_title = get_the_title($post);
									?>
									
										<a class="underline font-semibold hover:text-electric-green-500 duration-200" href="<?php echo($service_link); ?>">
											<?php echo($service_title); ?>
										</a>

									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>