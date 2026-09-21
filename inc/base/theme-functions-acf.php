<?php 	
	// Function to get the blocks from the blocks folder
	function hy_get_blocks() {
		$blocks = [];
		$blocks_dir = get_theme_file_path() . "/blocks/";

		//check directory exists
		if(!is_dir($blocks_dir)) {
			return($blocks);
		}

		// Get blocks recursively
		$blocks = hy_scan_blocks_directory($blocks_dir);
		
		return($blocks);
	}

	/**
	 * Recursively scan the blocks directory to find all blocks
	 * 
	 * @param string $directory The directory to scan
	 * @param string $prefix An optional prefix for nested blocks
	 * @return array Array of block names
	 */
	function hy_scan_blocks_directory($directory, $prefix = '') {
		$blocks = [];
		$excluded = ["..", ".", ".DS_Store", "_base-block", "_unused"];

		// Catch missing folder
		if (!is_dir($directory)) return $blocks;

		foreach (scandir($directory) as $item) {
			if (in_array($item, $excluded)) continue;

			$path = $directory . '/' . $item;

			// Skip if this or any parent directory is _unused
			if (strpos($prefix . '/' . $item, '/_unused/') !== false || $item === '_unused') continue;

			if (is_dir($path)) {
				// Use directory_filenames_callback to check for block.json
				$has_block_json = false;
				directory_filenames_callback($path, ['json'], function($filename) use (&$has_block_json) {
					if ($filename === 'block.json') $has_block_json = true;
				});

				if ($has_block_json) {
					$block_name = $prefix ? $prefix . '/' . $item : $item;
					$blocks[] = $block_name;
				}

				// Recursively scan subdirectories
				$nested_blocks = hy_scan_blocks_directory($path, $prefix ? $prefix . '/' . $item : $item);
				if (!empty($nested_blocks)) {
					$blocks = array_merge($blocks, $nested_blocks);
				}
		 }
		}

		return $blocks;
	}


	// Function to get the options directories from the options folder
	function hy_get_acf_field_groups(){
		$options = [];

		//check directory exists 
		if(!is_dir(get_theme_file_path()."/acf-json/")){
			return($options);
		}

		//obtain blocks from directory, strip out any funny business
		$options = scandir(get_theme_file_path()."/acf-json/");
		$options = array_values(array_diff($options, ["..", ".", ".DS_Store", "_base-block"]));
		return($options);
	}

	// Return the first n sentences of the excerpt
	function get_the_excerpt_first_sentences($post_id, $num_sentences = 0) {
		$excerpt = get_the_excerpt($post_id);
		$excerpt_split = explode(". ", $excerpt);
		$excerpt_trimmed = "";

		if (!$num_sentences || count($excerpt_split) <= $num_sentences)
			return $excerpt;

		foreach($excerpt_split as $index => $sentence) {
			$excerpt_trimmed .= "{$sentence}. ";
			if ($index + 1 == $num_sentences)
				break;
		}

		return strlen($excerpt_trimmed) ? $excerpt_trimmed : false;
	}

	/* ACF Wrapper Functions */

	// Function to return the repeater field content as an array
	function get_repeater_field_content_as_array($selector, $post_id = false) {
		$field = get_field($selector, $post_id);
		if(!$field || !is_array($field) || !$field[0]) 
			return false;
		$sub_field_names = array_keys($field[0]);
		$content = [];
		$row_index = 0;
		while(have_rows($selector, $post_id)) {
			the_row();
			$content[$row_index] = [];
			foreach($sub_field_names as $sub_field_name) {
				$name = str_replace("{$selector}_", "", $sub_field_name);
				$content[$row_index][$name] = get_sub_field($sub_field_name);
			}
			$row_index++;
		}
		return $content;
	}


		// Function to set proper block ID (with type of block for easier code reading)
	function set_block_id( $block , $override_slug = false ) {

		if( !is_array($block) ) {
			return uniqid();
		}

		$name = "";
		$block_id = "";
		$name_isolated = "";
		$final_id = uniqid();

		if( array_key_exists("name", $block) ) {
			$name = $block["name"];
			$name_array = explode("/", $name);
			$name_isolated = end($name_array);
		}

		if($override_slug) {
			$name_isolated = $override_slug;
		}

		if( array_key_exists("id", $block) ) {
			$block_id = $block["id"];
		}

		if($name_isolated && $block_id) {
			// If we have both ID and name
			$final_id = "{$name_isolated}_{$block_id}";
		} elseif($name_isolated) {
			// If we have name only
			$final_id = "{$name_isolated}_{$final_id}";
		} elseif($block_id) {
			$final_id = $block_id;
		}

		return $final_id;
	}

	/**
	 * @return bool Returns true if the field is not empty, otherwise false.
	 */
	function has_field_value(string $field_name, string $context = "option") {
		$value = get_field($field_name, $context);
		return !empty($value);
	}