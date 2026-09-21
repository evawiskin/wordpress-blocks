<!-- modal content -->
<div class="z-20 relative w-full lg:w-3/5 h-5/6 lg:h-[42rem]">
	<button aria-label="Close modal">
		<?php if ($close_icon = get_svg_icon("x")) : ?>
			<span class="modal-dismiss z-10 bg-forest-green-600 w-8 h-8 lg:w-12 lg:h-12 block text-electric-green-500 absolute top-3 right-3 lg:-top-4 lg:-right-4 hover:text-white transition-colors duration-200">
				<?php echo($close_icon) ?>
			</span>
		<?php else : ?>
			Close
		<?php endif; ?>
	</button>
	<div class="modal-load-content absolute inset-0 overflow-y-auto overscroll-contain"></div>
</div>
