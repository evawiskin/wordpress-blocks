<?php

	/* 
		Register Rest API route for purge cache
	*/
	function init_wp_rocket_routes() {

		// set namespace
		$namespace = "custom/v1";
	
		// route
		$route = "/clear-wp-rocket-cache";
	
		// parameters for current endpoint
		$route_params = [
			"methods"  => "POST",
			"callback" => "rest_purge_wp_rocket_cache",
			"permission_callback" => function($request) {
				
				// set secret key
				$secret_key = "Dm6v2MLMOlCnLCr";

				//check for X-WP-Rocket-Secret-Key header
				$provided_key = $request->get_header("X-WP-Rocket-Secret-Key");

				// check if secret key is set
				if(!$provided_key) {
					return new WP_Error("rest_forbidden", __("You do not have permission to access this resource.", "custom"), ["status" => 401]);
				}

				if($provided_key !== $secret_key) {
					return new WP_Error("rest_forbidden", __("You do not have permission to access this resource.", "custom"), ["status" => 401]);
				}

				return true;
			}
		];
	
		register_rest_route( $namespace, $route, $route_params );
	
	}


/* REST API Routes */
	add_action("rest_api_init", "init_wp_rocket_routes");