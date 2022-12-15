<?php
	function get_current_userID(){
	    global $wpdb;
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
	    $q = "SELECT 1 userID FROM $user_table WHERE userID = $cur_userID;";
	    $res = $wpdb->query($q);
	    if($res == false){
	        //should not be allowed to start a form untill they are in bcr users
	        return "userID does not exist in bcr user table, has not registerd";
	    }
	    //check if user in wp bcr users
	    return intval($cur_userID);
	}

	functions string_to_array($str_ids) {
		$arr_ids = array_map('intval', explode(',', $str_ids));
		return $arr_ids;
	}
	
	functions array_to_string($arr_ids) {
		$str_ids = implode(",",$arr_ids);
		return $str_ids;
	}
?>