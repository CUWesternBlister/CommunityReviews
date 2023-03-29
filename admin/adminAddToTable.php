<?php
function my_submenu_page_callback() {
    // Your submenu page code goes here
    echo "hello world";
}

function my_add_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=communityreviews', // The parent menu slug
        'My Submenu Page', // The page title
        'My Submenu Page', // The menu title
        'manage_options', // The required user capability to access the page
        'my-submenu-page', // The menu slug
        'my_submenu_page_callback' // The callback function to display the page content
    );
}

add_action( 'admin_menu', 'my_add_submenu_page' );

?>