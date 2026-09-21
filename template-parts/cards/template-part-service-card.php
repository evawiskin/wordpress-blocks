<?php
	if (!isset($args))
		$args = [];

	$args = array_merge([
		"data_id" => "",
		"bg_color" => "",
		"lead_color" => "",
		"text_color" => "",
		"top_icon" => false,
		"help_icon" => "arrow-right",
		"heading" => "",
		"link" => false,
		"content" => "",
		"onclick" => "",
	], $args);

	$data_id = $args["data_id"];
	$bg_color = $args["bg_color"];
	$lead_color = $args["lead_color"];
	$text_color = $args["text_color"];
	$top_icon = $args["top_icon"];
	$help_icon = $args["help_icon"];
	$heading = $args["heading"];
	$link = $args["link"];
	$onclick = $args["onclick"];
	$content = $args["content"];

	if(!$link && $onclick) {
		$link = "#";
	}

	$container_class = "transition md:hover:scale-105 hover:shadow-xl w-full rounded flex flex-col gap-y-2 p-6 bg-{$bg_color}";

?>



<?php if($link || $onclick): ?>
	<a href="<?php echo($link); ?>" class="hy-card <?php echo($container_class); ?>" <?php if($onclick) echo("onclick='{$onclick}'"); ?> data-id="<?php echo($data_id); ?>">
<?php else: ?>
	<div class="<?php echo($container_class); ?>" data-number="<?php echo($number); ?>">
<?php endif; ?>

	<?php if($top_icon): ?>
		<div class="w-12 h-12 text-<?php echo($lead_color); ?>">
			<?php echo( get_svg_icon($top_icon) ); ?>
		</div>
	<?php endif; ?>
		
		<div class="mt-2 flex flex-row justify-start gap-x-4 items-start">

			<h3 class="theme-heading-tiny font-normal <?php if($text_color) echo(" text-{$text_color}"); ?>">
				<?php echo($heading); ?>
			</h3>

			<div class="flex shrink-0 size-6 text-<?php echo($lead_color); ?>">
				<?php echo( get_svg_icon($help_icon) ); ?>
			</div>

		</div>

	<p class="font-semibold <?php if($text_color) echo(" text-{$text_color}"); ?>">
		<?php echo($content); ?>
	</p>

<?php if($link): ?>
	</a>
<?php else: ?>
	</div>
<?php endif; ?>