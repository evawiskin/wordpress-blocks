<?php
	/* 
		Usage: Simply pull in this template part on any page where you want to display the reading progress.
		You'll need to decide if you want a horizontal or circular reading progress bar.
		And then style it accordingly. It's just floating in a fixed position in this demonstration either on the left or at the top
	*/

	/* $args are required! */
	if (!$args || !array_key_exists("progress_type", $args))
		return;

	$build_version = defined("BUILD_VERSION") ? mt_rand() : "0.0.0";

	if(($circular_reading_progress_script_uri = get_theme_file_uri("assets/dist/js/reading-progress-circular.js")) && $args["progress_type"] == "circular")
		wp_enqueue_script("custom-circular-reading-progress", $circular_reading_progress_script_uri, [], mt_rand(), false);

	if(($horizontal_reading_progress_script_uri = get_theme_file_uri("assets/dist/js/reading-progress-horizontal.js")) && $args["progress_type"] == "horizontal")
		wp_enqueue_script("custom-horizontal-reading-progress", $horizontal_reading_progress_script_uri, [], mt_rand(), false);

?>



<?php 
	//Circular reading progress display
	if ($args["progress_type"] == "circular"): ?>
	<div class="fixed z-20 w-48 h-48 left-20 top-16">
		<svg
			width="40px"
			height="40px"
			viewbox="0 0 110 110"
			class="-rotate-90 transfom scale-x-flip"
		>
			<circle id="reading-progress-circle" cx="45" cy="45" r="40" stroke-width="9" class="opacity-0 stroke-teal-500 fill-transparent"/>
		</svg>
	</div>
<?php endif; ?>

<?php 
	//Horizontal reading progress display
	if ($args["progress_type"] == "horizontal"): ?>
		<div id="reading-progress" class="hidden lg:block relative w-full h-2 bg-gray-200">
			<div id="reading-progress-fill" class="w-0 h-full transition-all duration-75 bg-forest-green-500"></div>
		</div>
<?php endif; ?>

<?php 
	//Horizontal reading progress display
	if ($args["progress_type"] == "horizontal_mobile"): ?>
		<div id="reading-progress" class="sticky top-0 z-20 lg:hidden w-full h-2 bg-gray-200">
			<div id="reading-progress-fill-mobile" class="w-0 h-full transition-all duration-75 bg-forest-green-500"></div>
		</div>
<?php endif; ?>