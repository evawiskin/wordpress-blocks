<?php
	/*
		PARTIAL INSTRUCTIONS

		The post filter can be used as either buttons or a dropdown.
		Ensure that whenever container you want to load your posts into has the id "ajax-load-posts" and a class of "relative".
		You can decide what template part you want to load into the container by passing the relative template part path as an agument e.g. "template-parts/cards/template-part-post-card".
		Enter any valid post type and taxonomy as arguments.
	*/
	
	if (!isset($args))
		$args = [];

	// Set default template args
	$args = array_merge([
		"post_type" => "post",
		"template_part" => "template-parts/cards/template-part-post-card",
		"taxonomy" => "category",
		"style" => "buttons"
	], $args);

	$terms = get_terms([
		"taxonomy" => $args["taxonomy"],
		"hide_empty" => true
	]);

	// If post type has no terms then bail
	if (empty($terms))
		return;

	// Enqueue JS
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
	if ($ajax_load_posts_js_uri = get_theme_file_uri("assets/dist/js/ajax-load-posts.js"))
		wp_enqueue_script("ajax-load-posts", $ajax_load_posts_js_uri, [], $build_version, true);
?>

<div
	id="post-filter"
	data-taxonomy="<?php echo($args["taxonomy"]); ?>"
	data-get-template="<?php echo($args["template_part"]); ?>"
	data-post-type="<?php echo($args["post_type"]) ?>"
	class="outside-container"
>

	<!-- Filter Buttons -->
	<?php if ($args["style"] == "buttons") : ?>

		<div class="flex flex-wrap gap-2.5 items-center">

			<?php foreach($terms as $term) : ?>

				<button class="relative border p-5 rounded hover:border-black transition-colors duration-200">
					<?php echo($term->name) ?>
					<input type="checkbox" class="post-term-input absolute inset-0 opacity-0 cursor-pointer" aria-hidden="true" value="<?php echo($term->term_id); ?>">
				</button>

			<?php endforeach; ?>
		</div>

	<!-- Filter Dropdown -->
	<?php else : ?>

		<div class="items-center gap-5 md:flex">
			<span class="block font-bold mb-1.5">Filter By</span>
			<div class="relative w-80 border border-gray-500 rounded cursor-pointer hover:border-teal-500 transition-colors duration-200">
				<div id="post-term-dropdown" class="flex gap-2.5 p-2.5 items-center justify-between">
					<span id="current-selected-text">Select an option</span>
					<?php if ($chevron_down = get_svg_icon("chevron-down")) : ?>
						<span class="chevron w-5 h-5 block"><?php echo($chevron_down) ?></span>
					<?php endif; ?>
				</div>
				<ul id="post-term-dropdown-list" class="absolute max-h-0 overflow-hidden inset-x-0 mt-3 transition-all duration-200 z-10 bg-white">
					<?php foreach($terms as $term) : ?>
						<li class="flex gap-2.5 px-3 py-1.5 first:pt-3 mb-1.5">
							<input type="checkbox" class="post-term-input cursor-pointer" value="<?php echo($term->term_id); ?>">
							<label for="<?php echo($term->name); ?>"><?php echo($term->name); ?></label>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

	<?php endif; ?>

</div>

<!-- Loader -->
<?php get_template_part("template-parts/partials/partial", "loader-icon"); ?>