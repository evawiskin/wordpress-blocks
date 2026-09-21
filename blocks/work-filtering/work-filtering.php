<?php
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
				"placeholder" => "Paragraph text goes here, click the plus button to choose a inner block to add to this container."
			]
		]
	];

    $allowed_blocks = [
        "core/heading",
        "core/paragraph"
    ];

    $block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "gretter-50";
    $block_text_color = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "forest-green-500";
    $button_class = "[&.is-active]:border-white [&.is-active]:bg-forest-green-500 [&.is-active]:text-white outline-none focus:outline-2 focus:outline-[#DDEDEE] px-2 py-2 text-xs rounded font-bold transition duration-300 ease-in-out filter-button text-forest-green-500 bg-[#DFEEEB]";

    // Store filter values amd keep track of active filters
    $work_categories = [];
    $active_work_categories = [];

    $work_categories = get_terms([
        "taxonomy" => ["service", "industry"],
        "hide_empty" => false, // Get all terms first, we'll filter manually
    ]);
    
    // Remove unnecessary categories and filter based on visible posts
    $work_categories = array_filter($work_categories, function ($term) {
        // Remove uncategorized
        if ($term->slug === "uncategorized") {
            return false;
        }
        
        // Check if this term has any published posts that are NOT hidden from archive
        $term_query = new WP_Query([
            "post_type" => "work",
            "post_status" => "publish",
            "posts_per_page" => 1, // We only need to know if at least 1 exists
            "fields" => "ids",
            "tax_query" => [
                [
                    "taxonomy" => $term->taxonomy,
                    "field" => "term_id",
                    "terms" => $term->term_id,
                ]
            ],
            "meta_query" => [
                "relation" => "OR",
                [
                    "key" => "cpt_work_hide_from_archive",
                    "compare" => "NOT EXISTS",
                ],
                [
                    "key" => "cpt_work_hide_from_archive",
                    "compare" => "==",
                    "value" => 0
                ]
            ]
        ]);
        
        $has_visible_posts = $term_query->have_posts();
        wp_reset_postdata();
        
        return $has_visible_posts;
    });

    // if when we first load the page, there are active categories in the url, we need to filter the work items
    if(isset($_GET["categories"]) && strlen( strip_tags($_GET["categories"]) > 0)):
        // turn into a string
        $active_work_categories = explode(",", strip_tags($_GET["categories"]));

        //converting the slug we get from the url, to an id we can use
        // if there is an invalid tag, we do not use it (obviously)
        foreach($active_work_categories as $index => $cat):

            // Remove taxonomy flag from slug and get term
            $taxonomy = isset(explode("_", $cat)[0]) ? explode("_", $cat)[0] : "";
            $term = get_term_by("slug", str_replace("{$taxonomy}_", "", $cat), $taxonomy);
            // Add to active filters
            if ($term && isset($term->term_id)) {
                $active_work_categories[$index] = $term->term_id;
            }
            else {
                unset($active_work_categories[$index]);
            }
        endforeach;
    endif;

    // Initialize pagination variables
    $posts_per_page = 6;
    $paged = 1;

    // Initial query arguments
    $args = [
        "post_type"      => "work",
        "posts_per_page" => $posts_per_page,
        "paged"          => $paged,
        "post_status"         => "publish",
        "fields"         => "ids",
        "meta_query" => [
            "relation" => "OR",
            [
                "key" => "cpt_work_hide_from_archive",
                "compare" => "NOT EXISTS",
                "value" => 0
            ],
            [
                "key" => "cpt_work_hide_from_archive",
                "compare" => "==",
                "value" => 0
            ]
        ]
    ];

    // Apply taxonomy filters if any
    if (count($active_work_categories)) {
        $args["tax_query"] = ["relation" => "OR"];
        foreach ($active_work_categories as $term_id) {
            $active_term = get_term($term_id); 
            if (!$active_term) continue;

            $args["tax_query"][] = [
                "terms"    => [$active_term->slug],
                "field"    => "slug",
                "taxonomy" => $active_term->taxonomy,
                "post_status"   => "publish",
                "meta_query" => [
                    "relation" => "OR",
                    [
                        "key" => "cpt_work_hide_from_archive",
                        "compare" => "NOT EXISTS",
                        "value" => 0
                    ],
                    [
                        "key" => "cpt_work_hide_from_archive",
                        "compare" => "==",
                        "value" => 0
                    ]
                ]
            ];
        }
    }

    $active_posts_query = new WP_Query($args);

    // Collect total pages for pagination
    $total_pages = $active_posts_query->max_num_pages;

    // Show block preview image
    if (get_field("is_preview")) :
?>
        <img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/work-filtering-block.jpg")) ?>" width="100%">
<?php
        return;
    endif;
?>

<div id="work" class="<?php echo("bg-{$block_bg} text-{$block_text_color}"); ?> inside-container-md">

    <!-- innerblocks / content  -->
    <div class="py-10">
        <InnerBlocks 
            class="prose" 
            template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
            allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
        />
    </div>

    <!-- Filter section -->
    <div class="container w-full flex flex-wrap flex-col gap-4 md:flex-row justify-center items-center md:items-start">
        <span class="p-1 text-current font-bold order-first text-base">
            Filter
        </span>
                <div 
            id="work-filtering-form"
            class="flex flex-wrap gap-x-3 md:gap-x-6 gap-y-2 justify-center flex-1 opacity-0 max-h-0 transition-all duration-700 ease-in-out" 
        >
            <!-- work filtering -->
            <?php
                foreach($work_categories as $category):
                    // Set/remove button active class
                    if (in_array($category->term_id, $active_work_categories))
                        $button_class .= " is-active";
                    else
                        $button_class = str_replace(" is-active", "", $button_class);                     
                
                    // Create term slug with corresponding taxonomy prepended as a flag
                    $tax_term_slug = "{$category->taxonomy}_{$category->slug}";
            ?>
                <button 
                    class="<?php echo($button_class) ?>"
                    onclick="filterSelection(event, '<?php echo($tax_term_slug); ?>')"
                    data-category="<?php echo($tax_term_slug); ?>"
                >
                    <span class="relative -top-px">
                        <?php echo($category->name); ?>
                    </span>
                </button>  
            <?php endforeach; ?>
        </div>

        <!-- Clear categories button -->
        <div class="flex justify-end">
            <button
                id="clear-filters-button"
                onclick="clearAllCategories()" 
                class="<?php echo(count($active_work_categories) > 0 ? '' : 'hidden '); ?>flex items-center gap-x-2 text-forest-green-500 font-bold mt-1 text-xs"
            >   
                <span>Clear</span>

                <div class="svg-wrapper w-2 h-2 mt-1">
                    <?php echo(get_svg_icon("hy-x", "hiyield-icons") ?: "x"); ?>
                </div>
            </button>
        </div>

    </div>
    <!-- content -->
    <div id="content-overlay" class="my-14 md:my-28 container relative">
        <div 
            id="work-filtering-content"
            class="flex flex-col gap-y-8 lg:gap-y-16"
        >
            <?php
                if($active_posts_query->have_posts()){

                    while($active_posts_query->have_posts()): 
                        $active_posts_query->the_post();

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
        </div>
    </div>
    <div class="flex items-center justify-center">
            <div id="loading-spinner" class="svg-wrapper hidden w-12 h-12 animate-spin">
                <?php
                    echo(get_svg_icon("hiyield-asterisk", "hiyield-icons"));
                ?>
                <p class='hidden text-current text-lg font-bold text-center'>Nothing to show, please remove some filters.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // On load animation for the filters
        const filterContainer = document.getElementById("work-filtering-form");
        if (filterContainer) {
            // Use a small timeout to ensure the browser has calculated the scrollHeight
            setTimeout(() => {
                filterContainer.style.maxHeight = filterContainer.scrollHeight + "px";
                filterContainer.classList.remove("opacity-0");
            }, 100);
        }

        let totalHeightFooter; // Default Height of the footer
        let categoryArr = [];

        let isLoading = false;
        let maxPages = 0;
        let noMorePosts = false;
        let currentPage = 2; // Pagination handling
        let safetyNet = 300; // Safety net for loading posts before reaching the bottom of the page

        const url = new URL(`${siteUrl}/wp-admin/admin-ajax.php`);

        // Debounce function specific to work filtering
        const debounceWorkFilter = (func, wait) => {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        };

        // Create a function to calculate the height of everything after the div with id of content-overlay - getting all of the elements and adding to footerHeight
        const initiateLoadPosition = () => {
            if (categoryArr.length > 0) {
                return false;
            }

            // Get the height of the footer
            const footer = document.querySelector("footer");
            totalHeightFooter = footer ? footer.getBoundingClientRect().height : 2800;

            const workSection = document.getElementById("work");

            // Get all sibling elements after content-overlay
            let sibling = workSection.nextElementSibling;

            let exit = 0;
            // Recursion on page load for calculating the height of everything after the work block
            while (sibling) {
                if (!sibling.nextElementSibling || exit >= 10) break;

                totalHeightFooter += sibling.getBoundingClientRect().height ?? 0;
                sibling = sibling.nextElementSibling;
                exit += 1;
            }

            return true;
        };

        const handleScroll = () => {
            if (isLoading || noMorePosts) return;

            // Calculation for getting bottom of screen position
            const scrollBottom = window.innerHeight + window.scrollY;
            if (!isLoading && scrollBottom >= ((document.documentElement.scrollHeight - totalHeightFooter) - safetyNet)) { // -1500 is a safety net to load before reaching the last post
                loadMorePosts();
            }
        };

        const loadMorePosts = () => {
            // Prevent further calls if already loading or no more posts
            if (isLoading || noMorePosts || categoryArr.length > 0) return;
            
            const outputContainer = document.getElementById("work-filtering-content");

            if (currentPage > maxPages & maxPages !== 0) {
                // If there's no more content, set the noMorePosts flag
                noMorePosts = true;

                const noMorePostsMessage = document.createElement("p");
                noMorePostsMessage.classList.add("text-current", "text-xl", "font-bold", "text-center");
                noMorePostsMessage.textContent = 'No more work to show.';

                outputContainer.appendChild(noMorePostsMessage);

                return;
            }

            isLoading = true;
            safetyNet += 100;

            const loadingSpinner = document.getElementById("loading-spinner");
            loadingSpinner.classList.remove("hidden");

            const overlay = document.getElementById("content-overlay");
            overlay.classList.add("opacity-70");

            url.searchParams.append("action", "ajax_load_more_work");
            url.searchParams.append("paged", currentPage);

            fetch(url, {
                method: "GET",
                headers: {
                    credentials: "same-origin"
                }
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((response) => {
                const data = response.data;

                maxPages = data.max_pages;

                if (data.content || data.max_pages !== 0) {

                    // Insert the new content directly into the DOM - decoding base64 encoded content
                    outputContainer.innerHTML += data.content;

                    currentPage++;
                }
            })
            .catch((error) => {
                console.error("Error loading posts:", error);
            })
            .finally(() => {
                // Reset the loading state and remove the overlay after delay
                isLoading = false;
                overlay.classList.remove("opacity-70");

                loadingSpinner.classList.add("hidden");
            });
        };

        const filterSelection = async(event, taxCategorySlug) => {

            const filterForm = document.getElementById("work-filtering-form");
            const outputContainer = document.getElementById("work-filtering-content");
            const loadingSpinner = document.getElementById("loading-spinner");
            const clearButton = document.getElementById("clear-filters-button");
            const buttons = document.querySelectorAll(".filter-button");
            const currentButton = event.currentTarget;
        
            // Toggle active button styling. If clearing filters remove active styling from all buttons
            if (currentButton) {
                currentButton.classList.toggle("is-active");
            } else {
                buttons.forEach((button) => {  
                    button.classList.remove("is-active");
                });
            }

            // Category is already active, so remove it
            if (categoryArr.includes(taxCategorySlug))
                categoryArr = categoryArr.filter((slug) => slug !== taxCategorySlug);
            else if (taxCategorySlug)
                // Category is not active, so add it
                categoryArr.push(taxCategorySlug);

            // Toggle clear button visibility based on active filters
            if (categoryArr.length > 0) {
                clearButton.classList.remove("hidden");
            } else {
                clearButton.classList.add("hidden");
            }

            // Update the URL with the active categories
            const params = new URLSearchParams();
            let newUrl = "";

            // Remove URL params if no filter applied
            if(categoryArr.length == 0) {
                params.delete("categories");
                newUrl = window.location.href.split("?")[0];
            } else {
                params.set("categories", categoryArr.join(","));
                newUrl = `${window.location.pathname}?${params.toString()}`;
            }
            
            window.history.pushState({}, "", newUrl);

            filterForm.classList.add("pointer-events-none");

            outputContainer.classList.add("opacity-70");
            loadingSpinner.classList.add("hidden");
            
            
            const url = `
                    ${siteUrl}/wp-admin/admin-ajax.php
                    ?action=ajax_block_work_filter&tax_categories=${categoryArr}
                `;

            await fetch(url, {
                method: "GET",
                headers: {
                    credentials: "same-origin",
                }
            })
            .then((response) => {
                return response.json();
            })
            .then((response) => {
                outputContainer.classList.remove("opacity-70");
                loadingSpinner.classList.add("hidden");
                outputContainer.innerHTML = response.content;
            })
            .catch((error) => {
                console.error(error);
            });
            
            // add it here after we have done the ajax call
            filterForm.classList.remove("pointer-events-none");

        };

        const clearAllCategories = () => {
            categoryArr = [];
            filterSelection(false, false);

            const historyUrl = window.location.href;

            document.location.reload("/work");

            // Append original url with params to history
            window.history.pushState({}, "", historyUrl);
        };

        // Make functions globally accessible for inline onclick handlers
        window.filterSelection = filterSelection;
        window.clearAllCategories = clearAllCategories;

        // Initialize on page load
        window.scrollTo(0, 0);

        const buttons = document.querySelectorAll(".filter-button");

        // Get active categories from buttons
        buttons.forEach((button) => {  
            if (button.classList.contains("is-active")) 
                categoryArr.push(button.dataset.category);
        });

        if (categoryArr.length === 0) {
            initiateLoadPosition();

            // Listen for scroll event to detect when to load in posts with debounce
            window.addEventListener("scroll", debounceWorkFilter(handleScroll, 100));
        }

        // Scroll to the top on page reload
        window.addEventListener("beforeunload", () => {
            window.scrollTo(0, 0); // Scroll to the top before the page reloads
        });
    });

</script>
