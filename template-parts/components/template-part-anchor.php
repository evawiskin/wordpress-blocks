<?php
	if(!array_key_exists("anchor", $args)) return;

	/*
		Note that the 'Top' value is in place because there is an assumption that an offset will be needed for a sticky header.
		If you don't have a sticky header you could set this to top-0.
	*/
?>
<a class="absolute -top-24 w-full h-px block pointer-events-none" id="<?php echo($args["anchor"]); ?>" aria-hidden="true" ></a>