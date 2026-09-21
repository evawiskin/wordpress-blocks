<?php 
    if (!$post) return;
    else

    $post_url = get_the_permalink();

    if(!isset($args))
        $args = [];

    $args = array_merge(
        [
            "icons"         => [],
            "wrapper_classes"      => "gap-x-8",
            "bookmark"      => false,
            "icon_classes"  => ""
        ], 
        $args
    );  
?>
    <div class="w-full items-center justify-between md:flex">
        <div class="relative w-full flex flex-wrap items-center <?php echo($args["wrapper_classes"]); ?>"> 
            <?php if($args["bookmark"]): ?>
                <button 
                    aria-label="Bookmark Page"
                    onclick="bookmarkPage(event)" 
                    class="w-5 h-5 transition-colors duration-200 hover:text-electric-green-500"
                >
                    <span class="sr-only">Bookmark Page</span>
                    <span aria-hidden="true" class="flex gap-2.5 items-center">
                        <?php 
                            $bookmark_icon = get_theme_file_path("assets/dist/imgs/feather-icons/bookmark.svg");
                            if (file_exists($bookmark_icon)) :
                        ?>
                            <span class=" block w-5 h-5"><?php  echo(file_get_contents($bookmark_icon)); ?></span>
                        <?php
                            endif;
                        ?>
                    </span>
                    <span id="bookmark-message" class="absolute left-0 -bottom-8 h-auto w-full text-center"></span>
                </button>
            <?php
                endif;
                // Create array of social buttons we want to add.
                $social_share_buttons = $args["icons"];

                foreach($social_share_buttons as $social_button) {
                    switch($social_button) {
                        case "twitter" :
                            $url = "https://twitter.com/intent/tweet?text={$post_url}";
                            break;
                        case "facebook" :
                            $url = "https://www.facebook.com/sharer/sharer.php?u={$post_url}";
                            break;
                        case "linkedin" :
                            $url = "https://www.linkedin.com/shareArticle?mini=true&url={$post_url}";
                            break;
                        default :
                            break;
                    }
                    $icon_file_path = get_theme_file_path("assets/dist/imgs/socials/{$social_button}.svg");
                    ?>
                        <a 
                            class="w-6 h-6 transition-colors duration-300 <?php echo($args["icon_classes"]); ?>"
                            aria-label="<?php echo(ucfirst($social_button)); ?> Social Link"
                            href="<?php echo($url) ?>" 
                            target="_blank"
                        >
                        <span class="sr-only"><?php echo(ucfirst($social_button)); ?> Social Link</span>
							<span aria-hidden="true">
                            <?php 
                                $social_icon = get_theme_file_path("assets/dist/imgs/socials/{$social_button}.svg");
                                if (file_exists($social_icon))
                                    echo(file_get_contents($social_icon))
                            ?>
                        </span>
                        </a>
                    <?php
                }
            ?>
            <a 
                href="<?php echo($post_url); ?>" 
                aria-label="Copy Post Link"
                onclick="copyPostLink(event)" 
                class="w-6 h-6 transition-colors duration-300 hover:text-electric-green-500 <?php echo($args["icon_classes"]); ?>"
            >
                <span class="sr-only">Copy Post Link</span>
                <span aria-hidden="true" class="flex gap-2.5 items-center">
                    <?php 
                        $link_icon = get_theme_file_path("assets/dist/imgs/feather-icons/link.svg");
                        if (file_exists($link_icon)) :
                    ?>
                            <span class=" block w-5 h-5"><?php  echo(file_get_contents($link_icon)); ?></span>
                    <?php
                        endif;
                    ?>
                </span>
                <span id="copy-message" class="absolute left-0 -bottom-8 h-auto w-full text-center"></span>
            </a>
        </div>
    </div>
    
    <script>
        // Post link copy button js
        const copyPostLink = (event) => {
            event.preventDefault();
            tempInput = document.createElement("input");
            pageUrl = window.location.href;
            document.body.appendChild(tempInput);
            tempInput.value = pageUrl;
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            // alert("URL Copied to Clipboard");
            document.getElementById("copy-message").innerHTML = "URL Copied to Clipboard";
            setTimeout(() => {
                document.getElementById("copy-message").innerHTML = "";
            }, 3000);
        }

        // Bookmark button js
        const bookmarkPage = (event) => {

            // It doesn't look like you can do this on browsers anymore due to new security restrictions.
            // This is a bummer (copilot's words). Added a simple alert with instructions of how to add a bookmark.
            // Potentially older browsers will allow bookmarks so we could add functionality for those browsers.

            //alert("Press " + (navigator.userAgent.toLowerCase().indexOf("mac") != -1 ? "Cmd" : "Ctrl") + "+D to bookmark this page.");
            document.getElementById("bookmark-message").innerHTML = "Press " + (navigator.userAgent.toLowerCase().indexOf("mac") != -1 ? "Cmd" : "Ctrl") + "+D to bookmark this page.";
            setTimeout(() => {
                document.getElementById("bookmark-message").innerHTML = "";
            }, 3000);

        }
    </script>