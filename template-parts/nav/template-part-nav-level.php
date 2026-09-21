<?php
    $defaults = [
        "depth" => 0,
    ];

    $args = wp_parse_args($args, $defaults);
    $depth = $args["depth"];

    switch($depth){
        case 0:
            $classes = "absolute z-10 list-unset pt-5 invisible opacity-0 transition-opacity pointer-events-none [.group:hover_>_&]:visible [.group:hover_>_&]:opacity-100 [.group:hover_>_&]:pointer-events-auto [.group:focus-within_>_&]:visible [.group:focus-within_>_&]:opacity-100 [.group:focus-within_>_&]:pointer-events-auto";
            break;
        case 1:
            $classes = "absolute top-0 h-full w-1/2 p-3 transition-transform duration-500 opacity-0 [.group:hover_>_&]:translate-x-full [.group:hover_>_&]:opacity-100";
            break;
    }
?>

<ul class="<?php echo($classes); ?>">

<?php // leave ul open as self closes ?>