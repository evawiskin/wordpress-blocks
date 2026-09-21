<?php 
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
		],
		[
			"hiyield/buttons"
		]
	];

	// Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph",
		"hiyield/buttons"
	];

	// Gather our loaded block data
	$block_data = is_array($block ?? []) ? ($block ?? []) : [];
	$block_bg = array_key_exists("backgroundColor", $block_data) ? "bg-{$block_data["backgroundColor"]}" : "";
	$block_text = array_key_exists("textColor", $block_data) ? "text-{$block_data["textColor"]}" : "";

	$card_name = get_field("block_join_the_team_card_name") ?: "You";
	$card_job_title = get_field("block_join_the_team_card_job_title") ?: "Your dream job";
	$card_image = get_field("block_join_the_team_card_image") ?: "";

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/join-the-team-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<!--- Define the block wrapper complete with it's background and text colour definitions --->
<section class="relative inside-container-xl <?php echo("{$block_bg} {$block_text}"); ?>">

	<?php 
		// Start off with an anchor template if one is in use
		if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>

	<div class="container grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-8 items-center">

		<div class="col-span-1 lg:col-span-3 xl:col-span-2 xl:col-start-3 order-last lg:order-first">
			<div class="-rotate-6 max-w-56 m-auto">
				<?php 
					$card_args = [
						"post_id" 		=> "join_the_team",
						"allow_click" 	=> false,
						"name"			=> $card_name,
						"job_title"		=> $card_job_title,
						"profile_image" => $card_image
					];

					get_template_part("template-parts/cards/template-part", "person-card", $card_args);
				?>
			</div>
		</div>

		<!--- Content --->
		<div class="col-span-1 lg:col-span-8 xl:col-span-5 lg:col-start-5 xl:col-start-6">
			<InnerBlocks  
				class="prose" 
				template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
				allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>"
			/>
		</div>
	</div>
</section>