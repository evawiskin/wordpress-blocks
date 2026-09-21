<?php

// Documentation is lacking and this needs a bit of a tidy up at some point.
class Mobile_Nav_Walker extends Walker_Nav_Menu {

	// Overwrite Inherited start_el and end_el
	function start_el(&$output, $menu_item, $depth = 0, $args = array(), $id = 0) {

		// Get and Set Intendation
		if(isset($args->item_spacing) && $args->item_spacing === "discard")
			$t = $n = "";
		else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat($t, $depth);

		// Resolve Parent entity
		$parent_id = false;
		if( $depth > 0 && $this->show_parent ) {
			$parent_id = (int)$menu_item->menu_item_parent;
            // Change property to stop show parent after each element
            $this->show_parent = false;
        }

		// Output Indentation and Newline
		$output .= "{$n}{$indent}";

		// get the menu item link attributes
		$link_atts["title"] = ! empty($menu_item->attr_title) ? esc_attr($menu_item->attr_title) : "";
		$link_atts["target"] = ! empty($menu_item->target) ? esc_attr($menu_item->target) : "";
		$link_atts["rel"] = esc_attr($menu_item->xfn);
		if ("_blank" === $menu_item->target && empty($menu_item->xfn ))
			$link_atts["rel"] = "noopener";
		$link_atts["href"] = ! empty($menu_item->url) ? esc_url($menu_item->url) : "";
		$link_atts["aria-current"] = $menu_item->current ? "page" : "";

		$menu_item_link_attributes = "";
		foreach ($link_atts as $attr => $value) {
			if ($value) {
				$menu_item_link_attributes .= "{$attr}=\"{$value}\"";
			}
		}
		$menu_item->link_attributes = $menu_item_link_attributes;

		ob_start();
		get_template_part("template-parts/nav/template-part", "nav-mobile-item", ["depth" => $depth, "menu_item" => $menu_item, "parent_id" => $parent_id]);
		$output .= ob_get_contents();
		ob_end_clean();

		// Output Newline
		$output .= $n;
	}
	public function end_el(&$output, $menu_item, $depth = 0, $args = null) {
		// Get and Set Intendation
		if(isset($args->item_spacing) && $args->item_spacing === "discard")
			$t = $n = "";
		else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat($t, $depth);

		// Output Indentation
		$output .= $indent;

		$output .= "</li>";

		// Output Newline
		$output .= $n;
	}

	// Overwrite Inherited start_lvl and end_lvl
	public function start_lvl(&$output, $depth = 1, $args = null) {

		// Parent title displayed
		// Change property to true to have parent title only in the start of level
		$this->show_parent = true;

		// Get and Set Intendation
		if(isset($args->item_spacing) && $args->item_spacing === "discard")
			$t = $n = "";
		else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat($t, $depth);

		// Output Indentation and Newline
		$output .= "{$n}{$indent}";

		ob_start();
		get_template_part("template-parts/nav/template-part", "nav-mobile-level", ["depth" => $depth]);
		$output .= ob_get_contents();
		ob_end_clean();

		// Output Newline
		$output .= $n;
	}
	public function end_lvl(&$output, $depth = 1, $args = null) {
		// Get and Set Intendation
		if(isset($args->item_spacing) && $args->item_spacing === "discard")
			$t = $n = "";
		else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat($t, $depth);

		// Output Indentation
		$output .= $indent;

		$output .= "</ul>";

		// Output Newline
		$output .= $n;
	}

}