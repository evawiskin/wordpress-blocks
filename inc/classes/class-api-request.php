<?php
	/**
	*  API Request Class Available Variables and Methods:

	*  Variables:
	*  private $api_key
	*  	-> The key set by the constructor with a default of ""
	*  private $api_curl
	*  	-> The curl object, not to be set or altered by other methods, bar "init_curl"

	*  private $api_url_base
	*  	-> The root API URL set by the constructor; afterwards consider read-only
	*  private $api_url_endpoint
	*  	-> Used in place of $api_url_base, consider read and write, using the $api_url_base as a base
	*  private $api_url_next
	*  	-> Used to store the next API URL to be proccessed, default "", gets set to the $api_url_endpoint via method

	*  public $query_params
	*  	-> Public $query_params variable, used when setting the CURL URL, default []
	*  public $api_data
	*  	-> Public $api_data variable, stores the current result data, default []

	*  Methods:
	*  private set_curl_opts_request()
	*  	-> Method to set defined headers on the CURL OBJ

	*  private set_curl_url($url_base)
	*  	-> Method to set the CURL OBJ's URL, takes in a base url, appends query params if available

	*  private get_curl_next()
	*  	-> Method to check the $api_url_next and then set_curl_url(), returns "true" on a set, "false" on invalid url
	*  private reset_curl_next()
	*  	-> Method to set the $api_url_next back to the default endpoint
	*
	*  public init_curl()
	*  	-> Closes Existing CURL Instances and Instantiates the CURL.
	*  	-> Stores the new CURL and set's opts onto the CURL OBJ.
	*  	-> Polymorphism is advised when using custom opts.
	*  public reset_curl()
	*  	-> Closes Existing CURL Instances.

	*  Usage:
	*  Create a function to get data back from a specific endpoint.
	*  The function will need to set variables and call multiple methods
	*  	-> $this->api_url_endpoint => needs setting.
	*  	-> $this->set_curl_opts_request() => needs calling.
	*  	-> $this->reset_curl_next() => needs calling.
	*  	-> $this->api_data => needs resetting.

	*  The function can then do a while loop using the get_curl_next method.
	*  This method will cause a loop while the variable $api_url_next is set and valid.
	*  To avoid infinite loops, it's best to set $api_url_next after you have executed the data.

	*  While looping, you can call "curl_exec($this->api_curl)" in order to get your data.
	*  This data will be in string format and need parsing.
	*  It is recomended to handle the CURL Error and String Parsing.

	*  After this while loop has finished it is best to call the following methods.
	*  	-> $this->reset_curl_next()
	*  	-> $this->reset_curl()

	*  The above methods don't require calling but it is advisable to do so.
	*/

	class ApiRequest {
		protected string $api_key = "";
		protected $api_curl = null;

		protected string $api_url_base = "";
		protected string $api_url_endpoint = "";
		protected string $api_url_next = "";

		public array $query_params = [];
		public array $api_data = [];

		function __construct(string $api_url, string $api_key = "") {
			$this->api_url_base = $api_url;
			$this->api_url_endpoint = $api_url;
			$this->api_key = $api_key;

			// Start CURL
			$this->init_curl();
		}

		// OPT SETs for Request
		protected function set_curl_opts_request() {
			// Setup Curl
			curl_setopt(
				$this->api_curl,
				CURLOPT_HTTPHEADER,
				[
					"Api-Key: {$this->api_key}"
				]
			);
		}

		protected function set_curl_url(string $url_base) {
			// Check for Existing Query Params
			if(strpos($url_base, '?'))
				// No New Built Query Params
				$query_params = "";
			else
				// Build Query Params
				$query_params = "?" . http_build_query($this->query_params);

			// Set Curl URL
			curl_setopt(
				$this->api_curl,
				CURLOPT_URL,
				"$url_base$query_params"
			);
		}

		protected function get_curl_next() {
			// Check if there is another API URL (7 chars for HTTP://)
			if(strlen($this->api_url_next) > 7){
				// Set API URL
				$this->set_curl_url($this->api_url_next);

				// Return True
				return true;
			}

			// Return False
			return false;
		}
		protected function reset_curl_next() {
			$this->api_url_next = $this->api_url_endpoint;
		}

		public function init_curl() {
			// Reset Curl
			$this->reset_curl();

			// Init CURL
			$this->api_curl = curl_init();

			// Setup Curl
			curl_setopt(
				$this->api_curl,
				CURLOPT_RETURNTRANSFER,
				true
			);
		}
		public function reset_curl() {
			// Check and Close Existing CURL
			if($this->api_curl)
				curl_close($this->api_curl);
		}

		function __destruct() {
			$this->reset_curl();
		}
	}