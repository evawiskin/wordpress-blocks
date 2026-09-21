<?php

    if(!isset($args)) {
        $args = [];
    }

    $args = array_merge(
        [
            "class"         => false,
            "type"          => "",
            "slug"	        => "",
            "options_by_slug" => [],
            "label" 	    => "",
            "all_label"		=> "",
            "filter_uid"   	=> uniqid(),
            "template"      => "select",
            "compare"       => false,
            "active_filters" => []
        ],
        $args
    );

    $class =  $args["class"];
    $type = $args["type"];
    $options_by_slug = $args["options_by_slug"];
    $label_singular = $args["label"];
    $label_plural = $args["all_label"];
    $filter_uid = $args["filter_uid"];
    $template = $args["template"];
    $compare = $args["compare"];
    $active_filters = $args["active_filters"];
    $title = get_field("post_filter_{$type}_title", "options");

    if ($arrow_down_icon = get_svg_icon("arrow-down"))
        $arrow_down = "<span class=\"mt-1 block w-6 h-6 text-flamingo-pink-500 flex-shrink-0\">{$arrow_down_icon}</span>";

    if($title)
        echo("<h3 class=\"flex items-center gap-4 text-forest-green-500 font-extrabold text-2xl\">{$title}{$arrow_down}</h3>");
?>

<?php
    // TEMPLATE == SELECT // use for single select
    if($template == "select"):
?>

    <label for="<?php echo($filter_uid); ?>" class="">
        <?php echo($label_singular); ?>
    </label>

    <?php foreach($options_by_slug as $slug => $options): ?>

        <select
            data-type="<?php echo($type); ?>"
            data-name="<?php echo($slug); ?>"
            id="<?php echo($filter_uid); ?>"
            class="filter-group"
            data-template="<?php echo($template); ?>"
            <?php if($compare) echo("data-compare='" . $compare . "'"); ?>
        >
            <option value="">Select an option...</option>
            <?php foreach($options as $value => $title): ?>
                <option 
                    value="<?php echo($value); ?>"
                    <?php if(isset($active_filters[$slug]) && $active_filters[$slug] == $value) echo("selected"); ?>
                >
                    <?php echo($title); ?>
                </option>
            <?php endforeach; ?>
        </select>
    
    <?php endforeach; ?>

<?php endif; ?>


<?php
    // TEMPLATE == CHECKBOX / RADIO - BUTTONS / (template might be adjusted to dropdown or something else as we want, use for multi-select)
    if($template == "checkbox" || $template == "radio"):
?>
	<div
        id="<?php echo($filter_uid); ?>"
        class="<?php echo($class); ?> filter-group flex flex-wrap gap-2.5 last:border-b last:border-forest-green-500 last:pb-8"
        data-type="<?php echo($type); ?>"
        data-template="<?php echo($template); ?>"
        <?php if($compare) echo("data-compare='{$compare}'"); ?>
    >
        <?php if($label_singular): ?>
            <div class="text-lg px-4 py-2 font-bold">
                <?php echo($label_singular); ?>
            </div>
        <?php endif; ?>
        <?php 
            foreach($options_by_slug as $slug => $options):
                foreach($options as $value => $title): 
                    $active = false;
                    // check if term is in the active filters tax query, if so, set active to true
                    if($type == "taxonomy" && isset($active_filters["tax_query"])) {
                        foreach($active_filters["tax_query"] as $tax_query_item) {
                            if($tax_query_item["taxonomy"] == $slug && in_array($value, $tax_query_item["terms"]))
                                $active = true;
                        }
                    }
        ?>
            <div class="relative" tabindex="0">
                <input 
                    type="<?php echo($template); ?>" 
                    id="<?php echo($value); ?>" 
                    name="<?php echo($slug); ?>" 
                    value="<?php echo($value); ?>" 
                    class="filter-item hidden peer"
                    <?php if($active) echo("checked"); ?>
                >
                <label for="<?php echo($value); ?>" class=" block relative text-xs px-4 py-2 rounded border border-forest-green-500 text-forest-green-500 !leading-none font-bold hover:text-white hover:bg-forest-green-500 peer-checked:bg-forest-green-500 peer-checked:border-forest-green-500 peer-checked:text-white transition-colors cursor-pointer">
                    <span class="-top-px relative">
                        <?php echo($title); ?>
                    </span>
                </label>
            </div>
        <?php 
                endforeach;
            endforeach; 
        ?>
    </div>
<?php endif; ?>

<?php
    // TEMPLATE == SEARCH
    if($template == "search"):
        $search_value = isset($_GET["s"]) ? $_GET["s"] : "";
        if(array_key_exists("s", $active_filters))
            $search_value = $active_filters["s"];
?>
    <div class="relative text-forest-green-500 last:border-b last:border-forest-green-500 last:pb-8">
        <?php if ($search_icon = get_svg_icon("search")) echo("<div class=\"absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none\"><span class=\"w-6 h-6 -mt-0.5\">{$search_icon}</span></div>") ?>
        <input 
            type="text" 
            data-type="search"
            data-template="search"
            placeholder="Search"
            class="filter-group w-full py-2.5 pr-3.5 pl-10 rounded border border-gray-300 shadow placeholder-current outline-none focus:outline-none focus:ring-forest-green-500 focus:border-forest-green-500 transition-colors"
            value="<?php echo($search_value); ?>"
        >
    </div>
<?php endif; ?>

<?php
    // TEMPLATE == TOGGLE
    if($template == "toggle"):
?>

    <?php foreach($options_by_slug as $slug => $options): ?>

        <div
            id="<?php echo($filter_uid); ?>"
            class="filter-group flex items-center <?php echo($class); ?>"
            data-type="<?php echo($type); ?>"
            data-name="<?php echo($slug); ?>"
            data-template="<?php echo($template); ?>"
        >

            <?php if($label_singular): ?>
                <div class="font-bold pr-5 text-forest-green-500">
                    <?php echo($label_singular); ?>
                </div>
            <?php endif; ?>
            <button 
                class="block w-5 h-5 text-flamingo-pink-500
                    <?php 
                        // if the active filter matches the second toggle option then rotate the button on load
                        if(isset($active_filters[$slug]) && $active_filters[$slug] == "orderby=date&order=asc") echo("rotate-180"); 
                    ?>
                "
            >
                <?php echo(get_svg_icon("sort", "hiyield-icons")); ?>
            </button>

            <?php foreach($options as $value => $title): ?>
                <input
                    type="radio"
                    id="<?php echo($value); ?>"
                    name="<?php echo($slug); ?>"
                    value="<?php echo($value); ?>"
                    class="filter-item peer hidden"
                    <?php 
                        // if there are active filters, check if this filter is active and set checked
                        if(isset($active_filters[$slug]) && $active_filters[$slug] == $value)
                            echo("checked");
                        // if there are no active filters, check if this is the first option and set checked
                        if(!isset($active_filters[$slug]) && $value == array_key_first($options))
                            echo("checked");
                    ?>
                >
            <?php endforeach; ?>
            </div>
    
    <?php endforeach; ?>

<?php endif; ?>