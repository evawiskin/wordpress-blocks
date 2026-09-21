<?php
    // These are the defined items for the Multistep Menu

    if(!$args && !array_key_exists("menu_item", $args))
        return;

    $defaults = [
        "depth" => 0,
    ];

    $args = wp_parse_args($args, $defaults);
    $menu_item = $args["menu_item"];
    $depth = $args["depth"];
    $parent_id = $args["parent_id"];

    $menu_item->has_children = false;
    if(in_array("menu-item-has-children", $menu_item->classes))
        $menu_item->has_children = true;

    $item_classes = "group";
    $link_classes = "flex items-center py-5 outline-none focus-within:outline-1 focus-within:outline-offset-1 focus-within:outline-black";
    if ($depth > 0) {
        $link_classes .= " text-lg";
    }

    $nav_item_mega_menu = get_nav_item_mega_menu($menu_item->ID);
    $opens_megamenu = !empty($nav_item_mega_menu["option_header_megamenu_nav_menus_repeater"]) && empty($menu_item->has_children);

    // Work with parent
    if( $parent_id && $parent_id > 0 ) {
        $parent_title = get_the_title( $parent_id );

        // Get original title from post / taxonomy
        $object_id  = get_post_meta( $parent_id, "_menu_item_object_id", true );
        $object     = get_post_meta( $parent_id, "_menu_item_object",    true );
        $type       = get_post_meta( $parent_id, "_menu_item_type",      true );

        // Choose right way to render parent title depends on parent object
        if ( "post_type" === $type ) {
            $parent_title = get_post( $object_id )->post_title;
        } elseif ( "taxonomy" === $type) {
            $parent_title = get_term( $object_id, $object )->name;
        }
    }
?>

<?php if( $parent_id && $parent_id > 0 ): ?>
    <!-- Link to parent -->
    <li class="text-base font-semibold">
        <button class="back flex items-center gap-6 py-5">
            <span class="block size-5 shrink-0 -mt-1"><?php echo(get_svg_icon("chevron-left")); ?></span>
            Back to main menu
        </button>
    </li>
<?php endif; ?>

<li class="menu-item <?php echo($item_classes); ?>">
    <?php if ($menu_item->has_children || $opens_megamenu): ?>
        <button 
            class="flex items-center gap-6 justify-between w-full py-5"
            aria-label="Open submenu for <?php echo($menu_item->title); ?>"
            aria-expanded="false"
            aria-controls="submenu-<?php echo($menu_item->ID); ?>"
        >
            <?php echo($menu_item->title); ?>
            <span class="block size-6 -rotate-90">
                <?php echo(get_svg_icon("chevron-down")); ?>
            </span>
        </button>
    <?php else: ?>
        <a class="<?php echo($link_classes); ?>" <?php echo($menu_item->link_attributes); ?>>
            <span><?php echo($menu_item->title); ?></span>
        </a>
    <?php endif; ?>
<?php 
    // If mega-menu enabled for this nav item and we have selected menus and there are no child items, load mega-menu template part
    if ($opens_megamenu) {
        get_template_part("template-parts/global/header/template-part", "mega-menu", [
            "nav_menus" => $nav_item_mega_menu["option_header_megamenu_nav_menus_repeater"],
            "parent_menu_item" => $menu_item
        ]);
    }
    // leave li open as self closes 
?>