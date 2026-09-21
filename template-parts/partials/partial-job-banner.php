<?php
    $defaults = [
        "post_id" => get_the_ID(),
        "post_type" => get_post_type(),
        "post_type_name" => "All ". ucwords(str_replace("_", " ", get_post_type())) . "s",
    ];
	$args = wp_parse_args($args, $defaults);

	$job_title = get_the_title($args["post_id"]);
	$job_location = get_field("cpt_job_location", $args["post_id"]);
	$job_salary = get_field("cpt_job_salary", $args["post_id"]);
	$job_closing_date = get_field("cpt_job_closing_date", $args["post_id"]);

	$form_anchor = get_field("cpt_job_form_anchor", $args["post_id"]);
?>

<div class="relative bg-forest-green-500 text-white overflow-x-hidden">
	<div class="max-lg:max-w-full max-lg:px-0 lg:container flex flex-wrap">
		<div class="w-full lg:w-5/12 lg:order-last">
			<div class="h-full relative">
				<div class="absolute inset-0 bottom-0 overflow-hidden flex justify-end lg:mr-break-out max-lg:!mr-0">
					<?php echo(get_svg_icon("hiyield-banner-logo", "hiyield-icons")); ?>
				</div>
				<div class="pb-16 container bottom-0 absolute flex justify-end">
					<?php
						$button_content = [
							"button_text"	=> "Apply now",
							"button_link"  	=> "#{$form_anchor}" //set id here when the contact block is made
						];

						get_template_part(
							"template-parts/partials/partial",
							"button",
							[
								"button_content"	=> $button_content,
								"button_classes"	=> "hy-button-secondary",
								"icon_right"		=> "chevron-down"
							]
						);
					?>
				</div>
			</div>
		</div>
		<div class="w-full container lg:w-7/12 lg:!px-0">
			<div class=" pt-12 pb-16 lg:pr-16 flex flex-col justify-center">

				<?php
					$post_type_url = get_post_type_archive_link($args["post_type"]);
				?>
				<a href="<?php echo($post_type_url); ?>" class="flex items-center font-semibold text-sm mb-8 hover:text-electric-green-500 transition-colors duration-300">
					<span class="w-4 h-4 block mr-4"><?php echo(get_svg_icon("arrow-left")); ?></span>
					Back to <?php echo($args["post_type_name"]); ?>
				</a>
				<div class="flex flex-col gap-y-10">
					<h1 class="theme-heading-big text-white">
						<?php echo($job_title); ?>
					</h1>
					<div class="flex flex-col gap-y-6">
						<h3 class="theme-heading-tiny">
							<?php echo($job_location); ?>
						</h3>
						<span class="font-semibold">
							<?php echo($job_salary); ?>
						</span>
						<?php if ($job_closing_date) : ?>
							<span class="font-semibold">
								Closing date: <?php echo($job_closing_date); ?>
							</span>
						<?php endif; ?>
						<div class="flex text-white">
							<?php 
								get_template_part(
									"template-parts/partials/partial",
									"post-share-buttons",
									[
										"icons" => ["linkedin"],
										"icon_classes" => "hover:text-electric-green-500"
									]
								);
							?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>