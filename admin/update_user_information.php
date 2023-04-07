<?php

inlcude table_utils

function bcr_update_user_info( $data, $form ) {

    $userID = get_current_userID($file);
    if ($form->id !=  6 || $userID == False) {
        return $data;
    }
    /*
    if (\FluentForm\Framework\Helpers\ArrayHelper::get($data, 'attributes.name') != '1_1') {
        return $data;
    }
    */
    global $wpdb;

    $user_table_name = $wpdb->prefix . "bcr_users";

    $sql = "SELECT * FROM $products_table_name;";

    $results  = $wpdb->get_results($sql);

    $new_dropdown = array();
    foreach ($results as $id => $product_obj) {
        $dropdown_entry = [
            "label" => $product_obj->productName,
            "value" => $product_obj->productName
        ];
        array_push($new_dropdown, $dropdown_entry);
    }

    $data['settings']['advanced_options'] = $new_dropdown;

    return $data;
}

//add_filter('fluentform_rendering_field_data_select', 'bcr_set_fluent_form_product_dropdown', 10, 2);
?>