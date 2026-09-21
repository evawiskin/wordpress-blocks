<?php 


	 /**
     * 
     * Function that displays a preview image for a block in the editor.
     * 
     * @param array $block The block data
     * 
     */
    function display_block_preview_image($block) {

        if(empty($block) || !is_array($block) || empty($block["render_template"]))
            return false;

        $block_name = str_replace(".php", "", $block["render_template"]);

        //check if the file exists
        $file_path = get_theme_file_uri("/blocks/{$block_name}/preview.jpg");
        $file_exists = file_exists(get_theme_file_path("/blocks/{$block_name}/preview.jpg"));
        if(get_field("preview") && is_admin() && $file_exists){
            echo("<img width=\"100%\" height=\"100%\" src=\"" . $file_path . "\" alt=\"\" />");
            return true;
        }
        return false;
    }


    /**
	 * @param array $block The block data
	 * @param bool $mobile Whether to set the padding for mobile
	 * @param array $breakpoints The breakpoints for the block e.g ["md", "lg"]
	 */
	function get_block_spacing($block, $mobile=false, $breakpoint = "xl") {
		$block_spacing = "";
	
		/*
			We don't need to do this on the admin side, Gutenberg will handle it in a wrapper div 
		*/
		if (
			!is_array($block) || 
			!isset($block["style"]["spacing"]) ||
			is_admin()
		) {
			return $block_spacing;
		}
	
		$spacing_data = $block["style"]["spacing"];
		$classes = [];
	
		// Define allowed scaled sizes based on your spacingSizes JSON
		$allowed_scaled_sizes = [
			"2xs",
			"xs",
			"sm",
			"md",
			"lg",
			"xl",
			"2xl",
			"3xl"
		];
	
		foreach ($spacing_data as $spacing_type => $spacing_values) {
			foreach ($spacing_values as $side => $value) {
				// Break down Gutenberg string
				$spacing_value_parts = explode("|", $value);
				
				// Get last piece (our slug)
				$spacing_value = end($spacing_value_parts);
	
				// Add the desktop class
				if ($mobile) {
					$desktop_class = "{$breakpoint}:{$spacing_type}-{$side}-{$spacing_value}";
					$classes[] = $desktop_class;

					// Check if the current spacing_value can be scaled
					if (isset($allowed_scaled_sizes[$spacing_value])) {
						$mobile_spacing_value = $allowed_scaled_sizes[$spacing_value];
						$mobile_class = "{$spacing_type}-{$side}-{$mobile_spacing_value}";
						$classes[] = $mobile_class;
					}

				} else {
					$desktop_class = "{$spacing_type}-{$side}-{$spacing_value}";
					$classes[] = $desktop_class;
				}
			}
        }

		// Remove duplicate classes to avoid redundancy, just in case.
		$classes = array_unique($classes);

		return implode(" ", $classes);
    }

	/**
     * 
     * Returns the base allowed blocks, allowing additional allowed blocks to be defined.
     * 
     */
	function get_allowed_blocks($additional_blocks = []){

        $default_allowed_blocks = [
			"core/heading",
			"core/paragraph",
			"core/list",
			"core/image",
			"core/quote",
			"core/separator",
			"core/embed",
			"hiyield/buttons",
			"hiyield/icon",
			"hiyield/icon-content",
			"hiyield/masonry-images",
			"hiyield/featured-image",
			"hiyield/custom-list"
        ];

		//Test Comment - should go to staging2

        $allowed_blocks = array_merge(
            $default_allowed_blocks,
            $additional_blocks
        );

        return($allowed_blocks);
    }


	/**
	 * Renders accordion items based on provided post IDs. Reduces repetitive code in FAQ block.
	 * @param array $post_ids Array of post IDs to render accordion items for.
	 * @param string $block_bg Optional block background class to pass to partial.
	 * @param string $block_text Optional block text class to pass to partial.
	 */
	function render_accordion_items($post_ids, $block_bg = '', $block_text = '') {
		if (empty($post_ids) || !is_array($post_ids)) {
			return;
		}

		$accordion_count = 0;
		foreach($post_ids as $post_id) {
			$accordion_title = get_field("cpt_faq_question", $post_id);
			$accordion_content = get_field("cpt_faq_answer", $post_id);

			$acc_args = [
				"accordion_count" => $accordion_count,
				"accordion_title" => $accordion_title,
				"accordion_content" => $accordion_content,
				"block_bg" => $block_bg,
				"block_text" => $block_text
			];

			// Use the correct partial path which matches template-parts/partials/partial-accordion.php
			get_template_part("template-parts/partials/partial", "accordion", $acc_args);

			$accordion_count++;
		}
	}
