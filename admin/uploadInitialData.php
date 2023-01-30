<?php
/**
 * 
 * Create required table contents
 * 
 */
function bcr_init_tables() {
    bcr_create_brands();
    bcr_create_skis();
}

/**
 * 
 * Add brands to brands table
 * 
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
 * 
 * Add Skis to products table
 * 
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

bcr_init_tables();
?>