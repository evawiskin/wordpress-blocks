
<?php
	$apple_favicons_sizes = ["57x57", "60x60", "72x72", "76x76", "120x120", "114x114", "152x152", "144x144"];
	$favicon_sizes = ["16x16", "32x32", "96x96", "196x196"];
	$microsoft_favicon_sizes = ["70x70", "144x144", "150x150", "310x150", "310x310"];

	generate_apple_favicon("#5bbad5", $apple_favicons_sizes);
	generate_favicon($favicon_sizes);
	generate_microsoft_favicon("#FFFFFF", $microsoft_favicon_sizes);
