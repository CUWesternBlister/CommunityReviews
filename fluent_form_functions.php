<?php
/**
 * Fill fluent form dropdowns from database
 * 
 * @return  Array   $data       form specific data
 */
function bcr_set_form_dropdowns( $data, $form ) {
    $id = $form->id;

    global $wpdb;

    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";

    $sql = $wpdb->prepare("SELECT categoryID FROM $review_forms_table_name WHERE reviewFormID = %s;", $id);

    $category_id = $wpdb->get_var($sql, 0, 0);

    $input_name = \FluentForm\Framework\Helpers\ArrayHelper::get($data, 'attributes.name');

    $new_dropdown = array();

    if($input_name == '1_1') {
        $brands_table_name = $wpdb->prefix . "bcr_brands";

        $sql = "SELECT brandName FROM $brands_table_name;";

        $results  = $wpdb->get_results($sql);

        foreach ($results as $id => $brand_obj) {
            $dropdown_entry = [
                "label" => $brand_obj->brandName,
                "value" => $brand_obj->brandName
            ];
            array_push($new_dropdown, $dropdown_entry);
        }
    } else if($input_name == '2') {
        $products_table_name = $wpdb->prefix . "bcr_products";

        $sql = $wpdb->prepare("SELECT productName FROM $products_table_name WHERE categoryID = %s;", $category_id);

        $results  = $wpdb->get_results($sql);

        foreach ($results as $id => $product_obj) {
            $dropdown_entry = [
                "label" => $product_obj->productName,
                "value" => $product_obj->productName
            ];
            array_push($new_dropdown, $dropdown_entry);
        }
    } else {
        return $data;
    }

    array_push($new_dropdown, ["label" => 'Other (Not Listed)', "value"=> 'Other (Not Listed)']);

    $data['settings']['advanced_options'] = $new_dropdown;

    return $data;
}

add_filter('fluentform_rendering_field_data_select', 'bcr_set_form_dropdowns', 10, 2);

/**
 * Ajax function to return the new dropdown options
 * 
 * @return  HTML
 */
function bcr_filter_fluent_forms_dropdown() {
    global $wpdb;

    $products_table_name = $wpdb->prefix . "bcr_products";

    if ( ! empty( $_POST['brand_selected'] ) ) {
        $selected_brand = sanitize_text_field( $_POST['brand_selected'] );
        $form_id = sanitize_text_field( $_POST['form_id'] );

        $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";
        $sql = $wpdb->prepare("SELECT categoryID FROM $review_forms_table_name WHERE reviewFormID = %s;", $form_id);
        $category_id = $wpdb->get_var($sql, 0, 0);

        $brands_table_name = $wpdb->prefix . "bcr_brands";
        $sql = $wpdb->prepare("SELECT brandID FROM $brands_table_name WHERE (brandName = %s);", $selected_brand);
        $selected_brand_id = $wpdb->get_var($sql, 0, 0);

        $sql = $wpdb->prepare("SELECT productName FROM $products_table_name WHERE (brandID = %s AND categoryID = %s);", array($selected_brand_id, $category_id));
    } else {
        $sql = $wpdb->prepare("SELECT productName FROM $products_table_name WHERE categoryID = %s;", $category_id);
    }

    $results  = $wpdb->get_results($sql);

    echo '<option value="">- Select Product -</option>';

    foreach ($results as $id => $product_obj) {
        $product_name = $product_obj->productName;

        echo '<option value="' . esc_html($product_name) . '">' . esc_html($product_name) . '</option>';
    }

    echo '<option value="Other (Not Listed)">Other (Not Listed)</option>';

    wp_die();
}

add_action( 'wp_ajax_bcr_filter_fluent_forms_dropdown', 'bcr_filter_fluent_forms_dropdown' );
add_action( 'wp_ajax_nopriv_bcr_filter_fluent_forms_dropdown', 'bcr_filter_fluent_forms_dropdown' );
?>