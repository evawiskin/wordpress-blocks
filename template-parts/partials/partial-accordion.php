<?php

	//check we have the minimum args that are needed to make an accordion passed in
	if ((!$args) || 
		!array_key_exists("accordion_count", $args) ||
		!array_key_exists("accordion_title", $args) ||
		!array_key_exists("accordion_content", $args)
		)
		return;
	$accordion_counter = $args["accordion_count"];
	$block_bg = array_key_exists("block_bg", $args) ? $args["block_bg"] : "";
	$block_text = array_key_exists("block_text", $args) ? $args["block_text"] : "";
	
	// Determine text color based on block background and text settings
	if ($block_text) {
		$text_color = $block_text;
		$button_text_classes = "text-{$block_text} hover:opacity-80";
	} else {
		$text_color = "text-electric-green-600";
		$button_text_classes = "text-electric-green-600 hover:opacity-80";
	}

	// make focus ring offset class match to background color
	if($block_bg){
		$button_text_classes .= " focus-within:ring-offset-{$block_bg}";
		$block_ring_color = "ring-{$text_color}";
	} else {
		$button_text_classes .= " focus-within:ring-offset-forest-green-600";
		$block_ring_color = "ring-gretter-50";
	}


?>
<div class="accordion-group border-b border-forest-green-500/10 mb-8 rounded-sm">
	<div>
		<button id="accordion-<?php echo($accordion_counter); ?>" 
				aria-controls="accordion-panel-<?php echo($accordion_counter); ?>"
				aria-expanded="false"
				class="
					flex flex-wrap items-center justify-between 
					w-full pb-8
					text-left
					<?php echo $button_text_classes; ?>
					transition-colors accordion-toggle
					focus:!outline-none
					focus-visible:ring-2 focus-visible:ring-offset-8
				">
				<h3 class="flex-1 has-font-area-normal-font-family !font-bold tracking-[0.0125em] text-base">
					<?php echo($args["accordion_title"]); ?>
				</h3>
			<?php
			
				$accordion_icon = get_theme_file_path("assets/src/imgs/feather-icons/chevron-down.svg");
				if(file_exists($accordion_icon)) : ?>
					<span class="block w-6 h-6 ml-4 transition transform accordion-icon">
						<?php echo(file_get_contents($accordion_icon)); ?>
					</span>
				<?php endif; ?>
		</button>
	</div>
	<div id="accordion-panel-<?php echo($accordion_counter); ?>" 
		class="overflow-hidden transition-all duration-300 max-h-0 accordion-body " 
		aria-labelledby="accordion-<?php echo($accordion_counter); ?>">
		<div class="pb-8 text-<?php echo $text_color; ?> prose">
			<?php echo($args["accordion_content"]); ?>
		</div>
	</div>
</div>