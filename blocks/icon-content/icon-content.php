<?php
	if ((!isset($block)) || !is_array($block))
		return;

	// Render block preview image.
	if (display_block_preview_image($block)) return;

	$block_id = set_block_id($block);
	$block_classes = new BlockClasses($block);
    $allowed_blocks = [
        "core/heading",
		"core/paragraph",
        "hiyield/icon"
    ];

	$gap = get_field("block_icon_content_gap") ?: "gap-10";
	$wrap_icon = get_field("block_icon_content_wrap_icon");

	$template = $block["template"] ?? [
        [
			"hiyield/icon",
			[
				"style" => [
					"spacing" => [
						"margin" => [
							"bottom" => "0",
						],
					]
				]
			]
		],
		[
			"core/heading",
			[
				"level" => 3,
				"placeholder" => "Heading Goes hi",
				"fontSize" => "text-hy-2xl",
				"style" => [
					"spacing" => [
						"margin" => [
							"bottom" => "0",
						]
					]
				]
			],
		],
	];

?>

<div id="<?php echo($block_id); ?>" class="hy-icon-content w-full <?php echo($wrap_icon ? '' : 'flex items-center'); ?> <?php echo($block_classes); ?>">
    <InnerBlocks 
        class="prose <?php echo($wrap_icon ? '' : 'flex items-center'); ?> <?php echo($gap); ?> w-full <?php echo($block_classes->justify); ?>" 
        template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
        allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
    />
</div>

<?php if ($wrap_icon) : ?>
<style>
    #<?php echo($block_id); ?> .prose {
        display: inline;
    }
    #<?php echo($block_id); ?> .prose > .wp-block {
        display: contents !important;
    }
    #<?php echo($block_id); ?> h1,
    #<?php echo($block_id); ?> h2,
    #<?php echo($block_id); ?> h3,
    #<?php echo($block_id); ?> h4,
    #<?php echo($block_id); ?> h5,
    #<?php echo($block_id); ?> h6,
	#<?php echo($block_id); ?> p{
        display: inline !important;
    }
    #<?php echo($block_id); ?> div[id*="icon_block"] {
        display: inline-flex !important;
        vertical-align: baseline;
        align-items: center;
		margin-left: 4px;
    }
    #<?php echo($block_id); ?> div[id*="icon_block"] span {
        display: inline-flex !important;
    }
    #<?php echo($block_id); ?> div[id*="icon_block"] svg {
        display: block;
        width: 100%;
        height: 100%;
    }
</style>
<?php endif; ?>