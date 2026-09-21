
<?php
    $container_class = isset($args["container_class"]) ? $args["container_class"] : "grid-cols-2 gap-4 mt-12";
    if (have_rows("option_company_info_locations", "option")) : 
?>
    <div class="grid font-bold <?php echo($container_class); ?>">
        <?php
            while (have_rows("option_company_info_locations", "option")) : the_row();
                $location_name = get_sub_field("option_company_info_locations_location_name");
                $location_address = get_sub_field("option_company_info_locations_location_address");
                $location_link = get_sub_field("option_company_info_locations_location_link");
        ?>
            <div class="flex gap-2">
                <?php 
                    if($asterisk = get_svg_icon("asterisk", "hiyield-icons")): 
                        $location_color = get_sub_field("option_company_info_locations_location_color");
                        $asterisk_color = $location_color ? "text-{$location_color}" : "text-forest-green-600";
                ?>
                        <span class="w-7 h-7 mt-1 flex-shrink-0 <?php echo($asterisk_color); ?>">
                            <?php echo($asterisk); ?>
                        </span>
                <?php endif; ?>

                <div>
                    <?php 
                        if($location_name):
                            $location_name_classes = "text-2xl font-bold";
                    ?>
                            <?php if($location_link): ?>
                                <a href="<?php echo($location_link); ?>" class="<?php echo($location_name_classes);?> hover:text-electric-green-500 transition-colors">
                            <?php else: ?>
                                <p class="<?php echo($location_name_classes); ?>">
                            <?php endif; ?>
                                    <?php echo($location_name); ?>
                            <?php if($location_link): ?>
                                </a>
                            <?php else: ?>
                                </p>
                            <?php endif; ?>
                    <?php endif; ?>
                            
                    <?php if($location_address): ?>
                        <p class="text-base font-normal mt-2"><?php echo($location_address); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php 
    elseif (is_admin()) : 
?>
    <div class="text-center py-8 text-gray-500 italic">
        You must add at least one address in site options to use this block.
    </div>
<?php 
    endif; 
?>
