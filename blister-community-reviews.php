<?php
/**
 * Plugin Name: Blister Community Reviews
 * Description: A plugin to facilitate Blister community created reviews.
 * Author: Gunnar Marquardt, Jayden Omi, Izak Litte, Jacob Vogel
 */
if (!defined('ABSPATH')) exit;

register_activation_hook(__FILE__, 'bcr_activation');
register_deactivation_hook(__FILE__, 'bcr_deactivation');
add_action( 'plugins_loaded', 'bcr_include');

function admin_menu_option()
{
    add_menu_page('Question Table Admin Page','Question Table Admin Page','manage_options','admin-menu','admin_page_question_table','',200);//name, display name, permssion to edit, slug, call back to what page looks like
}
add_action('admin_menu','admin_menu_option');

function admin_page_question_table()
    {

        if(array_key_exists('submit_new_question',$_POST))
        {
            update_option('question_content',$_POST['add_question']);
            //update_option('ideapro_footer_scripts',$_POST['footer_script']);

            ?>
            <div id="setting-error-settings-updated" class="updated_settings_error notice is-dismissible"><strong>Settings have been saved.</strong></div>
            <?php

        }

        $question = get_option('question_content','none');
        //$footer_scripts = get_option('ideapro_footer_scripts','none');


        ?>
        <div class="wrap">
            <h2>Update Question Table</h2>
            <form method="post" action="">
            <label for="add_question">Add Question</label>
            <textarea name="add_question" class="large-text"><?php print $question;?></textarea>
            <input type="submit" name="submit_new_question" class="button button-primary" value="ADD QUESTION">
            </form>
        </div>  
        <?php
    }
    /*
    <label for="footer_scripts">Footer Scripts</label>
    <textarea name="footer_script" class="large-text"><?php print $footer_scripts; ?></textarea>
    */

/*function insert_question($record, $ajax_handler)
{
    $question = get_option('question_content','none');
    $fields = array(
        "q_id" => 1,
        "q_content" => question,
    );
    global $wpdb;
    $table_name ='blister_question_table';
    $output['success'] = $wpdb->insert($table_name, $fields);
    $ajax_handler->add_response_data( true, $output);
    //print $header_scripts;
}
add_action('wp_head','insert_question', 10, 2);*/


function bcr_activation() {
    require_once( plugin_dir_path( __FILE__ ) . '/admin/activation.php');
}

/**
 * Load Blister Community Reviews deactivation functions
 * 
 * @return void
 */
function bcr_deactivation() {
    require_once( plugin_dir_path( __FILE__ ) . '/admin/deactivation.php');
}

function bcr_include() {
    require_once( plugin_dir_path( __FILE__ ) . 'functions.php');
}


/*
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
*/
/*
 function knowthyself_write_to_table($record, $ajax_handler) {
        $form_name = $record->get_form_settings( 'form_name' );
        
        if($form_name == 'Know_Thyself_Form'){
        
            $raw_fields = $record->get('fields');

            $fields = [];

            foreach($raw_fields as $id => $field) {
                $fields[$id] = $field['value'];
            }

            global $wpdb;

            $table_name = 'KnowThySelfSkiing';
            
            $output['success'] = $wpdb->insert($table_name, $fields);
            
            $ajax_handler->add_response_data( true, $output);
        }
    }

    add_action( 'elementor_pro/forms/new_record', 'knowthyself_write_to_table', 10, 2);
*/
?>

