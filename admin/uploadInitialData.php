<?php
/** 
 * Create required initial table entries
 * 
 * @return void
 */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function bcr_init_tables() {

    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "w") or die('fopen failed');

    fwrite($file, "bcr_init_tables\n");

    $brand_table_update_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_brands_table_init.csv';
    $product_table_update_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_products_table_init.csv';
    
    bcr_update_brands_table($brand_table_update_file, $file);
    bcr_update_products_table($product_table_update_file, $file);
    

    $categories_table_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_categories.sql.gz';
    $categories_sql = bcr_prepare_sql($categories_table_file);
    dbDelta($categories_sql);
}

/**
 *Updates the product table with the passed in .csv for values
 * 
 * 
 * 
 * @return boolean success 
 */
function bcr_update_products_table($csvFile){
    global $wpdb;

    $csv = array_map('str_getcsv', file($csvFile));

    $header = array_shift($csv);

    $products_table_name = $wpdb->prefix . "bcr_products";
    $sql = "INSERT INTO $products_table_name (`categoryID`, `brandID`, `productName`) VALUES";

    $csvLength = count($csv);
    $numProductsToEnter = 0;
    $count = 1;
    $runQuery = FALSE;

    $productsToEnter = array();

    foreach ($csv as $row){
    
        $productName = $row[3];

        $check = "SELECT CASE WHEN EXISTS (
            SELECT * FROM $products_table_name
            WHERE productName = '$productName'
        ) THEN 'True' ELSE 'False' END AS product_exists;";
        $checkRes = $wpdb->get_results($check);

        $productInTableArr = $checkRes[0];
        $productInTable = $productInTableArr->product_exists;

        if($productInTable == "False"){
            $productsToEnter[$numProductsToEnter] = $row;
            $numProductsToEnter = $numProductsToEnter+1;
        }
    }

    if($numProductsToEnter != 0){
        $runQuery = TRUE;
        foreach ($productsToEnter as $row){

            $categoryID = $row[1];
            $brandName = $row[2];
            $productName = $row[3];

            $brandID = getBrandID($brandName);

            if($count != $numProductsToEnter){
                $concatString = "($categoryID, $brandID, '$productName'),";
                $sql = $sql . "\n" . $concatString;
            }else{
                $concatString = "($categoryID, $brandID, '$productName');";
                $sql = $sql . "\n" . $concatString;
            }

            $count = $count+1;
        }
    }

    if($runQuery){
        $success = $wpdb->get_results($sql);
    } else{
        $success = FALSE;
    }
    return $success;

}
/**
 *Gets the brandID associated with the brandName passed in
 * 
 * 
 * 
 * @return int brandID
 */
function getBrandID($brandName){
    global $wpdb;

    $brandTableName = $wpdb->prefix . "bcr_brands";
    $sql = "SELECT brandID FROM $brandTableName WHERE brandName='Blizzard';";
    $res = $wpdb->get_row($sql);

    return $res->brandID;
}


/**
 *Updates the brand table with the passed in .csv for values
 * 
 * 
 * 
 * @return boolean success 
 */
function bcr_update_brands_table($csvFile){
    global $wpdb;
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "w") or die('fopen failed');

    fwrite($file, "before array_map() call in upload brandCSV function\n");

    $typeFile = gettype($csvFile);

    fwrite($file, "Type of csvFile: $typeFile\n");
    fwrite($file, "this is the string csvFile: $csvFile\n");

    
    $csv = array_map('str_getcsv', file($csvFile));
   

    fwrite($file, "csv: $csv after array_map call\n");

    //$header = array_shift($csv);

    
    fwrite($file, "header in update brands table function: $header\n");

    $brands_table_name = $wpdb->prefix . "bcr_brands";
    $sql = "INSERT INTO $brands_table_name (`brandName`) VALUES";

    $csvLength = count($csv);
    $numBrandsToEnter = 0;
    $count = 1;
    $runQuery = FALSE;

    $brandsToEnter = array();

    foreach ($csv as $row){
    
        $brandName = $row[1];

        $check = "SELECT CASE WHEN EXISTS (
            SELECT * FROM $brands_table_name
            WHERE brandName = '$brandName'
        ) THEN 'True' ELSE 'False' END AS brand_exists;";
        $checkRes = $wpdb->get_results($check);

        $brandInTableArr = $checkRes[0];
        $brandInTable = $brandInTableArr->brand_exists;

        if($brandInTable == "False"){
            $brandsToEnter[$numBrandsToEnter] = $row;
            $numBrandsToEnter = $numBrandsToEnter+1;
        }
    }

    if($numBrandsToEnter != 0){
        $runQuery = TRUE;
        foreach ($brandsToEnter as $row){

            $brandID = $row[1];

            if($count != $numBrandsToEnter){
                $concatString = "('$brandID'),";
                $sql = $sql . "\n" . $concatString;
            }else{
                $concatString = "('$brandID');";
                $sql = $sql . "\n" . $concatString;
            }

            $count = $count+1;
        }
    }

    fwrite($file, "sql: $sql at the end of brand to csv funciton\n");

    if($runQuery){
        $success = $wpdb->get_results($sql);
    } else{
        $success = FALSE;
    }
    return $success;

}

/**
 *Creates prepares the sql command, takes in file path to gzipped sql file 
 * 
 * 
 * 
 * @return $sql_command 
 */
function bcr_prepare_sql($fileName){
    global $wpdb;
    $sql_command = "";
    $lines = gzfile($fileName);
    foreach($lines as $key => $line ){
        $sql_command.= $line;
    }
    $sql_command = str_replace("wp_", $wpdb->prefix, $sql_command);
    $sql_command = str_replace("wp8o_", $wpdb->prefix, $sql_command);

    return $sql_command;
}

//bcr_init_tables();
?>