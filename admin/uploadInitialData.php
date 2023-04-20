<?php
/** 
 * Create required initial table entries
 * 
 * @return void
 */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function bcr_init_tables() {
    $categories_table_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_categories.sql.gz';
    $brands_table_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_brands.sql.gz';
    //$products_table_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_products.sql.gz';
    $questions_table_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_questions.sql.gz';

    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "w") or die('fopen failed');

    $product_table_update_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_product_table_update.csv';

    bcr_update_products_table($product_table_update_file, $file);

    $categories_sql = bcr_prepare_sql($categories_table_file);
    $brands_sql = bcr_prepare_sql($brands_table_file);
    //$products_sql = bcr_prepare_sql($products_table_file);
    $questions_sql = bcr_prepare_sql($questions_table_file);

    
    fwrite($file, "questions_sql: \n".$questions_sql."\n\n");
    dbDelta($categories_sql);
    dbDelta($brands_sql);
    //dbDelta($products_sql);
    dbDelta($questions_sql);

    $addedFlag = bcr_update_reviews_with_flag();
    //echo($addedFlag);
}

/**
 *Updates the product table with the passed in .csv for values
 * 
 * 
 * 
 * @return boolean success 
 */
function bcr_update_products_table($csvFile, $testFile){
    global $wpdb;
    $csv = array_map('str_getcsv', file($csvFile));



    $header = array_shift($csv);

    //$data = array();
    //$count = 0;
    $products_table_name = $wpdb->prefix . "bcr_products";
    $sql = "INSERT INTO $products_table_name (`productID`, `categoryID`, `brandID`, `productName`) VALUES";

    $csvLength = count($csv);
    $numProductsToEnter = 0;
    $count = 1;
    $runQuery = FALSE;

    $productsToEnter = array();

    foreach ($csv as $row){
    
        $productName = $row[3];

        $check = "SELECT CASE WHEN EXISTS (
            SELECT * FROM wp_bcr_products
            WHERE productName = '$productName'
        ) THEN 'True' ELSE 'False' END AS product_exists;";
        $checkRes = $wpdb->get_results($check);

        $productInTableArr = $checkRes[0];
        $productInTable = $productInTableArr->product_exists;

        //fwrite($testFile, "Before if statement, productInTable = $productInTable\n");
        if($productInTable == "False"){
            //fwrite($testFile, "Reached inside if statement. row: $row, numProductsToEnter: $numProductsToEnter\n");
            $productsToEnter[$numProductsToEnter] = $row;
            $numProductsToEnter = $numProductsToEnter+1;
        }

        //fwrite($testFile, "product_exists: $productInTable. productName: $productName\n");
    }

    if($numProductsToEnter != 0){
        $runQuery = TRUE;
        foreach ($productsToEnter as $row){

            $productID = $row[0];
            $categoryID = $row[1];
            $brandID = $row[2];
            $productName = $row[3];

            if($count != $numProductsToEnter){
                $concatString = "($productID, $categoryID, $brandID, '$productName'),";
                $sql = $sql . "\n" . $concatString;
            }else{
                $concatString = "($productID, $categoryID, $brandID, '$productName');";
                $sql = $sql . "\n" . $concatString;
            }

            $count = $count+1;
            //fwrite($testFile, "sql string(inner loop): $sql\n");
        }
    }

    if($runQuery){
        $success = $wpdb->get_results($sql);
    } else{
        $success = FALSE;
    }
    //fwrite($testFile, "res: $res\n");
    //fwrite($testFile, "sql string: $sql\n");
    return $success;

}


/**
 *UPdates the reviews table to have attribute FlaggedForReview (REMOVE BEFORE FINAL RELEASE)
 * 
 * 
 * 
 * @return boolean s$uccess 
 */
function bcr_update_reviews_with_flag(){
    global $wpdb;
    $reviews_table_name = $wpdb->prefix . "bcr_reviews";

    $checkSQL = "SELECT CASE WHEN EXISTS (
        SELECT * FROM information_schema.columns 
        WHERE table_name = 'wp_bcr_reviews' AND column_name = 'FlaggedForReview'
    ) THEN 'True' ELSE 'False' END AS column_exists;";

    $check = $wpdb->get_results($checkSQL);

    if($check->column_exists){
        $sql = "ALTER TABLE $reviews_table_name ADD COLUMN FlaggedForReview INT DEFAULT 0;";

        $res = $wpdb->get_results($sql);
    }

    return $check;
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

/**
 * Add brands to brands table from csv
 * 
 * @return void
 */
function bcr_create_brands() {
    global $wpdb;

    $brands_table_name = $wpdb->prefix . "bcr_brands";

    if(($brands_file = fopen(plugin_dir_path( __FILE__ ) . 'brands.csv', "r")) !== FALSE) {
        while(($row = fgetcsv($brands_file, 1000, ",")) !== FALSE) {
            $wpdb->insert($brands_table_name, array("brandName" => $row[0]));
        }
    }
    fclose($brands_file);
}

/**
 * Add Skis to products table from csv
 * 
 * @return void
 */
function bcr_create_skis() {
    global $wpdb;

    $categories_table_name = $wpdb->prefix . "bcr_categories";
    $products_table_name = $wpdb->prefix . "bcr_products";

    $wpdb->insert($categories_table_name, array('categoryName' => "Skis", 'sportID' => 2));

    if(($skis_file = fopen(plugin_dir_path( __FILE__ ) . 'skiProducts.csv', "r")) !== FALSE) {
        while(($row = fgetcsv($skis_file, 1000, ",")) !== FALSE) {
            $wpdb->insert($products_table_name, array("productName" => $row[1], 'categoryID' => $row[0], 'brandID' => $row[2]));
        }
    }
    fclose($skis_file);
}

/**
 * Add questions to table from csv
 * 
 * @return void
 */
function bcr_create_questions() {
    global $wpdb;

    $questions_table_name = $wpdb->prefix . "bcr_questions";

    if(($questions_file = fopen(plugin_dir_path( __FILE__ ) . 'questions.csv', "r")) !== FALSE) {
        while(($row = fgetcsv($questions_file, 1000, ",")) !== FALSE) {
            $wpdb->insert($questions_table_name, array("questionID" => $row[0], "questionContent" => $row[1], "questionDisplayContent" => $row[2]));
        }
    }
    fclose($questions_file);
}

bcr_init_tables();
?>