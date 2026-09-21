<?php 
    $newsletter_title = get_field("option_newsletter_title", "options");
    $newsletter_form_id = get_field("option_footer_newsletter_form", "options");

    if($newsletter_form_id):
?>

<div>
    <?php if($newsletter_title) : ?>
        <?php echo("<h4 class=\"text-white theme-heading-micro mb-4 lg:text-right\">{$newsletter_title}</h4>"); ?>
    <?php endif; ?>

    <?php echo(do_shortcode("[gravityform id=\"{$newsletter_form_id}\" title=\"false\" ajax=\"true\"]"))?>
</div>

<?php endif; ?>