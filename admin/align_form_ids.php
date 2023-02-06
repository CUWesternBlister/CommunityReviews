<?php
function align_form_ids() {
    global $wpdb;

    $fluent_forms_table_name = $wpdb->prefix . "fluentform_forms";
    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";

    $sql = $wpdb->prepare("SELECT id, title FROM $fluent_forms_table_name;");
    $existing_forms = $wpdb->get_results($sql);

    foreach($existing_forms as $id => $form) {
        $wpdb->insert($review_forms_table_name, array("reviewFormID" => $form->id, "reviewFormName" => $form->title, "categoryID" => 1));
    }
}

align_form_ids();
?>