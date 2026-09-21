<?php
	if(!class_exists("Customized_Walker_Nav_Menu"))
		require_once(get_theme_file_path("inc/classes/class-customized-walker-nav-menu.php"));

	if(!class_exists("Mobile_Walker_Nav_Menu"))
	require_once(get_theme_file_path("inc/classes/class-mobile-walker-nav-menu.php"));

	class Navwalker extends Customized_Walker_Nav_Menu {
		/*
			Start and End EL and LVLs Arrays:
			String Array of instance functions with assigned depths.
			Use the key of "default" for setting a fallback call
		*/
		protected $start_el_callables = [
			"default" 	=> "start_el_depth_0",
			0 			=> "start_el_depth_0",
			1 			=> "start_el_depth_1"
		];
		protected $end_el_callables = [
			"default" 	=> "end_el_depth_0",
			0 			=> "end_el_depth_0"
		];
		protected $start_lvl_callables = [
			"default" 	=> "start_lvl_depth_1",
			0 			=> "start_lvl_depth_1"
		];
		protected $end_lvl_callables = [
			"default" 	=> "end_lvl_depth_1",
			0 			=> "end_lvl_depth_1"
		];

	/**
	 *  Below are some customizable depths and levels.
	 *  They will get called based on the depth in operation.
	 *  Some functions, like start_lvl, start at a higher depth.
	 */

	// Depth 0
		function start_el_depth_0(&$output, &$item) {
			$output .= "<li>";
		}
		function end_el_depth_0(&$output, &$item) {
			$output .= "</li>";
		}

	// Depth 1
		function start_lvl_depth_1(&$output) {
			$output .= "<ul>";
		}
		function end_lvl_depth_1(&$output) {
			$output .= "</ul>";
		}

		function start_el_depth_1(&$output, &$item) {
			$output .= "<li>";
		}
		function end_el_depth_1(&$output, &$item) {
			$output .= "</li>";
		}
	}
