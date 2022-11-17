<?php
/**
 * Display excerpts of reviews to a shortcode call
 * 
 * @param string[]  $atts       Shortcode attributes
 * @param string    $content    Content wrapped by shortcode
 * 
 * @return string   Shortcode output
 */
function bcr_display_reviews() {
    global $wpdb;

    $reviews_table_name = $wpdb->prefix . "bcr_reviews";
    $products_table_name = $wpdb->prefix . "bcr_products";
    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";
    $questions_table_name = $wpdb->prefix . "bcr_questions";
    $reviews_answers_table_name = $wpdb->prefix . "bcr_reviews_answers";
    $answers_table_name = $wpdb->prefix . "bcr_answers";
    $know_thyself_answers_table_name = $wpdb->prefix . "bcr_know_thyself_answers";

    //Pass sql query through any applied filters and sanitize it for execution
    $sql = $wpdb->prepare(apply_filters('bcr_display_reviews_query', "SELECT * FROM $reviews_table_name"));
    if (!str_contains($sql, 'LIMIT')) {
        $sql .= ' LIMIT 20';
    }
    $sql .= ';';
    $rows = $wpdb->get_results($sql);

    $output = "<table><tr><th>Blister Community Reviews</th></tr>";

    foreach ($rows as $row) {
        $productID_sql = $wpdb->prepare("SELECT productID FROM $review_forms_table_name WHERE (reviewFormID = %s) LIMIT 1;", $row->reviewFormID);
        $productID = $wpdb->get_var($productID_sql, 0, 0);
        $productName_sql = $wpdb->prepare("SELECT productName FROM $products_table_name WHERE (productID = %s) LIMIT 1;", $productID);
        $productName = $wpdb->get_var($productName_sql, 0, 0);
        $reviewID = $row->reviewID;
        $knowThyselfID = $row->knowThyselfID;
        $output .= "<tr><td>";
        $output .= "<strong>This is a Review for " . esc_html($productName) . "</strong>";
        for($i = 0; $i < 4; $i++) {
            $j = 0;
            if($i == 0) {
                $answerID_sql = $wpdb->prepare("SELECT answerID FROM $know_thyself_answers_table_name WHERE (knowThyselfID = %s) LIMIT 1;", $knowThyselfID);
            } else {
                $answerID_sql = $wpdb->prepare("SELECT answerID FROM $reviews_answers_table_name WHERE (reviewID = %s) LIMIT 3;", $reviewID);
                $j = $i-1;
            }
            $answerID = $wpdb->get_var($answerID_sql, 0, $j);
            $answer_sql = $wpdb->prepare("SELECT * FROM $answers_table_name WHERE (answerID = %s) LIMIT 1;", $answerID);
            $answer = reset($wpdb->get_results($answer_sql));
            $questionID = $answer->questionID;
            $question_sql = $wpdb->prepare("SELECT questionContent FROM $questions_table_name WHERE (questionID = %s) LIMIT 1;", $questionID);
            $question = $wpdb->get_var($question_sql, 0, 0);
            $output .= "<br>" . esc_html($question) . "     " . esc_html($answer->answerContent);
        }
        $output .= "</td></tr>";
    }

    $output = $output . "</table>";

    return $output;
}

add_shortcode('display_community_reviews', 'bcr_display_reviews');

/**
 * Write contents of Elementor forms with a specific name to database
 * 
 * @param $record       Information about the form submission
 * @param $ajax_handler Handler for ajax instance
 */
function bcr_store_review($record, $ajax_handler) {

    $raw_fields = $record->get('fields');

    $fields = [];

    foreach($raw_fields as $id => $field) {
        $fields[$id] = $field['value'];
    }

    global $wpdb;

    //if('Example Product Review' == $form_name) {
        $reviews_table_name = $wpdb->prefix . "bcr_reviews";
        $answers_table_name = $wpdb->prefix . "bcr_answers";
        $questions_table_name = $wpdb->prefix . "bcr_questions";
        $know_thyself_table_name = $wpdb->prefix . "bcr_know_thyself";
        $know_thyself_answers_table_name = $wpdb->prefix . "bcr_know_thyself_answers";
        $reviews_answers_table_name = $wpdb->prefix . "bcr_reviews_answers";

        $num_entries = 0;
        foreach($fields as $questionID => $answer) {
            /*$sql = $wpdb->prepare("SELECT questionID FROM $questions_table_name WHERE (questionContent = %s)", $questionContent);
            $questionID = reset($wpdb->query($sql))->questionID;*/

            $result = $wpdb->insert($answers_table_name, array('answerContent' => $answer, 'questionID'=> $questionID));
            if($result != false){
                $num_entries += $result;
            }
        }

        $last_answer_id = $wpdb->get_var("SELECT answerID FROM $answers_table_name ORDER BY answerID DESC LIMIT 1", 0, 0);

        $wpdb->insert($know_thyself_table_name, array('userID' => 0, 'knowThyselfFormID' => 1));
        $knowThyselfID = $wpdb->get_var("SELECT knowThyselfID FROM $know_thyself_table_name ORDER BY knowThyselfID DESC LIMIT 1", 0, 0);

        $wpdb->insert($reviews_table_name, array('userID' => 0, 'knowThyselfID' => $knowThyselfID, 'reviewFormID' => 1));
        $reviewID = $wpdb->get_var("SELECT reviewID FROM $reviews_table_name ORDER BY reviewID DESC LIMIT 1", 0, 0);

        $wpdb->insert($know_thyself_answers_table_name, array('knowThyselfID' => $knowThyselfID, 'answerID' => ($last_answer_id+1-$num_entries)));
        $num_entries--;

        for($i = $last_answer_id+1-$num_entries; $i <= $last_answer_id; $i++) {
            $wpdb->insert($reviews_answers_table_name, array('reviewID' => $reviewID, 'answerID' => $i));
        }
    //}
}

add_action( 'elementor_pro/forms/new_record', 'bcr_store_review', 10, 2);

/**
 * Function to read the question content for a specific question
 * 
 * @param int       $id The questionID of the question to read
 * 
 * @return string   Shortcode output
 */
function bcr_read_question_content($atts = [], $content = null) {
    $defaults = array(
        'id' => '1',
    );
    $atts = shortcode_atts($defaults, $atts);

    global $wpdb;

    $question_table_name = $wpdb->prefix . "bcr_questions";

    $sql = $wpdb->prepare("SELECT questionContent FROM $question_table_name WHERE (questionID = %s)", $atts['id']);

    $output = $wpdb->get_var($sql, 0, 0);
    
    return $output;
}

add_shortcode('read_question', 'bcr_read_question_content');
?>