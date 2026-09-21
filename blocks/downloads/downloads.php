<?php

$block_id = set_block_id($block);
$block_classes = new BlockClasses($block);

$allowed_blocks = get_allowed_blocks();
$template = [
	[
		"core/heading",
		[
			"placeholder" => "Enter your heading here",
			"style" => [
				"elements" => [
					"link" => [
						"color" => [
							"text" => "var:preset|color|electric-green-500",
						],
					],
				],
				"spacing" => [
					"margin" => [
						"bottom" => "var:preset|spacing|8px",
					],
				],
			],
			"textColor" => "electric-green-500",
			"fontSize" => "small-heading",
		]
	],
	[
		"core/paragraph",
		[
			"placeholder" => "Enter your paragraph here",
			"style" => [
				"typography" => [
					"fontStyle" => "normal",
					"fontWeight" => "600",
				],
				"spacing" => [
					"margin" => [
						"bottom" => "var:preset|spacing|32px",
					],
				],
			],
		]
	],
	[
		"hiyield/buttons",
		[]
	]
];

?>

<section
	id="<?php echo($block_id); ?>"
	class="<?php echo($block_classes); ?>"
>
	<div class="container grid-design">
		<InnerBlocks
			class="col-span-full"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>
	</div>
</section>
