<?php
	$template = [
		[
			"core/heading",
			[
				"level" => 6,
				"placeholder" => "Heading Goes Here",
				"fontSize" => "micro-heading",
				"textColor" => "electric-green-500"
			]
		],
		[
			"hiyield/custom-list"
		]
	];

	$step_title = get_field("block_step_header");
	$step_icon_file = get_field("block_step_icon");
	$step_icon = ($step_icon_file) ? get_svg_icon($step_icon_file, "icon-selector-icons") : false;

	$icon_after_text = get_field("block_step_icon_after_text") ?: false;
?>

<div class="col-span-1 flex justify-center">
	<div class="w-fit flex flex-col gap-10 lg:gap-20">
		<div class="flex items-center">
			<h3 class="lg:w-3/4 theme-heading-small heading-primary text-electric-green-500 !mb-0"><?php echo($step_title); ?></h3>

			<?php if($step_icon): ?>
				<div class="hidden lg:block w-1/4<?php if(!$icon_after_text) echo(" order-first"); ?>">
					<div class="w-10 h-10 m-auto -rotate-6">
						<?php echo($step_icon); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		
		
		<InnerBlocks
			template="<?php echo(esc_attr(wp_json_encode($template))); ?>"
		/>

		<div class="block lg:hidden<?php if(!$icon_after_text) echo(" order-first"); ?>">
			<?php if($step_icon): ?>
				<div class="w-10 h-10 m-auto">
					<?php echo($step_icon); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>