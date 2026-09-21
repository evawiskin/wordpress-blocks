<?php
    $defaults = [
        "author_person_id" => "",
		"author_name" => "",
		"author_job_title" => ""
    ];
	$args = wp_parse_args($args, $defaults);

	if(!$args["author_person_id"])
		return;

	$author_person_id = $args["author_person_id"];
	$author_name = $args["author_name"];
	$author_job_title = $args["author_job_title"];
	$author_image = get_field("cpt_person_profile_image", $author_person_id);
	$author_linkedin_url = get_field("cpt_person_linkedin_url", $author_person_id);
	
	$author_first_name = explode(" ", $author_name)[0];



?>

<div class="relative bg-forest-green-500 text-white lg:h-[calc(100vh_-_108px)] lg:max-h-[35.313rem] flex">
		<div class="max-lg:max-w-full max-lg:px-0 lg:container flex flex-wrap min-h-full w-full">
			<div class="w-full lg:w-5/12 lg:order-last">
				<?php if($author_image): ?>
					<div class="min-h-96 lg:min-h-[35.313rem] h-full lg:mr-break-out max-lg:!mr-0 relative">
						<div class="absolute inset-0">
							<?php echo(wp_get_attachment_image($author_image, "full", false, ["class" => "object-cover w-full h-full"])); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="w-full container lg:w-7/12 lg:!px-0">
				<div class=" pt-12 pb-16 lg:pr-16 flex flex-col justify-center h-full">
					<a href="<?php echo(get_post_type_archive_link("post")); ?>" class="flex items-center text-sm font-semibold mb-8 hover:text-electric-green-700 transition-colors duration-200">
						<span class="w-4 h-4 block mr-4"><?php echo(get_svg_icon("arrow-left")); ?></span>
						Back to Blog
					</a>
					
					<div class="flex flex-col justify-center h-full">
						<h1 class="theme-heading-big text-gretter-50"><?php echo($author_name); ?></h1>
						<?php if($author_job_title): ?>
							<p class="mt-4 font-bold"><?php echo($author_job_title); ?></p>
						<?php endif; ?>

						<?php if($author_linkedin_url): ?>
							<p class="mt-6 mb-2">Connect with <?php echo($author_first_name); ?></p>

							<?php if ($linkedin_icon = get_svg_icon("linkedin-square", "feather-icons-social")) : ?>
								<a href="<?php echo($author_linkedin_url); ?>" class="hover:text-electric-green-700 transition-colors duration-200" target="_blank" rel="nofollow">
									<span class="block w-5 h-5">
										<?php echo($linkedin_icon) ?>
									</span>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>