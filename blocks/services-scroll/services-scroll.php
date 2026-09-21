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
		"core/paragraph"
	];

	//get script build constant from wp-config
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	$block_data = $block;
	$block_id = $block_data["id"];
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/services-scroll-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;

	// Get terms from selection
	$terms_repeater = get_field("block_services_scroll_repeater");

	if(!$terms_repeater) {
		return;
	}

	$terms = [];

	// Loop throug repeater
	foreach ($terms_repeater as $repeater_item) {
		$term_id = $repeater_item["block_services_scroll_parent_service"];
		$term = get_term_by( "id", $term_id, "service" );
		if($term) {
			$terms[] = $term;
		}
	}

	$terms_count = count($terms_repeater);
	$columns = "";

	switch($terms_count) {
		case 2:
			$columns = "md:grid-cols-2";
			break;
		case 3:
			$columns = "lg:grid-cols-3";
			break;
		case ($terms_count > 3):
			$columns = "md:grid-cols-2 lg:grid-cols-3";
			break;

	}

	$active_service = "";

	if( isset( $_GET["service"] ) ) {
		$active_service = $_GET["service"];
	}

	//enqueue swiper
	swiper_enqueue_scripts();

?>

<section
	id="<?php echo($block_id); ?>"
	class="services-scroll inside-container-xl relative <?php if($block_text_center) echo("text-center "); if($block_bg) echo("bg-{$block_bg} "); if($block_text) echo(" text-{$block_text}"); ?>"
	data-active-service="<?php echo($active_service); ?>"
>

	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	

	<div class="container relative z-10">

		<InnerBlocks  
			class="prose"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
		/>

		<!-- Cards -->
		<div class="services-scroll-cards mt-12 mb-12 lg:mb-24 grid gap-6 grid-cols-1 <?php echo($columns); ?>">
			<?php foreach($terms as $key => $term):
				$term_icon = get_field("tax_service_card_icon", $term);
				$term_slug = $term->slug;
				$args = [
					"data_id" => $term->slug,
					"bg_color" => "gretter-50",
					"lead_color" => "flamingo-pink-500",
					"text_color" => "forest-green-600",
					"top_icon" => $term_icon,
					"help_icon" => "arrow-down",
					"heading" => $term->name,
					"link" => "#",
					"content" => $term->description
				];
				get_template_part("template-parts/cards/template-part", "service-card", $args);
			endforeach; ?>
		</div>


		<!-- Mobile view // Hidden from LG -->
		<div class="relative flex lg:hidden flex-col lg:flex-row gap-y-8 flex-wrap mt-8 items-start justify-between">

			<?php
			foreach($terms as $key => $term):

				$card_color = get_field("tax_service_card_color", $term) ?: "electric-green-500";
				$card_icon = get_field("tax_service_card_icon", $term);
				$card_banner_id = get_field("tax_service_card_banner", $term);
				$card_desc = get_field("tax_service_card_description", $term);
				$child_terms = get_terms( [ "taxonomy" => "service", "parent" => $term->term_id, "hide_empty" => false ] );
				$term_name = $term->name;
				$term_slug = $term->slug;
			?>

				<!-- <?php echo($term_name); ?> / Heading section -->
				<div
					data-id="<?php echo($term->slug); ?>"
					class="mobile-service-section w-full flex flex-row justify-start items-center gap-x-6"
				>
					<div class="w-12 h-12 text-<?php echo($card_color); ?>">
						<?php echo( get_svg_icon($card_icon) ); ?>
					</div>
					<h3 class="theme-heading-medium font-normal <?php if($block_text) echo(" text-{$block_text}"); ?>">
						<?php echo($term_name); ?>
					</h3>
				</div>
					
				<!-- <?php echo($term_name); ?> / Banner / Column 1 -->
				<div class="flex w-full lg:w-1/3 flex-col gap-y-4">
					<?php if($card_banner_id): ?>
						<!-- Banner -->
						<div class="w-full aspect-[1/1] shadow-lg">
							<?php echo(wp_get_attachment_image($card_banner_id, "large", false, ["class" => "w-full h-full object-cover rounded overflow-hidden"])); ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- <?php echo($term_name); ?> / Links / Column 2 -->
				<div class="flex w-full lg:w-1/3 flex-col gap-y-4 px-0 lg:px-8 ">
					<!-- Links array -->
					<div class="flex flex-col gap-y-4 <?php if($block_text) echo(" text-{$block_text}"); ?>">
						<?php foreach($child_terms as $child_term):

							//Detect if service should be hidden
							$hide_service = get_field("taxonomy_service_hide_from_service_block", $child_term);
							if($hide_service)
								continue;


							$child_url = get_term_link($child_term);
							$child_name = $child_term->name;
						?>
							<a class="hover:!underline font-semibold" href="<?php echo($child_url); ?>">
								<?php echo($child_name); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- <?php echo($term_name); ?> / Description / Column 3 -->
				<div class="flex w-full lg:w-1/3 flex-col">
					<!-- Links array -->
					<div class="service-card-desc text-<?php echo($card_color); ?>">
						<?php echo($card_desc); ?>
					</div>
				</div>

			<?php
			endforeach;
			?>

		</div>


		<!-- Desktop view // Visible from LG -->
		<div class="desktop-service-section relative hidden lg:grid grid-cols-3 gap-x-8">

			<!-- Column with banners -->
			<div class="w-full">
				<div class="w-full sticky sticky-prevent top-16" id="<?php echo($block_id); ?>-banner">

					<?php
					foreach($terms as $key => $term):

						$class="h-auto visible opacity-100";
						if($key) {
							$class="h-0 invisible opacity-0";
						}
						$card_color = get_field("tax_service_card_color", $term) ?: "electric-green-500";
						$card_icon = get_field("tax_service_card_icon", $term);
						$card_banner_id = get_field("tax_service_card_banner", $term);
						$term_name = $term->name;
						$term_slug = $term->slug;
					?>
						<div class="service flex flex-col gap-8 transition-opacity duration-500 <?php echo($class); ?>" data-id="<?php echo($term_slug); ?>">

							<div class="w-full flex flex-row justify-start items-center gap-x-6 h-16">
								<div class="w-12 h-12 text-<?php echo($card_color); ?>">
									<?php echo( get_svg_icon($card_icon) ); ?>
								</div>
								<h3 class="theme-heading-medium font-normal <?php if($block_text) echo(" text-{$block_text}"); ?>">
									<?php echo($term_name); ?>
								</h3>
							</div>
								
							<?php if($card_banner_id): ?>
								<!-- Banner -->
								<div class="w-full aspect-[1/1] shadow-lg">
									<?php echo(wp_get_attachment_image($card_banner_id, "large", false, ["class" => "w-full h-full object-cover rounded overflow-hidden"])); ?>
								</div>
							<?php endif; ?>
							
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Column with Links -->
			<div class="w-full">
				<div class="w-full flex flex-col gap-y-8" id="<?php echo($block_id); ?>-links">
					<?php
					foreach($terms as $term):

						$term_slug = $term->slug;
						$child_terms = get_terms( [ "taxonomy" => "service", "parent" => $term->term_id, "hide_empty" => false ] );
						$text_color = get_field("tax_service_card_color", $term) ?: "electric-green-500";
					?>
						<div class="service w-full first:mt-24 mt-12 mb-12 last:mb-24 " data-id="<?php echo($term_slug); ?>">
							<!-- Links array -->
							<div class="flex flex-col gap-y-4 <?php if($block_text) echo(" text-{$block_text}"); ?>">
								<?php foreach($child_terms as $child_term):

									//Detect if service should be hidden
									$hide_service = get_field("taxonomy_service_hide_from_service_block", $child_term);
									
									if($hide_service)
										continue;


									$child_url = get_term_link($child_term);
									$child_name = $child_term->name;
								?>
									<a class="font-semibold duration-200 text-white <?php if($text_color) echo(" hover:text-{$text_color}"); ?>" href="<?php echo($child_url); ?>">
										<?php echo($child_name); ?>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Column with Descriptions -->
			<div>
				<div class="flex flex-col sticky sticky-prevent top-16" id="<?php echo($block_id); ?>-desc">
					<?php
					foreach($terms as $key => $term):
						$class="h-auto visible opacity-100";
						if($key) {
							$class="h-0 invisible opacity-0";
						}
						$card_color = get_field("tax_service_card_color", $term) ?: "electric-green-500";
						$card_desc = get_field("tax_service_card_description", $term);
						$term_name = $term->name;
						$term_slug = $term->slug;
					?>
						<div class="service w-full transition-opacity duration-500 <?php echo($class); ?>" data-id="<?php echo($term_slug); ?>">
							<div class="mt-24 flex w-full flex-col">
								<!-- Links array -->
								<div class="service-card-desc text-<?php echo($card_color); ?>">
									<?php echo($card_desc); ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
		
	</div>
</section>