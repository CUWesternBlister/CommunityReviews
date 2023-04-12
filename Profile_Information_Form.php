<?php
function Profile_Information_Form($atts){
    ?>
    <head>
        <style>
            .user_agreement{
                width: 80%;
                height: 200px;
                overflow-y: scroll;
                border: 1px solid;
                margin-bottom: 5px;
            }
            .user_acceptance{
                height: 20px;
                width: 20px;
            }
            .field-1{
                width: 200px;
            }
            .field-2{
                width: 100px;
            }
            h2{
                font-weight: bold;
            }
        </style>
    </head>
    <form id="Profile_Info_Form" action='/community-reviews-profile/'  method="post">
        <label>Preferred System of Measurement:</label><br>
        <input type="radio" id="metric" name="measurement" value="metric" onclick="changefields('metric')" required/>
        <label for="metric">Metric</label>
        <input type="radio" id="imperial" name="measurement" value="imperial"onclick="changefields('imperial')" checked="checked"/>
        <label for="imperial">Imperial</label><br>
        <label id="height_label"></label><br>
        <label id="weight_label"></label>
        <input type="number" id="weight" name="weight" class="field-1" required/><br>
        <label id="experience_label">Experience Level: <br></label>
        <select name="experience" id="experience" class="field-1" required>
            <option value="Beginner">Beginner</option>
            <option value="Novice">Novice</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
            <option value="Expert">Expert</option>
        </select><br><br>
        <div class="user_agreement">
            <p>This website is referred to in these notices as the “Site,” and when “we”, “our” or “us” are used below, blisterreview.com (Blister) is referred to. End users are referred to as “you” below.</p>

            <h1>TERMS & CONDITIONS</h1>

            <p>By visiting, accessing, or using the Site and any linked pages, features, or content on the Site, you signify that you have read, understand and agree to be bound by these Terms & Conditions. You also agree to our use of your personal information and content in accordance with our <a href="http://blistergearreview.com/privacy-policy">Privacy Policy</a>.</p>

            <h2>Your Account</h2>

            <p>You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer, and you agree to accept responsibility for all activities that occur under or in relation to your account or password.<br><br>

            Account Information: When you create a Blister Community Reviews account, we store and use the information you provide during that process, such as the first and last name you enter, your height, your weight, your experience level, and any other information you may provide during the account creation process. We may publicly display the username that you provide, as well as any photo or other information you submit through the account creation process, as part of your account profile.</p>

            <h2>User Content</h2>

            <p>You may submit comments, reviews, posts, feedback, questions, answers, notes, messages, ideas, suggestions or other communications a user submits (“User Content”) on the Site.<br><br>

            Public Content: Your contributions to the Site are intended for public consumption and are therefore viewable by the public, including your reviews and account profile information. Your account profile (e.g., your username, height, weight, and experience level) is also intended for public consumption.<br><br>

            You agree that as a condition of permitting you to submit User Content to us, you represent and warrant that (a) it was not copied in whole or in part from any other work, and/or that you own or otherwise control all the intellectual property rights to it; (b) it is not libelous, obscene, injurious to third parties (as determined by us), and does not contain any form of spam (as determined by us). You agree to use your own, current e-mail address to submit all User Content.<br><br>

            You agree that we have the right, at any time, without notice and without limitations, to (a) refuse to allow you to submit further User Content; (b) remove and delete your User Content; and (c) delete, cancel, or deactivate your account and right to submit User Content by any means possible, without limitations.</p>

            <h2>E-Mail Communications</h2>

            <p>BY REGISTERING FOR AN ACCOUNT, YOU EXPRESSLY CONSENT TO RECEIVE EMAILS FROM US. There is an unsubscribe link in the footer of every email that we send. You can be removed instantly from our list by clicking it.</p>

            <h2>EUROPEAN RESIDENTS: YOUR PRIVACY RIGHTS AND INTERNATIONAL DATA TRANSFER</h2>


            <p>If you are a European Resident, you have the right to access your personal data, and the right to request that we correct, update, or delete your personal data. You can object to the processing of your personal information, ask us to restrict processing of your personal information, and request portability of your personal information. Similarly, if we have collected and processed your personal information with your consent, then you can withdraw your consent at any time by reaching out <a href="https://blisterreview.com/about/contact">via our Contact Us page</a>. Withdrawing your consent will not affect the lawfulness of any processing we conducted prior to your withdrawal, nor will it affect processing of your personal information conducted in reliance on lawful processing grounds other than consent. The Site generally provides you with a reasonable means to view and change your profile information and you can opt-out of marketing communications at any time by clicking on the “unsubscribe” or “opt-out” link in the marketing emails we send you. If you have any questions or comments about the processing of your personal information, you may <a href="https://blisterreview.com/about/contact">contact us</a> as described above.<br>


            For European Residents, please note that the personal information we obtain from or about you may be transferred, processed, and stored outside of the EEA, United Kingdom or Switzerland for the purposes described in this Privacy Policy, including in the United States of America. We take the privacy of our users seriously and therefore take steps to safeguard your information, including ensuring an adequate level of data protection in accordance with E.U. and United Kingdom standards in effect as of the date of this Privacy Policy.<br><br>

                For European Residents, our legal basis for collecting and using the information described above will depend on the specific information concerned and the context in which we collect it. We, however, will normally collect personal information from you only where we have your consent to do so, where we need the personal information to perform a contract with you, or where the processing is in our legitimate interests and not overridden by your data protection interests or fundamental rights and freedoms. In some cases, we may also have a legal obligation to collect personal information from you or may otherwise need the personal information to protect your vital interests or those of another person (for instance, to prevent, investigate, or identify possible wrongdoing in connection with the Site or to comply with legal obligations). If we ask you to provide personal information to comply with a legal requirement or to perform a contract with you, we will make this clear at the relevant time and advise you whether the provision of your personal information is mandatory or not (as well as of the possible consequences if you do not provide your personal information). If we collect and use your personal information in reliance on our legitimate interests (or those of any third party), this interest will typically be to operate our Site, communicate with you in relation to our Site, or for our other legitimate commercial interests, for instance, when responding to your queries, to analyze and improve our platform, engage in marketing, or for the purposes of detecting or preventing fraud. If you have questions about or need further information concerning the legal basis on which we collect and use your personal information, please contact us via the <a href="https://blisterreview.com/about/contact">Contact Us</a> form on our site.</p>

        </div>
        <input type="checkbox" id ="accept" name="accept" class="user_acceptance" oninvalid="this.setCustomValidity('In order to continue you must accept our Terms and Conditions')" required>
        <label for="accept">Click here to indicate that you have read and agree to these <strong>Terms and Conditions</strong></label><br>
        <input type="hidden" id="height" name="height">
        <input type="Submit" id="submit" name="Submit_Profile" value="Submit" style="background-color: #6EC1E4">
    </form>

    <script>
        function changefields( val ){
            if (document.contains(document.getElementById("height_cm"))){
                document.getElementById("height_cm").remove();
            }
            else if (document.contains(document.getElementById("height_feet"))){
                document.getElementById("height_feet").remove();
                document.getElementById("height_inches").remove();
            }
            var form = document.getElementById("Profile_Info_Form");
            var hlabel = document.getElementById('height_label');
            var wlabel = document.getElementById('weight_label');
            if (String(val).valueOf() == String('metric').valueOf()){
                hlabel.innerHTML = "Height in cm: <br>";
                wlabel.innerHTML = "Weight in kg: <br>";
                var height_cm = document.createElement("input");
                height_cm.type = "number";
                height_cm.id = "height_cm";
                height_cm.min = '0';
                height_cm.required = true;
                height_cm.className = "field-1";
                height_cm.addEventListener("input", getHeight);
                form.insertBefore(height_cm, hlabel.nextSibling);
            }
            else {
                hlabel.innerHTML = "Height in feet and inches: <br>";
                wlabel.innerHTML = "Weight in lbs: <br>";
                var height_feet = document.createElement("input");
                height_feet.type = "number";
                height_feet.id = "height_feet";
                height_feet.min = '0';
                height_feet.required = true;
                height_feet.placeholder = "feet";
                height_feet.className = "field-2";
                height_feet.addEventListener("input", getHeight);
                var height_inches = document.createElement("input");
                height_inches.type = "number";
                height_inches.id = "height_inches";
                height_inches.min = '0';
                height_inches.required = true;
                height_inches.placeholder = "inches";
                height_inches.className = "field-2";
                height_inches.addEventListener("input", getHeight);
                form.insertBefore(height_inches, hlabel.nextSibling);
                form.insertBefore(height_feet, hlabel.nextSibling);
            }
            init()
        }
        function getHeight(){
            var form = document.getElementById("Profile_Info_Form");
            if (document.getElementById("imperial").checked){
                let height_inches = document.getElementById("height_inches");
                let height_feet = document.getElementById("height_feet");
                let height = document.getElementById("height");
                height.value = 12*Number(height_feet.value) + Number(height_inches.value);
            }
            if (document.getElementById("metric").checked){
                let height_cm = document.getElementById("height_cm");
                let height = document.getElementById("height");
                height.value = Number(height_cm.value);
            }
        }
        function init(){
            <?php
            $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
            $myfile = fopen($file_path, "a") or die('fopen failed');
            $info = get_user_information($myfile);
            if ($info) {
                ?>
                if(document.getElementById("imperial").checked) {
                    let height_inches = document.getElementById("height_inches");
                    let height_feet = document.getElementById("height_feet");
                    let weight = document.getElementById("weight");
                    var H_inch = parseInt(<?php echo ($info->height) % 12;?>, 10);
                    var H_feet = parseInt(<?php echo (int)(($info->height) / 12);?>, 10);
                    var W = parseInt(<?php echo $info->weight;?>, 10);
                    height_inches.value = H_inch;
                    height_feet.value = H_feet;
                    weight.value = W;
                }
                else{
                    let height_cm = document.getElementById("height_cm");
                    let weight = document.getElementById("weight");
                    var H = parseInt(<?php echo round(($info->height)*2.54);?>, 10);
                    var W = parseInt(<?php echo round(($info->weight)*0.4536);?>, 10);
                    height_cm.value = H;
                    weight.value = W;
                }
                getHeight();
        <?php
            }?>
        }
        changefields('imperial')
        init()
    </script>
    <?php
}
add_shortcode('Profile_Information_Form', 'Profile_Information_Form');
if(isset($_POST['Submit_Profile'])){
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "w") or die('fopen failed');

    $userID = get_current_userID($file);
    $fields['userID'] = $userID;
    $fields['unit_preference'] = $_POST['measurement'];
    $fields['skiAbility'] = $_POST["experience"];

    if($_POST['measurement'] == "metric"){
        $fields['height'] = round(0.3937*(int)$_POST['height']);
        $fields['weight'] = round(2.2046*$_POST["weight"]);
    }
    else{
        $fields['height'] = (int)$_POST['height'];
        $fields['weight'] = $_POST["weight"];
    }

    global $wpdb;
    $user_table_name = $wpdb->prefix . "bcr_users";
    $q = $wpdb->prepare("SELECT userID FROM $user_table_name WHERE userID = %s;", $userID);
    $res = $wpdb->query($q);

    if($res){
        $wpdb->update($user_table_name, $fields, array("userID"=>$userID));
    }else {
        $wpdb->insert($user_table_name, $fields);
    }
}
?>
