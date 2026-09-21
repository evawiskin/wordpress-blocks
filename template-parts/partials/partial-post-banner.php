<?php
    $defaults = [
        "post_id" => get_the_ID(),
        "post_type" => get_post_type(),
        "post_type_name" => "All ". ucwords(str_replace("_", " ", get_post_type())) . "s",
		"author_id" => get_post_field("post_author")
    ];
	$args = wp_parse_args($args, $defaults);
?>

<div class="relative bg-forest-green-500 text-white">
		<div class="max-lg:max-w-full max-lg:px-0 lg:container flex flex-wrap">
			<div class="w-full lg:w-5/12 lg:order-last">
				<?php if(has_post_thumbnail($args["post_id"])): ?>
					<div class="min-h-32 h-full lg:mr-break-out max-lg:!mr-0 relative">
						<div class="absolute inset-0">
							<?php echo(wp_get_attachment_image(get_post_thumbnail_id($args["post_id"]), "full", false, ["class" => "object-cover w-full h-full"])); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="w-full container lg:w-7/12 lg:!px-0">
				<div class=" pt-12 pb-16 lg:pr-16 flex flex-col justify-center">
					<a href="<?php echo(get_post_type_archive_link($args["post_type"])); ?>" class="flex items-center text-sm font-semibold mb-8 hover:text-electric-green-700 transition-colors duration-200">
						<span class="w-4 h-4 block mr-4"><?php echo(get_svg_icon("arrow-left")); ?></span>
						Back to <?php echo($args["post_type_name"]); ?>
					</a>
					<h1 class="theme-heading-big text-gretter-50"><?php echo(get_the_title($args["post_id"])); ?></h1>
					<?php if(has_excerpt($args["post_id"])): ?>
						<p class="mt-6"><?php echo(get_the_excerpt($args["post_id"])); ?></p>
					<?php endif; ?>
					<div class="mt-8">
						<?php get_template_part("template-parts/components/template-part", "author-details", ["post_id" => $args["post_id"], "author_id" => $args["author_id"], "link_avatar" => true]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>