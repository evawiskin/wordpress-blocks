<?php
/**
*	The header for our theme
*
*	This is the template that displays all of the <head>
*
* 	@link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* 	@package WordPress
* 	@subpackage Hi Yield Wordpress Starter
* 	@since 1.0
*/

$args = array_merge(["header_partial" => "template-parts/global/header/template-part-header"], $args ?? []);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" class="antialiased scroll-smooth bg-forest-green-600 text-white">
<head>
	<meta charset="<?php bloginfo("charset"); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo("pingback_url"); ?>">

	<?php
		get_template_part("template-parts/partials/partial-favicon");
		wp_head();
	?>
	<script>
		const siteUrl = "<?php echo(site_url()); ?>";
	</script>


	<?php if (strpos(site_url(), "hiyield.co.uk") !== false): ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script>
			// Why do we set up "gtag" when we could push to the dataLayer itself?
			// Well, the gtag function only allows you to push, nothing more.
			// This is meant to help avoid accidents when modifying the dataLayer directly.
			window.dataLayer = window.dataLayer || [];
			function gtag() { dataLayer.push(arguments); }

			// Setup Gtag consent with the default of "denied"
			gtag("consent", "default", {
				"ad_user_data": "denied",
				"ad_personalization": "denied",
				"ad_storage": "denied",
				"analytics_storage": "denied",
				"wait_for_update": 500,
				"url_passthrough": true,
				"ads_data_redaction": true,
			});
		</script>

		<!-- Start Google Tag Manager -->
		<?php if (($gtm_id = get_field("option_api_keys_gtm_id", "options")) && !WP_DEBUG): ?>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
			new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
			"https://www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,"script","dataLayer","<?php echo($gtm_id); ?>");</script>
		<?php endif; ?>
		<!-- End Google Tag Manager -->
	<?php endif; ?>
</head>
<body <?php body_class("min-h-screen flex flex-col frontend font-area-normal"); ?>>

	<!-- Google Tag Manager (noscript) -->
	<?php if ((strpos(site_url(), "hiyield.co.uk") !== false) && !empty($gtm_id) && !WP_DEBUG): ?>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo(esc_attr($gtm_id)); ?>"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<?php endif; ?>
	<!-- End Google Tag Manager (noscript) -->

	<?php get_template_part(slug: $args["header_partial"]) ?>
	<main id="main" class="flex-1 site-main scroll-mt-32 <?php if(is_404()) echo "flex flex-col"; ?>" role="main">
