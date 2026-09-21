<?php
    function render_block_anchor($block){
        if(array_key_exists("anchor", $block))
            get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block["anchor"]]);
    }
    
    /**
     * 
     * Pulls in block background media (image or video) using shared ACF block fields (front-end only).
     * 
     */
    function render_block_background_media_frontend($block) {

        // Get background media type and sources
        $is_video_background_type = get_field("background_media_background_type");
        $bg_img_desktop = get_field("background_media_desktop_image_id");
        $bg_img_mobile = get_field("background_media_mobile_image_id");
        if (!$bg_img_mobile) {
            $bg_img_mobile = $bg_img_desktop;
        }

        // Get background video - force to be an array
        $video_file = get_field("background_media_background_video_file") ?: [];
        if (!is_array($video_file)) {
            $video_file = [];
        }
        $video_url = isset($video_file["url"]) ? $video_file["url"] : false;
        $video_type = isset($video_file["mime_type"]) ? $video_file["mime_type"] : false;

        // Store background media details in global to reuse in editor action. This saves us needing to call ACF functions twice per block.
        global $hy_block_background_media;
        $hy_block_background_media = [
            "is_video_background_type" => $is_video_background_type,
            "bg_img_desktop" => $bg_img_desktop,
            "video_url" => $video_url,
            "video_type" => $video_type
        ];

        // Do not output anything in the backend (admin)
        if (is_admin()) {
            return;
        }

        // Output image(s) if present
        if ((!$is_video_background_type) && ($bg_img_desktop || $bg_img_mobile)) : ?>
            <div class="absolute inset-0">
                <?php
                    if ($bg_img_desktop) {
                        $image_class = "size-full object-cover object-center";
                        if ($bg_img_mobile && ($bg_img_mobile !== $bg_img_desktop)) {
                            $image_class .= " hidden md:block";
                        }
                        echo wp_get_attachment_image($bg_img_desktop, "full", false, [ "class" => $image_class . " pointer-events-none" ]);
                    }
                    if ($bg_img_mobile && ($bg_img_mobile !== $bg_img_desktop)) {
                        $image_class = "size-full object-cover object-center";
                        if ($bg_img_desktop) {
                            $image_class .= " md:hidden";
                        }
                        echo wp_get_attachment_image($bg_img_mobile, "large", false, [ "class" => $image_class . " pointer-events-none" ]);
                    }
                ?>
            </div>
        <?php endif;

        // Output video if present
        if (!empty($video_url) && !empty($video_type)) : ?>
            <video 
                width="100%" 
                height="100%" 
                autoplay muted loop
                class="absolute inset-0 size-full object-cover pointer-events-none z-0"
            >
                <source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_type); ?>">
            </video>
        <?php endif;

        // Overlay logic based on block color/gradient
        $has_bg_color = !empty($block["backgroundColor"]);
        $has_gradient = !empty($block["gradient"]);
        if ($has_bg_color || $has_gradient) :
            $overlay_color = "bg-black/30";
            if ($has_gradient) {
                $overlay_color = "has-{$block["gradient"]}-gradient-background";
            } elseif ($has_bg_color) {
                $overlay_color = "has-{$block["backgroundColor"]}-background-color opacity-30";
            }
        ?>
            <div aria-hidden="true" class="absolute inset-0 pointer-events-none <?php echo($overlay_color); ?>"></div>
        <?php endif;
    }

    // Render background media for block - editor only 
    // (this must be applied after the closing section tag in the editor so that the image spans the editor block wrapper while padding is applied)
    function render_block_background_media_editor($block) {

        // Get background media details from global set in previous action. Check is_admin to ensure this only runs in the editor.
        global $hy_block_background_media;
        if (empty($hy_block_background_media) || !is_array($hy_block_background_media) || !is_admin()) {
            return;
        }

        // Merge expected keys into global
        $hy_block_background_media = array_merge([
            "is_video_background_type" => false,
            "bg_img_desktop" => false,
            "has_bg_overlay" => false,
            "video_url" => false,
            "video_type" => false
        ], $hy_block_background_media);

        // If everything is false bail
        if (empty(array_filter($hy_block_background_media))) {
            return;
        }
        extract($hy_block_background_media);

        // Output background image if set (desktop only) - inline styling to override admin CSS
        if (!empty($bg_img_desktop)) :
            echo(wp_get_attachment_image($bg_img_desktop, "full", false, [
                "style" => "position: absolute; width: 100%; height: 100%; inset: 0; object-fit: cover; z-index: 0"
            ]));

        // Output background video if set - inline styling to override admin CSS
        elseif (!empty($video_url) && !empty($video_type)) :
        ?>
            <video 
                width="100%" 
                height="100%" 
                autoplay muted loop
                style="position: absolute; z-index: 0"
                class="inset-0 size-full object-cover pointer-events-none"
            >
                <source src="<?php echo($video_url); ?>" type="<?php echo($video_type); ?>">
            </video>
        <?php
        endif;

        // Overlay logic based on block color/gradient
        $has_bg_color = !empty($block["backgroundColor"]);
        $has_gradient = !empty($block["gradient"]);
        if ($has_bg_color || $has_gradient) :
            $overlay_color = "bg-black/30";
            if ($has_gradient) {
                $overlay_color = "has-{$block["gradient"]}-gradient-background";
            } elseif ($has_bg_color) {
                $overlay_color = "has-{$block["backgroundColor"]}-background-color opacity-30";
            }
        ?>
            <div aria-hidden="true" class="absolute inset-0 pointer-events-none <?php echo($overlay_color); ?>"></div>
        <?php endif;
    }
    
    add_action("hy_block_start", "render_block_anchor");
    add_action("hy_columns_block_start", "render_block_background_media_frontend");
    add_action("hy_columns_after_block_end", "render_block_background_media_editor");