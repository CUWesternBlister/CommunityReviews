<?php
add_action( 'admin_menu', 'bcr_admin_page');

/**
 * Create the menu page for Blister Community Reviews in wordpress
 */
function bcr_admin_page() {
    add_menu_page(
        'Community_Reviews',
        'Community Reviews',
        'administrator',
        'bcr_commuinty_reviews',
        'bcr_admin_page_html',
        'none'
    );
}

/**
 * HTML for rendering the admin page
 */
function bcr_admin_page_html() {   
    ?>
    <head>
    </head>
    <div>
        <form action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="post">
            <input type="hidden" name="action" value="bcr_admin_form_response">
            <?php
                echo bcr_display_reviews(true);
            ?>
            <input type="submit" value="Submit Changes">
        </form>
    </div>

    <?php
}
?>