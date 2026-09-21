<?php 
    if ((!isset($block)) || !is_array($block)) {
        $block = [];
    }
    $block_classes = new BlockClasses($block);
?>
<div class="<?php echo($block_classes); ?>">
    <?php 
        if(array_key_exists("anchor", $block))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
	?>	
    <?php get_template_part("template-parts/components/template-part", "address-list", [ "container_class" => "grid-cols-1 gap-10 md:grid-cols-3" ]); ?>
</div>