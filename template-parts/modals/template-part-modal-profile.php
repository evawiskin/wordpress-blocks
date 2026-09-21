<div 
	id="modal_content"
	class="relative w-full h-full flex items-start lg:items-center justify-center bg-forest-green-600 overflow-y-auto pt-20 lg:pt-32 z-20">
	
	<div class="container lg:max-h-3/4 relative">
		<button aria-label="Close modal" class="modal-dismiss bg-forest-green-600 w-8 h-8 lg:w-12 lg:h-12 block text-electric-green-500 absolute top-4 lg:-top-12 right-8 hover:text-white transition-colors duration-200 md:right-16">
			<?php if ($close_icon = get_svg_icon("x")) : ?>
				<span class="">
					<?php echo($close_icon) ?>
				</span>
			<?php else : ?>
				Close
			<?php endif; ?>
		</button>

		<!-- Columns Layout -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 md:gap-x-16 xl:gap-x-32 px-8 lg:px-32 my-16 lg:my-0 gap-y-16">

			<!-- Left side / Card -->
			<div class="w-full flex justify-start lg:justify-center">
				<div class="w-3/4 flex flex-col -rotate-[6deg] text-forest-green-500 rounded overflow-hidden group">
					<div class="relative aspect-1">
						<img src="" id="profile_image" alt="" class="hidden w-full h-full object-cover object-center"/>
						<img src="" id="profile_image_fun" alt="" class="absolute w-full h-full object-cover inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100" />
					</div>
					<div class="flex flex-col gap-y-4 p-4 bg-gretter-50">
						<div id="person_name" class="text-xl font-extrabold"></div>
						<div id="job_title" class="font-bold"></div>
					</div>
				</div>
			</div>

			<!-- Right side / Content -->
			<div class="flex flex-col justify-center gap-y-14 text-white">

				<!-- Description -->
				<div class="flex flex-col gap-y-6">
					<span id="person_description" class="font-semibold"></span>
				</div>

				<!-- Likes -->
				<div class="flex flex-col gap-y-6 font-bold">
					<div class="flex gap-x-3.5 items-center">
						<div class="size-6 text-electric-green-500 flex-shrink-0">
							<?php echo(get_svg_icon("heart")); ?>
						</div>
						<span id="person_likes"></span>
					</div>
					<div class="flex gap-x-3.5 items-center">
						<div class="size-6 text-electric-green-500 flex-shrink-0">
							<?php echo(get_svg_icon("heart-cross")); ?>
						</div>
						<span id="person_dislikes"></span>
					</div>
				</div>

				<!-- Linkedin -->
				<a id="person_linkedin" class="hidden flex flex-row gap-2 border border-2 text-white border-electric-green-500 w-max px-5 py-3 rounded" href="" target="_blank" rel="nofollow">

					<?php if ($linkedin_icon = get_svg_icon("linkedin-square", "feather-icons-social")) : ?>
						<span class="block w-5 h-5">
							<?php echo($linkedin_icon) ?>
						</span>
					<?php endif; ?>
		
					<span class="font-semibold">Go to Profile</span>

					<?php if ($external_link_icon = get_svg_icon("external-link")) : ?>
						<span class="block w-5 h-5">
							<?php echo($external_link_icon) ?>
						</span>
					<?php endif; ?>
				</a>

			</div>

		</div>
	</div>

</div>
