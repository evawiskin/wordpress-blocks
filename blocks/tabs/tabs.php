<?php

    // Enqueue tabs js file
    tabs_enqueue_scripts();

	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"hiyield/tab"
	];

    $block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

    $section_heading = get_field("block_tabs_heading");
    $layout = get_field("block_tabs_layout") ? get_field("block_tabs_layout") : "show";

    // Check for child blocks in block content
    // Function works only with blocks wrapped in special comment, see tab.php
    $inner_blocks = parse_blocks( $content );

    // Create empty blocks collection
    $buttons = [];
    $i=0;

    // Walk through blocks and fill out buttons collection
    foreach ( $inner_blocks as $inner_block ) {
        if($inner_block["blockName"] == "core/tab") {
            $buttons[$i]["id"] = $inner_block["attrs"]["id"];
            $buttons[$i]["label"] = $inner_block["attrs"]["label"];
            $i++;
        }
    }

    $nav_classes = "border-solid border-b-2 border-gray-100 bg-white sticky top-24 z-20 overflow-hidden";
    if($layout == "hide") {
        $nav_classes = "border-solid border-b-2 border-gray-100 bg-white";
    }

?>

<section
    id="<?php echo($block["id"]); ?>"
    class="
        tabs-wrapper inside-container-sm
        <?php echo($layout); ?>
        <?php if($block_text_center) echo("text-center "); echo("{$block_spacing_classes} ");
        if($block_bg) echo("bg-{$block_bg}"); if($block_text) echo(" text-{$block_text}"); ?>"
        data-layout="<?php echo($layout); ?>"
    >

    <?php if($section_heading): ?>
        <div class="container relative text-center mb-8">
            <h3><?php echo($section_heading); ?></h3>
        </div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <div class="<?php echo($nav_classes); ?>">
        <div class="container flex justify-center">
            <?php foreach( $buttons as $button): ?>
                    <a
                        data-for="<?php echo($button["id"]); ?>"
                        class="tab-trigger transition-all !text-black font-bold px-6 pb-2 pt-4 border-solid border-b-4 border-transparent"
                        href="#"
                    >
                        <?php echo($button["label"]); ?>
                    </a>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Tabs Body -->
	<div class="container py-8">
		<InnerBlocks
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>
        </div>
</section>