<nav id="mobile-menu" 
	aria-labelledby="mobile-menu-toggle" 
	class="fixed top-0 right-0 z-50 w-full h-full transition transform translate-x-full mobile-menu-active"
	tabindex="-1"
>
	<?php 
		// Get Close Icon
		$close_icon = get_theme_file_path("/assets/dist/imgs/feather-icons/x.svg");
	?>
	<button 
		aria-label="Toggle Mobile Menu" 
		aria-controls="mobile-menu"
		class="absolute w-8 h-8 transition-all top-4 right-4 hover:text-purple-400"
		onclick="toggleMobileMenu(event)"
	>
		<?php if(file_exists($close_icon)) : ?>
				<?php echo(file_get_contents($close_icon)); ?>
		<?php endif; ?>
	</button>

	<div class="flex w-full pt-24 bg-white">
		<div class="container flex flex-col justify-between">
			<?php
				//gets primary header menu slug, gets primary header menu menu object
				if (($primary_header_menu_slug = get_field("option_header_primary_menu", "options")) &&
					$primary_header_menu = wp_get_nav_menu_object($primary_header_menu_slug)) : ?>
					<nav aria-label="<?php echo($primary_header_menu->name);?>" class="w-full">
						<?php
							wp_nav_menu([
								"container" 	=> false, 						// removes div container
								"depth"			=> 2,							// allows for 2 levels of dropdowns
								"fallback_cb"	=> false,						// falls back to nothing if menu unavailable
								"item_class"	=> "mobile-menu-item ",						// classes on each li
								"menu_class" 	=> "flex flex-wrap flex-col text-white text-2xl font-bold text-teal-500 list-unset",	// classes on the ul
								"menu_id"		=> "primary-header-menu",		// id on the ul
								"menu"			=> $primary_header_menu_slug	// gets menu by slug
							]);
						?>
					</nav>
			<?php endif; ?>
		</div>
	</div>
</nav>