<?php
/** 
 * Call back function for CSV Upload Submenu
 * 
 * @return void
 */
function bcr_add_products_callback(){


    displayCSVMenu();
}

/** 
 * Defines the html for CSV Uplaod Submenu
 * 
 * @return void
 */
function displayCSVMenu(){
    ?>
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    </head>

    <div class="CSV-table-upload-admin-display">
        <div id="bcr_admin_display_specific_tables">
        <?php
            global $wpdb;

            $table_names_array = array(
                $wpdb->prefix . "bcr_categories",
                $wpdb->prefix . "bcr_brands",
                $wpdb->prefix . "bcr_products"
            );

            bcr_display_CSV_file_upload();
            bcr_display_category_text_field_upload();
            

            foreach($table_names_array as $table_name) {
                bcr_display_table_contents($table_name);
            }
        ?>
        </div>

    </div>    


<?
}

/** 
 * Displays a text field for entering a category
 * 
 * @return void
 */
function bcr_display_category_text_field_upload(){
    ?>
    <div class="community-reviews-upload-category">
        <label for="community-reviews-upload-CSV">Add a New Category:</label>
        <div id = "Add Category">
            <label for="parentID">parentID (Must an existing CategoryID or 0):</label>
            <input type="text" id="parentID" name="parentID" size="10"/>
            <label for="categoryName">categoryName:</label>
            <input type="text" id="categoryName" name="categoryName" size="20"/>
            <button type="button" id="upload_category" onclick="bcr_uploadCategoryButtonClicked()" >Upload Category</button>
        </div>
    </div>
    <script>
        function bcr_uploadCategoryButtonClicked(){
            const parentIDField = document.getElementById('parentID');
            const categoryNameField = document.getElementById('categoryName');
            const parentID = parentIDField.value;
            const categoryName = categoryNameField.value;
            console.log(parentID, categoryName);
            jQuery.ajax({
                url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                method: 'POST',
                data: {
                    action: 'insertCategory',
                    parentID: parentID,
                    categoryName: categoryName
                },
                success: function(result) {
                    console.log(result);
                    location.reload();
                }
            });
        }
    </script>
    <?php
}

/** 
 * Defines the html for upload CSV button
 * 
 * @return void
 */
function insertCategory(){
    global $wpdb;

    $categoryTableName = $wpdb->prefix . "bcr_categories";

    $parentID = $_POST['parentID'];
    $categoryName = $_POST['categoryName'];

    $result = "";

    if($parentID == 0){
        $categoryExists = check_for_category($categoryName);
        if(!$categoryExists){
            $sql = "INSERT INTO $categoryTableName (`parentID`, `categoryName`) VALUES";
            $sql = $sql . "\n" . "($parentID, '$categoryName');";

            $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
            $file = fopen($file_path, "w") or die('fopen failed');
            fwrite($file, "insert category sql1: $sql\n");

            $queryResult = $wpdb->get_results($sql);
            $result =  "Inserted Category: $categoryName with parentID: $parentID.\n";
        } else{
            $result =  "Category already exists.\n";
        }
    } else{
        $sql = "SELECT * FROM $categoryTableName WHERE categoryID=$parentID;";
        $res = $wpdb->get_row($sql);
        if($res){
            $categoryExists = check_for_category($categoryName);
            if(!$categoryExists){
                $sql = "INSERT INTO $categoryTableName (`parentID`, `categoryName`) VALUES";
                $sql = $sql . "\n" . "($parentID, '$categoryName');";

                $queryResult = $wpdb->get_results($sql);
                $result = "Inserted Category: $categoryName with parentID: $parentID.\n";
            } else{
                $result = "Category already exists.\n";
            }
        } else{
            $result = "parentID: $parentID doesn't already exist as a category in the table.\n";
        }
    }

    echo $result;
    wp_die();
}

add_action( 'wp_ajax_insertCategory', 'insertCategory' );
add_action( 'wp_ajax_nopriv_insertCategory', 'insertCategory' );


/** 
 * Defines the html for upload CSV button
 * 
 * @return void
 */
function bcr_display_CSV_file_upload(){
    ?>
    <div class="community-reviews-upload-CSV">
        <label for="community-reviews-upload-CSV-brand">Upload a Brand CSV:</label>
        <div id="Brand CSV Upload">
            <fieldset>
                <input type="file" name="Brand File Upload" id="brandFileUpload" accept=".csv" />
            </fieldset>
            <button type="button" id="upload_brand_csv" onclick="bcr_uploadBrandButtonClicked()" >Upload Brand CSV</button>
        </div>
    </div>
    <script>
        var file;

        // Get the file input element
        const fileInput = document.getElementById('brandFileUpload');

        // Add an event listener to the file input element
        fileInput.addEventListener('change', handleFileSelect, false);

        function handleFileSelect(event){
            window.file = event.target.files[0];
            console.log(file);
        }

        function bcr_uploadBrandButtonClicked(){
            const brandCSVField = document.getElementById('brandFileUpload');
            const brandCSV = brandCSVField.value;
            var stringData;
            console.log(`this is file: ${file}`);
            Papa.parse(file, {
                complete: function(results) {
                    for (let i = 0; i < results.data.length; i++) {
                        console.log(results.data);
                        stringData = JSON.stringify(results.data);
                        console.log(`stringData: ${stringData}\n`);
                    }
                }
            });
            console.log(brandCSV);
            jQuery.ajax({
                url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                method: 'POST',
                data: {
                    action: 'adminUploadBrandCSV',
                    brandCSV: file,
                    stringData: stringData
                },
                success: function(result){
                    console.log(result);
                }
            });
            console.log("post Ajax call");
        }
    
    </script>
    <div class="community-reviews-upload-CSV">
        <label for="community-reviews-upload-CSV-product">Upload a Product CSV:</label>
        <div id="Product CSV Upload">
            <fieldset>
                <input type="file" name="Product File Upload" id="productFileUpload" accept=".csv" />
            </fieldset>
            <button type="button" name="upload_product_csv" onclick="bcr_uploadProductButtonClicked()" >Upload Product CSV</button>
        </div>
    </div>
    <script>

        function bcr_uploadProductButtonClicked(){
            const productCSVField = document.getElementById('productFileUpload');
            const productCSV = productCSVField.value;
            console.log(productCSV);
        }

    </script>
    <?php
}

/** 
 * Upload brand CSV to brand table
 * 
 * @return void
 */
function adminUploadBrandCSV(){
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "w") or die('fopen failed');

    $stringData = $_POST['stringData'];

    fwrite($file, "entered UploadBrandCSV ajax funciton.\n");
    fwrtie($file, "string data in adminUploadBrandCSV: ");

    $brandCSV = $_POST['brandCSV'];

    fwrite($file, "after getting brandCSV\n");
    require_once( plugin_dir_path( __FILE__ ) . 'uploadInitialData.php');
    //$result = bcr_update_brands_table($brandCSV);
    $result = FALSE;

    fwrite($file, "after brand csv funciton call\n");

    echo $result;
    wp_die();
}

add_action( 'wp_ajax_adminUploadBrandCSV', 'adminUploadBrandCSV' );
add_action( 'wp_ajax_nopriv_adminUploadBrandCSV', 'adminUploadBrandCSV' );



/** 
 * Add submenu for uploading CSVs 
 * 
 * @return void
 */
function add_bcr_add_products_submenu_page() {  
    add_submenu_page(
        'edit.php?post_type=communityreviews', // The parent menu slug
        'Add Products', // The page title
        'Add Products, Brands, Categories', //submenu title
        'manage_options', // The required user capability to access the page
        'bcr-add-product-brand-category', // The menu slug
        'bcr_add_products_callback' // The callback function to display the page content
    );
}

add_action( 'admin_menu', 'add_bcr_add_products_submenu_page' );
?>