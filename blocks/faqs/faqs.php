<?php

if ((!isset($block)) || !is_array($block)) return;
if (display_block_preview_image($block)) return;

$block_id = set_block_id($block);
$block_classes = new BlockClasses($block, "relative");

// Inner block settings
$allowed_blocks = get_allowed_blocks();

$template = [
	[
		"core/heading",
		[
			"level" => 2,
			"placeholder" => "FAQs",
			"fontSize" => "text-hy-5xl",
			"style" => [
				"typography" => [
					"fontWeight" => "300",
					"fontStyle" => "normal"
				]
			]
		]
	],
	[
		"core/paragraph",
		[
			"placeholder" => "Enter your paragraph text here...",
			"fontSize" => "text-hy-lg"
		]
	]
];

// Get fields from block (ACF)
$faqs_to_show = get_field("block_faqs_faqs_to_show");
$subjects = get_field("block_faqs_select_subjects") ?: [];

// block colour classes (if available from block attributes)
$block_data = isset($block) && is_array($block) ? $block : [];
$block_bg = ($block_data && array_key_exists("backgroundColor", $block_data)) ? $block_data["backgroundColor"] : "";
$block_text = ($block_data && array_key_exists("textColor", $block_data)) ? $block_data["textColor"] : "";

// two column layout toggle (keep compatibility with previous versions)
$use_two_col_layout = get_field("block_faqs_use_two_col_layout");
if ($use_two_col_layout === null || $use_two_col_layout === '') {
	$use_two_col_layout = true; // default to true
}

// Prepare the query args
$args = [];
switch($faqs_to_show) {

	// Show custom selected FAQs
	case "custom":
		$selected_faqs = get_field("block_faqs_select_faqs");
		$args = [
			"post_type" => "faq",
			"post__in" => $selected_faqs,
			"posts_per_page" => -1,
			"fields" => "ids",
			"orderby" => "post__in"
		];
		break;

	// Show FAQs by subject
	case "subject":
		$args = [
			"post_type" => "faq",
			"orderby" => "date",
			"order" => "DESC",
			"posts_per_page" => -1,
			"fields" => "ids",
			"tax_query" => [
				[
					"taxonomy" => "faq_subject",
					"terms" => $subjects,
					"include_children" => false
				]
			]
		];
		break;

	case "all":
		$args = [
			"post_type" => "faq",
			"numberposts" => -1,
			"post_status" => "publish",
			"fields" => "ids"
		];
		break;
}

// Do the query
$posts = get_posts($args);

// Fallback: if no posts found for "all" mode, perform a simple query and extract IDs
if (empty($posts) || !is_array($posts)) {
	if ($faqs_to_show === 'all') {
		$q = new WP_Query([
			'post_type' => 'faq',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		]);
		if ($q->have_posts()) {
			$posts = wp_list_pluck($q->posts, 'ID');
		}
		wp_reset_postdata();
	}
}

if (empty($posts) || !is_array($posts)) {
	return; // nothing to render
}

// Load JS if we have accordions
accordions_enqueue_scripts();

if(is_admin()):
	get_template_part("template-parts/utility/block-admin-message", null, [
		"message" => "Please note: This blocks content acts like an accordion (opens/closes) on the frontend, and it not always open like you see below. To edit the content of the FAQs, please edit the FAQ posts <a href='" . admin_url("edit.php?post_type=faq") . "' target='_blank'>here</a>."
	]);
endif;
?>

<section id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr((string)$block_classes); ?>">
	<?php do_action("hy_block_start", $block); ?>
	<div class="container">
		<div class="grid grid-cols-12 gap-4">
			<div class="prose <?php echo $use_two_col_layout ? 'col-span-12 lg:col-span-4' : 'col-span-12 lg:col-span-8 lg:col-start-3'; ?>">
				<InnerBlocks 
					allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
					template="<?php echo esc_attr(wp_json_encode($template)); ?>"
				/>
			</div>

			<div class="<?php echo $use_two_col_layout ? 'col-span-12 lg:col-span-8' : 'col-span-12 lg:col-span-8 lg:col-start-3 mt-8'; ?>">
				<?php if($faqs_to_show === 'subject'): ?>
					<?php
					// If no specific subjects selected, use all terms for faq_subject
					if(!$subjects) {
						$subjects = get_terms_by_post_type("faq", "faq_subject");
					}

					foreach($subjects as $subject):
						// Allow passing term IDs or term objects
						if(is_int($subject) || is_string($subject)) {
							$subject = get_term($subject, "faq_subject");
						}
						if (!$subject || is_wp_error($subject)) {
							continue; // Skip if subject is invalid
						}

						$args_per_subject = [
							"post_type" => "faq",
							"tax_query" => [
								[
									"taxonomy" => "faq_subject",
									"field" => "slug",
									"terms" => $subject->slug
								]
							],
							"posts_per_page" => -1,
							"fields" => "ids"
						];

						$posts_per_subject = get_posts($args_per_subject);
					?>
						<h3 class="text-hy-3xl mt-8 mb-4"><?php echo esc_html($subject->name); ?></h3>
						<?php render_accordion_items($posts_per_subject, $block_bg, $block_text); ?>
					<?php endforeach; ?>
					<?php else: ?>
						<?php render_accordion_items($posts, $block_bg, $block_text); ?>
					<?php endif; ?>
			</div>
		</div>
	</div>
</section>