<?php 
	/*
		This is the team-profile-cards block. 

		The configuration for this block supports:
			- Background Color
			- Text Color
			- Anchor
		It also allows for Inner Blocks via JSX.

		In this block we will loop over each team and output their profile.
		The profile we output will use the template part "cards/template-part-person-card.php".
		This template part takes in the following arguments:
			- post_id
			- allow_click
			- flex_direction
	*/

	// Placeholder content when the block loads in the editor.
	$template = [
		[
			"core/heading",
			[
				"level" => 2,
				"placeholder" => "Heading Goes Here"
			]
		],
		[
			"core/paragraph",
			[
				"placeholder" => "Paragraph text goes here"
			]
		]
	];

	// Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph"
	];

	// Gather our loaded block data
	$block_data = is_array($block ?? []) ? ($block ?? []) : [];
	$block_id = array_key_exists("id", $block_data) ? $block_data["id"] : uniqid("block_");
	$block_bg = array_key_exists("backgroundColor", $block_data) ? "bg-{$block_data["backgroundColor"]}" : "";
	$block_text = array_key_exists("textColor", $block_data) ? "text-{$block_data["textColor"]}" : "";

	$modal_id = "profile-modal";

	// SVG Assets
	$svg_hiyield_asterisk_path = get_theme_file_path("assets/src/imgs/hiyield-icons/hiyield-asterisk.svg");
	$svg_hiyield_asterisk = file_exists($svg_hiyield_asterisk_path) ? file_get_contents($svg_hiyield_asterisk_path) : "*";

	// Enqueue Block Assets
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
	if($block_teams_script_uri = get_theme_file_uri("assets/dist/js/block-teams.js"))
		wp_enqueue_script("block-teams", $block_teams_script_uri, [], $build_version, false);

	// Query our teams and get all of their IDs
	$query_teams = new WP_Term_Query([
		"taxonomy" 	=> "team",
		"fields" 	=> "ids"
	]);

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/teams-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<!--- Define the block wrapper complete with it's background and text colour definitions --->
<section 
	id="<?php echo($block_id); ?>"
	class="relative inside-container-lg <?php echo("{$block_bg} {$block_text}"); ?>"
>
	<?php 
		// Catch incorrect configuration
		if(!$query_teams->terms): 
			if(is_admin()):
		?>
			<h2 class="text-white bg-forest-green-500 p-4">No teams with people found.</h2>
		<?php
			endif;
			return;
		endif; 
	?>

	<?php 
		// Start off with an anchor template if one is in use
		if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>

	<div class="container">
		<!--- Heading and Leading --->
		<InnerBlocks  
			class="prose mb-16" 
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
			allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
		/>

		<!--- Cards Grid --->
		<div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-6">

			<!--- Iteration over teams --->
			<?php 
				foreach($query_teams->terms as $term_id): 
					$term_name = get_term_field("name", $term_id);
					$term_slug = get_term_field("slug", $term_id);

					// Query our people and get all of their IDs
					$query_people = new WP_Query([
						"post_type" 		=> "person",
						"posts_per_page" 	=> -1,
						"tax_query" 		=> [
							[
								"taxonomy" 	=> "team",
								"terms" 	=> $term_id
							]
						]
					]);

					// Defining here to avoid intellisense incorrectly highlighting the element
					$function_args_onmouseover = "'{$block_id}', '{$term_slug}'";
					$function_args_onmouseleave = "'{$block_id}'";
			?>
				<div 
					class="
						col-span-1 relative
						flex flex-col items-center 
						justify-center text-center
					"
					onmouseover="teamCardMouseover(<?php echo($function_args_onmouseover); ?>)"
					onmouseleave="teamCardMouseleave(<?php echo($function_args_onmouseleave); ?>)"
				>
					<div class="absolute children:w-full children:h-full text-forest-green-500">
						<?php echo($svg_hiyield_asterisk); ?>
					</div>
					<h2 class="relative theme-heading-tiny max-w-[10.313rem]">

						<?php
							//detect if first word in term name is < 10 characters
							$term_name_words = explode(" ", $term_name);
							$first_word = $term_name_words[0];
							$first_word_length = strlen($first_word);

							//also detect if there are less than 3 words in the term name
							//we need these to wrap in various ways in the design that aren't fully logical
							if($first_word_length < 10 && count($term_name_words) < 3)
								$term_name = str_replace($first_word, "<span class='block'>{$first_word}</span>", $term_name);
						?>	
						<?php echo($term_name); ?>
					</h2>
				</div>

				<!--- Iteration over people --->
				<?php foreach($query_people->posts as $post): ?>
					<div 
						class="col-span-1 transition-opacity duration-200"
						data-team="<?php echo($term_slug); ?>"
					>
						<?php 
							get_template_part(
								"template-parts/cards/template-part", 
								"person-card",
								[
									"post_id" 			=> $post->ID,
									"flex_direction" 	=> "flex-col",
									"modal_id" => $modal_id,
									"wrapper_class" => "h-full"
								]
							); 
						?>
					</div>
			<?php 
					endforeach;

					// Loop through team members again and check that at least 1 has modal enabled. If so get the modal template and enqueue JS and exit loop.
					foreach($query_people->posts as $post_id) {

						// Get modal template
						if (get_field("cpt_person_display_modal", $post_id)) {
							get_template_part(
								"template-parts/modals/template-part",
								"modal",
								[
									"modal_id" => $modal_id,
									"modal_type" => "profile-modal",
									"modal_persistent" => true,
									"modal_effect" => "slide-right"
								]
							);

							// Enqueue JS
							$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";
							if($modals_script_uri = get_theme_file_uri("assets/dist/js/modals.js"))
								wp_enqueue_script("custom-modals", $modals_script_uri, [], $build_version, false);
							break;
						}
					}
		
						
				endforeach;
			?>

		</div>
	</div>
</section>