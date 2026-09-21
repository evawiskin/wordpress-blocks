<?php
	$defaults = [
        "post_id" => get_the_ID(),
        "post_type" => get_post_type(),
        "post_type_name" => "All ". ucwords(str_replace("_", " ", get_post_type())) . "s",
    ];
	$args = wp_parse_args($args, $defaults);

	$post_id = $args["post_id"];

	$person_name = get_field("cpt_person_name", get_the_ID());
	$person_job_title = get_field("cpt_person_job_title", $post_id) ?: false;
	$person_pronouns = get_field("cpt_person_pronouns", $post_id) ?: false;
	$person_banner_image = get_field("cpt_person_banner_image", $post_id);

	if(!$person_name || !$person_job_title):
		if(is_admin())
			echo("Please specify a person_name and person_job_title for this card.");
		return;
	endif;

?>

<div class="relative bg-forest-green-600 text-white overflow-hidden">
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 container">
		<div class="w-full my-14 flex flex-col gap-y-14 justify-start">
			<!-- left side of the banner --> 
			<?php
				//get post type archive link
				$post_type_url = get_post_type_archive_link($args["post_type"]);
				//get option for archive page and get page title
				$archive_page_id = get_option("page_for_{$args["post_type"]}");

			?>
			<a href="<?php echo($post_type_url); ?>" class="flex items-center text-sm mb-8 font-semibold hover:text-electric-green-500 duration-300">
				<span class="w-4 h-4 block mr-4"><?php echo(get_svg_icon("arrow-left")); ?></span>
				Back to <?php echo(get_the_title($archive_page_id)); ?>
			</a>
			<div class="flex flex-col gap-y-10">
				<h1 class="theme-heading-big">
					<?php echo($person_name); ?>
				</h1>
				<div class="flex flex-col gap-y-6">
					<h3 class="theme-heading-tiny">
						<?php echo($person_job_title); ?>
					</h3>
					<?php if($person_pronouns): ?>
						<span class="font-semibold">
							<?php echo($person_pronouns); ?>
						</span>
					<?php 
						endif; 

						//social share
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

		<!-- banner img desktop --> 
		<div class="w-full hidden lg:block">
			<div class="h-full relative lg:mr-break-out max-lg:!mr-0">
				<div 
					class="
						w-full h-full inset-0 bottom-0 flex justify-end 
						lg:mr-break-out max-lg:!mr-0 aspect-w-3 aspect-h-2
					"
				>
					<div class="object-cover">
						<?php 
							echo(
								wp_get_attachment_image(
									$person_banner_image, 
									"large", 
									false, 
									["class" => "w-full h-full object-cover object-center"]
								)
							);
						?>
					</div>
				</div>
			</div>
		</div>


	</div>
	
	<!-- Banner Image Mobile -->
	<div class="w-full lg:hidden object-cover aspect-w-3 aspect-h-2">
		<?php 
			echo(
				wp_get_attachment_image(
					$person_banner_image, 
					"large", 
					false, 
					["class" => "w-full h-full object-cover object-center"]
				)
			);
		?>
	</div>
</div>
