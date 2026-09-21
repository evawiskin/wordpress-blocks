<?php 
	//counter which is required for unique accordion ids
	//and aria labelling inside the partial
	$accordion_count = 0;

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
	//Core Gutenberg blocks that are allowed to be used within this block.
	$allowed_blocks = [
		"core/heading",
		"hiyield/custom-heading",
		"core/paragraph",
		"hiyield/buttons"
	];

	//get script build constant from wp-config
	$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

	$block_data = $block;
	$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
	$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";
	$block_text_center = ($block_data && array_key_exists("alignText", $block_data) && $block_data["alignText"] == "center" ) ? true : false;

	$container = get_field("block_content_hero_container_size") ?: "inside-container-lg";
	$block_asterisk_overflow = get_field("block_content_hero_overflow_y") ? true : false;

	// Show block preview image
    if (get_field("is_preview")) :
?>
		<img src="<?php echo(get_theme_file_uri("assets/dist/imgs/block-previews/content-hero-block.jpg")) ?>" width="100%">
<?php
		return;
	endif;
?>

<section 
	class="
		<?php echo($container); ?> 
		overflow-x-clip relative 
		<?php 
			if($block_text_center) 
				echo("text-center "); 
			if($block_bg) 
				echo("bg-{$block_bg} "); 
			if($block_text) 
				echo(" text-{$block_text}"); 
			if(!$block_asterisk_overflow || $block_asterisk_overflow == 0)
				echo(" overflow-y-hidden");
		?>
	"
>

	<?php if(array_key_exists("anchor", $block_data))
			get_template_part("template-parts/components/template-part", "anchor", ["anchor" => $block_data["anchor"]]);
	?>	

	<div class="absolute top-1/4 md:top-1/2 -translate-y-1/2 -right-[20%] w-1/2 z-10 text-electric-green-500 opacity-20">
		<?php echo(get_svg_icon("hiyield-asterisk", "hiyield-icons")); ?>
	</div>

	<div class="container relative z-10">
		<?php 
			if(is_single()) :
				$post_id = get_the_ID();
				$post_type = get_post_type($post_id);
				if($post_type == "service")
					$archive_link = "/services/";
				else
					$archive_link = get_post_type_archive_link($post_type);
				
		?>
			<a 
				href="<?php echo($archive_link); ?>" 
				class="flex items-center gap-4 mb-16"
			>
				<?php if($arrow_left_icon = get_svg_icon("arrow-left")): ?>
					<span class="w-4 h-4"><?php echo($arrow_left_icon); ?></span>
				<?php endif; ?>

				Back
			</a>
		<?php endif; ?>

		<div class="grid grid-cols-10 gap-4">
			<InnerBlocks  
				class="prose col-span-8 sm:col-span-6 xl:col-span-5"
				template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
				allowedBlocks="<?php echo(esc_attr(wp_json_encode($allowed_blocks))); ?>" 
			/>

		</div>
	</div>
</section>