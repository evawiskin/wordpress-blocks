<?php
	/*
		Example Google Map use.
		Simply pull this template part into your template file (eg front-page.php) to undertstand how this functionality can be used.
	*/	

	$dummy_locations = [
		[
			"lat" => "50.9058",
			"lng" 	=> "-1.3910",
			"title"		=> "St Marys Stadium"
		],
		[
			"lat" 	=> "50.8616",
			"lng" 	=> "-0.0837",
			"title"	=> "AMEX Stadium"
		],
		[
			"lat" 	=> "50.7963",
			"lng" 	=> "-1.0638",
			"title"	=> "Fratton Park"
		],
		[
			"lat" 	=> "50.7352",
			"lng" 	=> "-1.8383",
			"title"	=> "Vitality Stadium"
		]
	];

	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
	$map_api_key = get_field("option_api_keys_google_maps_key", "options");

	if(($google_map_script_uri = get_theme_file_uri("assets/dist/js/google-map.js")) && $map_api_key){
		wp_enqueue_script("custom-google-map-js", $google_map_script_uri, [], $build_version, true);
		wp_enqueue_script("google-map-api", "https://maps.googleapis.com/maps/api/js?key={$map_api_key}&callback=initMap&libraries=&v=weekly", '', $build_version, true);
	}

?>

<div class="container outside-container">
	<div class="mx-auto max-w-prose">
		<?php if ($dummy_locations): ?>
			<div id="google-map" class="aspect-w-2 aspect-h-2 acf-map"></div>
			<ul>
				<?php foreach($dummy_locations as $location): ?>
					<li 
						class="google-map-location"
						data-lat="<?php echo($location["lat"]); ?>"
						data-lng="<?php echo($location["lng"]); ?>"
						data-title="<?php echo($location["title"]); ?>"
					></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>