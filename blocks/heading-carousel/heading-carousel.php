<?php
	$allowed_blocks = ["core/heading"];
	$template = [
		[
			"core/heading",
			[
				"level" => 1,
				"textColor" => "white",
				"fontSize" => "huge-heading",
				"placeholder" => "Heading Goes Here"
			]
		],
	];

	$position = "prepend";
	if(get_field("block_heading_carousel_append") == true)
		$position = "append";

	$heading_carousel_items = [];
	while(have_rows("heading_carousel_items")){
		the_row();
		$heading_carousel_items[] = get_sub_field("heading_carousel_item");
	}

	$total_items = count($heading_carousel_items);
	
	$block_id = $block["id"];

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/heading-carousel-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>
<div 
	class="mb-4 last:mb-0 <?php echo("js-heading-carousel-{$block_id}"); ?>"
	data-position="<?php echo($position); ?>"
	data-total-items="<?php echo($total_items); ?>"
	<?php foreach($heading_carousel_items as $key => $item): ?>
		data-heading-carousel-item-<?php echo($key); ?>="<?php echo(esc_attr($item)); ?>"
	<?php endforeach; ?>
>
	<InnerBlocks  
		class="js-heading-carousel"
		template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
	/>

	<?php 
		// if in the admin then we don't want to run the script or it'll duplicate the heading
		if(!is_admin()):
	?>

<script>
	document.addEventListener("DOMContentLoaded", () => {
		const block = document.querySelector(".js-heading-carousel-<?php echo($block_id); ?>");
		const position = block.dataset.position;
		const totalItems = block.dataset.totalItems;
		const items = [];
		const heading = block.querySelector(".js-heading-carousel").children[0];
		const headingText = heading.innerHTML;
		for (let i = 0; i < totalItems; i++)
				items.push(block.getAttribute(`data-heading-carousel-item-${i}`));
		const itemClasses = "js-heading-carousel-item transition-opacity duration-300 md:whitespace-nowrap md:inline-flex"; 


		// If the first item is in the heading, wrap it with a span for transition
		const firstItemIndex = headingText.indexOf(items[0]);

		setTimeout(() => {
			// if we successfully found the first item in the heading text
			if (firstItemIndex !== -1) {
				// wrap a span around it as this will now be the first item
				heading.innerHTML = headingText.substring(0, firstItemIndex) +
				`<span class="${itemClasses} opacity-0">${items[0]}</span>` +
				headingText.substring(firstItemIndex + items[0].length);
			} else{
				// if we didn't find the first item in the heading text, just set the first item as the innerHTML
				heading.innerHTML = `<span class="${itemClasses} opacity-0">${items[0]}</span>`;
			}
		}, 5000)

		setTimeout(() => {

			// select the span
			const firstItemSpan = heading.querySelector("span");

			// set the innerHTML of the span to the first item
			firstItemSpan.innerHTML = items[1]

			// remove the opacity on the span
			setTimeout(() => {
				firstItemSpan.classList.remove("opacity-0");
			}, 300);
			
			const item = block.querySelector(".js-heading-carousel-item");

			// Set the initial item to the second item in the array on initial load
			// as the first item is already written inline in the heading
			// Change the item every 5 seconds
			let currentItemNumber = 1; // - the first value will be shown when we cycle through the carousel as we reset back to 0 in the interval
			setInterval(() => {
				currentItemNumber++;
				// If we've reached the end of the array, reset to 0
				if (currentItemNumber >= totalItems)
					currentItemNumber = 0;
				// Fade out the item
				item.classList.add("opacity-0");
				// Wait 300ms for the opacity transition to finish
				setTimeout(() => {
						item.innerHTML = items[currentItemNumber];
						item.classList.remove("opacity-0");
				}, 300);
			}, 5000);

		}, 5000)
	});
</script>


	<?php endif; ?>

</div>
