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
    echo bcr_display_reviews();
}
?>