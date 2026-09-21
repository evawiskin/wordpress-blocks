<footer class="py-12 bg-gretter-50 text-forest-green-600">
	<div class="container flex flex-col gap-12">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-y-8 lg:gap-y-12">

			<!-- Col 1 - Footer Menu -->
			<div class="grid grid-cols-1 md:grid-cols-2 gap-12">
				
				<!-- Footer Menu -->
				<?php 
					if(($primary_footer_menu_slug = get_field("option_footer_menu_primary", "options")) &&
						$primary_footer_menu = wp_get_nav_menu_object($primary_footer_menu_slug)
					): ?>
						<nav aria-label="<?php echo esc_attr($primary_footer_menu->name);?>">
							<?php 
								wp_nav_menu([
									"container" 	=> false, 						// removes div container
									"depth"			=> 1,							// allows for 2 levels of dropdowns
									"fallback_cb"	=> false,						// falls back to nothing if menu unavailable
									"menu_class" 	=> "has-tiny-heading-font-size font-bold list-unset",			// classes on the ul
									"item_class"	=> "mb-4 lg:mb-2 last:mb-0",
									"menu_id"		=> "footer-menu",		// id on the ul
									"anchor_class"	=> "transition-all hover:text-forest-green-400",
									"menu"			=> $primary_footer_menu_slug	// gets menu by slug
								]);
							?>
						</nav>
				<?php endif; ?>

				<?php 
					$secondary_footer_menu_slug = get_field("option_footer_menu_secondary", "options");
				
					$secondary_footer_menu = $secondary_footer_menu_slug
						? wp_get_nav_menu_object($secondary_footer_menu_slug)
						: false;

					if($secondary_footer_menu):
				?>
					<nav id="footer-secondary-menu" aria-label="<?php echo esc_attr($secondary_footer_menu->name);?>">
						<?php 
							wp_nav_menu([
								"container" 	=> false, 						// removes div container
								"depth"			=> 1,							// allows for 2 levels of dropdowns
								"fallback_cb"	=> false,						// falls back to nothing if menu unavailable
								"menu_class" 	=> "has-tiny-heading-font-size list-unset", // classes on the ul
								"item_class"	=> "mb-4 lg:mb-2 last:mb-0",
								"anchor_class"	=> "transition-all hover:text-forest-green-400 font-bold",
								"menu_id"		=> "footer-menu",				// id on the ul
								"menu"			=> $secondary_footer_menu_slug	// gets menu by slug
							]);
						?>
					</nav>
				<?php endif; ?>
			</div>

			<!-- Col 2 -->
			<div class="flex flex-col justify-between gap-12">

				<!-- Contact Details -->
				<div class="theme-heading-mini sm:theme-heading-small heading-secondary flex flex-col justify-between lg:items-end">

					<!-- Contact Details -->
					<?php if($company_sales_email = get_field("option_company_info_sales_email", "options")): ?>
						<a href="mailto:<?php echo esc_attr($company_sales_email); ?>" class="hover:text-forest-green-400 transition-all mb-6 break-all">
							<?php echo esc_html($company_sales_email); ?>
						</a>
					<?php endif; ?>
					<?php if($company_sales_number = get_field("option_company_info_sales_number", "options")): 
						
						if(!$company_sales_display_number = get_field("option_company_info_sales_display_number", "options"))
							$company_sales_display_number = $company_sales_number;
					?>
						<a href="tel:<?php echo esc_attr($company_sales_number); ?>" class="hover:text-forest-green-400 transition-all mb-6">
							<?php echo esc_html($company_sales_display_number); ?>
						</a>
					<?php endif; 

					// Social Icons
					get_template_part("template-parts/components/template-part-social-icons", "", ["hover_class" => "hover:text-forest-green-400"]);
					
					if($newsletter_page_link = get_field("option_footer_newsletter_page_link", "options")):
						$newsletter_page_link = array_merge([
							"title" => "",
							"url" => "",
							"target" => "_self"
						], $newsletter_page_link);

						get_template_part("template-parts/partials/partial", "button", [
							"button_content" => [
								"button_text" => $newsletter_page_link["title"],
								"button_target" => $newsletter_page_link["target"],
								"button_link" => $newsletter_page_link["url"]
							],
							"aria_label" => $newsletter_page_link["title"],
							"button_classes" => "hy-button-primary text-[1rem]",
							"icon_right" => "arrow-right",
						]);
					endif; ?>
				</div>
			</div>
		</div>

		<!-- Bottom Footer Area -->
		<div class="grid grid-design text-xs pt-12 border-t border-forest-green-600/20 items-center">

			<div class="col-span-full lg:col-span-7">
				<p class="mb-4 flex flex-col md:flex-row flex-wrap gap-x-2 gap-y-4 list-unset font-bold">
					<span class="company">&copy; Hiyield LTD, all rights reserved</span>
					<span aria-hidden="true" class="hidden md:inline-block text-current">|</span>
					<span class="tagline"><?php echo esc_html("Made with love by nice people"); ?></span>
					<span aria-hidden="true" class="hidden md:inline-block text-current">|</span>
					<span class="company-number"><?php echo esc_html("Company #11362053"); ?></span>
					<span aria-hidden="true" class="hidden md:inline-block text-current">|</span>
					<span class="vat"><?php echo esc_html("VAT #3042 893 16"); ?></span>
				</p>

			<!-- Policy Menu -->
			<?php get_template_part("template-parts/global/footer/template-part-footer-menu-quaternary"); ?>
		</div>
		<div class="col-span-full lg:col-span-5 flex flex-wrap items-center gap-8 lg:flex-nowrap lg:gap-12 lg:justify-end">
			<!-- Hosting Badge (under lg) -->
			<div class="flex lg:hidden h-fit w-fit gap-4 px-4 py-3 border-2 border-forest-green-600 rounded-lg">
				<span class="block shrink-0 w-6 h-7 fill-forest-green-600 text-forest-green-600 asterisk-rotate-linear"><?php echo(get_svg_icon("asterisk", "hiyield-icons")); ?></span>
				<div class="flex items-center w-48 fill-forest-green-600 text-forest-green-600">
					<?php echo(get_svg_icon("Poweredbyhorizontal", "misc")) ?>
				</div>
			</div>
			
			<!-- Hosting Badge (above lg) -->
			<div class="hidden lg:flex items-center h-fit shrink-0 max-w-[247px] gap-4 px-4 py-3 border-2 border-forest-green-600 rounded-lg">
				<span class="block shrink-0 w-10 h-11 fill-forest-green-600 text-forest-green-600 asterisk-rotate-linear"><?php echo(get_svg_icon("asterisk", "hiyield-icons")); ?></span>
				<div class="flex items-center fill-forest-green-600 text-forest-green-600">
					<?php echo(get_svg_icon("Poweredbylarge", "misc")) ?>
				</div>
			</div>
			
			<!-- B Corp Banner -->
			<?php
				$args = [
					"wrapper_class" => "!w-auto max-w-52 lg:max-w-48 shrink-0 fill-forest-green-600",
				];
				get_template_part("template-parts/components/template-part-bcorp-banner", "", $args);
			?>
		</div>
	</div>
</footer>