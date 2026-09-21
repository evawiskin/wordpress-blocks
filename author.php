<?php
/**
 *  The template for displaying the author page
 *  
 *  @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *  
 *  @package WordPress
 *  @subpackage Hi Yield Wordpress Starter
 *  @since 1.0
 */

get_header(); 

$author_id = get_query_var("author");

$linked_person = get_field("user_linked_person", "user_{$author_id}");
$author_person_id = $linked_person->ID;
$author_name = get_field("cpt_person_name", $author_person_id);
$author_job_title = get_field("cpt_person_job_title", $author_person_id);
$author_bio  = get_field("cpt_person_bio", $author_person_id);
$author_quote = get_field("cpt_person_author_quote", $author_person_id);

?>

<div>
    <?php 
    		// Get the post banner
		get_template_part(
			"template-parts/partials/partial", 
			"author-banner", 
			[
				"author_person_id" => $author_person_id,
                "author_name" => $author_name,
                "author_job_title" => $author_job_title
			]
		); 
    ?>
    
    <div class="bg-gretter-50 text-forest-green-600 py-20">
        <div class="grid lg:grid-cols-12 gap-8 container">
            <div class="lg:col-span-8 lg:col-start-5 lg:order-last">
                <?php if ($author_bio): ?>
                    <div class="prose">
                        <h3 class="theme-heading-mini">About</h3>
                        <p class="text-forest-green-600 font-semibold">
                            <?php echo($author_bio); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($author_quote): ?>
                    <div class="mt-12 border-l-8 border-flamingo-pink-500 pl-5">
                        <p class="font-extrabold text-forest-green-600 text-2xl">
                            "<?php echo($author_quote); ?>"
                        </p>

                        <p class=" text-forest-green-600 font-semibold mt-8">
                            — <?php echo($author_name); ?>, <?php echo($author_job_title); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-span-full lg:col-span-3 pt-10 lg:pt-0">
                <h3 class="lg:text-xl font-area-normal">Recent Articles</h3>
                <?php

                $paged = (get_query_var("paged")) ? get_query_var("paged") : 1;

                $args = array(
                    "author"         => $author_id,
                    "post_type"      => "post",
                    "posts_per_page" => 5
                );

                $author_posts = new WP_Query($args);

                if ($author_posts->have_posts()):
                    while ($author_posts->have_posts()) : $author_posts->the_post();
                        $article = get_permalink();
                        $label = get_the_title();
                ?>
                    <div class="py-6 lg:text-xl border-b border-forest-green-400">
                        <a 
                            class="font-extrabold hover:text-electric-green-700"
                            href="<?php echo($article); ?>"
                        >
                            <?php echo($label); ?>
                        </a>
                    </div>
                <?php endwhile; ?>

                
                <?php
                    wp_reset_postdata();
                    else: 
                ?>
                    <p>No posts found for this author.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php
get_footer();
