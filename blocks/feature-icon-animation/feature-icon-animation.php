<?php 
	if ((!isset($block)) || !is_array($block))
		return;

	$block_bg = isset($block["backgroundColor"]) ? "bg-{$block["backgroundColor"]}" : "bg-transparent";
	$block_text = isset($block["textColor"]) ? "text-{$block["textColor"]}" : "text-black";
	$block_class = "{$block_bg} {$block_text}";

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

	$allowed_blocks = [
		"core/heading",
		"core/paragraph",
		"hiyield/buttons",
	];

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/feature-icon-animation-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<section class="<?php echo($block_class); ?>">

	<?php if (array_key_exists("anchor", $block))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>

	<div class="container inside-container-xl items-end gap-x-10 md:flex">

		<!-- Animated Icons -->
		<div class="self-stretch md:w-1/3">

			<div class="flex h-full items-end overflow-hidden mb-10 md:mb-0 md:justify-center">

				<!-- Asterisk 1 -->
				<div class="h-full">
					<?php if ($asterisk_icon = get_svg_icon("asterisk", "hiyield-icons")) : ?>
						<span class="block mb-2 asterisk-rotate-linear w-12 md:w-24"><?php echo($asterisk_icon); ?></span>
						<span class="block w-5 h-4/5 mx-auto rounded-sm min-h-16 md:min-h-32 <?php echo(str_replace("text-", "bg-", $block_text)) ?>"></span>
					<?php endif; ?>
				</div>

				<!-- Asterisk 2 -->
				<div class="h-full flex flex-col justify-end">
					<?php if ($asterisk_icon = get_svg_icon("asterisk", "hiyield-icons")) : ?>
						<span class="block mb-2 asterisk-rotate-linear w-12 md:w-24"><?php echo($asterisk_icon); ?></span>
						<span class="block w-5 h-1/4 min-h-8 mx-auto rounded-sm md:min-h-16 <?php echo(str_replace("text-", "bg-", $block_text)) ?>"></span>
					<?php endif; ?>
				</div>
		
			</div>
		
		</div>

		<!-- Inner Blocks -->
		<div class="max-w-2xl md:w-2/3">
			<InnerBlocks  
				class="prose" 
				template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
				allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
			/>
		</div>
	</div>

</section>