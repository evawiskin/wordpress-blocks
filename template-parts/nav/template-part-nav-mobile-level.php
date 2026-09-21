<?php 
    $defaults = [
        "depth" => 0,
    ];

    $args = wp_parse_args($args, $defaults);
    $depth = $args["depth"];

    $classes="submenu list-unset absolute pt-24 top-0 bg-forest-green-500 w-full h-full transition-all z-40 translate-x-full";

?>

<ul class="<?php echo($classes); ?>">

<?php // leave ul open as self closes ?>