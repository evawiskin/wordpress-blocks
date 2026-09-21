<?php

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
				"placeholder" => "Paragraph text goes here, click the plus button to choose a inner block to add to this container.",
				"fontSize" => "xl"
			]
		]
	];

	$id = $block["id"];
	$block_bg = ($block && array_key_exists("backgroundColor", $block)) ? $block["backgroundColor"] : "";
	$img = get_field("block_content_slider_item_image");

	
?>

<div id="<?php echo($id); ?>" class="swiper-slide w-full rounded !h-auto <?php if($block_bg) echo("bg-{$block_bg}"); ?>">

	<div class="w-full h-full flex flex-col lg:flex-row items-center lg:items-end <?php if(is_admin()) echo("!h-auto"); ?>">

		<!-- Mobile Asterisk -->
		<div class="block lg:hidden absolute -top-32 -right-32 w-80 z-0 text-electric-green-500 opacity-20 pointer-events-none">
			<?php echo(get_svg_icon("hiyield-asterisk", "hiyield-icons")); ?>
		</div>

		<InnerBlocks
			class="z-10 prose w-full h-full flex flex-col px-4 lg:px-16 pt-12 lg:pt-24 pb-12 lg:pb-24 self-end justify-self-end links-with-arrow-icon"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>

		<div class="hidden lg:flex flex-col items-end justify-end w-full lg:w-2/5 min-w-2/5 mt-0 lg:mt-24 px-8 lg:px-0">
			<div class="w-full aspect-w-4 aspect-h-3">
				<?php echo(wp_get_attachment_image($img, "medium_large", false, ["class" => "w-full h-full object-contain object-right-bottom"])); ?>
			</div>
		</div>

	</div>


	
</div>