<?php

	$id = $block["id"];
	$label = get_field("block_tab_label");
	
?>

<!-- wp:tab {"id":"<?php echo $id ?>", "label": "<?php echo $label ?>"} -->
<div id="<?php echo($id); ?>" class="tab-body transition-all">
	<InnerBlocks />
</div>
<!-- /wp:tab -->