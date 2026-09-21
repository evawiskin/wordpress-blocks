<?php
	if ((!isset($block)) || !is_array($block)) {
        return;
    }

    // Render block preview image.
    if (display_block_preview_image($block)) {
        return;
    }

	swiper_enqueue_scripts();

	$allowed_blocks = get_allowed_blocks();
	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here",
				"textAlign" => "center"
			]
		],
		[
			"core/paragraph",
			[
				"placeholder" => "Paragraph text goes here, click the plus button to choose an inner block to add to this container.",
				"fontSize" => "xl"
			]
		]
	];
	$block_id = set_block_id($block);
	$block_classes = new BlockClasses($block);
	$swiper_nav_button_class = "py-2 px-1 text-white bg-white/10 backdrop-blur border border-gray-300 rounded hover:bg-white hover:text-forest-green-500 transition-colors duration-200 disabled:pointer-events-none disabled:opacity-50 md:py-5 md:px-3";

	// Get client images and determine source based on selection mode
	$client_images =
		get_field("block_clients_select_mode") ?
		array_map(function($term_id) {
			$term = get_term($term_id);
			if ($term instanceof WP_Term) {
				return get_field("taxonomy_client_image", "term_{$term_id}") ?: $term->name;
			}
		}, get_field("block_clients_terms") ?: []) :
		array_column(get_field("block_clients_repeater") ?: [], "block_clients_repeater_image");

	// Filter out null values
	$client_images = array_filter($client_images);
	$is_admin = is_admin();

	// Dynamically set number of columns based on number of client images up to 4 columns
	if (count($client_images) > 1) {
		$desktop_cols = count($client_images) <= 4 ? count($client_images) - 1 : 4;
		$tablet_cols = count($client_images) <= 3 ? count($client_images) - 1 : 3;
		$mobile_cols = count($client_images) <= 2 ? count($client_images) - 1 : 2;
	} else {
		$desktop_cols = 1;
		$tablet_cols = 1;
		$mobile_cols = 1;
	}
?>
<section class="inside-container-lg <?php echo($block_classes); ?>">
	<?php do_action("hy_block_start", $block); ?>	
	<div class="container">
		<InnerBlocks 
			class="prose mb-5 md:mb-10" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>

		<div class="flex items-center  justify-between gap-x-8 md:gap-x-16">
			<button 
				class="<?php echo($swiper_nav_button_class); ?>" 
				id="swiper-prev-clients-swiper-<?php echo($block_id); ?>"
				aria-label="View previous selection of client logos"
			>
				<span class="block size-3 md:size-4"><?php echo(get_svg_icon("chevron-left")); ?></span>
			</button>
			<div 
				class="container swiper flex items-center justify-center flex-nowrap" 
				id="clients-swiper-<?php echo($block_id); ?>" 
				data-pagination="false" 
				data-navigation="true" 
				data-loop="true"
				data-mobilecol="<?php echo($mobile_cols); ?>" 
				data-mobilegap="10" 
				data-tabletcol="<?php echo($tablet_cols); ?>" 
				data-tabletgap="15" 
				data-desktopcol="<?php echo($desktop_cols); ?>" 
				data-slides-count="<?php echo(count($client_images)); ?>"
			>
				<div class="swiper-wrapper <?php if($is_admin) echo("flex gap-4 overflow-x-auto"); ?>">
					<?php if (!empty($client_images)) : ?>
						<?php foreach ($client_images as $image_id) : ?>
							<div 
								class="swiper-slide grid items-start justify-start w-auto shrink-0"
								<?php if ($is_admin) : // Simulate front-end view but allow overflow scroll to see whats added ?>
									style="justify-content: center; flex-basis: calc((100% / <?php echo($desktop_cols); ?>) - 1rem);"
								<?php endif; ?>
							>
							<?php
								if (is_int($image_id)) :
									echo(
										get_attachment_image_or_contents(
											$image_id, 
											"small", 
											false, 
											["class" => "w-full !h-24 text-current object-contain"]
										)
									);
								else :
								?>
									<p class="mx-12"><?php echo(esc_html($image_id)); ?></p>
								<?php
								endif;
							?>
							</div>
						<?php endforeach; ?>
					<?php
						elseif ($is_admin) :
							get_template_part("template-parts/utility/block-admin-message", null, [
								"message" => "Select images to begin"
							]);
						endif;
					?>
				</div>
			</div>
			<button 
				class="<?php echo($swiper_nav_button_class); ?>" 
				id="swiper-next-clients-swiper-<?php echo($block_id); ?>"
				aria-label="View next selection of client logos"
			>
				<span class="block size-3 md:size-4"><?php echo(get_svg_icon("chevron-right")); ?></span>
			</button>
		</div>
	</div>
</section>