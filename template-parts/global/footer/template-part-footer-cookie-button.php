<?php 
    // Strict false checking here as the value can be null and in that situation we would want to show the button
    if(get_field("option_cookie_show_edit_button", "options") !== false):
?>
    <div 
        id="cookie-preferences"
        class="hidden"
    >
        <button 
            type="button"
            data-cc="show-preferencesModal"
            class="hover:text-forest-green-400"
        >
            <span class="text-md font-semibold shrink-0">
                <?php echo(get_field("option_cookie_edit_button_text", "options") ?: "Cookie Preferences"); ?>
            </span>
        </button>
    </div>

<?php 
    // The end user has to be able to change their preferences so let's fallback to a simple text button
    else:
?>
    <div id="cookie-preferences">
        <button 
            type="button" 
            data-cc="show-preferencesModal" 
            class="cc-link relative flex items-center space-x-2 transition hover:opacity-80"
        >
            <span class="leading-none font-medium underline underline-offset-4">
                <?php echo(get_field("option_cookie_edit_button_text", "options") ?: "Manage Cookies"); ?>
            </span>

            <?php if ($arrow_up = get_svg_icon("arrow-up")): ?>
                <span class="w-4 h-4 block shrink-0">
                    <?php echo($arrow_up) ?>
                </span>
            <?php endif; ?>
        </button>
    </div>

<?php endif; ?>