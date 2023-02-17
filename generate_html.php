<?php
/* 
* These functions generate html for the post content
*
*
*
*/
function format_questions_answers_post_content($qs_and_ans, $form_name, $file){
    //$start = "\n\n format_questions_answers_post_content \n";
    //fwrite($file, $start);
    $titleQuestions = $qs_and_ans['title'];

    $testingConditionsQuestions = $qs_and_ans['testingConditions'];

    $multipleChoiceQuestions = $qs_and_ans['multipleChoice'];

    $testimonyQuestions = $qs_and_ans['testimony'];


    $pluginData = get_plugin_data(plugin_dir_path( __FILE__ ).'/blister-community-reviews.php');
    $pluginVersion = $pluginData['Version'];

    $html = "";

    if ($form_name == 'Ski Review Form'){//ski

        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        $html .= gen_HTML_for_testing_conditions_qs($testingConditionsQuestions, $form_name);
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

    if ($form_name == 'Summit_Ski_Boot_Review_Form'){//boot

        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        $html .= gen_HTML_for_testing_conditions_qs($testingConditionsQuestions, $form_name);
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

    if ($form_name == 'Summit_Apparel_Form'){//apparel

        $html .= gen_HTML_for_multiple_choice_qs2($multipleChoiceQuestions, $form_name);
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }
    if ($form_name == 'Snowboard Review'){//snowboard

        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        $html .= gen_HTML_for_testing_conditions_qs($testingConditionsQuestions, $form_name);
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

        if ($form_name == 'Climbing Skin Review'){//apparel

        $html .= gen_HTML_for_multiple_choice_qs2($multipleChoiceQuestions, $form_name);
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

        if ($form_name == 'Backpack review'){//apparel

        $html .= gen_HTML_for_multiple_choice_qs2($multipleChoiceQuestions, $form_name);
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }
    return $html;
}

function gen_HTML_for_multiple_choice_qs($mulipleChoiceQs, $formName){
    //the object is now: ["id" => $id, "question" => $display, "answer" => $answer];
    //not question=>answer
    //below should sort of be the update to these fucntions:
    $html = '<div id = "multipleChoice" class = "long_container">
            <div id = "multipleChoiceTitle" class = "section_title">Product Review</div>';
    foreach($mulipleChoiceQs as $arr){
            $html.='<div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title">'.esc_html($arr['question']).'</div>
                    <div id = "'.esc_html($formName).'_answer_'.esc_html($arr['id']).'"  class = "answer">'.esc_html($arr['answer']).'</div>';    
    }
    return $html.'</div>';
}

function gen_HTML_for_multiple_choice_qs2($mulipleChoiceQs, $formName ){
    $html = '<div id = "multipleChoice2" class = "whole_container">
            <div id = "multipleChoiceTitle2" class = "section_title">Product Review</div>';
    foreach($mulipleChoiceQs as $arr){
            $html.='<div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title">'.esc_html($arr['question']).'</div>
                    <div id = "'.esc_html($formName).'_answer_'.esc_html($arr['id']).'" class = "answer">'.esc_html($arr['answer']).'</div>';    
    }
    return $html.'</div>';
}

function gen_HTML_for_testing_conditions_qs($testingConditions, $formName){
    $html = '<div id = "testingConditions" class = "short_container">
            <div id = "testingConditionsTitle" class = "section_title">Testing Conditions</div>';
    foreach($testingConditions as $arr){
            $html.='<div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title">'.esc_html($arr['question']).'</div>
                    <div id = "'.esc_html($formName).'_answer_'.esc_html($arr['id']).'" class = "answer">'.esc_html($arr['answer']).'</div>';    
    }
    return $html.'</div>';
}

function gen_HTML_for_testimony_qs($testimony, $formName){
    $html = '<div id = "testimony" class = "whole_container">
            <div id = "testimonyTitle" class = "section_title">Testimony</div>';
    foreach($testimony as $arr){
        if($arr['answer'] != ""){
            $html.='<div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title">'.esc_html($arr['question']).'</div>
                    <div id = "'.esc_html($formName).'_answer_'.esc_html($arr['id']).'" class = "answer">'.esc_html($arr['answer']).'</div>';    
        }
    }
    return $html.'</div>';
}
?>
