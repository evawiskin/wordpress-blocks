/**
 * Used to call WP Hooks for Blocks
 * https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/
 */

const {addFilter} = wp.hooks;

function AddParentToCoreImageBlock(settings, name) {
    if (name !== "core/image") {
        return settings;
    }
    return {
        ...settings, 
        parent: ["hiyield/image-wrapper"]
    };
}

wp.hooks.addFilter(
    "blocks.registerBlockType",
    "hiyield/image-block-wrapper",
    AddParentToCoreImageBlock
);