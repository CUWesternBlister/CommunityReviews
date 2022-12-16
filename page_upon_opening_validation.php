<?php
require 'table_utils.php';
function Summit_Review_Validation() {
	$file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt'; 
    $myfile = fopen($file_path, "a") or die('fopen failed');
    $start = "\n\n SUMMIT INSERT INTO REVIEW TABLE \n";
    write($myfile, $start);
    if ( is_page( 'Community Reviews Profile' ) || is_page( 'Summit Review Form' )) {
    	global $wpdb;
        //get current user id 
        $cur_userID = get_current_userID($myfile);
        if(strcmp(gettype($current_userID),"string")){
            fwrite($myfile,$current_userID."\n");
            //die("user not found"); //should be a redirct to another page
        }
        fwrite($myfile,"userID = ".strval($current_userID)."\n");
        //see if user in user table
        $user_table = $wpdb->prefix . "bcr_users";
        $q = "SELECT userID FROM $user_table WHERE userID = $cur_userID;"
        $res = $wpdb->query($q);
        if($res == false){
        	fwrite($myfile,"the user was not validated and is being redirected to profile information form\n");
        	//open profile information form
        	wp_redirect( "http://blister-capstone-project.local/profile-information-form/", 301 ); //may need to change
    		exit();
        }else{//open summit review or do nothing  
        	fwrite($myfile,"the user was validated and can go to deired page\n");
        }
    }
    fwrite($myfile, "\n\n-------------------------------------------\n\n") or die('fwrite 1 failed');
    fclose($myfile);
}
//add_action( 'wp_enqueue_scripts', 'Summit_Review_Validation' );
?>