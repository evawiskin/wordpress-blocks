/**
 * Scripts to run when in WordPress Gutenberg editor
 * 
 * Unregister any block styles we don't want user to be able to select
 * that have been registered via js so can't be unregisterd via php
 */
wp.domReady( () => {

    const allowedEmbedBlocks = [
        "vimeo",
        "youtube",
    ];
	wp.blocks.getBlockVariations("core/embed").forEach(function (blockVariation) {
		if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
			wp.blocks.unregisterBlockVariation("core/embed", blockVariation.name);
		}
	});

    // Button
    wp.blocks.unregisterBlockStyle("core/button", "fill");
    wp.blocks.unregisterBlockStyle("core/button", "outline");
    // Separator
    wp.blocks.unregisterBlockStyle("core/separator", "default");
    wp.blocks.unregisterBlockStyle("core/separator", "wide");
    wp.blocks.unregisterBlockStyle("core/separator", "dots");
    // Image
    wp.blocks.unregisterBlockStyle("core/image", "default");
    wp.blocks.unregisterBlockStyle("core/image", "rounded");
    // Quote
    wp.blocks.unregisterBlockStyle("core/quote", "default");
    wp.blocks.unregisterBlockStyle("core/quote", "plain");
    // Table
    wp.blocks.unregisterBlockStyle("core/table", "regular");
    wp.blocks.unregisterBlockStyle("core/table", "stripes");
    // Tag Cloud
    // wp.blocks.unregisterBlockStyle("core/tag-cloud", "default");
    // wp.blocks.unregisterBlockStyle("core/tag-cloud", "outline");
    // Site logo
    // wp.blocks.unregisterBlockStyle("core/site-logo", "default");
    // wp.blocks.unregisterBlockStyle("core/site-logo", "rounded");
    // Social Links
    // wp.blocks.unregisterBlockStyle("core/social-links", "default");
    // wp.blocks.unregisterBlockStyle("core/social-links", "logos-only");
    // wp.blocks.unregisterBlockStyle("core/social-links", "pill-shape");
    
});