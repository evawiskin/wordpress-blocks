<?php
if ((!isset($block)) || !is_array($block)) {
    return;
}

// Render block preview image.
if (display_block_preview_image($block)) {
    return;
}

$block_id = set_block_id($block);
$block_classes = new BlockClasses($block);

$allowed_blocks = ["hiyield/custom-list-item"];
$template = [
    ["hiyield/custom-list-item"],
    ["hiyield/custom-list-item"],
    ["hiyield/custom-list-item"],
];
?>
<ul id="<?php echo esc_attr($block_id); ?>" class="w-full hy-custom-list <?php echo esc_attr($block_classes); ?>">
    <InnerBlocks
        allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
        template="<?php echo esc_attr(wp_json_encode($template)); ?>"
    />
</ul>

<?php if (is_admin()) : ?>
    <style>
        :where(.editor-styles-wrapper) .wp-block-hiyield-custom-list {
            width: 100%;
        }
    </style>
<?php endif; ?>