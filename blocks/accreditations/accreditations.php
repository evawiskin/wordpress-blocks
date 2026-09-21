<?php 
	/*
		This is the accreditation-cards block. 

		The configuration for this block supports:
			- Background Color
			- Text Color
			- Anchor
		It also allows for Inner Blocks via JSX.

		In this block we will loop over each team and output their profile.
		The profile we output will use the template part "cards/template-part-person-card.php".
		This template part takes in the following arguments:
			- post_id
			- allow_click
			- flex_direction
	*/

	// Placeholder content when the block loads in the editor.
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

	// Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = get_allowed_blocks();

	// Gather our loaded block data
	$block_id = set_block_id($block);
	$block_classes = new BlockClasses($block, "relative");

	// SVG Assets
	$svg_hiyield_asterisk_path = get_theme_file_path("assets/src/imgs/hiyield-icons/hiyield-asterisk.svg");
	$svg_hiyield_asterisk = file_exists($svg_hiyield_asterisk_path) ? file_get_contents($svg_hiyield_asterisk_path) : "*";

	// wordpress loop for the accreditation cards
	$handpick_accreditations = get_field("block_accreditations_handpick_accreditations");
	$accreditation_ids =
		$handpick_accreditations ?
		get_field("block_accreditations_accreditation_posts") :
		get_posts([
			"post_type" => "accreditation",
			"post_status" => "publish",
			"posts_per_page" => -1,
			"orderby" => "menu_order",
			"order" => "ASC",
			"fields" => "ids"
		]);

	// Show block preview image
	if (get_field("is_preview")) : ?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/accreditations-block.jpg")) ?>" width="100%">
		<?php
		return;
	endif;

	// Get variation
	$variation = get_field("block_accreditations_variation");
	$template_part = match($variation) {
		"hover-card" => "accreditation-card-hover",
		"hero-card"  => "accreditation-card-hero",
		default      => "accreditation-card-fusion",
	};

	$slides_per_view = get_field("block_accreditations_slides_per_view") ?: 4;
	$wrapped_layout = get_field("block_accreditations_wrapped_layout");
	$swiper_nav_button_class = "py-2 px-1 shrink-0 text-white bg-forest-green-500 rounded hover:bg-forest-green-600 transition-colors duration-200 disabled:pointer-events-none disabled:opacity-50 md:py-5 md:px-3";

	//enqueue swiper
	swiper_enqueue_scripts();

?>

<!--- Define the block wrapper complete with it's background and text colour definitions --->
<section 
	id="<?php echo($block_id); ?>"
	class="<?php echo($block_classes); ?>"
>
	<?php 
		// Start off with an anchor template if one is in use
		if(array_key_exists("anchor", $block))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>

	<div class="container">
		<!--- Heading and Leading --->
		<InnerBlocks  
			class="<?php echo(esc_attr("prose" . ($wrapped_layout ? " flex flex-wrap items-center gap-8" : ""))); ?>"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>

		<!--- Cards Swiper --->
		<?php if (empty($accreditation_ids) && is_admin()) : ?>
			<p class="py-20 text-center"><?php echo($handpick_accreditations ? "Choose Accreditations to begin" : "You must publish at least one Accreditation to use this block"); ?></p>
		<?php else : ?>

			<div class="flex items-center justify-between gap-x-8 md:gap-x-16 mt-8 invisible opacity-0">
				<button
					class="<?php echo($swiper_nav_button_class); ?>"
					id="swiper-prev-<?php echo $block_id; ?>_swiper"
					aria-label="View previous accreditations"
				>
					<span class="block size-3 md:size-4"><?php echo(get_svg_icon("chevron-left")); ?></span>
				</button>

				<div
					class="swiper overflow-hidden"
					id="<?php echo $block_id; ?>_swiper"
					data-mobilecol="<?php echo($variation === "hero-card" ? 1 : 2); ?>"
					data-mobilegap="<?php echo($variation === "hero-card" ? 20 : 32); ?>"
					data-tabletcol="2"
					data-tabletgap="<?php echo($variation === "hero-card" ? 20 : 32); ?>"
					<?php if ($variation === "hero-card") : ?>
					data-desktopcol="<?php echo(max(1, $slides_per_view - 2)); ?>"
					data-desktopgap="<?php echo($variation === "hero-card" ? 30 : 64); ?>"
					data-xlcol="<?php echo($slides_per_view); ?>"
					data-xlgap="30"
					<?php else : ?>
					data-desktopcol="<?php echo($slides_per_view); ?>"
					data-desktopgap="64"
					<?php endif; ?>
					data-autoplay="false"
					data-navigation="true"
					data-slides-count="<?php echo(count($accreditation_ids)); ?>"
				>
					<div class="swiper-wrapper" data-swiper-disabled-class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-14">
						<?php
							// loop over each accreditation
							foreach($accreditation_ids as $accreditation_id) :
								// output the accreditation card
								get_template_part("template-parts/cards/template-part", $template_part, $args = ["accreditation_id" => $accreditation_id]);
							endforeach;

							// reset the post data
							wp_reset_postdata();
						?>
					</div>
				</div>

				<button
					class="<?php echo($swiper_nav_button_class); ?>"
					id="swiper-next-<?php echo $block_id; ?>_swiper"
					aria-label="View next accreditations"
				>
					<span class="block size-3 md:size-4"><?php echo(get_svg_icon("chevron-right")); ?></span>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>