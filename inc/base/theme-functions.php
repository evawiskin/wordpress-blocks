<?php

	/* General Functions */

		// Getting Asset Directory via Internal Path
		function get_theme_asset_directory() {
			return (get_theme_file_path("/assets"));
		}

		// Getting Asset Directory via URI Path
		function get_theme_asset_directory_uri() {
			return (get_theme_file_uri("/assets"));
		}

		// Getting colors Array from theme.json
		// Returns array with 3 keys each item - [0]["name" => "Name", "slug" => "custom-color-500", "color" => "#12345"]
		function get_theme_color_palette() {

			$path = get_theme_file_path("theme.json");
			$theme_json = file_get_contents($path);
			$theme = json_decode($theme_json, true);
			
			if( isset( $theme["settings"]["color"]["palette"] ) ) {
				$palette = $theme["settings"]["color"]["palette"];
				return $palette;
			} else {
				return false;
			}
		}

		// Generic File Crawling with Callback
		function directory_filenames_callback(string $directory, array $extensions = [], callable $callback = null) {
			// Catch Missing Folder
			if(!is_dir($directory)) return;

			// Loop Over Files
			foreach(scandir($directory) as $filename) {
				$seperators = explode(".", $filename);
				$extension = end($seperators);

				// Match Extension and Perform Callback
				if(in_array($extension, $extensions) && $callback)
					$callback($filename);
			}
		}

		// Wrapper to get ALL file names match an array of extensions
		function get_directory_filenames(string $directory, array $extensions = []) {
			$filenames = [];

			directory_filenames_callback(
				$directory, 
				$extensions, 
				function (string $filename) use (&$filenames) { 
					$filenames[] = $filename;
				}
			);

			return $filenames;
		}

		// Function to get All Image Names paired with thier File Names
		function get_files_as_key_name_pair(string $path = "/assets/dist/imgs/", string $extension = "svg") {
			// Images Array
            $imgs = [];

			// Get Dir
            $img_dir = get_theme_file_path($path);

			// Inner Function Call
			directory_filenames_callback(
				$img_dir, 
				[$extension], 
				function (string $filename) use (&$imgs, &$extension) { 
					// Icon Name Var
					$img_name = $filename;

					// Format Name
					$img_name = str_replace(".$extension", "",  $img_name);
					$img_name = str_replace("-", " ", $img_name);
					$img_name = str_replace("_", " ", $img_name);

					// Array Add
					$imgs[$filename] = ucwords($img_name);
				} 
			);

			// Return Array
			return($imgs);
		}


		// Utility BlockFolders Function
		function file_extract_json_script(string $file_path, string $file_data_attribute = "data-extract") {
			// Get New DOMDocument
			$file_document = new DOMDocument();

			// Set WhiteSpace Preservations
			$file_document->preserveWhiteSpace = false;

			// Set Internal Errors
			libxml_use_internal_errors(true);

			// Check and Load Document
			if(!$file_document->loadHTMLFile($file_path)) 
				return false;

			// Make Document Finder
			$file_document_finder = new DOMXPath($file_document);

			// Get ACF FlatFile Blocks Script
			$file_document_elements = 
				$file_document_finder->query("*/script[@{$file_data_attribute}]");

			// Check and Return the Retrived Element
			if(
				$file_document_elements && 
				count($file_document_elements) == 1
			)
				return $file_document_elements[0]->textContent;

			return false;
		}

		// Add custom meta to a post to count the amount of views
		function set_post_views($post_id) {

			// Only iterate a post count if user is not logged in and is on production site
			//if (is_user_logged_in() || ((defined("WP_PRODUCTION")) && WP_PRODUCTION == false))
			//	return;

			$count_key = "post_views_count";
			$count = get_post_meta($post_id, $count_key, true);
			if ($count == "") {
				$count = 0;
				delete_post_meta($post_id, $count_key);
				add_post_meta($post_id, $count_key, "0");
			} else {
				$count++;
				update_post_meta($post_id, $count_key, $count);
			}
		}

		// Get svg icon from assets folder
		function get_svg_icon($icon = "chevron-right", $folder = "feather-icons") {

			// Remove file extension if we have one
			$icon = str_replace(".svg", "", $icon);

			$icon_path = get_theme_file_path("assets/dist/imgs/{$folder}/{$icon}.svg");
			return file_exists($icon_path) ? file_get_contents($icon_path) : false;
		}

		// Append icon to text
		function append_icon_to_text($text, string $icon = "arrow-right", string $icon_class = "w-6 h-5") {
		
			if (!$text)
				return false;

			$words = explode(" ", $text);
			$icon_append = end($words);
			foreach ($words as $index => $word) {
				if ($index == count($words) - 1 && $icon = get_svg_icon($icon)) {
					$icon_append = 
						"<span class=\"inline-flex items-end gap-2\">
							<span>
							{$word} 
							</span>
							<span class=\"inline-block {$icon_class}\">{$icon}</span>
						</span>";
				}
			}
			$words[count($words) - 1] = $icon_append;
			return implode(" ", $words);
		}

		// Get attachment image or if SVG get contents - accepts same args as wp_get_attachment_image
		function get_attachment_image_or_contents($attachment_id, $size = "thumbnail", $icon = false, $attr = "", $classes = "") {
			$file_contents = "";
			// get image type
			$type = get_post_mime_type($attachment_id);
			// check if its an SVG, skip if not
			if($type == "image/svg+xml") {
				// get the file from attachment id
				$file = get_attached_file($attachment_id);
				// get the attachment classes if set
				if($attr && array_key_exists("class", $attr))
					$classes = $attr["class"];
				// get the attachment file contents
				if($file && file_exists($file))
					$file_contents = file_get_contents($file);
				// return the file contents with or without classes on a wrapping span
				if($file_contents){
					if($classes)
						return("<span class=\"svg-wrapper {$classes}\">{$file_contents}</span>");
					else
						return("<span>{$file_contents}</span>");
				}
			}
			// if not an SVG, return the image
			return(wp_get_attachment_image($attachment_id, $size, $icon, $attr));
		}

		// Get a mega-menu from site options for a nav item. Returns false if the nav item doesn't have one. Returns matching row from the repeater if it does.
		function get_nav_item_mega_menu(int $nav_item_id) {

			// Get site megamenus from options
			$site_mega_menus = get_field("option_header_megamenu_repeater", "options") ?: [];

			// Filter out the mega menus that match the nav item ID
			$nav_item_mega_menus = array_filter($site_mega_menus, function($row) use ($nav_item_id) {
				return 
					(isset($row["option_header_megamenu_trigger_menu_item"])) &&
					$row["option_header_megamenu_trigger_menu_item"] == $nav_item_id;
			});

			// Return the first matching mega menu found
			if (count($nav_item_mega_menus) > 0) {
				return reset($nav_item_mega_menus);
			}
			return false;
		}

	/* Snippet Functions */

		// Function to Get Snippet Content
		function get_snippet_path($post_type = false) {
			// Check Post Type
			if(!$post_type) $post_type = get_post_type();

			// Get Type Snippet
			$snippet = get_theme_file_path( "template-parts/page/snippet-$post_type.php" );

			// Check and Get the Snippet
			if(!file_exists($snippet)){
				return(get_theme_file_path( "template-parts/page/snippet-post.php" ));
			}
			else {
				return($snippet);
			}
		}

		// Function to Do Snippet Content
		function do_snippet_content($post_type = false) {
			require(get_snippet_path($post_type));
		}

	/* Custom Category Functions */

		// Function to get either a Post Type's Custom or Normal Categories
		function get_post_categories( $post_type ) {
			// Check if Post is not a Post
			if($post_type != "post"){
				return(get_terms(["taxonomy"  => "$post_type-category"]));
			}
			
			return(get_terms(["taxonomy"  => "category"]));
		}

		// Function to get either a Post's Custom or Normal Categories
		function get_the_post_categories( $post = 0 ) {
			// Get Post via ID or Global
			$post = get_post( $post );

			// Check if Post is not a Post
			if(get_post_type($post->ID) != "post"){
				// Get Post CPT Specific Terms
				$terms = get_the_terms($post->ID, get_post_type($post->ID) . "-category");
			}
			else {
				// Get Post Terms
				$terms = get_the_terms($post->ID, "category");
			}
			
			// Return Categories
			if(!$terms) $terms = [];
			return($terms);
		}

	/* Breadcrumb Functions */

		// Function to get Breadcrumbs using Yoast
		function get_breadcrumbs_yoast( $before = "",  $after = "" ) {
			// Check Plugin Status and the Function Status
			if(is_plugin_active("yoast-seo")){
				// Check Breadcrumb Function
				if(function_exists("yoast_breadcrumb")){
					// Return the Yoast Breadcrumb
					return(yoast_breadcrumb( $before, $after, false ));
				}
			}

			// Return Failure
			return(null);
		}

		// Function to get Breadcrumbs using WP
		function get_breadcrumbs_wp( $post_id, $start = "",  $end = "", $tag = "a" ) {
			// Breadcrumbs WP Array
			$wp_breadcrumbs = [];

			// Parent Post ID
			$post_parent = wp_get_post_parent_id($post_id);

			// While Has Parent
			while($post_parent){
				// Get Post Permalink
				$post_permalink = get_the_permalink($post_parent);

				// Add to Breadcrumbs
				$wp_breadcrumbs[] = 
					"$start <$tag href=\"{$post_permalink}\">" . get_the_title($post_parent) . "</$tag> $end";

				// Set Post Parent
				$post_parent = wp_get_post_parent_id($post_parent);
			}

			// Reverse Array
			$wp_breadcrumbs = array_reverse($wp_breadcrumbs);

			// Implode Array
			$wp_breadcrumbs = implode("", $wp_breadcrumbs);

			// Return Breadcrumbs String
			return($wp_breadcrumbs);
		}

		// Function to get Breadcrumbs using Yoast or WP
		function get_breadcrumbs( $post_id, string $breadcrumb_start = "", string $breadcrumb_end = "", string $breadcrumb_tag = "a" ) {
			// Check ID
			if(!is_null($post_id) && is_int($post_id) && $post_id > 0){
				// Try using Yoast
				if(function_exists("get_breadcrumbs_yoast")) $yoast_breadcrumbs = get_breadcrumbs_yoast( $breadcrumb_start, $breadcrumb_end );

				// Check and Return Yoast Breadcrumbs
				if($yoast_breadcrumbs){
					return($yoast_breadcrumbs);
				}

				// Try using WP
				if(function_exists("get_breadcrumbs_wp")) $wp_breadcrumbs = get_breadcrumbs_wp( $post_id, $breadcrumb_start, $breadcrumb_end, $breadcrumb_tag );
				
				// Check and Return WP Breadcrumbs
				if($wp_breadcrumbs){
					return($wp_breadcrumbs);
				}
			}

			// Return Failure
			return(null);
		}

	/* Favicon Functions */

		// Function to generate apple favicons and safari tab
		function generate_apple_favicon($color, $sizes) {

			$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

			// Cache get_theme_file_uri
			$theme_uri = get_theme_file_uri();
			$favicons_folder = "assets/dist/imgs/favicons";

			// Safari pinned tab lives in the favicons folder
			echo("<link rel=\"mask-icon\" href=\"{$theme_uri}/{$favicons_folder}/safari-pinned-tab.svg?v={$build_version}\" color=\"{$color}\" />");

			foreach ($sizes as $size) {
				$link =  "{$favicons_folder}/apple-touch-icon-{$size}.png";
				echo("<link rel=\"apple-touch-icon-precomposed\" sizes=\"{$size}\" href=\"{$theme_uri}/{$link}?v={$build_version}\"/>");
			}
		}

		// Function to generate favicons
		function generate_favicon($sizes) {

			$build_version = defined("BUILD_VERSION") ? BUILD_VERSION : "0.0.0";

			// Cache get_theme_file_uri
			$theme_uri = get_theme_file_uri();
			$favicons_folder = "assets/dist/imgs/favicons";
			
			foreach ($sizes as $size) {
				$link =  "{$favicons_folder}/favicon-{$size}.png";
				echo("<link rel=\"icon\" type=\"image/png\" href=\"{$theme_uri}/{$link}?v={$build_version}\" sizes=\"{$size}\" />");
			}
		}

		// Function to generate microsoft favicons
		function generate_microsoft_favicon($tile_color, $sizes) {
			// Cache get_theme_file_uri
			$theme_uri = get_theme_file_uri();
			$favicons_folder = "assets/dist/imgs/favicons";

			echo("<meta name=\"msapplication-TileColor\" content=\"{$tile_color}\" />");

			foreach ($sizes as $size) {
				$link =  "{$favicons_folder}/mstile-{$size}.png";
				echo("<meta name=\"msapplication-TileImage\" content=\"{$theme_uri}/{$link}\" />");
			}
		}

	/* User Functions */

		// Function to detmine the user's role and it's capabilities
		function has_backend_access(?WP_User $user = null) {
			// Get the current user if no user was passed in
			if(!$user && is_user_logged_in())
				$user = wp_get_current_user();

			// Check the user's role
			if($user) {
				$allowed_roles = [
					"author",
					"editor",
					"administrator",
					"content-editor",
					"content-administrator"
				];

				// Check if any of the values declared above exist
				if(array_intersect($allowed_roles, $user->roles))
					return true;
			}

			return false;
		}

	/* Pagination Functions */

		// Function to show pagination with page numbers and prev/next icons
		function get_numeric_pagination_links() {

			global $wp_query;

			$total_pages = $wp_query->max_num_pages;
		
			if ($total_pages > 1){
				$current_page = max(1, get_query_var("paged", 1));
		
				return(paginate_links([
					"base" => get_pagenum_link(1) . "%_%",
					"format" => "page/%#%",
					"current" => $current_page,
					"total" => $total_pages,
					"show_all" => true,
					"prev_text" => __( "<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 19l-7-7 7-7\" /></svg>" ),
					"next_text" => __( "<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 5l7 7-7 7\" /></svg>" ),
					"type"		=> "list"
				]));
			}
		}
		



		/*
		*	Function to determine a fallback image if the post_thumbnail is not set
		*	@param string $size - Size of the fallback image
		*	@param array $args - Any args that should be passed into wp_get_attachment_image
		*/

		function the_post_thumbnail_fallback(string $size, array $args) {

			// we tend to always just use the company logo when we set a fallback at all
			// but might be nice in future to just add a field for this in the cms
			$fallback_image = get_field("option_company_info_logo", "options");

			if($fallback_image) 
				echo("<div class=\"w-full h-full flex items-center justify-center\">".wp_get_attachment_image($fallback_image, $size ?: "full", false, $args ?: [])."</div>");
		}


		/*
		*	Function to strip and sanitize form fields 
		*	accepts an array of fields and returns them as an array
		*/
		function sanitize_form_fields(array $pre_processed_fields) {
			$processed_fields = [];
			foreach($pre_processed_fields as $field_name => $field_value) {
				// Strip that jank
				$field_value = str_replace(["<", ">", "=", "\\", "\""], "", $field_value);
				// Sanitize that jank
				switch($field_name) {
					case "email":
					case "email_address":
						$processed_fields[$field_name] = sanitize_email($field_value);
						break;
					default:
						$processed_fields[$field_name] = sanitize_text_field($field_value);
				}
			}
			return($processed_fields);
		}



	/* 
	*
	*	Cloudways GeoLocation Functions 
	*
	* 	These functions are used to get the user's country and continent
	* 	Note that you will need to have this setting enabled on the server for this to work
	* 	You aren't going to get these $_SERVER variables on local either, remember! 
	* 	Reference: https://support.cloudways.com/geo-location/
	*/

		/*
		*	Get User Country
		*	Reference: https://www.geonames.org/countries/ - Full Country Name is returned by Cloudways
		*   @return string|bool
		*/

		function get_user_country(){

			//check if domain is localhost
			if (strpos($_SERVER["HTTP_HOST"], "localhost") !== false) return "United Kingdom";

			if (
				!array_key_exists("HTTP_X_FORWARDED_COUNTRY", $_SERVER) ||
				gettype($_SERVER["HTTP_X_FORWARDED_COUNTRY"]) != "string" ||
				!$_SERVER["HTTP_X_FORWARDED_COUNTRY"]
			) return false;

			return strval($_SERVER["HTTP_X_FORWARDED_COUNTRY"]);
		}

		/*
		*	Get User Continent
		*	Reference: https://www.geonames.org/countries/ - ISO 2 Letter Code is returned by Cloudways
		*   @return string|bool
		*/

		function get_user_continent(){

			//check if domain is localhost
			if (strpos($_SERVER["HTTP_HOST"], "localhost") !== false) return "EU";

			if (
				!array_key_exists("HTTP_X_FORWARDED_CONTINENT", $_SERVER) ||
				gettype($_SERVER["HTTP_X_FORWARDED_CONTINENT"]) != "string" ||
				!$_SERVER["HTTP_X_FORWARDED_CONTINENT"]
			) return false;

			return strval($_SERVER["HTTP_X_FORWARDED_CONTINENT"]);
		}

		/*
		*	User is in what country or continent?
		*	@param string $country - Country or Continent to check against
		*   
		*   @return bool
		*/

		function user_is_in(string $country_or_continent){
			if (
				get_user_country() === $country_or_continent || 
				get_user_continent() === $country_or_continent
				) return true;
			return false;
		}

		/*  Looks up all the taxonomy terms that exist on a given post type
		* 	
		*  @param string $post_type - Post type to look up
		*  @param string $taxonomy - Taxonomy to look up
		*  @return array - Array of terms
		*/

		function get_terms_by_post_type(string $post_type, string $taxonomy) {
			// Check if the result is cached
			$cache_key = "terms_by_post_type_{$post_type}_{$taxonomy}";
			$terms_by_post_type = get_transient($cache_key);

			if ($terms_by_post_type === false) {
				// args for wp query
				$args = [
					"post_type" => $post_type,
					"posts_per_page" => -1,
					"fields" => "ids",
				];	

				$post_type_query = new WP_Query($args);
				$post_type_posts = [];
				$terms_by_post_type = [];

				// create array of post ids
				if ($post_type_query->have_posts())
					$post_type_posts = $post_type_query->posts;
				else 
					return $terms_by_post_type;

				// Use an associative array to store terms
				$terms_associative = [];

				// iterate through posts and get the terms and adding to an array
				foreach ($post_type_posts as $post_id) {
					$terms = get_the_terms($post_id, $taxonomy);

					if ($terms) {
						foreach ($terms as $term) {
							$terms_associative[$term->term_id] = $term;
						}
					}
				}

				// Convert back to a regular array
				$terms_by_post_type = array_values($terms_associative);

				// Store the result in cache
				set_transient($cache_key, $terms_by_post_type, HOUR_IN_SECONDS);
			}

			// return array of terms
			return $terms_by_post_type;
		}

	/* Post Archive Functions */

		// Callback Wrapper function for output_page_select_html_element that excludes archive pages.
		function settings_archive_page_select_callback($args) {
			// Ensure the setting_id array key is set
			$args = array_merge(
				[
					"setting_id" => ""
				],
				$args
			);
			// Parse the current setting's value
			$setting_value = is_numeric(get_option($args["setting_id"], 0)) 
				? intval(get_option($args["setting_id"])) 
				: 0;
			// Get all the post types that have archives
			$post_types = get_post_types(
				[
					"public" => true,
					"_builtin" => false,
					"has_archive" => true
				]
			);
			// Get the selected archive page IDs
			// Start with the fallback "0" in the array
			// To disallow the "none" option
			$archive_page_post_types_post_ids = ["none" => 0];
			foreach ($post_types as $post_type) {
				// generate underscored post type name
				$post_type_underscored = str_replace("-", "_", $post_type);
				// Only assign if the post_type has got a page selected
				if ($post_type_archive_page_id = get_option("page_for_{$post_type_underscored}"))
					$archive_page_post_types_post_ids[$post_type] = $post_type_archive_page_id;
			}
			// Output the HTML element
			output_page_select_html_element(
				$args["setting_id"], 
				$setting_value, 
				$archive_page_post_types_post_ids
			);
		}

		/*
			Utility function to display a page select field

			@param string $select_name - The name of the select field
			@param int $current_page - The id of the currently selected page
			@param array $disabled_ids - An array of page IDs that should be disabled - Provide "0" to disable empty selection
			@param array $excluded_ids - An array of page IDs to exclude from the select field
			@return void
		*/
		function output_page_select_html_element(string $select_name, int $current_page = 0, array $disabled_ids = [], array $excluded_ids = []) {
			// Get fresh permalinks
			flush_rewrite_rules(false);
			// Get all the accepted pages
			$pages = get_pages(["exclude" => $excluded_ids]);
			// Output select field
			?>
				<select 
					id="<?php echo($select_name); ?>" 
					name="<?php echo($select_name); ?>"
				>
					<!--- None Page Option --->
					<option 
						value="0"
						<?php 
							if($current_page == 0 || !get_post_status($current_page)) 
								echo("selected");

							// Not else-if to allow for the "none" option to be disabled
							if(in_array(0, $disabled_ids)) 
								echo(" disabled ");
						?>
					>
						None
					</option>
					<!--- Page Select Options --->
					<?php foreach($pages as $page) : ?>
						<option 
							value="<?php echo($page->ID); ?>" 
							<?php 
								if($current_page == $page->ID) 
									echo("selected");
								elseif(in_array($page->ID, $disabled_ids))
									echo("disabled");
							?>
						>
							<?php echo($page->post_title ?: "Unnamed Page"); ?>
						</option>
					<?php endforeach; ?>
				</select>
			<?php
		}

		/*
			Utility function to get the post type for an archive page.

			@param int $post_id - The archive page's post id
			@return string - The post type for the archive page
			@return false - If the post id is not an archive page
		*/
		function get_archive_page_post_type(int $post_id) {
			// Get all the post types that have archives
			$post_types = get_post_types(
				[
					"public" => true,
					"_builtin" => false,
					"has_archive" => true
				]
			);
			// Go through all of the post types
			foreach($post_types as $post_type => $post_type_underscored) {
				// If the current page is one of the archive pages then return that type
				// Utilizes Type Casting which could cause an error here if $post_type is not a string
				// We would rather throw an error here then have a bug that is hard to track down
				if(get_option("page_for_{$post_type_underscored}") == $post_id)
					return (string) $post_type;
			}
			return false;
		}	

		//Very simplistic reading duration. This could probably be improved dramatically. 
		//Maybe stored on the post meta itself?
		//Maybe we could derive it from an SEO plugin?
		function calculate_reading_duration($post_id){
			$content = get_post_field("post_content", $post_id);
			$word_count = str_word_count(strip_tags($content));
			$reading_duration = ceil($word_count / 200);
			return $reading_duration;
		}

		// Function to get the randomised asterisk image from people posts
		function get_randomised_person() {
			//Get all the person posts on the site that have 'cpt_person_can_be_displayed_randomly' ACF field set to true.
			$available_random_people_ids = get_posts([
				"post_type" => "person",
				"posts_per_page" => -1,
				"meta_query" => [
					[
						"key" => "cpt_person_can_be_displayed_randomly",
						"value" => true
					]
				],
				"fields" => "ids"
			]);

			// Get a random person post - checking that they have an asterisk image assigned, and that it"s not empty.
			$randomised_person = get_posts([
				"post_type" => "person",
				"posts_per_page" => 1,
				"post__in" => $available_random_people_ids,
				"meta_query" => [
					[
						"key" => "cpt_person_asterisk_image",
						"compare" => "EXISTS"
					],
					[
						"key" => "cpt_person_asterisk_image",
						"compare" => "!=",
						"value" => ""
					]
				],
				"orderby" => "rand",
				"fields" => "ids"
			]);

			if(!$randomised_person)
			return;

			return $randomised_person[0];
		}

		function rest_purge_wp_rocket_cache( WP_REST_Request $request ){

			// Load WP Files
			require_once("wp-load.php");

			$return = [];

			if( function_exists("rocket_clean_domain") ) {
				rocket_clean_domain();
				$return[] = [
					"cache" => "Cache purged"
				];
			} else {
				return new WP_Error("no_cache", "Cache disabled or WP Rocket not found", ["status" => 404]);
			}

			if( function_exists("rocket_clean_minify") ) {
				rocket_clean_minify();
				$return[] = [
					"minify" => "Minified files purged"
				];
			} else {
				return new WP_Error("no_minify", "Minify feature disabled or WP Rocket not found", ["status" => 404]);
			}
		
			return new WP_REST_Response($return, 200);
		}

		/*
		* Function to get the post type archive page slug and cache it to reduce database queries
		* @param string $post_type - The post type to get the archive page slug for
		* @return string|bool - The post type archive page slug or false if it doesn't exist
		*/
		function get_post_type_archive_page_slug($post_type) {
			$archive_page = false;
			$page_for_archive_id = get_option("page_for_{$post_type}");
			if ($page_for_archive_id) {
				$cache_key = "post_name_{$page_for_archive_id}";
				$archive_page = get_transient($cache_key);
				if ($archive_page === false) {
					$archive_page = get_post_field("post_name", $page_for_archive_id);
					set_transient($cache_key, $archive_page);
				}
			}
			return $archive_page;
		}


				function generate_svg_mapping() {
			$base_dir = get_theme_file_path() . '/assets/src/imgs';
			$base_url = get_theme_file_uri() . '/assets/src/imgs';
			$svg_files = array();

			// Recursive function to scan directories
			function scan_directory($directory, $base_dir, $base_url, &$svg_files) {
				$files = scandir($directory);
				foreach ($files as $file) {
					// Skip current and parent directory pointers
					if ($file === '.' || $file === '..') continue;
		
					$file_path = $directory . DIRECTORY_SEPARATOR . $file;
		
					if (is_dir($file_path)) {
						// Recursively scan subdirectories
						scan_directory($file_path, $base_dir, $base_url, $svg_files);
					} elseif (is_file($file_path) && strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) === 'svg') {
						// Get the relative path and URL
						$relative_path = str_replace($base_dir, '', $file_path);
						$svg_name = basename($file_path);
						$svg_files[$svg_name] = $base_url . str_replace(DIRECTORY_SEPARATOR, '/', $relative_path);
					}
				}
			}

			// Start scanning from the base directory
			scan_directory($base_dir, $base_dir, $base_url, $svg_files);

			return $svg_files;
		}

// Purposefully Leaving PHP Tag Open




