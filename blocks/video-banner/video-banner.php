<?php
	if ((!isset($block)) || !is_array($block))
		return;

	$video_upload = get_field("block_video_banner_file");

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/video-banner-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
		
	// Render placeholder in editor
	if (!is_array($video_upload) && is_admin()) :
		?>
			<div class="bg-white py-24 text-center">
				<p>Add a video to get started...</p>
			</div>
		<?php
		return;
	elseif ((!is_array($video_upload)) || !array_key_exists("url", $video_upload) ) :
		return;
	endif;
		
	$video_url = $video_upload["url"];
	$video_type = array_key_exists("subtype", $video_upload) ? $video_upload["subtype"] : "mp4";
	$video_id = wp_unique_id();

	// Enqueue Js
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
	if ($video_banner_controls = get_theme_file_uri("assets/dist/js/video-banner-controls.js")) 
		wp_enqueue_script("video-banner-controls", $video_banner_controls, [], $build_version, true);
?>
<section class="relative">

	<!-- Video -->
	<div class="aspect-w-1 aspect-h-1 md:aspect-w-3 md:aspect-h-1 overflow-hidden">
		<video 
			muted playsinline autoplay width="100%" height="100%" 
			id="video-banner-<?php echo($video_id) ?>"
			class="w-full h-full object-center object-cover"
			onended="allowReplay(event, <?php echo($video_id) ?>)"
		>
			<source src="<?php echo($video_url) ?>" type="video/<?php echo($video_type) ?>">
		</video>
	</div>

	<!-- Controls -->
	<div class="absolute bottom-9 right-9 text-white hover:opacity-50 transition-opacity duration-200">

		<!-- Pause -->
		<?php if ($pause_icon = get_svg_icon("pause-circle")) : ?>
			<button
				aria-label="Pause video"
				onclick="videoBannerPause(event, <?php echo($video_id) ?>)" 
				id="video-banner-pause-<?php echo($video_id) ?>" 
				class="block w-12 h-12"
			>
				<?php echo($pause_icon) ?>
			</button>
		<?php endif; ?>
	
		<!-- Play -->
		<?php if ($play_icon = get_svg_icon("play-circle")) : ?>
			<button
				aria-label="Play video"
				onclick="videoBannerPlay(event, <?php echo($video_id) ?>)" 
				id="video-banner-play-<?php echo($video_id) ?>" 
				class="block w-12 h-12 hidden"
			>
				<?php echo($play_icon) ?>
			</button>
		<?php endif; ?>

		<!-- Rewind -->
		<?php if ($replay_icon = get_svg_icon("replay-circle")) : ?>
			<button
				aria-label="Replay video"
				onclick="videoBannerReplay(event, <?php echo($video_id) ?>)"
				id="video-banner-replay-<?php echo($video_id) ?>" 
				class="block w-12 h-12 p-[0.188rem] hidden"
			>
				<?php echo($replay_icon) ?>
			</button>
		<?php endif; ?>
	</div>
</section>