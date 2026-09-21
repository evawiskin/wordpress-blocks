<?php
	if (!isset($args))
		$args = [];

	// Set default args
	$args = array_merge([
		"post_id" => get_the_ID(),
		"taxonomy" => "category",
		"character_threshold_mobile" => 21,
		"character_threshold_desktop" => 35,
		"term_class" => " flex items-center rounded bg-white text-forest-green-500 text-xs px-2 py-2 font-bold block text-center !leading-none whitespace-nowrap"
	], $args);

	$post_id = $args["post_id"];
	$taxonomy = $args["taxonomy"];
	$terms = get_the_terms($post_id, $taxonomy);

	// If no terms found bail
	if ((!is_array($terms)) || !count($terms))
		return;

	// Track total characters used between term names on mobile and desktop
	$total_characters_mobile = 0;
	$total_characters_desktop = 0;
	$character_threshold_mobile = $args["character_threshold_mobile"];
	$character_threshold_desktop = $args["character_threshold_desktop"];

	// Store classes for re-use
	$term_class = $args["term_class"];
?>

<!-- Mobile Display -->
<div 
	style="
		grid-template-columns: <?php foreach($terms as $term) echo("max-content "); ?>;" 
	class="gap-2 flex mt-4 md:mt-0 md:hidden"
>
	<?php 
		foreach ($terms as $index => $term) :
			if ($total_characters_mobile >= $character_threshold_mobile) :
	?>
			<span class="<?php echo($term_class) ?>">
				+ <?php echo(count($terms) - $index) ?> more
			</span>
	<?php
				break;
			endif;
	?>
		<span class="<?php echo($term_class) ?>">
			<span class="-top-px relative">
				<?php echo($term->name) ?>
			</span>
		</span>
	<?php
			$total_characters_mobile += strlen($term->name);
		endforeach;
	?>
</div>


<!-- Desktop Display -->
<div class="hidden gap-2 md:flex flex-row flex-wrap">
	<?php 
		foreach ($terms as $index => $term) :
			if ($total_characters_desktop >= $character_threshold_desktop) :
	?>
			<span class="<?php echo($term_class) ?>">
				+ <?php echo(count($terms) - $index) ?> more
			</span>
	<?php
				break;
			endif;
	?>
		<span class="<?php echo($term_class) ?>">
			<span class="-top-px relative">
				<?php echo($term->name) ?>
			</span>
		</span>
	<?php
			$total_characters_desktop += strlen($term->name);
		endforeach;
	?>
</div>