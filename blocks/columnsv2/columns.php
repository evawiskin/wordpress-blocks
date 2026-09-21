<?php

	if ((!isset($block)) || !is_array($block))
		return;

	// Render block preview image.
	if (display_block_preview_image($block)) return;
	

	$allowed_blocks = [
		"hiyield/column2"
	];

	$block_id = set_block_id($block);

	// Use BlockClasses system
	$block_classes = new BlockClasses($block, "relative");

	$template = [];

	// Columns count on Desktop - passed to sub column blocks via ProvidesContext in block.json
	$column_count = (get_field("block_columns_count")) ? get_field("block_columns_count") : "2";


	for($i = 0; $i < $column_count; $i++) {
		$template[] = [
			"hiyield/column2"
		];
	}
?>

<section 
	id="<?php echo($block_id); ?>" 
	class="<?php echo($block_classes); ?>"
>
	<?php 
		do_action("hy_block_start", $block); 
		do_action("hy_columns_block_start", $block);
	?>

	<div class="container relative z-10">
		<InnerBlocks 
			class="grid grid-cols-1 lg:grid-cols-12 gap-8 <?php echo($block_classes->items); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>
	</div>
</section>
<?php 
do_action("hy_columns_after_block_end", $block); // Background media (editor)

if(is_admin()): ?>
	<style>
		/* Target the .wp-block parent based on child's data attribute using :has() selector */
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="1"]) { grid-column: span 1 / span 1 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="2"]) { grid-column: span 2 / span 2 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="3"]) { grid-column: span 3 / span 3 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="4"]) { grid-column: span 4 / span 4 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="5"]) { grid-column: span 5 / span 5 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="6"]) { grid-column: span 6 / span 6 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="7"]) { grid-column: span 7 / span 7 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="8"]) { grid-column: span 8 / span 8 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="9"]) { grid-column: span 9 / span 9 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="10"]) { grid-column: span 10 / span 10 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="11"]) { grid-column: span 11 / span 11 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-span="12"]) { grid-column: span 12 / span 12 !important; }
		
		/* Column start positions using :has() */
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="1"]) { grid-column-start: 1 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="2"]) { grid-column-start: 2 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="3"]) { grid-column-start: 3 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="4"]) { grid-column-start: 4 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="5"]) { grid-column-start: 5 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="6"]) { grid-column-start: 6 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="7"]) { grid-column-start: 7 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="8"]) { grid-column-start: 8 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="9"]) { grid-column-start: 9 !important; }
		#<?php echo($block_id); ?> .wp-block:has([data-column-start="10"]) { grid-column-start: 10 !important; }
	</style>
<?php endif; 