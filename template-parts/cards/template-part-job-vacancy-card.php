<?php
	$args = array_merge(
		[
			"job_id" 		 => false,
			"index"			 => 0,
			"max_posts"		 => 0
		],
		$args ?? []
	);

	// Ensure we've got at least the post_id
	$job_id = $args["job_id"];
	if(!$job_id && is_admin()):
		echo("Please specify a job_id for this card.");
		return;
	endif;

	$job_role = get_field("cpt_job_role", $job_id);
	$job_description = get_field("cpt_job_description", $job_id);
	$job_salary = get_field("cpt_job_salary", $job_id);
	$job_hours = get_field("cpt_job_hours", $job_id);
	$job_apply_cta = get_permalink($job_id);
?>

<!-- start of job vacancy card -->
<div 
	class="
		grid grid-cols-6 md:grid-cols-12 gap-x-8 gap-y-8 lg:gap-y-12
		text-white py-[2.625rem] hover:lg:bg-white 
		hover:lg:bg-opacity-10 duration-100 relative
		<?php echo($args["index"] === ($args["max_posts"] - 1) ? "border-y" : "border-t"); ?>
	"
>
<a class="absolute inset-0" href="<?php echo(get_permalink($job_id)); ?>"></a>
	<!-- job role -->
	<div class="col-span-6 md:col-span-4 xl:col-span-3 md:pl-6">
		<h6 class="theme-heading-tiny">
			<?php echo($job_role); ?>
		</h6>
	</div>
	<!-- job description -->
	<div class="col-span-6 md:col-span-4 xl:col-span-5">
		<div class="w-full xl:w-3/4">
			<span class="font-semibold">
				<?php echo($job_description); ?>
			</span>
		</div>
	</div>
	<!-- job salary -->
	<div class="flex flex-row gap-6 md:flex md:flex-col xl:flex-row justify-between md:justify-center xl:justify-between items-start xl:items-center col-span-6 md:col-span-4 lg:pr-6">
		<div>
			<div class="flex flex-col">
				<span class="font-bold">
					<?php echo($job_salary); ?>
				</span>
				<?php if($job_hours): ?>
					<span class="font-semibold">
						<?php echo($job_hours); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
		<!-- apply cta -->
		<div>
			<?php 
				$button_content = [
					"button_text"	=> "Apply Now",
					"button_link"	=> $job_apply_cta
				];

				get_template_part(
					"template-parts/partials/partial",
					"button",
					[
						"button_content"	=> $button_content,
						"button_classes"	=> "hy-button-primary",
						"icon_right"		=> "arrow-right"
					]
				);
			?>
		</div>
	</div>
</div>
