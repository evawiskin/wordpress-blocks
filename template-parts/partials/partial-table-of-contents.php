<?php
    $defaults = [
        "post_id" => get_the_ID(),
    ];
    $args = wp_parse_args($args, $defaults);
    // Get all the blocks from the post
    $blocks = parse_blocks(get_the_content(null, false, $args["post_id"]));
    // Get just the heading blocks
    $heading_blocks = array_filter($blocks, function($block) {
        return $block["blockName"] === "core/heading";
    });
    $headings = [];
    function processHeadingMatches($level_match, $anchor_match, $title_match) {
        // if anchor is empty, create one from the title
        if ($anchor_match == false) {
            $anchor_match = sanitize_title_with_dashes($title_match);
        }
        // if the title contains a link, get the link's text
        if (strpos($title_match, "<a") !== false) {
            preg_match("#<a.*?>(.*?)</a>#", $title_match, $title_link_matches);
            $title_match = $title_link_matches[1];
        }
        // return the heading data
        return [
            "level" => $level_match,
            "anchor" => $anchor_match,
            "title" => $title_match,
        ];
    }

    // Loop through each heading block and get the content
    foreach($heading_blocks as $heading_block) {

        $block_content = $heading_block["innerHTML"];

        // If block's classes contains "heading-contents" we remove this heading from table of contents
        if(!isset($heading_block["attrs"]["className"])) {
            continue;
            
        }

        if(!str_contains($heading_block["attrs"]["className"], "heading-contents") ) {
            continue;
        }

        // Check if the heading block doesn't have an id,
        if (strpos($block_content, "id=") === false) {
            // Get the heading level and title
            preg_match("#<h([1-6]).*?>(.*?)</h[1-6]>#", $block_content, $heading_matches);
            // create an id using the title (same as the render filter will) and add it to the array
            if (is_array($heading_matches) && !empty($heading_matches))
                $headings[] = processHeadingMatches($heading_matches[1], false, $heading_matches[2]);
        } else {
            // Get the heading level, id, and title
            preg_match('#<h([1-6]).*?id="(.*?)">(.*?)</h[1-6]>#', $block_content, $heading_matches);
            // check if $matches is an array and not empty
            if (is_array($heading_matches) && !empty($heading_matches))
                $headings[] = processHeadingMatches($heading_matches[1], $heading_matches[2], $heading_matches[3]);
        }
    }

?>

<ul class="list-none flex flex-col gap-3 list-unset">
    <?php foreach($headings as $i => $heading): ?>
        <li data-heading-count="<?php echo($i); ?>" class="group text-sm md:text-lg xl:text-xl text-forest-green-500 font-semibold [&.is-active]:font-extrabold">
            <a class="group-hover:underline group-[.is-active]:underline" href="#<?php echo($heading["anchor"]); ?>">
                <?php echo($heading["title"]); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php 
    // get the magic
    scroll_magic_enqueue_scripts(); 
?>
<script>
    document.addEventListener("DOMContentLoaded", function(event){
        // setup the magic
        const controller = new ScrollMagic.Controller();
        const headings = <?php echo(json_encode($headings)); ?>;
        let scenes = [];
        // loop through each heading and create a scene for it
        headings.forEach(function(heading, i) {
            // use the heading's anchor as the trigger element the toggle the relevant table of contents item's active class
            scenes[i] = new ScrollMagic.Scene({
                triggerElement: `#${heading.anchor}`
            })
                .setClassToggle(`[data-heading-count='${i}']`, "is-active")
                .addTo(controller);
            // store the current scene's offset
            heading.offset = scenes[i].scrollOffset();
            // if not the first scene, set the duration of the previous scene using the current scene's offset minus the previous scene's offset
            if(i > 0)
                scenes[i-1].duration(heading.offset - headings[i-1].offset);
        });
    });
</script>