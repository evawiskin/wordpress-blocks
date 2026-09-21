<!-- Conditional Site Logo -->
<?php if (($site_logo = get_field("option_company_info_logo", "options"))) : ?>
    <a href="<?php echo(get_home_url()); ?>" class="block" aria-label="<?php echo(get_bloginfo("name")); ?>">
        <?php echo(wp_get_attachment_image($site_logo, "full", null, ["class" => "w-36 h-auto", "aria-label" => get_bloginfo("name")])); ?>
    </a>
<?php endif; ?>