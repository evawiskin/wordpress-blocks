<?php if (have_rows("footer_areas_repeater", "options")): ?>
		<?php while (have_rows("footer_areas_repeater", "options")): the_row();
			
			$footer_area_type = get_sub_field("footer_area_type");
			
			switch ($footer_area_type) {

				case "menu": 
					if(($footer_nav_menu_slug = get_sub_field("footer_area_menu_select")) &&
						$footer_nav_menu = wp_get_nav_menu_object($footer_nav_menu_slug)
					): ?>
						<nav aria-label="<?php echo($footer_nav_menu->name);?>">
							<h5 class="mb-1 font-semibold text-teal-500"><?php echo($footer_nav_menu->name);?></h5>
							<?php
								wp_nav_menu([
									"container"	 	=> false,					// removes div container
									"depth"			=> 1,						// allows for 1 level of dropdowns
									"fallback_cb"	=> false,					// falls back to nothing if menu unavailable
									"item_class" 	=> "mb-1 hover:opacity-80",	// classes on each li
									"menu_class" 	=> "text-xs",				// classes on the ul
									"menu"		 	=> $footer_nav_menu_slug	// gets menu by slug
								]);
							?>
						</nav>
					<?php endif;
				break;

				case "company-address": ?>
					<div>
						<?php if ($footer_area_title = get_sub_field("footer_area_title")) : ?>
							<h5 class="mb-1 font-semibold text-teal-500"><?php echo($footer_area_title); ?></h5>
						<?php endif; ?>
						<div class="text-xs">
							<?php echo(get_field("company_info_address", "options")); ?>
						</div>
					</div>
			<?php
				break;

				case "company-opening-hours": ?>
					<div>
						<?php if ($footer_area_title = get_sub_field("footer_area_title")) : ?>
							<h5 class="mb-1 font-semibold text-teal-500"><?php echo($footer_area_title); ?></h5>
						<?php endif; ?>
						<div class="text-xs">
							<?php echo(get_field("company_opening_hours", "options")); ?>
						</div>
					</div>
			<?php
				break;

				case "wysiwyg": ?>
					<div>
						<?php echo(get_sub_field("footer_area_wysiwyg")); ?>
					</div>
			<?php
				break;

				default:
				
			}



		endwhile; ?>
<?php endif;