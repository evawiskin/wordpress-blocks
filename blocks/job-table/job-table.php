<?php
	// Render block preview image.
	if (display_block_preview_image($block)) return;

	$block_classes = new BlockClasses($block);

    $template = [
        [
            "core/heading",
            [
                "level" => 2,
                "placeholder" => "Heading Goes Here"
            ]
        ],
        [
            "core/paragraph",
            [
                "placeholder" => "Paragraph text goes here"
            ]
        ]
    ];

	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph"
	];

    $no_vacancies_text = get_field("block_job_listing_no_vacancies_text") ?: "There are currently no vacancies. Please check back later.";

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/jobs-table-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;
?>

<div class="container <?php echo($block_classes); ?>">
    <div class="flex flex-col gap-y-8 md:gap-y-16">
        <InnerBlocks  
            class="prose col-span-12 lg:col-span-4"
            template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
            allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
        />
        <div>
        <?php 

$args = [
    "post_type"         => "job",
    "posts_per_page"    => -1
];

if(get_field("block_job_listing_select"))
    $args["post__in"] = get_field("block_job_listing_select");

$job_vacancies = new WP_Query($args);

if($job_vacancies->have_posts()):
?>
                <div class="hidden md:grid grid-cols-12 pb-6 text-white theme-heading-micro gap-x-8">
                    <!-- role heading --> 
                    <div class="col-span-3 px-6">
                        <h3>
                            Role
                        </h3>
                    </div>
                    <!-- description heading -->
                    <div class="col-span-5">
                        <h3>
                            Description
                        </h3>
                    </div>
                    <!-- salary heading -->
                    <div class="col-span-2">
                        <h3>
                            Salary
                        </h3>
                    </div>
                </div>

            <?php 
                    //index so we can add the border to the bottom of the last card
                    $index = 0;
                    while($job_vacancies->have_posts()):
                        $job_vacancies->the_post();

                        get_template_part(
                            "template-parts/cards/template-part", 
                            "job-vacancy-card",
                            [
                                "job_id"         => get_the_ID(),
                                "index"          => $index,
                                "max_posts"      => $job_vacancies->post_count
                            ]
                        );
                        $index++;
                    endwhile;
                    
                    wp_reset_postdata();
                else:
                    echo($no_vacancies_text);
                endif;
            ?>
        </div>
    </div>
</div>
