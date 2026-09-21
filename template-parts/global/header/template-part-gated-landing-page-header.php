<?php
/**
 * Simplified header for gated landing pages.
 */

?>

<header class="py-4 bg-forest-green-500 text-white sticky transition-all z-50 duration-200 <?php if(is_admin_bar_showing()) echo("top-8"); else echo("top-0"); ?>" data-scrollup-only="true">
	<?php
		get_template_part(
            slug: "template-parts/global/header/template-part", 
            name: "skip-to-main-content"
        );
	?>
    
    <div class="container flex justify-between items-center">
        <?php 
            get_template_part(
                slug: "template-parts/components/template-part",
                name: "site-logo"
            );

            get_template_part(
                slug: "template-parts/components/template-part",
                name: "header-ctas"
            );
        ?>
    </div>
</header>
