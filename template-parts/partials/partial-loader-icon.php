<div id="post-loader-icon" class="hidden">
	<?php if ($loader = get_svg_icon("loader")) : ?>
		<span class="w-5 h-5 block animate-spin"><?php echo($loader) ?></span>
	<?php else : ?>
		<p>Loading...</p>
	<?php endif; ?>
</div>