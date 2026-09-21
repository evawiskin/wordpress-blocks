<?php
	if(!class_exists("ApiRecord"))
		require_once(get_theme_file_path("inc/classes/class-api-record.php"));

	class ApiRecordExample extends ApiRecord {
		protected function check_api_data(array &$api_data) {
			if(
				array_key_exists("id", $api_data) &&
				array_key_exists("name", $api_data) &&
				array_key_exists("date", $api_data) &&
				true // Additional Checks
			) return true;
			return false;
		}
		protected function store_api_data(array &$api_data) {
			$this->api_data = $api_data;
			$this->api_id = $api_data["id"];
			$this->api_last_updated = date("Ymd", strtotime($api_data["date"]));
		}

		protected function create_wordpress() {
			$wordpress_example_term = wp_insert_term(
				$this->api_data["name"], 
				"example-category", 
				[
					"slug" => sanitize_title($this->api_data["name"])
				]
			);

			// Check Create
			if(is_a($wordpress_example_term, "WP_Error"))
				return;
			$this->wordpress_id = $wordpress_example_term["term_id"];
		}
		protected function update_wordpress() {
			wp_update_term(
				$this->wordpress_id, 
				"example-category", 
				[
					"title" => $this->api_data["name"],
					"slug"  => sanitize_title($this->api_data["name"])
				]
			);
		}
	}