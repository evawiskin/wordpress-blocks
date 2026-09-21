<?php 
/**
*	The template for displaying 404 pages (not found)
*
*	@link https://codex.wordpress.org/Creating_an_Error_404_Page
*
* 	@package WordPress
* 	@subpackage Hi Yield Wordpress Starter
* 	@since 1.0
*/

get_header();

$hiyield_icon = get_svg_icon("hiyield-asterisk", "hiyield-icons");

$not_found_title = get_field("option_not_found_title", "options") ?: "Page not found";
$not_found_description = get_field("option_not_found_description", "options") ?: "Please check the URL in the address bar and try again.";
$link = get_field("option_not_found_link", "options");

?>
<div class="bg-forest-green-500 inside-container-xl flex-1">
	<div class="container max-w-2xl mx-auto text-white text-center">
		
		<p class="font-athletics font-extrabold text-[14rem] leading-none flex justify-center items-center tracking-tighter">
			<?php if($hiyield_icon): ?>
				4<span class="h-32 w-32 sm:w-52 sm:h-52 mb-8 -mr-[.05em] inline-block text-electric-green-500 asterisk-rotate">
					<?php 
						//randomise SVG so clippath works.
						$icon_code = $hiyield_icon;
						$icon_code = str_replace("clip0_", "clip".mt_rand(), $icon_code);
						echo($icon_code); 
					?>
				</span>4
			<?php else: ?>
				404
			<?php endif; ?>
		</p>
		
		<h1 class="theme-heading-medium text-teal-600"><?php echo($not_found_title); ?></h1>
		
		<p class="font-semibold mt-8"><?php echo($not_found_description); ?></p>

		<?php if (is_array($link)): ?>
			<div class="mt-8">
				<?php
					get_template_part("template-parts/partials/partial", "button", [
						"button_classes" => "hy-button-primary flex-nowrap mx-auto",
						"button_content" => [
							"button_text" => $link["title"],
							"button_target" => $link["target"],
							"button_link" => $link["url"],
						],
						"icon_right" => "arrow-right"
					]);
				?>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php
get_footer();