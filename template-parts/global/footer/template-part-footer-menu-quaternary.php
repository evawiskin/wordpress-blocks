<?php

	if(($quaternary_footer_menu_slug = get_field("option_footer_menu_quaternary", "options")) &&
		$quaternary_footer_menu = wp_get_nav_menu_object($quaternary_footer_menu_slug)
	): ?>
		<nav aria-label="<?php echo($quaternary_footer_menu->name);?>" class="mt-auto footer-quaternary">
			<?php
				$after_element = "md:after:content-['|'] after:absolute after:-right-2.5 after:top-1/2 after:-translate-y-1/2 after:h-full last:after:content-none";

				// Capture the menu output so we can append a filterable cookie item inside the <ul>
				ob_start();
				wp_nav_menu([
					"container" 	 	=> 	false,
					"depth" 		=> 	1,
					"fallback_cb" 	=> 	false,
					"item_class" 	=> 	 "font-bold relative no-underline hover:text-forest-green-400 {$after_element}",
					"menu_class" 	=> 	"flex flex-col md:flex-row flex-wrap gap-4 list-unset
					lg:items-center",
					"menu" 		=> 	$quaternary_footer_menu_slug
				]);
				$menu_html = (string) ob_get_clean();

				// Get the cookie button HTML (raw). We capture its output into a string too.
				ob_start();
				get_template_part("template-parts/global/footer/template-part-footer-cookie-button");
				$cookie_html = (string) ob_get_clean();

				/**
				 * Directly inject cookie button into quaternary menu.
				 */
				$injected = apply_filters('hy_footer_quaternary_cookie_menu_item', $cookie_html, $quaternary_footer_menu);

				// If the filter returned empty, just echo the original menu HTML
				if (empty($injected)) {
					echo $menu_html;
				} else {
					// Try to insert the injected item inside the <ul> if present
					if (preg_match('#<ul[^>]*>(.*?)</ul>#si', $menu_html, $matches)) {
						$ul = $matches[0];
						$inner = $matches[1];
						$replacement = str_replace($inner, $inner . $injected, $ul);
						// Replace the original <ul> in the menu HTML with our augmented one
						$menu_html = str_replace($ul, $replacement, $menu_html);
					}
					echo($menu_html);
				}

			?>
		</nav>
	<?php endif;
