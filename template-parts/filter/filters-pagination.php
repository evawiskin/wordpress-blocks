<?php

    if(!isset($args)) {
        $args = [];
    }

    $current_page = $args["current_page"];
    $max_num_pages = $args["max_num_pages"];

    if($max_num_pages < 2) {
        return;
    }

    $arrow_right = get_svg_icon("arrow-right", "feather-icons");
    $arrow_left = get_svg_icon("arrow-left", "feather-icons");

?>

<form id="pagination" class="py-6 flex flex-row flex-wrap items-center justify-center gap-4">

    <div class="w-full grid grid-cols-12">

        <!-- Previous button -->
        <div class="col-span-4 lg:col-span-3 flex justify-start">
            <a id="pagination-prev" class="group pagination-control cursor-pointer flex flex-row justify-center items-center gap-2 <?php if($current_page == 1) echo("hidden"); ?>">
                <span class="w-5 h-5 flex items-center justify-center mt-1 text-forest-green-400 group-hover:text-black transition-colors">
                    <?php echo($arrow_left); ?>
                </span>
                <span class="font-bold text-forest-green-400 group-hover:text-black transition-colors group-hover:underline decoration-1 underline-offset-2 leading-none">Previous</span>
            </a>
        </div>

        <div class="col-span-4 lg:col-span-6 flex flex-col items-center justify-center">

            <!-- Inputs -->
            <div class="w-full flex flex-wrap flex-row gap-y-2 gap-x-4 lg:gap-x-6 justify-center items-center">
                <?php
                    $i = 0; 
                    while ($i < $max_num_pages) : $i++;

                        // Only show pagination items within a range to prevent overflow issues
                        if ($i < $current_page - 2 || $i > $current_page + 2) 
                            continue;

                        if ($i == $current_page - 2) :
                    ?>
                            <span class="text-forest-green-400">...</span>
                    <?php
                        continue;
                        endif;
                    ?>
                    <div>
                        <input
                            class="pagination-trigger hidden peer"
                            type="radio"
                            id="<?php echo("page_" . $i); ?>"
                            <?php if($i == $current_page) echo("checked"); ?>
                            value="<?php echo($i); ?>"
                            name="pagination"
                        >
                        <label
                            for="<?php echo("page_" . $i); ?>"
                            class="cursor-pointer text-base text-forest-green-400 border-transparent font-bold decoration-1 underline-offset-2 peer-checked:underline peer-checked:text-black peer-checked:opacity-80 peer-checked:cursor-default hover:text-black transition-colors"
                        >
                            <?php echo($i); ?>
                        </label>
                    </div>
                <?php
                    if ($i == $current_page + 2) :
                ?>
                    <span class="text-forest-green-400">...</span>
                <?php
                    endif;
                endwhile;
                ?>
            </div>

        </div>

        <!-- Next button -->
        <div class="col-span-4 lg:col-span-3 flex justify-end">
            <a id="pagination-next" class="group pagination-control cursor-pointer flex flex-row justify-center items-center gap-2 <?php if($current_page == $max_num_pages) echo("hidden"); ?>">
                <span class="font-bold text-forest-green-400 group-hover:text-black transition-colors group-hover:underline decoration-1 underline-offset-2 leading-none">Next</span>
                <span class="w-5 h-5 flex items-center justify-center mt-1 text-forest-green-400 group-hover:text-black transition-colors">
                    <?php echo($arrow_right); ?>
                </span>
            </a>
        </div>

    </div>

</form>