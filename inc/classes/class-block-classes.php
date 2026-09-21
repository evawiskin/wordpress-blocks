<?php
	/**
	* Class used to implement the Block_Classes object.
	*
	* @property string $background_color The background color class. EG 'backgroundColor' in Gutenberg
	* @property string $text_color The text color class. EG 'textColor' in Gutenberg
	* @property string $align_text The text alignment class. EG 'alignText' in Gutenberg
	* @property string $justify The flex justify alignment class. EG 'align' in Gutenberg
	* @property string $items The flex items alignment class. EG 'alignContent' in Gutenberg
	* @property string $spacing The spacing class. EG 'spacing' in Gutenberg, handled by get_block_spacing().
	* @property string $classes The additional classes. These can be added to the block.
	* @author jakemackie | ThePlayerSD | Hiyield
	*/
	class BlockClasses {
		public string $background_color = "";
		public string $text_color = "";
		public string $align_text = "";
		public string $justify = "";
		public string $items = "";
		public string $spacing = "";
		public string $classes = "";
		public string $padding = "";
		public string $margin = "";
		public $is_admin = false;

		/**
		 * Constructor for the BlockClasses class.
		 *
		 * @param array &$block The block data
		 * @param string $classes The additional classes
		 * @return BlockClasses The BlockClasses object
		 */
		public function __construct(array &$block, string $classes = "") {
			$this->classes = $classes;
			$this->is_admin = is_admin();
			try {
				// Utilizing the has-* variants
				if (
					!empty($block["backgroundColor"]) &&
					// Don't output background colour in editor if we have background media
					(empty(get_field("background_media_desktop_image_id")) || !$this->is_admin) && 
					(empty(get_field("background_media_background_video_file")) || !$this->is_admin)
				) 
					$this->background_color = "has-{$block["backgroundColor"]}-background-color";
				if (!empty($block["textColor"])) 
					$this->text_color = "has-{$block["textColor"]}-color";
				if (!empty($block["alignText"])) 
					$this->align_text = "has-text-align-{$block["alignText"]}";

				// Mapping align to justify-*
				if (!empty($block["align"])) {
					switch($block["align"]) {
						case "center":
							$this->justify = "justify-center";
							break;
						case "right":
							$this->justify = "justify-end";
							break;
						case "left":
						default:
							$this->justify = "justify-start";
							break;
					}
				}

				// Mapping alignContent to items-*
				if (!empty($block["alignContent"])) {
					switch($block["alignContent"]) {
						case "center":
							$this->items = "items-center";
							break;
						case "bottom":
							$this->items = "items-end";
							break;
						default:
							$this->items = "items-start";
							break;
					}
				}

				// Add 'flex' if we have either align or alignContent
				if (!empty($this->justify) || !empty($this->items)) 
					$this->classes .= " flex";

				// This will always try and apply, the function handles its own cases.
				$this->spacing = get_block_spacing($block);

				// Extract padding and margin classes from spacing incase we want to use them separately (e.g. margin on parent and padding on child)
				$spacing_classes = explode(" ", $this->spacing);
				foreach ($spacing_classes as $class) {
					if (str_starts_with($class, "padding-")) 
						$this->padding .= "$class ";
					elseif (str_starts_with($class, "margin-"))
						$this->margin .= "$class ";
				}
				$this->padding = trim($this->padding);
				$this->margin = trim($this->margin);
			} 
			catch (TypeError | Exception $exception) {
				error_log($exception->getMessage());
			}
		}

		/**
		 * Returns the classes as a string.
		 *
		 * @return string The classes as a string
		 */
		public function __toString(): string {
			return implode(" ", get_object_vars($this));
		}
	}

	// Purposefully Leaving PHP Tag Open