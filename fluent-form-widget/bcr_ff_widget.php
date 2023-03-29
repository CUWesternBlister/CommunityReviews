<?php

class bcr_ff_field extends \FluentForm\App\Services\FormBuilder\BaseFieldManager
{
    public function __construct()
    {
        parent::__construct(
            'bcr_ff_element_key',
            'BCR Question Selection',
            ['bcr', 'BCR', 'community', 'reviews'],
            'general' // where to push general/advanced
        );
        add_filter("fluentform_validate_input_item_{$this->key}", [$this, 'validate'], 10, 5);
        // add_filter('fluentform_response_render_' . $this->key, array($this, 'renderResponse'), 10, 3);
        // add_filter('fluentform_validate_input_item_' . $this->key, array($this, 'validateInput'), 10, 5);
    }

    function getComponent()
    {
        return [
            'index'          => 15, // The priority of your element
            'element'        => $this->key, // this is the unique identifier.
            'attributes'     => [
                'name'        => $this->key, // initial name of the input field
                'class'       => '', // Custom element class holder
                'value'       => '', // Default Value holder
                'type'        => 'text', // type of your element eg: text/number/email/tel
                'placeholder' => __('Select BCR Question', 'text-domain') // Default Placeholder
            ],
            'settings'       => [
                'container_class'     => '',
                'placeholder'         => '',
                'label'               => $this->title,
                'label_placement'     => '',
                'help_message'        => '',
                'validate_on_change'  => false, //maybe this would be true an where we can validate if question exists
                'target_input'        => '',
                'admin_field_label'   => '',
                'error_message'       => __('Not sure yet but this is beacuse of the validate_on_change setting', 'text-domain'),
                'validation_rules'    => [
                    'required'           => [
                        'value'   => false,
                        'message' => __('This field is required', 'fluentformpro'),
                    ],
                ],
                'conditional_logics'  => [] //this will be requred for the other option of adding a new question
            ],
            'editor_options' => [
                'title'      => $this->title . ' Field',
                'icon_class' => 'el-icon-phone-outline', // icon of the form in editor----------------sooemthing that is BCR or skis
                'template'   => 'inputText' // The template that will show in editor preview
            ],
        ];
    }

    public function getGeneralEditorElements()
    {
        return []; // return your general settings keys
        // If you check the code in parent class method you will see that it returns an array of keys. 
        //We can override this method in our class if we need new settings or default settings key will be returned.
    }

    //what if you need a new type of settings input to store user values, it is very simple
    //We can define our own ui setting inputs here for the general section of the editor
    public function generalEditorElement()
    {
        return [
            'target_input'       => [
                'template'  => 'select',
                'label'     => 'Select Exsiting Question',
                'help_text' => 'The input value will be matched with target input and show error if not matched',
                'options' => array(
                    array(
                        "label" => "4FRNT\r",
                        "value" => "4FRNT",
                        "id" => 0),
                    array( 
                        "label"=> "Atomic\r",
                        "value"=> "Atomic",
                        "id"=> 1)
                )
            ],
            'error_message'      => [
                'template' => 'inputText',
                'label'    => 'Error Message',
            ],
            'validate_on_change' => [
                'template' => 'inputCheckbox',
                'label'    => 'Validate on Change',
                'options'  => array(
                    array(
                        'value' => true,
                        'label' => 'Yes',
                    ),
                )
            ],
        ];
    }

    public function getAdvancedEditorElements()
    {
        //were we will set name attribute
        return []; // return your advanced settings keys
    }

    public function render($data, $form)
    {
        $data['attributes']['id'] = $this->makeElementId($data, $form);
        $this->pushScripts($data, $form);
        return (new FluentForm\App\Services\FormBuilder\Components\Text())->compile($data, $form);//------------------As this is a text input we can leverage already built text input to compile it and then we will apply our validation on it. 
    }//useful method available here like extractValueFromAttributes, extractDynamicValues, makeElementId, check the parent class for more

    private function pushScripts($data, $form)
        {
            add_action('wp_footer', function () use ($data, $form) {
                    if (!ArrayHelper::isTrue($data, 'settings.validate_on_change')) {
                        return;
                    }
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            function confirmValidate() {
                                
                                let confirmInput = jQuery('.<?php echo $form->instance_css_class; ?>').find("#<?php echo $data['attributes']['id']; ?>");
                                let targetName = '<?php echo ArrayHelper::get($data, 'settings.target_input') ?>';
                                let message = '<?php echo ArrayHelper::get($data, 'settings.error_message') ?>';
                                let targetInput = jQuery("input[name='" + targetName + "']")
                                let timeout = null;
                                confirmInput.on("keyup", function () {
                                    clearTimeout(timeout); 
                                    timeout = setTimeout(() => {
                                        validate()
                                    }, 1500);
                                });
                                function validate() {
                                    if (confirmInput.val() !== targetInput.val()) {
                                        let div = $('<div/>', {class: 'error text-danger'});
                                        confirmInput.closest('.ff-el-group').addClass('ff-el-is-error');
                                        confirmInput.closest('.ff-el-input--content').find('div.error').remove();
                                        confirmInput.closest('.ff-el-input--content').append(div.text(message));
                                    } else {
                                        confirmInput.closest('.ff-el-group').removeClass('ff-el-is-error');
                                        confirmInput.closest('.ff-el-input--content').find('div.error').remove();
                                    }
                                }
                            }
                            confirmValidate();
                        });
                    </script>
                    <?php
            }, 999);
        }

        public function validate($errorMessage, $field, $formData, $fields, $form)
        {
            $ConfirmInputName = ArrayHelper::get($field, 'raw.attributes.name');
            $targetInputName = ArrayHelper::get($field, 'raw.settings.target_input');
            $message = ArrayHelper::get($field, 'raw.settings.error_message');
            
            if (ArrayHelper::get($formData, $ConfirmInputName) != ArrayHelper::get($formData, $targetInputName)) {
                $errorMessage = [$message];
            }
            
            return $errorMessage;
        }
    // /**
    //  * @param $response string|array|number|null - Original input from form submission
    //  * @param $field array - the form field component array
    //  * @param $form_id - form id
    //  * @return string
    //  */
    // public function renderResponse($response, $field, $form_id)
    // {
    //     // $response is the original input from your user
    //     // you can now alter the $response and return
    //     return $response;
    // }

    // public function validateInput($errorMessage, $field, $formData, $fields, $form)
    // {
    //     $fieldName = $field['name'];
    //     if (empty($formData[$fieldName])) {
    //         return $errorMessage;
    //     }
    //     $value = $formData[$fieldName]; // This is the user input value
    //     /*
    //      * You can validate this value and return $errorMessage
    //      */
    //     return [$errorMessage];
    // }
}

/*
 * Finally initialize the class
 */
// add_action('plugins_loaded',function (){
//     new bcr_ff_field();
// });

?>