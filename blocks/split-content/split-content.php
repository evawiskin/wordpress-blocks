<?php 
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
		"hiyield/custom-heading",
		"core/paragraph",
		"hiyield/buttons"
	];

    $block_data = $block;
    $block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";

    $block_image = get_field("block_50_50_image");
    $image_position_field = get_field("block_50_50_direction");
    $image_position = $image_position_field ? "order-last" : "order-first";

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/split-content-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;
?>

<div class="<?php echo($block_bg ?: 'bg-gretter-50'); ?> inside-container-xl">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-5">
            <InnerBlocks  
                class="prose <?php echo($image_position); ?> py-5"
                template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
                allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
            />
            <?php if($block_image): ?>
                <div class="h-full rounded">
                    <?php 
                        echo(
                            wp_get_attachment_image(
                                $block_image,
                                "full",
                                false,
                                [
                                    "class" => "w-full h-full object-cover rounded"
                                ]
                            )
                        )
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>