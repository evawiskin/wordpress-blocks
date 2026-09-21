<?php
    $header_message_toggle = get_field("option_header_message_toggle", "options");
    $header_message	= get_field("option_header_message_wysiwyg", "options");

    if ($header_message_toggle && $header_message) : ?>
        <div class="bg-purple-800 text-white">
            <div class="container items-center justify-center hidden py-2 text-sm md:flex">
                <?php if ($header_message_toggle && $header_message) : ?>
                    <div class="prose text-center">
                        <?php echo($header_message); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php endif; ?>