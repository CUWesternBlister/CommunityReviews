<?php
	function get_current_userID($file){
	    global $wpdb;
	    //$start = "          SUMMIT get user id \n";
	    //fwrite($file, $start);
	    if ( ! function_exists( 'get_current_user_id' ) ) {
	        return 0;
	    }
	    $cur_userID = get_current_user_id();
	    //$str = "-------- " . strval($cur_userID) . " ----------\n";
	    //fwrite($file, $str);
	    if($cur_userID == 0){
	        //then not logged in
	        //we should check this field when they click to start a review form.
	        return "userID does not exist, or user is not logged in";
	    }
	    $user_table = $wpdb->prefix . "bcr_users";
	    $q = $wpdb->prepare("SELECT 1 userID FROM $user_table WHERE userID = %s;", $cur_userID);
	    $res = $wpdb->query($q);

	    //check if user in wp bcr users
	    return intval($cur_userID);
	}
    function get_bcr_user(){
        global $wpdb;
        $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
        $myfile = fopen($file_path, "a") or die('fopen failed');
        $userID = get_current_userID($myfile);
        //fwrite($myfile, $userID);
        $user_table_name = $wpdb->prefix . "bcr_users";
        $q = "SELECT * FROM $user_table_name WHERE userID = $userID LIMIT 1;";
        $userEntry = $wpdb->get_results($q);
        return $userEntry;
    }
/*
	functions string_to_array($str_ids) {
		$arr_ids = array_map('intval', explode(',', $str_ids));
		return $arr_ids;
	}
	
	functions array_to_string($arr_ids) {
		$str_ids = implode(",",$arr_ids);
		return $str_ids;
	}
*/

function print_to_test_file($input, $message, $file){
	$input_p = print_r($input, true);
    fwrite($file, $message."\n".$input_p."\n\n");
}

function get_all_form_names($file){
    global $wpdb;
    //$start = "\n\n SUMMIT get all form names \n";
    //fwrite($file, $start);
    $review_forms_table = $wpdb->prefix . "bcr_review_forms";
    $q = "SELECT reviewFormName FROM $review_forms_table;";
    $res = $wpdb->get_results($q);
    $return_array = [];
    foreach ($res as $value) {
        $return_array[] = $value->reviewFormName;
    }
    return $return_array;
}

?>