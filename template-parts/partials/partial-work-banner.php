<?php
    $defaults = [
        "post_id" => get_the_ID(),
        "post_type" => get_post_type(),
        "post_type_name" => "All ". ucwords(str_replace("_", " ", get_post_type())) . "s",
    ];
	$args = wp_parse_args($args, $defaults);

?>

<div class="container inside-container-lg grid grid-cols-12">
	<div class="col-span-12 lg:col-span-9 text-forest-green-500">
		<a href="<?php echo(get_post_type_archive_link($args["post_type"])); ?>" class="flex items-center text-sm mb-8 font-semibold">
			<span class="w-4 h-4 block mr-4"><?php echo(get_svg_icon("arrow-left")); ?></span>
			Back to <?php echo($args["post_type_name"]); ?>
		</a>
		<h1 class="theme-heading-tiny heading-secondary mb-8"><?php echo(get_the_title($args["post_id"])); ?></h1>
		<?php if($title_field = get_field("cpt_work_title")): ?>
			<h2 class="heading-primary theme-heading-big"><?php echo($title_field); ?></h2>
		<?php endif; ?>
	</div>
</div>