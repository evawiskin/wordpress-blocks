<?php
	/**
	 * Base Requirements - Don't Add Junk To This File - Require It Once!
	 *
	 * @link https://developer.wordpress.org/themes/basics/theme-functions/
	 *
	 * @package WordPress
	 * @subpackage WordPress
	 * @since 1.0.0
	 */

	/**
	 * Order Of Initalisation:
		* 1. Base WP Init 
		* 2. Enqueue Scripts/Styles
		* 3. Get Theme Functions
		* 
		* 4. Custom Action Functions File(s)
		* 5. Custom Filter Functions File(s)
		* 6. Custom Taxonomy Functions
		* 7. Custom Post Type Functions
		* 
		* 8.  Use Template Actions File(s)
		* 9.  Use Template Filters File(s)
		* 10. Use Template Taxonomy
		* 11. Use Template Post Types
		* 
	* The Use of Functions are permitted in: 
		* 1. Init
		* 2. Enqueue
		* 3. Template Part Files
		* 
		* 4. Template Actions File(s)
		* 5. Template Filters File(s)
		* 6. Template Taxonomy
		* 7. Template Post Types
		* 
	* The Declaration of Functions are permitted in:
		* 1. Init 
		* 2. Enqueue
		* 3. Theme Functions
		* 
		* 4. Custom Actions File(s)
		* 5. Custom Filters File(s)
		* 6. Custom Taxonomy
		* 7. Custom Post Types
		*
	*/



	// Initilise Global PHP:
	require_once(get_theme_file_path("/inc/base/init.php"));

	// Enqueue Scripts & Styles:
	require_once(get_theme_file_path("/inc/base/enqueue.php"));

	// Load Theme Functions:
	require_once(get_theme_file_path("/inc/base/theme-functions.php"));

	// Load Block Specific Theme Functions:
	require_once(get_theme_file_path("/inc/base/theme-functions-blocks.php"));

	// Load ACF Specific Theme Functions:
	require_once(get_theme_file_path("/inc/base/theme-functions-acf.php"));

	// Load Block Classes
	require_once(get_theme_file_path("/inc/classes/class-block-classes.php"));

	// Load Nav Walker
	require_once(get_theme_file_path("/inc/classes/class-navwalker.php"));

	// Custom Actions File(s):
	directory_filenames_callback(
		$custom_actions_path = get_theme_file_path("/inc/hooks/actions/"),
		["php"],
		function (string $filename) use ($custom_actions_path) {
			require_once("$custom_actions_path/$filename");
		}
	);

	// Custom Filters File(s):
	directory_filenames_callback(
		$custom_filters_path = get_theme_file_path("/inc/hooks/filters/"),
		["php"],
		function (string $filename) use ($custom_filters_path) {
			require_once("$custom_filters_path/$filename");
		}
	);

	// Custom Taxonomy File:
	require_once(get_theme_file_path("/inc/hooks/register/custom-taxonomy.php"));

	// Custom REST API endpoints:
	require_once(get_theme_file_path("/inc/hooks/register/custom-rest-api.php"));

	// Custom Post Types File:
	require_once(get_theme_file_path("/inc/hooks/register/custom-post-types.php"));

	// Custom Block Styles File:
	if (function_exists("register_block_style")){
		require_once(get_theme_file_path("/inc/hooks/register/custom-block-styles.php"));
	}

	// Custom Block Pattern File:
	if (function_exists("register_block_pattern")){
		require_once(get_theme_file_path("/inc/hooks/register/custom-block-patterns.php"));
	}

	// Template Actions File(s):
	directory_filenames_callback(
		$template_actions_path = get_theme_file_path("/inc/template/actions/"),
		["php"],
		function (string $filename) use ($template_actions_path) {
			require_once("$template_actions_path/$filename");
		}
	);

	// Template Filters File(s):
	directory_filenames_callback(
		$template_filters_path = get_theme_file_path("/inc/template/filters/"),
		["php"],
		function (string $filename) use ($template_filters_path) {
			require_once("$template_filters_path/$filename");
		}
	);

	// Register ACF blocks found in /blocks/<name>/block.json
add_action( 'init', function () {
    foreach ( glob( get_stylesheet_directory() . '/blocks/*/block.json' ) as $block ) {
        register_block_type( $block );
    }
} );
// Tell ACF to load/save field definitions from each block folder.
add_filter( 'acf/settings/load_json', function ( $paths ) {
    foreach ( glob( get_stylesheet_directory() . '/blocks/*', GLOB_ONLYDIR ) as $dir ) {
        $paths[] = $dir;
    }
    return $paths;
} );