<?php

    if(!isset($args)) {
        $args = [];
    }

    $args = array_merge([
        "text" => "Tap to play"
    ], $args);

	$text = $args["text"];

?>

<div class="custom-cursor-mobile absolute center-absolute z-20 w-28 h-28 rounded-full flex items-center justify-center p-2 pointer-events-none">
    <span class="animate-pulse absolute inset-0 bg-sunshine-yellow-500/70 rounded-full"></span>
    <span class="bg-sunshine-yellow-500 z-10 rounded-full w-full h-full text-center flex items-center justify-center text-black text-sm font-bold p-7">
        <?php echo($text); ?>
    </span>
</div>
