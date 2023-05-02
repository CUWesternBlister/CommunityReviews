<?php
/**
 * Reads in the ID and name of all fluent forms currently
 * on the site at the time of this function running and
 * populates the bcr_review_forms table with that information.
 * 
 * @return void
 */
function align_form_ids() {
    global $wpdb;

    $fluent_forms_table_name = $wpdb->prefix . "fluentform_forms";
    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";

    $sql = "SELECT id, title FROM $fluent_forms_table_name;";
    $existing_forms = $wpdb->get_results($sql);

    foreach($existing_forms as $id => $form) {
        //--------------------------------------Temporary solution for Summit only---------------------------------------------------------
        $categoryIDs = array(3 => "Summit_Apparel_Form", 2 => "Summit_Ski_Boot_Review_Form", 5 => "Climbing Skin Review", 4 => "Ski Review Form", 7 => "Snowboard Review");
        
        $category = array_search($form->title, $categoryIDs);

        if($category === false) {
            $category = 1;
        }

        $num_existing = $wpdb->get_results("SELECT * FROM $review_forms_table_name WHERE reviewFormID LIKE '%" . $form->id . "%' ");

        if($num_existing == 0) {
            $wpdb->insert($review_forms_table_name, array("reviewFormID" => $form->id, "reviewFormName" => $form->title, "categoryID" => $category));
        }
    }
}

//align_form_ids();
?>