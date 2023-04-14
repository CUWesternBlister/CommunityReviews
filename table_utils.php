<?php

	function check_for_product($product_name){
		//echo "check_for_product()<br>";
		global $wpdb;
		$prod_table = $wpdb->prefix . "bcr_products";
		$sql = $wpdb->prepare("SELECT * FROM $prod_table WHERE productName = '$product_name';");
		//echo "SELECT * FROM $prod_table WHERE productName = $product_name;<br>";
		$res = $wpdb->get_row($sql);
		//echo print_r($res, true)."<br>";
		if ($res) {
			//echo "returning  prod ture<br>";
			return true;
		} else {
			//echo "returning prod false<br>";
			return false;
		}
	}
	function check_for_brand($brand_name){
		//echo "check_for_brand()<br>";
		global $wpdb;
		$brand_table = $wpdb->prefix . "bcr_brands";
		$sql = $wpdb->prepare("SELECT * FROM $brand_table WHERE brandName = '$brand_name';");
		//echo "SELECT * FROM $brand_table WHERE brandName = $brand_name;<br>"; 
		$res = $wpdb->get_row($sql);
		//echo print_r($res, true)."<br>";
		if ($res) {
			//echo "returning brand true<br>";
			return true;
		} else {
			//echo "returning brand false<br>";
			return false;
		}
	}
	// function check_for_category($category_name){
	// 	global $wpdb;
	// 	$categories_table = $wpdb->prefix . "bcr_categories";
	// 	$sql = $wpdb->prepare("SELECT * FROM $categories_table WHERE productName = $category_name;");
	// 	$res = $wpdb->query($sql);
	// 	if ($res == 0 || !$res ) {
	// 		//echo "returning false<br>";
	// 		return false;
	// 	} else {
	// 		//echo "returning true<br>";
	// 		return true;
	// 	}
	// }
	// function check_for_sport($sport_name){
	// 	global $wpdb;
	// 	$categories_table = $wpdb->prefix . "bcr_categories";
	// 	$sql = $wpdb->prepare("SELECT * FROM $categories_table WHERE categoryName = $sport_name;");
	// 	$res = $wpdb->query($sql);
	// 	if ($res == 0 || !$res ) {
	// 		//echo "returning false<br>";
	// 		return false;
	// 	} else {
	// 		//echo "returning true<br>";
	// 		return true;
	// 	}
	// }


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

function get_current_userID($file){
	global $wpdb;
	if ( ! function_exists( 'get_current_user_id' ) ) {
		return 0;
	}
	$cur_userID = get_current_user_id();
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
	$user_table_name = $wpdb->prefix . "bcr_users";
	$q = "SELECT * FROM $user_table_name WHERE userID = $userID LIMIT 1;";
	$userEntry = $wpdb->get_results($q);
	return $userEntry;
}

function print_to_test_file($input, $message, $file){
	$input_p = print_r($input, true);
    fwrite($file, $message."\n".$input_p."\n\n");
}

function get_all_form_names($file){
    global $wpdb;
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