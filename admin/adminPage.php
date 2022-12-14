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
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    </head>
    <?php echo BCR_PATH . "admin/adminForms.js"?>
    <div>
        <form id="reviews_form" onSubmit="return false;">
            <input type="hidden" name="action" value="bcr_admin_form_response">
            <?php
                echo bcr_display_reviews(true);
            ?>
            <input type="submit" value="Submit Changes">
        </form>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#reviews_form").submit(function (event) {
                var form = $(this);
                var url = "<?php echo esc_url(admin_url('admin-ajax.php'));?>";

                $.ajax({
                    type: "post",
                    url: url,
                    data: form.serialize()
                });
            });
        });
</script>

    <?php
}
?>