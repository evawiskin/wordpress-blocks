<?php 
    // this is partial (mainly) for the work page

    if(!isset($args))
        $args = [];

    $args = array_merge(
        [
            "post_type"         => "work",
            "posts_per_page"    => -1,
            "post_status"       => "publish"
        ], $args
    );


    // exclude any posts that have cpt_work_hide_from_archive set to true
    $args["meta_query"] = [
        "relation" => "OR",
        [
            "key"       => "cpt_work_hide_from_archive",
            "value"     => 0,
            "compare"   => "NOT EXISTS"
        ],
        [
            "key"       => "cpt_work_hide_from_archive",
            "value"     => 0,
            "compare"   => "=="
        ]
    ];

    $query = new WP_Query($args);
    if($query->have_posts()){
        while($query->have_posts()): 
            $query->the_post(); 

            get_template_part(
                "template-parts/cards/template-part",
                "work-card",
                [
                    "post_id"       => get_the_ID()
                ]
            );
        endwhile;
    } else {
        echo("<p class='text-current text-lg font-bold text-center'>Nothing to show, please remove some filters.</p>");
    }

    wp_reset_postdata();
?>