<?php
	if ((!isset($block)) || !is_array($block))
		return;

	$main_video_url = get_field("block_video_file");
	$cover_video_url = get_field("block_video_cover_video");
	$cover_video_image = get_field("block_video_cover_image");
	$cover_video_image_url = "";
	if ($cover_video_image && is_array($cover_video_image))
		$cover_video_image_url = $cover_video_image["sizes"]["large"]; // Use large size for better quality

	$block_bg = array_key_exists("backgroundColor", $block) ? "bg-{$block["backgroundColor"]}" : "bg-transparent";

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/video-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;

	// Render placeholder in editor
	if (!is_string($main_video_url) && is_admin()) :
?>
		<div class="bg-forest-green-700 py-24 text-center">
			<p>Add a video to get started...</p>
		</div>
<?php
		return;
	endif;

	// Enqueue Js
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
	if ($video_block_script_uri = get_theme_file_uri("assets/dist/js/video-block.js")) 
		wp_enqueue_script("custom-video-block", $video_block_script_uri, [], $build_version, true);
?>

<section class="inside-container-lg <?php echo($block_bg); ?>">

	<div class="px-5 max-w-5xl mx-auto xl:px-0">
		<div class="video-section block has-cursor cursor-pointer-auto relative aspect-w-16 aspect-h-9">

			<!-- Custom Cursor -->
			<?php get_template_part( "template-parts/partials/partial-cursor-mobile", "", ["text" => "Play showreel"] ); ?>

			<div 
				class="video-container rounded-md overflow-hidden bg-black w-full object-center md:object-cover"
				data-cover-video-url="<?php echo($cover_video_url); ?>"
				data-main-video-url="<?php echo($main_video_url); ?>"
			>

				<?php 
					// if we have a cover image and a video, show the cover image and js will replace it with the video
					// if not then show the cover video if it exists, if not just show the main video
					if($cover_video_image_url && ($cover_video_url || $main_video_url)): 
				?>
					<img class="w-full h-full object-cover" src="<?php echo($cover_video_image_url); ?>" alt="<?php echo(esc_attr($cover_video_image['alt'] ?? '')); ?>">
				<?php elseif($cover_video_url): ?>
					<video
						loop
						muted
						playsinline
						width="100%" 
						height="100%" 
						class="is-cover-video w-full h-full object-cover"
						data-autoplay-on-scroll="true"
					>
						<source src="<?php echo($cover_video_url) ?>" type="video/<?php echo(pathinfo($cover_video_url, PATHINFO_EXTENSION)) ?>">
					</video>
				<?php else: ?>
					<video
						controls
						width="100%" 
						height="100%" 
						class="w-full h-full object-cover"
					>
						<source src="<?php echo($main_video_url) ?>" type="video/<?php echo(pathinfo($main_video_url, PATHINFO_EXTENSION)) ?>">
					</video>
				<?php endif; ?>

			</div>

		</div>
	</div>
</section>