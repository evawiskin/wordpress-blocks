<?php
	/*
		Example Accordions use.
		Simply pull this template part into your template file (eg front-page.php) to undertstand how this functionality can be used.
	*/

//example accordion data. this could take the form of a custom post type, acf repeater field, something from an api etc

$data = [
	[
		"accordion_title"	=> "Accordion Number 1",
		"accordion_content"	=> "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Rerum, culpa nam hic velit ipsum modi non temporibus sint maxime inventore eaque ipsam impedit dolor quisquam blanditiis! Soluta a quod laudantium.",
	],
	[
		"accordion_title"	=> "Accordion Number 2",
		"accordion_content"	=> "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Rerum, culpa nam hic velit ipsum modi non temporibus sint maxime inventore eaque ipsam impedit dolor quisquam blanditiis! Soluta a quod laudantium.",
	],
	[
		"accordion_title"	=> "Accordion Number 3",
		"accordion_content"	=> "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Rerum, culpa nam hic velit ipsum modi non temporibus sint maxime inventore eaque ipsam impedit dolor quisquam blanditiis! Soluta a quod laudantium.",
	]
];

//counter which is required for unique accordion ids
//and aria labelling inside the partial
$accordion_count = 0;

//get script build constant from wp-config
$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

//enqueue accordions JS
if($accordions_script_uri = get_theme_file_uri("assets/dist/js/accordions.js"))
	wp_enqueue_script("custom-accordions", $accordions_script_uri, [], $build_version, false);

//setting multiple open to true or false easily allows to switch between multiple
//open accordions or not. Note that a wrapper IS needed around the accordions as this is 
//used by the JS to iterate through accordions and close if multiple is false
?>
<div class="container">
	<div data-multiple-open="true" class="mx-auto max-w-prose">
		<?php
			foreach($data as $accordion){

				//pass in the data and get the partial
				$args = [
						"accordion_title" => $accordion["accordion_title"], 
						"accordion_content" => $accordion["accordion_content"], 
						"accordion_count" => $accordion_count
					];
				get_template_part("template-parts/partials/partial", "accordion", $args);
				$accordion_count++;
			}
		?>
	</div>
</div>