<?php
function Profile_Information_Form($atts){
    ?>
    <form id="Profile_Info_Form" action='/community-reviews-homepage/'  method="post">
        <label>Preferred System of Measurement:</label><br>
        <input type="radio" id="metric" name="measurement" value="metric" onclick="changefields('metric')" required/>
        <label for="metric">Metric</label>
        <input type="radio" id="imperial" name="measurement" value="imperial"onclick="changefields('imperial')" checked="checked"/>
        <label for="imperial">Imperial</label><br>
        <label id="height_label"></label>
        <input type="number" id="height" name="height" required/><br>
        <label id="weight_label"></label>
        <input type="number" id="weight" name="weight" required/><br>
        <label id="experience_label">Experience Level: <br></label>
        <select name="experience" id="experience" required>
            <option value="Beginner">Beginner</option>
            <option value="Novice">Novice</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
            <option value="Expert">Expert</option>
        </select><br>
        <input type="Submit" id="submit" name="Submit_Profile" value="Submit">
    </form>

    <script>
        function changefields( val ){
            var form = document.getElementById("Profile_Info_Form");
            var hlabel = document.getElementById('height_label');
            var wlabel = document.getElementById('weight_label');
            if (String(val).valueOf() == String('metric').valueOf()){
                hlabel.innerHTML = "Height in cm.: <br>"
                wlabel.innerHTML = "Weight in kg.: <br>"
            }
            else {
                hlabel.innerHTML = "Height in in.: <br>"
                wlabel.innerHTML = "Weight in lbs.: <br>"
            }
            form.insertBefore(label, height)
        }
        changefields('imperial')
    </script>
    <?php
}
add_shortcode('Profile_Information_Form', 'Profile_Information_Form');
if(isset($_POST['Submit_Profile'])){
    echo "hello!";
    $file = fopen('testfile.txt', "w") or die('fopen failed');
    $userID = get_current_userID($file);
    $fields['userID'] = $userID;
    $fields['unit preference'] = $_POST['measurement'];
    $fields['height'] = $_POST['height'];
    $fields['weight'] = $_POST["weight"];
    $fields['skiAbility'] = $_POST["experience"];


}
?>
