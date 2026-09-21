<?php
	//Note that simply removing the data-scrollup-only attribute will cause the header to be sticky on all pages at all times
	//Removing 'sticky' will remove sticky functionality entirely.
?>
<header class="bg-forest-green-500 text-white sticky transition-all z-50 duration-200 <?php if(is_admin_bar_showing()) echo("top-8"); else echo("top-0"); ?>" data-scrollup-only="true">
	<?php
		get_template_part("template-parts/global/header/template-part", "skip-to-main-content");
	?>
	<div class="container flex flex-wrap gap-8 xl:gap-14 items-center justify-between py-4">
			<?php get_template_part("template-parts/components/template-part-site-logo"); ?>

			<?php 
				$primary_header_menu_slug = get_field("option_header_primary_menu", "options");
				//gets primary header menu slug, gets primary header menu menu object
				if (($primary_header_menu_slug) && $primary_header_menu = wp_get_nav_menu_object($primary_header_menu_slug)) : ?>
					<nav aria-label="<?php echo($primary_header_menu->name);?>" class="hidden ml-auto lg:flex">
						<?php
							wp_nav_menu([
								"container" 	=> false, 						// removes div container
								"depth"			=> 2,							// allows for 2 levels of dropdowns
								"fallback_cb"	=> false,						// falls back to nothing if menu unavailable
								"item_class"	=> "relative",						// classes on each li
								"menu_class" 	=> "flex flex-wrap gap-4 xl:gap-16 list-unset",			// classes on the ul
								"menu_id"		=> "primary-header-menu",		// id on the ul
								"menu"			=> $primary_header_menu_slug,	// gets menu by slug
								"walker"		=> new Customized_Walker_Nav_Menu()			// custom nav walker
							]);
						?>
					</nav>
			<?php endif; ?>

			<?php 
				get_template_part("template-parts/components/template-part", "header-ctas",
				["wrapper_classes" => "hidden lg:block flex flex-wrap gap-4"]) 
			?>
			<?php get_template_part("template-parts/global/header/template-part", "hamburger"); ?>
	</div>

</header>
<!-- 
	Overlay for mobile menu 
	-->
	<div 
		id="mobile-menu-overlay" 
		class="fixed inset-0 z-10 w-full h-full transition-all duration-300 opacity-0 cursor-pointer pointer-events-none" 
		aria-hidden="true"
		aria-label="Toggle Mobile Menu" 

		onclick="toggleMobileMenu(event)"
	></div>
	

<?php
	// Choose mobile-menu-base to show simple menu and mobile-menu-multistep to show complex one (iOS-like menu)
	get_template_part("template-parts/global/header/template-part", "mobile-menu-custom"); 
?>