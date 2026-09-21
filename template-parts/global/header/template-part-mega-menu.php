<?php
    // Set arg defaults
    if (!isset($args))
        $args = [];
    $args = array_merge([
        "nav_menus" => [],
        "uid" => uniqid("mega-menu-"),
        "parent_menu_item" => null,
        "cta_link" => null
    ], $args);
    $nav_menus = $args["nav_menus"];
    $uid = $args["uid"];
    $parent_menu_item = $args["parent_menu_item"];
    $cta_link = $args["cta_link"];

    // Check we have menus
    if (empty($nav_menus) || !is_array($nav_menus)) {
        return;
    }
    $megamenu_class = "
        submenu
        z-10
        overflow-hidden 
        transition-all 
        duration-200 
        absolute 
        top-0
        inset-x-0 
        translate-x-full
        h-screen
        overflow-y-scroll
        text-base
        text-gretter-50
        lg:top-auto
        lg:translate-x-0 
        lg:max-h-fit 
        lg:opacity-0 
        lg:invisible 
        lg:group-hover:visible 
        lg:group-hover:opacity-100 
        lg:pt-5
        shadow-lg 
        nav-level
    ";
?>
<div 
    id="<?php echo($uid); ?>" 
    class="<?php echo($megamenu_class); ?>"
>
    <div class="bg-forest-green-500 pt-24 pb-16 border-t border-white/20 lg:pt-8">
        <div class="container pb-7 lg:pb-0">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 px-4 2xl:px-16">

                <!-- Back to main menu button (mobile) -->
                <button class="back flex items-center gap-6 py-5 lg:hidden">
                    <span class="block size-5 shrink-0 -mt-1"><?php echo(get_svg_icon("chevron-left")); ?></span>
                    Back to main menu
                </button>
                <?php 
                    foreach($nav_menus as $index => $nav_menu) :

                        // Force expected keys from nested sub-fields
                        $nav_menu = array_merge([
                            "option_header_megamenu_nav_menus_select" => "",
                            "option_header_megamenu_sub_menu_layout" => false
                        ], $nav_menu);
                        $nav_menu_slug = $nav_menu["option_header_megamenu_nav_menus_select"];
                        $is_detailed_layout = $nav_menu["option_header_megamenu_sub_menu_layout"];

                        // Get nav menu object from slug
                        $nav_menu_object = wp_get_nav_menu_object($nav_menu_slug);
                        if (!$nav_menu_object) {
                            continue;
                        }


                        // Calculate column span based on total number of menus
                        $total_menus = count($nav_menus);
                        $col_span = 6;
                        $class = "col-span-full";
                        $class .= $is_detailed_layout ? " lg:col-span-8" : " lg:col-span-4";
                        $is_even = $index % 2 === 1;
                        $layout = $is_detailed_layout ? "grid-cols-2 gap-x-6 gap-y-4 lg:grid" : "regular";
                        $nav_item_class = $is_detailed_layout ? "inline-flex gap-4 rounded-xl active:underline lg:hover:bg-white/10 py-4 lg:py-2 lg:px-4" : "py-4 active:underline lg:hover:underline lg:py-0";

                        // Output individual menu items
                        $menu_name = $nav_menu_object->name;
                        $nav_menu_items = wp_get_nav_menu_items($nav_menu_object->term_id);
                        if (empty($nav_menu_items)) {
                            continue;
                        }
                ?>
                    <div class="mb-6 last:mb-0 lg:mb-0 <?php echo($class); ?>">
                        <h4 class="has-mini-heading-font-size font-extrabold mb-6 <?php if ($is_even) echo("lg:ml-16"); ?>">
                            <?php echo($menu_name); ?>
                        </h4>
                        <nav class="<?php if ($is_even) echo("lg:border-l lg:border-white/20 lg:pl-16"); ?>">
                            <ul class="list-unset font-semibold text-gretter-50 <?php echo($layout); ?>">
                                <?php 
                                    foreach ($nav_menu_items as $nav_menu_item) :
                                        $nav_item_icon = "";
                                        if ($is_detailed_layout) {
                                            $nav_item_icon_select = get_field("nav_item_icon", $nav_menu_item->ID) ?: "";
                                            $nav_item_icon = is_string($nav_item_icon_select) ? get_svg_icon($nav_item_icon_select) : "";
                                        }
                                ?>
                                    <li class="last:mb-0 lg:mb-4">
                                        <a 
                                            href="<?php echo($nav_menu_item->url); ?>"
                                            class="block transition-all duration-200 <?php echo($nav_item_class); ?>"
                                        >
                                            <?php if ($nav_item_icon) : ?>
                                                <span class="block size-6 shrink-0 text-electric-green-500">
                                                    <?php echo($nav_item_icon); ?>
                                                </span>
                                            <?php endif; ?>

                                            <span class="flex flex-col gap-2">
                                                <?php 
                                                    // Nav item title 
                                                    echo($nav_menu_item->title);

                                                    // Nav item description
                                                    if ($nav_menu_item->description && $is_detailed_layout) :
                                                ?>
                                                    <span class="hidden text-xs text-gray-200 lg:block"><?php echo($nav_menu_item->description); ?></span>
                                                <?php 
                                                    endif; 
                                                ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php 
                                    endforeach; 
                                ?>
                            </ul>   
                        </nav>
                    </div>
                <?php 
                    endforeach;
                ?>
            </div>
        </div>

        <!-- Bottom Section (mobile) - parent-link + contact button -->
        <div class="mt-auto lg:hidden">
            <?php
                // Clickable parent menu item link
                if ($parent_menu_item instanceof WP_Post) :
                ?>
                    <a 
                        href="<?php echo($parent_menu_item->url); ?>" 
                        class="flex items-center gap-2 text-xl font-extrabold py-6 px-4 md:px-16"
                    >
                        View all <span class="lowercase"><?php echo($parent_menu_item->title); ?></span>
                        <span class="block size-5 shrink-0 -mt-1"><?php echo(get_svg_icon("chevron-right")); ?></span>
                    </a>
                <?php
                endif; 
            ?>
            <div class="px-4 py-6 border-t border-white/10 md:px-16">
                <?php
                    get_template_part("template-parts/components/template-part", "header-ctas",
                        ["wrapper_classes" => "flex flex-col gap-4"]
                    );
                ?>
            </div>
        </div>
    </div>
    <!-- CTA Section -->
    <?php
        if (!empty($cta_link) && is_array($cta_link)) :
            // Merge expected keys
            $cta_link = array_merge([  
                "title" => "",
                "url" => "",
                "target" => ""
            ], $cta_link);
        ?>
        <div class="bg-forest-green-600 text-gretter-50 text-xs -mt-px">
            <div class="container">
                <div class="px-8 2xl:px-16">
                    <a 
                        href="<?php echo($cta_link["url"]); ?>" 
                        <?php if ($cta_link["target"]) echo('target="' . esc_attr($cta_link["target"]) . '"'); ?>
                        class="group flex items-center gap-2 py-4 font-semibold transition-colors hover:underline"
                    >
                        <?php echo($cta_link["title"]); ?>
                        <span class="block shrink-0 size-4 text-electric-green-500 group-hover:translate-x-1 transition-transform duration-200">
                            <?php echo(get_svg_icon("chevron-right")); ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    <?php
    endif;   
    ?>
</div>
