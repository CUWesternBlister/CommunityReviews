<?php
/**
 * Plugin Name: Blister Community Reviews
 * Description: A plugin to facilitate Blister community created reviews.
 * Author: Gunnar Marquardt, Jayden Omi, Izak Litte, Jacob Vogel
 */

add_action( 'elementor_pro/forms/new_record', function( $record, $ajax_handler ) {
    
    $raw_fields = $record->get( 'fields' );
    $fields = [];
    foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = $field['value'];
    }
    
    global $wpdb;
    //$output['success'] = $wpdb->insert('form_submissions', array( 'name' => $fields['name'], 
                                                                //'email' => $fields['email'], 'message' => $fields['message']));
    $output['success'] = $wpdb->insert('form_submissions', $fields);
    $ajax_handler->add_response_data( true, $output );
    
}, 10, 2);


function get_record_from_form_submissions($atts) {
	$atts = shortcode_atts(

		array(
			'name'=>''
		),
		$atts,
		'form_submissions'
	);
	global $wpdb;
	$name = $atts['name'];
	$nameget = $wpdb->prepare('SELECT * FROM form_submissions WHERE name = %s limit 1', $name);
	$nameresults = $wpdb->get_results($nameget);
	if ( $nameresults ) {
        $name_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->name;
            },
            $nameresults
        );
        $email_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->email;
                
            },
            $nameresults
        );
        $message_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->message;
            },
            $nameresults
        );
        return "Name: ".implode( ', ', $name_subs)."<br>Email: ".implode(', ', $email_subs)."<br>Message: ".implode(', ', $message_subs);
    }
    return '';
}
add_shortcode( 'form_submissions', 'get_record_from_form_submissions' );
/*
//-----------------------test for custom post----------------------------------------------------------
function create_posttype() {
register_post_type( 'news',
// CPT Options
array(
  'labels' => array(
   'name' => __( 'news' ),
   'singular_name' => __( 'News' )
  ),
  'public' => true,
  'has_archive' => false,
  'rewrite' => array('slug' => 'news'),
 )
);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );


//------------------------------------------Gunnars code--------------------------------------------------
if (!function_exists('capston_install')) {
    ///**
    * Create the required custom tables when the plugin is activated
    ------------------------
    function capstone_install() {
        global $wpdb;
​
        $table_name = $wpdb->prefix . "capstoneTest";
​
        $charset_collate = $wpdb->get_charset_collate();
​
        $sql = "CREATE TABLE $table_name (
            id int(9) NOT NULL AUTO_INCREMENT,
            fieldOne varchar(128) DEFAULT '' NOT NULL,
            fieldTwo varchar(128) DEFAULT '' NOT NULL,
            fieldThree varchar(128) DEFAULT '' NOT NULL,
            PRIMARY KEY  (id)
            ) $charset_collate;";
​
            //ensure that the required function is loaded
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
    }
​
    register_activation_hook(__FILE__, 'capstone_install');
}
​
if (!function_exists('capstone_uninstall')) {
    /**
    * Remove the custom database table when the plugin is uninstalled
    -------------------------------
    function capstone_uninstall() {
        global $wpdb;
​
        $table_name = $wpdb->prefix . "capstoneTest";
​
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }
​
    register_deactivation_hook(__FILE__, 'capstone_uninstall');
}
​
if (!function_exists('capstone_write_to_table')) {
    function capstone_write_to_table($record, $ajax_handler) {
        $raw_fields = $record->get('fields');
​
        $fields = [];
​
        foreach($raw_fields as $id => $field) {
            $fields[$id] = $field['value'];
        }
​
        global $wpdb;
​
        $table_name = $wpdb->prefix . "capstoneTest";
​
        $wpdb->insert($table_name, array('fieldOne' => $fields['fieldOne'], 'fieldTwo' => $fields['fieldTwo'], 'fieldThree' => $fields['fieldThree']));
    }
​
    add_action( 'elementor_pro/forms/new_record', 'capstone_write_to_table', 10, 2);
}
​
if (!function_exists('capstone_read_most_recent')) {
    /**
     * 
     * Function to display the n most recent reviews from the database in a table
     * 
     * @param int       $display_number  The maximum number of recent reviews to display
     * @return string   Shortcode output
     --------------------------------------
    function capstone_read_most_recent($atts = [], $content = null) {
        $defaults = array(
            'display_number' => '10',
        );
        $atts = shortcode_atts($defaults, $atts);
​
        $atts['display_number'] = absint($atts['display_number']);
        $n = $atts['display_number'];
​
        global $wpdb;
​
        $table_name = $wpdb->prefix . "capstoneTest";
​
        $sql = "SELECT * FROM $table_name LIMIT $n;";
        $rows = $wpdb->get_results($sql);
​
        $output = "<table><tr><th>$n most recent mock reviews</th></tr>";
​
        foreach ($rows as $row) {
            $output = $output . "<tr><td>";
            $output = $output . "This is a mock review in which field 1 is \"" . $row->fieldOne . "\",";
            $output = $output . " field 2 is \"" . $row->fieldTwo . "\",";
            $output = $output . " and field 3 is \"" . $row->fieldThree . "\".";
            $output = $output . "</td></tr>";
        }
​
        $output = $output . "</table>";
​
        return $output;
    }
​
    add_shortcode('capstone_read_most_recent', 'capstone_read_most_recent');
}


//-----------------------------


function get_record_from_form_submissions($atts) {
    $atts = shortcode_atts(
        array(
            'id'=>'',
            'sport'=>''
        ),
        $atts,
        'form_submissions'
    );
    global $wpdb;
    $id = $atts['id'];
    $s = $atts['sport']
    $answers = $wpdb->prepare('SELECT * FROM Knowtheyself_form_submissions WHERE user_ID  = id AND sport = s  limit 1', $id, $s);
    $questions = $wpdb->prepare('SELECT * FROM Knowtheyself_forms WHERE sport = id limit 1', $s);
    $ans_results = $wpdb->get_results($answers);
    $q_results = $wpdb->get_results($questions);
    if ( $ans_results and $q_results) {
        $_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->name;
            },
            $nameresults
        );
        $email_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->email;
            },
            $nameresults
        );
        $message_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->message;
            },
            $nameresults
        );
        return "Name: ".implode( ', ', $name_subs)."<br>Email: ".implode(', ', $email_subs)."<br>Message: ".implode(', ', $message_subs);
    }
    return '';
}
add_shortcode( 'form_submissions', 'get_record_from_form_submissions' ); 
*/