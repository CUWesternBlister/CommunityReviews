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

    if($input_name == 'brand') {
        $brands_table_name = $wpdb->prefix . "bcr_brands";

        $sql = $wpdb->prepare("SELECT brandName FROM $brands_table_name;");

        $results  = $wpdb->get_results($sql);

        foreach ($results as $id => $brand_obj) {
            $dropdown_entry = [
                "label" => $brand_obj->brandName,
                "value" => $brand_obj->brandName
            ];
            array_push($new_dropdown, $dropdown_entry);
        }
    } else if($input_name == 'product') {
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

    $data['settings']['advanced_options'] = $new_dropdown;

    return $data;
}

add_filter('fluentform_rendering_field_data_select', 'bcr_set_form_dropdowns', 10, 2);
?>