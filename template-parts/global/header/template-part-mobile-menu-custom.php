<?php 
    accordions_enqueue_scripts();
?>

<!-- Mobile menu container -->
<nav id="mobile-menu" 
    aria-labelledby="mobile-menu-toggle" 
    class="fixed top-0 right-0 z-30 w-full h-screen transition transform translate-x-full bg-forest-green-500 mobile-menu-active lg:hidden"
    tabindex="-1"
>
    <!-- Mobile menu Inner Wrapper with proper height constraints -->
    <div class="flex flex-col max-w-screen">
        <div class="container flex-grow overflow-y-scroll flex flex-col h-full">
            <!-- Primary Navigation Menu -->
            <div class="py-16">
                <?php
                //gets primary header menu slug, gets primary header menu menu object
                if (($primary_header_menu_slug = get_field("option_header_primary_menu", "options")) &&
                $primary_header_menu = wp_get_nav_menu_object($primary_header_menu_slug)) : ?>
                    <div aria-label="<?php echo($primary_header_menu->name);?>" class="w-full">
                        <?php
                            wp_nav_menu([
                                "container" 	=> false,                         // removes div container
                                "depth"			=> 3,                           // allows for 2 levels of dropdowns
                                "fallback_cb"	=> false,                        // falls back to nothing if menu unavailable
                                "item_class"	=> "mobile-menu-item",           // classes on each li
                                "menu_class" 	=> "flex flex-wrap flex-col text-2xl font-extrabold accordion-section list-unset mt-5", // classes on the ul
                                "menu_id"		=> "mobile-menu",                // id on the ul
                                "menu"			=> $primary_header_menu_slug,    // gets menu by slug
                                "walker"		=> new Mobile_Nav_Walker() // custom nav walker
                            ]);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="py-6 border-t border-white/10">
            <div class="container">
                <?php
                    get_template_part("template-parts/components/template-part", "header-ctas",
                    ["wrapper_classes" => "flex flex-col gap-4"]);
                ?>
            </div>
        </div>
    </div>
</nav>
