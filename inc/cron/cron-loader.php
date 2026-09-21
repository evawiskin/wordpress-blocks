<?php
	// CRON starter file that makes WordPress functions available

	/// WordPress Root Directory
	$wordpress_root = 
		strlen($_SERVER["DOCUMENT_ROOT"]) > 0 
			? $_SERVER["DOCUMENT_ROOT"] 
			: $_SERVER["PWD"];

	/// Check for WP App Nesting
	if (file_exists("{$wordpress_root}/wp-app"))
		$wordpress_root .= "/wp-app";

	/// Check WordPress Load
	if (!file_exists("{$wordpress_root}/wp-load.php")) {
		echo("
			<br> --- <br>
			Unable to find WP LOAD file.
			<br>
			Looking inside the directory of : {$wordpress_root} :
			<br> --- <br>
		");

		die();
	}

	// Require Setup

	/// Load WP Files
	require_once("{$wordpress_root}/wp-load.php");