<?php
/**
 * Minimal footer for gated landing pages.
 */

?>
<footer id="hiyield" class="py-12 bg-gretter-50 text-forest-green-600">
	<div class="container flex flex-col gap-12">
		<!-- Bottom Footer Area -->
		<div class="grid-design text-xs py-7 items-center">

			<div class="col-span-full lg:col-span-7">
				<p class="mb-4 flex flex-col md:flex-row flex-wrap gap-x-2 gap-y-4 list-unset font-bold">
					<span class="company">&copy; <?php echo esc_html("Hiyield Ltd") . ' ' . esc_html( date("Y") ); ?></span>
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

			<div class="col-span-full lg:col-span-5">
				<!-- Hosting Badge (under lg) -->
				<div class="flex lg:hidden mt-8 lg:mt-0 pr-12 md:pr-0 ">
					<div class="flex lg:hidden h-fit w-fit gap-4 px-4 py-3 border-2 border-forest-green-600 rounded-lg">
						<span class="flex items-center shrink-0 w-6 h-7 asterisk-rotate-linear"><?php echo(get_svg_icon("asterisk", "hiyield-icons")); ?></span>
						<div class="flex items-center w-48">
							<?php echo(get_svg_icon("Poweredbyhorizontal", "misc")) ?>
						</div>
					</div>
				</div>

				<!-- Hosting Badge (above lg) & B Corp Banner -->
				<div class="flex gap-8 items-center lg:justify-end lg:order-last pr-12 md:pr-0 mt-8 lg:mt-0">
					<div class="hidden lg:flex items-center h-fit w-full max-w-[247px] gap-4 px-4 py-3 border-2 border-forest-green-600 rounded-lg">
						<span class="flex shrink-0 w-10 h-11 asterisk-rotate-linear"><?php echo(get_svg_icon("asterisk", "hiyield-icons")); ?></span>
						<div class="flex items-center">
							<?php echo(get_svg_icon("Poweredbylarge", "misc")) ?>
						</div>
					</div>
					
					<?php
						get_template_part(
							slug: "template-parts/components/template-part",
							name: "bcorp-banner",
							args: ["wrapper_class" => "max-w-52 lg:max-w-48"]
						);
					?>
				</div>
			</div>
		</div>
	</div>
</footer>