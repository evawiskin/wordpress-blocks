<?php

if ((!isset($block)) || !is_array($block)) {
    return;
}

// Render block preview image.
if (display_block_preview_image($block)) {
    return;
}

$block_id = set_block_id($block);
$allowed_blocks = get_allowed_blocks(["hiyield/tile"]);

// Use BlockClasses system
$block_classes = new BlockClasses($block);


// Template
$template = [
    [
        "core/heading",
        [
            "placeholder" => "Column Heading",
            "fontSize" => "text-hy-5xl"
        ]
    ],
    [
        "core/paragraph",
        [
            "placeholder" => "Column content goes here. You can add text, images, buttons, and more.",
            "fontSize" => "text-hy-lg"
        ]
    ]
];


// Block Context to get Column Count
$column_count = 2; // Default fallback
if (isset($context["acf/fields"]["block_columns_count"]) && $context["acf/fields"]["block_columns_count"]) {
    $column_count = $context["acf/fields"]["block_columns_count"];
} elseif (isset($context["acf/fields"]["block_columns_count_key"]) && $context["acf/fields"]["block_columns_count_key"]) {
    $column_count = $context["acf/fields"]["block_columns_count_key"];
}
// Get Column Span Override if set
$column_span_override = get_field("block_column_span");

// Use the column count and any override to set the column span class
if ($column_span_override) {
	switch($column_span_override) {
		case "3":
			$numeric_span = 3;
			$column_span_class = "lg:col-span-3";
			break;
		case "4":
			$numeric_span = 4;
			$column_span_class = "lg:col-span-4";
			break;
		case "5":
			$numeric_span = 5;
			$column_span_class = "lg:col-span-5";
			break;
		case "7":
			$numeric_span = 7;
			$column_span_class = "lg:col-span-7";
			break;
		case "8":
			$numeric_span = 8;
			$column_span_class = "lg:col-span-8";
			break;
		case "9":
			$numeric_span = 9;
			$column_span_class = "lg:col-span-9";
			break;
		case "12":
			$numeric_span = 12;
			$column_span_class = "lg:col-span-12";
			break;
		default:
			$numeric_span = 6;
			$column_span_class = "lg:col-span-6";
			break;
	}
} else {
	switch($column_count) {
		case "2":
			$numeric_span = 6;
			$column_span_class = "lg:col-span-6";
			break;
		case "3":
			$numeric_span = 4;
			$column_span_class = "lg:col-span-4";
			break;
		case "4":
			$numeric_span = 3;
			$column_span_class = "lg:col-span-3";
			break;
		default:
			$numeric_span = 6;
			$column_span_class = "lg:col-span-6";
			break;
	}
}

// Start Class - Places the column in a specific grid column location.
$column_start = get_field("block_column_start", $block["id"]);
$start_class = ($column_start && intval($column_start) >= 1 && intval($column_start) <= 10) ? "lg:col-start-" . intval($column_start) : "";

// In some situations we might want to modify the styling based on whether the block has a background color set.
if ($block && array_key_exists("backgroundColor", $block) && !empty($block["backgroundColor"])) {
	$bg_classes = "rounded-2xl";
} else {
	$bg_classes = "";
}

// Only add h-full if items alignment is not items-start
$height_class = ($block_classes->items == "items-start") ? "h-full" : "";

?>
	<div 
	class="flex h-full <?php echo($block_classes); ?> <?php echo($column_span_class); ?> <?php echo($start_class); ?> <?php echo($bg_classes); ?>"
	data-column-span="<?php echo($numeric_span); ?>"
	data-column-start="<?php echo($column_start); ?>"
>
	<InnerBlocks 
		class="column <?php echo($height_class); ?> flex-1 prose w-full <?php echo($block_classes->items); ?>"
		template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
	/>
</div>