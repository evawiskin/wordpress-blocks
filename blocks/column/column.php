<?php
	$block_data = $block;
	$block_id = str_replace("_", "-", $block_data["id"]);
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$content_alignment = ($block_data && array_key_exists("alignContent", $block_data)) ? $block_data["alignContent"] : ""; // Top, center, bottom
	$content_alignment = ($content_alignment == "top") ? "start" : (($content_alignment == "center") ? "center" : "end");
	$column_span = (get_field("block_column_span")) ? get_field("block_column_span") : "1";
	$mobile_order = (get_field("block_column_mobile_order")) ? "order-first lg:order-none" : "";
?>

<div 
	class="flex items-<?php echo($content_alignment); ?>
		<?php 
			if($block_bg) echo("bg-{$block_bg}"); 
			if($block_text) echo(" text-{$block_text}"); 
			if($column_span > 1) echo("col-span-1 lg:col-span-{$column_span}");
			if($mobile_order) echo(" {$mobile_order}");
		?>
	">
	<?php 
		if (array_key_exists("anchor", $block_data)) 
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
		
	?>
	<InnerBlocks 
		class="column prose"
	/>
</div>

<?php if($column_span > 1): ?>
	<script>
		const columnSpan = <?php echo($column_span); ?>;
		const blockId = "<?php echo($block_id); ?>";
		window.onload = function(){
			const columnWrapper = document.getElementById(blockId);
			if(columnWrapper)
				columnWrapper.dataset.columnSpan = columnSpan;
		};
	</script>
<?php endif; ?>