<?php
/* 
* These functions generate html for the post content
*
*
*
*/
function format_questions_answers_post_content($qs_and_ans, $form_name, $file){

    $titleQuestions = $qs_and_ans['title'];
    
    $testingConditionsQuestions = $qs_and_ans['testingConditions'];
    
    $multipleChoiceQuestions = $qs_and_ans['multipleChoice'];
    
    $testimonyQuestions = $qs_and_ans['testimony'];
   

    $pluginData = get_plugin_data(plugin_dir_path( __FILE__ ).'/blister-community-reviews.php');
    $pluginVersion = $pluginData['Version'];


    if ($form_name == 'Ski Review Form'){//ski

        $html .= '<div id = "testimonyAndTestingContainer" class = "whole_container">';
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        $html .= gen_HTML_for_testing_conditions_qs($testingConditionsQuestions, $form_name);
        $html .= '</div>';
        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

    if ($form_name == 'Summit_Ski_Boot_Review_Form'){//boot

        $html .= '<div id = "testimonyAndTestingContainer" class = "whole_container">';
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        $html .= gen_HTML_for_testing_conditions_qs($testingConditionsQuestions, $form_name);
        $html .= '</div>';
        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

    if ($form_name == 'Summit_Apparel_Form'){//apparel

        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }
    if ($form_name == 'Snowboard Review'){//snowboard

        $html .= '<div id = "testimonyAndTestingContainer" class = "whole_container">';
        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        $html .= gen_HTML_for_testing_conditions_qs($testingConditionsQuestions, $form_name);
        $html .= '</div>';
        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

        if ($form_name == 'Climbing Skin Review'){//apparel

        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
        //$html .= '<div class = "whole_container_version" hidden="hidden">
        //    <div> Blister Community Reviews Version: '.$pluginVersion.'</div>
        //    </div>';
    }

        if ($form_name == 'Backpack review'){//apparel

        $html .= gen_HTML_for_testimony_qs($testimonyQuestions, $form_name);
        $html .= gen_HTML_for_multiple_choice_qs($multipleChoiceQuestions, $form_name);
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
    $html = '<div id = "multipleChoice" class = "whole_container_justified">';
    foreach($mulipleChoiceQs as $arr){
            //$html.='<div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title">'.esc_html($arr['question']).'</div>
            //        <div id = "'.esc_html($formName).'_answer_'.esc_html($arr['id']).'"  class = "answer">'.esc_html($arr['answer']).'</div>';   
            if ($arr['answer'] == 'Terrible'){
                $html.= '<div id = circleContainter"'.esc_html($formName).'_'.esc_html($arr['id']).'" class = circle_container">
                    <div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title_no_padding">'.esc_html($arr['question']).'</div>
                    <div id = "circle1'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle2'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    <div id = "circle3'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    <div id = "circle4'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    <div id = "circle5'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    </div>';
            } 
            if ($arr['answer'] == 'Not very good'){
                $html.= '<div id = circleContainter"'.esc_html($formName).'_'.esc_html($arr['id']).'" class = circle_container">
                    <div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title_no_padding">'.esc_html($arr['question']).'</div>
                    <div id = "circle1'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle2'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle3'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    <div id = "circle4'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    <div id = "circle5'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    </div>';
            } 
            if ($arr['answer'] == 'Average / Okay'){
                $html.= '<div id = circleContainter"'.esc_html($formName).'_'.esc_html($arr['id']).'" class = circle_container"> 
                    <div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title_no_padding">'.esc_html($arr['question']).'</div>
                    <div id = "circle1'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle2'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle3'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle4'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    <div id = "circle5'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    </div>';
            } 
            if ($arr['answer'] == 'Pretty good'){
                $html.= '<div id = circleContainter"'.esc_html($formName).'_'.esc_html($arr['id']).'" class = circle_container">
                    <div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title_no_padding">'.esc_html($arr['question']).'</div>
                    <div id = "circle1'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle2'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle3'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle4'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle5'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle-grey"></div>
                    </div>';
            } 
            if ($arr['answer'] == 'Excellent'){
                $html.= '<div id = circleContainter"'.esc_html($formName).'_'.esc_html($arr['id']).'" class = circle_container">
                    <div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title_no_padding">'.esc_html($arr['question']).'</div>
                    <div id = "circle1'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle2'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle3'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle4'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    <div id = "circle5'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "clip-circle"></div>
                    </div>';
            } 


    }
    return $html.'</div>';
    //return $html;
}

function gen_HTML_for_testing_conditions_qs($testingConditions, $formName){
    $html = '<div id = "testingConditions" class = "short_container">';
    foreach($testingConditions as $arr){
            $html.='<div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title">'.esc_html($arr['question']).'</div>
                    <div id = "'.esc_html($formName).'_answer_'.esc_html($arr['id']).'" class = "answer">'.esc_html($arr['answer']).'</div>';    
    }
    return $html.'</div>';
}

function gen_HTML_for_testimony_qs($testimony, $formName){
    $html = '<div id = "testimony" class = "long_container">';
    $reversed_list = array_reverse($testimony, true);
    foreach($reversed_list as $arr){
        if($arr['answer'] != ""){
            $html.='<div id = "'.esc_html($formName).'_'.esc_html($arr['id']).'" class = "question_title">'.esc_html($arr['question']).'</div>
                    <div id = "'.esc_html($formName).'_answer_'.esc_html($arr['id']).'" class = "answer">'.esc_html($arr['answer']).'</div>';    
        }
    }
    return $html.'</div>';
}

function format_reviewerInfo($userInfo, $userName){
    $html .= '<div id = "reviewerInfoContainer" class = "whole_container_flex">
        <div id= " userName" class = "short_container_no_float">Reviewer: '.esc_html($userName). '</div>
        <div id= "userHeight" class = "short_container_no_float">Height: '.esc_html($userInfo->heightFeet).' ft, '.esc_html($userInfo->heightInches).' in</div>
        <div id "userWeight" class = "short_container_no_float">Weight: '.esc_html($userInfo->weight).' lbs</div>
        <div id= "userAbility" class = "short_container_no_float">Ability: '.esc_html($userInfo->skiAbility).'</div>
        </div>';
    return $html;

}

function format_review_excerpt($userInfo, $userName, $qs_and_ans){
    $testimonyQuestions = $qs_and_ans['testimony'];
    $reversed_testimony_list = array_reverse($testimonyQuestions, true);
    $personal_testimony = $reversed_testimony_list[0];

    /*if($personal_testimony['question'] == 'About Product'){
        $html .= '<div id = "excerpt_container" class = whole_container>
            <div id = "excerpt_testimony_container" class = "long_container">'.esc_html($personal_testimony['answer']).'</div>
            <div id = "excerpt_attributes_container" class = "short_container">
            <div id= "userName" class = "userInfo">Reviewer: '.esc_html($userName).'</div>
            <div id= "userHeight" class = "userInfo">Height: '.esc_html($userInfo->heightFeet).' ft, '.esc_html($userInfo->heightInches).' in</div>
            <div id "userWeight" class = "userInfo">Weight: '.esc_html($userInfo->weight).' lbs</div>
            <div id= "userAbility" class = "userInfo">Reviewer Ability: '.esc_html($userInfo->skiAbility).'</div>
            </div>
            </div>';
        return $html;
    }else{*/

        $html .= '<div id= "userName" class = "userInfo">Reviewer: '.esc_html($userName).'</div>
            <div id= "userHeight" class = "userInfo">Height: '.esc_html($userInfo->heightFeet).' ft, '.esc_html($userInfo->heightInches).' in</div>
            <div id "userWeight" class = "userInfo">Weight: '.esc_html($userInfo->weight).' lbs</div>
            <div id= "userAbility" class = "userInfo">Reviewer Ability: '.esc_html($userInfo->skiAbility).'</div>';
        return $html;

    /*}*/
}

function get_post_title($title_arr){
$str = "";
$year = "";
$html = "";
foreach($title_arr as $arr){
    if($arr['id'] == 9){
            $year .= $arr["answer"] . ' ';
    }
    else{
        if($arr['id'] == 3){
            $str.=$arr["answer"] . 'cm ';
        }else{
            $str.=$arr["answer"] . ' ';  
        }  
    }
}
$html = $str . $year; 
return $html;
}
?>
