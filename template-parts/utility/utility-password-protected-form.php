<?php // This file is used to password protect areas in wordpress, this overrides the Wordpress Default ?>

<form 
    action="<?php echo(esc_url(site_url("wp-login.php?action=postpass", "login_post")))?>" 
    method="post"
    class="mt-24 lg:mt-52 container w-full flex gap-6 lg:gap-12 flex-col"
>
    <div>
        <h1 class="block text-4xl text-pretty">
            This is a Password Protected Area
            <span class="ml-2.5 size-8 inline-block">
                <?php echo(get_svg_icon("lock", "feather-icons")); ?>
            </span>
        </h1>
        <p class="pt-2">Password Protected content area(s) we consider either sensitive or unfinished.</p>
    </div>
    <label class="flex flex-col gap-4 text-2xl font-bold text-left">
        <span>Password:</span>
        <input type="password" name="post_password" class="max-w-md py-2 px-4 lex items-center rounded-lg text-black">
        <button type="submit" class="hy-button-primary w-fit">Submit</button>
    </label>
</form>