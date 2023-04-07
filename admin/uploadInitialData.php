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
    $products_table_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_products.sql.gz';
    $questions_table_file = plugin_dir_path( __FILE__ ) . 'wp_bcr_questions.sql.gz';

    $categories_sql = bcr_prepare_sql($categories_table_file);
    $brands_sql = bcr_prepare_sql($brands_table_file);
    $products_sql = bcr_prepare_sql($products_table_file);
    $questions_sql = bcr_prepare_sql($questions_table_file);

    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "w") or die('fopen failed');
    fwrite($file, "questions_sql: \n".$questions_sql."\n\n");
    dbDelta($categories_sql);
    dbDelta($brands_sql);
    dbDelta($products_sql);
    dbDelta($questions_sql);
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