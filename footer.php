<?php
/**
* 	The template for displaying the footer
*
* 	Contains the closing of the #content div and all content after.
*
* 	@link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* 	@package WordPress
* 	@subpackage Hi Yield Wordpress Starter
* 	@since 1.0
*/

$args = array_merge([
	"footer_partial"         => "template-parts/global/footer/template-part-footer",
	"include_cookie_consent" => true,
	"include_carbon_checker" => !WP_DEBUG,
], $args ?? []);

?>
	</main><!-- #main -->
		<?php get_template_part(slug: $args["footer_partial"]); ?>
	<?php wp_footer(); ?>
	<?php if (!empty($args["include_cookie_consent"])): ?>
		<script type="module" src="<?php echo(esc_url(get_theme_file_uri("assets/dist/js/cookieconsent.js"))); ?>"></script>
	<?php endif; ?>

	<?php if (!empty($args["include_carbon_checker"])): ?>
		<!-- Carbon Checker Tool -->
		<script type="text/javascript" src="https://www.webco2.io/wti.webco2.1.js"></script>
		<script type="text/javascript">wti_webco2.init(["8a|!sG&NxPkj0--0kxx"]);</script>
	<?php endif; ?>

</body>
</html>
