<?php
require 'update_community_reviews_custom_posts.php';
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
    add_submenu_page(
        'bcr_commuinty_reviews',
        'BCR Update Custom Posts',
        'BCR Update Custom Posts',
        'tech_admin',
        'manage_options',
        'bcr_admin_update_custom_post_submenu_page',
        'bcr_admin_update_custom_post_submenu_page_callback'
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
            
            bcr_display_version();
            bcr_display_user_reset();
            bcr_run_table_structure_update();

            foreach($table_names_array as $table_name) {
                bcr_display_table_contents($table_name);
            }
        ?>
    </div>
    <?php
}

/**
 * Displays the current plugin version
 * 
 * @return void
 */
function bcr_display_version() {
    $plugin_data = get_plugin_data(BCR_PATH.'/blister-community-reviews.php');
    ?>
        <div id="plugin_info">
            <p><?php
                $plugin_info = esc_html($plugin_data['Name']) . " version " . esc_html($plugin_data['Version']);
                echo $plugin_info; 
            ?></p>
        </div>
    <?php
}

/**
 * Displays a button to reset the current user's profile information
 * 
 * @return void
 */
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


//
function bcr_run_table_structure_update() {

            if(array_key_exists("table_structure_update_button", $_POST)) {
                global $wpdb;
                $result = [];

                $ra_name = $wpdb->prefix . 'bcr_reviews_answers'; // replace 'bcr_users' with your table name
                $result['drop review answers'] = $wpdb->query("DROP TABLE IF EXISTS $ra_name;");
                $r_name = $wpdb->prefix . 'bcr_reviews'; // replace 'bcr_users' with your table name
                $result['drop reviews'] = $wpdb->query("DROP TABLE IF EXISTS $r_name;");
                $a_name = $wpdb->prefix . 'bcr_answers'; // replace 'bcr_users' with your table name
                $result['drop answers'] = $wpdb->query("DROP TABLE IF EXISTS $a_name;");
                // $q_name = $wpdb->prefix . 'wp_bcr_questions'; // replace 'bcr_users' with your table name
                // $result['drop questions'] = $wpdb->query("DROP TABLE IF EXISTS $q_name;");


                $table_name = $wpdb->prefix . "bcr_users";
                $sql1 = "ALTER TABLE $table_name DROP COLUMN heightFeet;";
                $result[1] = $wpdb->query( $sql1 );
                $sql2 = "ALTER TABLE $table_name DROP COLUMN heightInches;";
                $result[2] = $wpdb->query( $sql2 );
                $sql3 = "ALTER TABLE $table_name ADD COLUMN unit_preference VARCHAR(512);";
                $result[3] = $wpdb->query( $sql3 );
                $sql4 = "ALTER TABLE $table_name ADD COLUMN height INT(9);";
                $result[4] = $wpdb->query( $sql4 );
                $sql5 = "TRUNCATE TABLE $table_name;";
                $result[5] = $wpdb->query($sql5);
        
            }

           ?>
            <div id="table_update">
                <form method="post">
                    <input id="myButton" type="submit" name="table_structure_update_button" class="button" value="Remove Review Tables & Update User Table"/>
                </form>
            </div>
            
           <?php
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

                $sql = "SELECT * FROM $esc_table_name";

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