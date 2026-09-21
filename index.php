<?php
    get_header();

    $title = "";
    if(is_archive())
        $title = get_the_archive_title();
    if(is_search())
        $title = "Search results for: " . get_search_query();

?>

    <div class="outside-container">
        <div class="container">
            <h1 class="font-bold text-2xl"><?php echo($title); ?></h1>
        </div>
    </div>

    <div class="outside-container">
	    <div class="container">
            <?php if(have_posts()): ?>
                
                <div id="ajax-load-posts" class="relative grid gap-8 lg:gap-y-16">
                    <?php
                        while(have_posts()):
                            the_post();
                            $post_type = get_post_type();
                            if(get_template_part("template-parts/cards/template-part", "{$post_type}-card") === false)
                                get_template_part("template-parts/cards/template-part", "default-card");
                        endwhile;	
                        wp_reset_postdata();
                    ?>
                </div>

                <?php get_template_part("template-parts/partials/partial", "pagination"); ?>

            <?php else: ?>

                <h4 class="text-lg">No <?php echo((is_search()) ? "results" : "posts"); ?> found.</h4>

            <?php endif; ?>

        </div>
	</div>



<?php get_footer(); // Function that imports footer.php