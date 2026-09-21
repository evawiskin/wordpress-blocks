<?php
    // Get passed message if set
    $message = isset($args["message"]) ? $args["message"] : false;

    if(!$message)
        return;
?>

<div class="bg-gray-100 border-l-4 border-gray-500 p-4 mb-4" role="alert">
    <span class="flex items-center gap-1 text-gray-700 mb-2 text-xs font-bold uppercase">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
        Instructions - Only visible in the editor
    </span>
    <?php if($message): ?>
		<p class="text-gray-700 margin-unset"><?php echo($message); ?></p>
	<?php endif; ?>
</div>