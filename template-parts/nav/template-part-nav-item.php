<?php
    // Check args
    if(!$args && !array_key_exists("menu_item", $args))
        return;

    $defaults = [
        "depth" => 0,
    ];

    $args = wp_parse_args($args, $defaults);
    $menu_item = $args["menu_item"];
    $depth = $args["depth"];
    $item_classes = "menu-item ";

    $menu_item->has_children = false;
    if(in_array("menu-item-has-children", $menu_item->classes))
        $menu_item->has_children = true;

    if($menu_item->classes[0] !== "menu-item")
        $item_classes .= $menu_item->classes[0];
    
    switch($depth){
        case 0:
            $item_classes .= " group";
            $link_classes = "inline-flex items-center transition-colors px-3 py-2 rounded-xl hover:bg-white/10";
            if (home_url($wp->request) . "/" == $menu_item->url)
                $link_classes .= " text-electric-green-500 hover:no-underline";
            break;
        case 1:
            if($menu_item->has_children){
                $item_classes .= " transition-all duration-500 hover:w-[48rem]";
            }
            $link_classes = "w-full inline-flex items-center relative transition-colors p-4 border border-white/10 bg-forest-green-600 hover:bg-forest-green-700";
            break;
        case 2:
            $item_classes .= " ";
            $link_classes = "inline-flex items-center w-full py-3 mb-3 px-4 bg-gray-100 rounded-sm";
            break;
    }

    $nav_item_mega_menu = get_nav_item_mega_menu($menu_item->ID);
    $chevron_down_svg_file = get_theme_file_path("/assets/dist/imgs/feather-icons/chevron-down.svg");
?>

<li 
    class="<?php echo($item_classes); ?>"
>
    <a 
        class="<?php echo($link_classes); ?>"
        <?php echo($menu_item->link_attributes); ?>
    >
        <span class=""><?php echo($menu_item->title); ?></span>
        <?php if($depth === 0 && ($menu_item->has_children || !empty($nav_item_mega_menu["option_header_megamenu_nav_menus_repeater"]))): ?>
            <div class="ml-2">
                <span class="block size-5 transition-transform group-hover:rotate-180">
                    <?php if(file_exists($chevron_down_svg_file)) echo(file_get_contents($chevron_down_svg_file)); ?>
                </span>
            </div>
    <?php endif; ?>
    </a>

<!-- Mega-menu -->
<?php
    // If mega-menu enabled for this nav item and we have selected menus and there are no child items, load mega-menu template part
    if (!empty($nav_item_mega_menu["option_header_megamenu_nav_menus_repeater"]) && empty($menu_item->has_children)) {
        get_template_part("template-parts/global/header/template-part", "mega-menu", [
            "nav_menus" => $nav_item_mega_menu["option_header_megamenu_nav_menus_repeater"],
            "cta_link" => $nav_item_mega_menu["option_header_megamenu_cta"]
        ]);
    }

    // leave li open as self closes
?>