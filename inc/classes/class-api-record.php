<?php
	class ApiRecord {
		// Protected Properties
		// - api_data: default to [], used for parsed api data
		protected array $api_data = [];

		// Public ID Properties
		// - wordpress_id: defaults to -1 AKA unset
		// - api_id: defaults to -1 AKA unset
		public int $wordpress_id = -1;
		public int $api_id = -1;

		// Public Properties
		// - api_last_updated: defaults to "" AKA unset
		// - api_recent: defaults to true AKA new record
		// - construct_complete: defaults to false, a flag to indicate the constructor completed successfully
		public string $api_last_updated = "";
		public bool $api_recent = true;
		public bool $construct_complete = false;

		// Setup data taking in basic required parameters
		// Has optional parameter for last_updated
		function __construct(array &$api_data, array &$wordpress_api_associative, string &$last_updated = "") {
			// Data Safety Check before storing data
			if(!$this->check_api_data($api_data)) return;
			$this->store_api_data($api_data);

			// Lookup ID in WordPress Job Locations Associative Array
			$wordpress_id = array_search($this->api_id, $wordpress_api_associative);
			if($wordpress_id) {
				$this->wordpress_id = $wordpress_id;

				// Last Updated Check
				// - Performs after the wordpress_id has been found to allow for unsetting
				// - Provides fallback "Ymd" Date AKA it was last updated today
				if(!$this->api_last_updated && ($api_last_updated = date("Ymd")))
					$this->api_last_updated = $api_last_updated;

				if($this->api_last_updated <= $last_updated) {
					$this->api_recent = false;
					return;
				}
			}

			// Marks that the constructor is complete
			// Won't get triggered if required checks fail IE: check_api_data
			// Will get triggered if non-required checks fail IE: $wordpress_id
			$this->construct_complete = true;
		}

		// Utility Functions
		// Commonly Overwritten
		protected function check_api_data(array &$api_data) {
			if(
				array_key_exists("id", $api_data) &&
				array_key_exists("last_updated", $api_data) &&
				true // Additional Checks
			) return true;
			return false;
		}
		protected function store_api_data(array &$api_data) {
			// Tailor for data parsing and to adjust api keys
			$this->api_data = $api_data;
			$this->api_id = $api_data["id"];
			$this->api_last_updated = date("Ymd", strtotime($api_data["last_updated"]));
		}
		protected function check_matching_wordpress_delete_criteria() {
			// Tailor for specific criteria handling
			// EG: if (status == trash) return true
			// Alternatively leave "false" to not include
			return false;
		}

		// Public Sync Fuction
		// Takes in counters for better progress displaying
		public function sync_wordpress(&$counters = ["created" => 0, "updated" => 0, "deleted" => 0]) {
			if(!$this->construct_complete) return;

			// Create Hook
			// - Relies upon the WordPress ID being -1 (AKA unset)
			if($this->wordpress_id == -1) {
				$this->create_wordpress();

				// Counter Iteration
				if(array_key_exists("created", $counters))
					$counters["created"]++;
			}
			else {
				// Delete Hook
				// - Triggered based on WordPress Delete Criteria
				if($this->check_matching_wordpress_delete_criteria()) {
					// Unset WordPress ID
					$this->wordpress_id = -1;

					// Counter Iteration
					if(array_key_exists("deleted", $counters))
						$counters["deleted"]++;
				}

				// Update Hook
				// - Utilizes WordPress ID for post selection
				else {
					$this->update_wordpress();

					// Counter Iteration
					if(array_key_exists("updated", $counters))
						$counters["updated"]++;
				}
			}
		}

		// WordPress Syncing Functions
		// Commonly Overwritten
		protected function create_wordpress() {
		}
		protected function update_wordpress() {
		}

		// Static Constructor
		// Creates and returns a New Class Instance; potentially providing additional static arguments
		// Commonly Overwritten
		protected static function construct(array &$api_data, array &$wordpress_api_associative, string &$last_updated = "") {
			return new static(
				$api_data, 
				$wordpress_api_associative, 
				$last_updated
			);
		}

		// Public Static Iterate Sync Function
		public static function iterate_sync_wordpress(array &$api_dataset, array &$wordpress_api_associative, string &$last_updated = "") {
			// Formatted Response
			// - Synced is default to an empty array
			// 	 It gets populated with a "ApiRecord" collection
			//
			// - Unsynced is default to the associative array
			// 	 It gets populated with a "wordpress_id => api_id" pair
			//
			// - Counters are WordPress Alteration Counters
			$response = [
				"synced" => [],
				"unsynced" => $wordpress_api_associative,
				"counters" => [
					"created" => 0,
					"updated" => 0,
					"deleted" => 0,
					"skipped" => 0
				]
			];

			// Iterate API Data
			foreach ($api_dataset as $api_data) {
				// Init Record Instance using Static Constructor
				$api_record = static::construct($api_data, $wordpress_api_associative, $last_updated);

				// Record Skipping based on if the Record is recent or not.
				// - A record without a wordpress counterpart will always be marked as a recent record.
				// - Bases valid records on set api timestamp
				// - Also checks if the construct completed
				if(!$api_record->api_recent || !$api_record->construct_complete)
					$response["counters"]["skipped"]++;

				// Record Syncing
				// - Handles Create based on if a WordPress ID was not found
				// - Handles Update based on if a WordPress ID was found
				else
					$api_record->sync_wordpress($response["counters"]);

				// Record Unsetting & Storage
				// - Used in determining a list of records to remove
				// - Used in compiling a list of records synced
				if($api_record->wordpress_id) {
					if(array_key_exists($api_record->wordpress_id, $response["unsynced"]))
						unset($response["unsynced"][$api_record->wordpress_id]);

					// Stores ApiRecord in "synced" Array
					$response["synced"][] = $api_record;
				}

				// Progress Logging via Modu every 500
				if(
					(
						$response["counters"]["created"] + 
						$response["counters"]["updated"] + 
						$response["counters"]["deleted"] +
						$response["counters"]["skipped"]
					) % 500 == 0
				)
					echo("
						We've had : " . 
						($response["counters"]["created"] + $response["counters"]["updated"]) . 
						" : post changes
						- there were {$response["counters"]["created"]} record(s) created.
						- there were {$response["counters"]["updated"]} record(s) updated. 
						- there were {$response["counters"]["deleted"]} record(s) deleted. 
						<br>
						We've skipped : {$response["counters"]["skipped"]} : record(s). 
						<br>
					");
			}

			// Returns Formatted Response
			return $response;	
		}

		// Clear up the stored data
		function __destruct() {
			unset($this->api_data);
			unset($this->construct_complete);

			unset($this->wordpress_id);
			unset($this->api_id);

			unset($this->api_last_updated);
			unset($this->api_recent);
		}
	}
