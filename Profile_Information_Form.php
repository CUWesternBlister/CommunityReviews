<?php
function Profile_Information_Form($atts){
    ?>
    <form id="Profile_Info_Form" action='/community-reviews-profile/'  method="post">
        <label>Preferred System of Measurement:</label><br>
        <input type="radio" id="metric" name="measurement" value="metric" onclick="changefields('metric')" required/>
        <label for="metric">Metric</label>
        <input type="radio" id="imperial" name="measurement" value="imperial"onclick="changefields('imperial')" checked="checked"/>
        <label for="imperial">Imperial</label><br>
        <label id="height_label"></label><br>
        <label id="weight_label"></label>
        <input type="number" id="weight" name="weight" style="width:200px" required/><br>
        <label id="experience_label">Experience Level: <br></label>
        <select name="experience" id="experience" style="width:200px" required>
            <option value="Beginner">Beginner</option>
            <option value="Novice">Novice</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
            <option value="Expert">Expert</option>
        </select><br><br>
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
                height_cm.style.width = "200px";
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
                height_feet.style.width = "200px";
                height_feet.addEventListener("input", getHeight);
                var height_inches = document.createElement("input");
                height_inches.type = "number";
                height_inches.id = "height_inches";
                height_inches.min = '0';
                height_inches.required = true;
                height_inches.placeholder = "inches";
                height_inches.style.width = "200px";
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
            <?php global $wpdb;
            global $wpdb;
            $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
            $myfile = fopen($file_path, "a") or die('fopen failed');
            $userID = get_current_userID($myfile);
            $user_table_name = $wpdb->prefix . "bcr_users";
            $q = "SELECT * FROM $user_table_name WHERE userID = $userID;";
            $info = $wpdb->get_row($q);
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
