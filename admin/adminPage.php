<?php
add_action( 'admin_menu', 'bcr_admin_page');

/**
 * Create the menu page for Blister Community Reviews in wordpress
 * 
 * @return void
 */
function bcr_admin_page() {
    add_menu_page(
        'Community_Reviews',
        'Community Reviews',
        'tech_admin',
        'bcr_commuinty_reviews',
        'bcr_admin_page_html',
        'none'
    );
}

/**
 * HTML for rendering the admin page
 * 
 * @return void
 */
function bcr_admin_page_html() {   
    ?>
    <div id="bcr_admin">
        <?php
            global $wpdb;

            $table_names_array = array(
                $wpdb->prefix . "bcr_users",
                $wpdb->prefix . "bcr_questions",
                $wpdb->prefix . "bcr_answers",
                $wpdb->prefix . "bcr_categories",
                $wpdb->prefix . "bcr_brands",
                $wpdb->prefix . "bcr_products",
                $wpdb->prefix . "bcr_review_forms",
                $wpdb->prefix . "bcr_reviews",
                $wpdb->prefix . "bcr_reviews_answers"
            );

            bcr_display_user_reset();

            foreach($table_names_array as $table_name) {
                bcr_display_table_contents($table_name);
            }
        ?>
    </div>
    <?php
}

function bcr_display_user_reset() {
    if(is_user_logged_in()) {
        if ( function_exists( 'get_current_user_id' ) ) {
	        $uid = get_current_user_id();

            if(array_key_exists('user_reset_button', $_POST)) {
                global $wpdb;

                $users_table_name = $wpdb->prefix . "bcr_users";

                $wpdb->delete($users_table_name, array("userID" => $uid));
            }

           ?>
            <div id="user_reset">
                <form method="post">
                    <input type="submit" name="user_reset_button" class="button" value="Reset Current User's Community Reviews Profile" />
                </form>
            </div>
           <?php
        }
    }
}

/**
 * Displays the given table as an html table for debugging
 * 
 * @param string    table_name
 * 
 * @return void
 */
function bcr_display_table_contents($table_name) {
    ?>
        <div id="<?php echo esc_html($table_name);?>">
            <h2>
               <?php echo esc_html($table_name);?> Table 
            </h2>
            <?php
                global $wpdb;

                $esc_table_name = esc_sql($table_name);

                $sql = $wpdb->prepare("SELECT * FROM $esc_table_name");

                $table_data = $wpdb->get_results($sql, ARRAY_A);

                $html = "<table>";

                $html .= "<tr>";
                foreach($table_data as $row) {
                    foreach($row as $col_name => $data) {
                        $html .= "<th>" . esc_html($col_name) . "</th>";
                    }
                    break;
                }
                $html .= "</tr>";

                foreach($table_data as $row) {
                    $html .= "<tr>";
                    foreach($row as $col_name => $data) {
                        $html .= "<td>" . esc_html($data) . "</td>";
                    }
                    $html .= "</tr>";
                }

                $html .= "</table>";
                
                echo $html;
            ?>
        </div>
    <?php
}
?>