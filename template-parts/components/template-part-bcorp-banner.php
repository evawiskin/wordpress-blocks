<?php

    // Args
    if (!isset($args))
        $args = [];

    // Set default args
    $args = array_merge([
        "wrapper_class" => "",
    ], $args);

    $wrapper_class = $args["wrapper_class"];

    $bcorp_page_url = get_field("option_company_bcorp_url", "options");
    $background = get_svg_icon("bcorp-banner", "misc");

?>

<?php if(!empty($bcorp_page_url)): ?>
    <a href="<?php echo($bcorp_page_url); ?>" class="w-full <?php echo($wrapper_class); ?>">
<?php else : ?>
    <div class="w-full <?php echo($wrapper_class); ?>">
<?php endif; ?>

    <!-- Logo -->
    <div class="w-full">
        <?php echo($background); ?>
    </div>

<?php if(!empty($bcorp_page_url)): ?>
    </a>
<?php else: ?>
    </div>
<?php endif; ?>
