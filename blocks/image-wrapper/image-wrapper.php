<?php
	$block_id = set_block_id($block);
	$block_classes = new BlockClasses($block);

	$allowed_blocks = ["core/image"];
	$template = [["core/image"]];
?>
	
<?php if (get_field("is_preview")): ?> 
	<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/image-wrapper-block.jpg")) ?>" width="100%">
<?php return; endif; ?>

<section
	id="<?php echo($block_id); ?>"
	class="<?php echo($block_classes); ?>"
>
	<?php if(array_key_exists("anchor", $block))
		get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>
	
	<InnerBlocks  
		class="container wp-block-image-wrapper"
		allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
	/>
</section>
