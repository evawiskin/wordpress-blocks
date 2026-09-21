<?php if(is_admin()): ?>
    <section class="relative inside-container-lg border border-gray-400 text-gray-600 bg-gray-100 flex flex-col justify-center items-center text-center">
        <?php if(get_archive_page_post_type(get_the_ID())): ?>
            <h4>The Archive Content Will Show Here</h4>
            <p>
                This is a placeholder block that enables you to choose where the archive content shows on archive pages.<br><br>
                This message is only visible in the editor, do not remove this block or the content will not show.
            </p>
        <?php else: ?>
            <h4>This Block Won't Work Here</h4>
            <p>
                This block won't display anything as it only works on archive pages.<br><br>
                You can set this page as an archive page in WordPress Settings -> Reading
            </p>
        <?php endif; ?>
    </section>
<?php endif; ?>