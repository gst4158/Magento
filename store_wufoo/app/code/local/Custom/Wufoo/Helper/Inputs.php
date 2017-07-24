<?php
// Standard Magento helper class
class Custom_Wufoo_Helper_Inputs extends Mage_Core_Helper_Abstract {

    /**
    * Requirement handling for input fields
    */
    public function wufooRequired($requried, $field) {
        // change markup if field is required
        $required = array();
        $required = array(
            'requiredStar'  => ( $requried == 1 ? '<em>*</em>' : '' ),
            'requiredLabel' => ( $requried == 1 ? "class=\"required\""  : '' ),
            'requiredInput' => ( $requried == 1 ? 'required-entry validate-'.$field->Type : '' )
        );
        return $required;
    }

    /**
    * Assist in class cleanup
    */
    public function wufooClassCleanup($word, $value, $replaceWith) {
        return preg_replace("/\S*$word\S*/i", "$replaceWith ",trim($value));
    }

    /**
    * Input Type: Default text
    * text, number, email,file, url, money,
    */
    public function wufooInputDefault($field, $requried, $childField = null) {

        // merge objects if needed
        $field = ( $childField ? (object) array_merge((array) $field, (array) $childField) : $field );
        
        // loop through each field type
        foreach($field as $key => $value) {                
            // generate variable variables
            ${$key} = isset($field->$key) ? $value : ' ';
        };

        // override some conditionals
        $fieldTitle  = ( isset($Label) ? $Label : $Title );
        $fieldType   = ( $Type == 'money' ? 'number' : $Type );
        
        // null some variables if this is a child input
        if ( $childField != null ) {
            $ClassNames = '';
        }
        if ($fieldTitle == 'Address Line 2') {
            $requried['requiredInput'] = '';
            $requried['requiredLabel'] = '';
            $requried['requiredStar'] = '';
        };
                
        // validation rules
        // adds custom messages to the following input types on form validation
        switch ($fieldType) {
            case "email" :
            case "url" :
            case "number" :
            case "money" :
            $requried['requiredInput'] = ( $requried['requiredInput'] ? "wufoo-required validate-{$field->Type}" : "validate-{$field->Type}" );
                break;
        
            default :
                break;
        }; // END switch
        
        // generate input html
        $html = '';
        $html .= "<div class=\"input-box wufoo-input {$ClassNames}\">";
        $html .=    "<label for=\"{$ID}\" {$requried['requiredLabel']}>{$fieldTitle}: {$requried['requiredStar']}</label>";
        $html .=    "<input type=\"{$fieldType}\" name=\"{$ID}\" id=\"{$ID}\" value=\"{$DefaultVal}\" class=\"{$requried['requiredInput']}\">";
        $html .=    "<span class=\"instructions\">{$Instructions}</span>";
        $html .= "</div>";
        
        // return input html
        return $html;
    }

    /**
    * Input Type: Date input field
    */
    public function wufooInputDate($field, $requried) {
        // function conditionals
        // this function is used in multiple sections
        $fieldTitle = ( $field->Title ? $field->Title : $field->Label );
        $fieldType = ( $field->Type ? $field->Type : 'text' );
        $fieldType = ( $field->Type == 'date' ? 'text' : $fieldType );

        // validation rules
        $requried['requiredInput'] = ( $requried['requiredInput'] ? "wufoo-required validate-{$field->Type}" : "validate-{$field->Type}" );

        // generate input html
        $html = '';
        $html .= "<div class=\"input-box wufoo-date {$field->ClassNames}\">";
        $html .=    "<label for=\"{$field->ID}-front\" {$requried['requiredLabel']}>{$fieldTitle}: {$requried['requiredStar']}</label>";
        $html .=    "<input  type=\"text\" name=\"{$field->ID}-front\" id=\"{$field->ID}-front\" value=\"{$field->DefaultVal}\" class=\"input-date {$requried['requiredInput']}\" data-serialize=\"false\" >";
        $html .=    "<input type=\"hidden\" name=\"{$field->ID}\" id=\"{$field->ID}\" value=\"\" class=\"input-date-hidden\" >";
        $html .=    "<span class=\"instructions\">{$field->Instructions}</span>";

                    // add magento date picking widget if needed
                    if ($field->Type == 'date') {
                        $html .= "<script>";
                        $html .= "jQuery( document ).ready(function() {";
                        $html .=    "Calendar.setup({";
                        $html .=        "inputField : '{$field->ID}-front',";
                        $html .=        "ifFormat : '%m/%e/%Y',";
                        $html .=        "button : 'date_from_trig',";
                        $html .=        "align : 'Bl',";
                        $html .=        "singleClick : true";
                        $html .=    "});";
                        $html .= "});";
                        $html .= "</script>";
                    }
        $html .= "</div>";

        // return input html
        return $html;
    }

    /**
    * Input Type: Date input field
    */
    public function wufooInputPhone($field, $requried) {
        // function conditionals
        // this function is used in multiple sections
        $fieldTitle = ( $field->Title ? $field->Title : $field->Label );
        $fieldType = 'tel';

        // validation rules
        $requried['requiredInput'] = ( $requried['requiredInput'] ? "wufoo-required validate-{$field->Type}" : "validate-{$field->Type}" );
        
        // generate input html
        $html = '';
        $html .= "<div class=\"input-box wufoo-phone {$field->ClassNames}\">";
        $html .=    "<label for=\"{$field->ID}-front\" {$requried['requiredLabel']}>{$fieldTitle}: {$requried['requiredStar']}</label>";
        $html .=    "<input type=\"text\" name=\"{$field->ID}-front\" id=\"{$field->ID}-front\" value=\"{$field->DefaultVal}\" class=\"input-phone {$requried['requiredInput']}\" data-serialize=\"false\" >";
        $html .=    "<input type=\"hidden\" name=\"{$field->ID}\" id=\"{$field->ID}\" value=\"\" class=\"input-phone-hidden\" >";
        $html .=    "<span class=\"instructions\">{$field->Instructions}</span>";
        $html .= "</div>";

        // return input html
        return $html;
    }

    /**
    * Input Type: Dropdown Selects
    */
    public function wufooInputSelect($field, $requried, $childField = null, $list = '') {

        // merge objects if needed
        $field = ( $childField ? (object) array_merge((array) $childField, (array) $field) : $field );

        // loop through each field type
        foreach($field as $key => $value) {
            // generate variable variables
            ${$key} = isset($field->$key) ? $value : ' ';

        };

        // override some conditionals
        $fieldTitle  = ( isset($Label) ? $Label : $Title );
        $defaultVal = ( isset($DefaultVal) ? $DefaultVal : $field->SubFields->DefaultVal );

        // null some variables if this is a child input
        if ( $childField != null ) {
            $ClassNames = '';
        };
        
        // function conditionals
        // this function is used in multiple sections
        $choices = ( $list ? $list : $field->Choices);

        // force validation if nothing is selected
        $requried['requiredInput'] = ( $requried['requiredInput'] ? 'validate-select' : $requried['requiredInput'] );

        // generate textarea html
        $html = '';
        $html .= "<div class=\"input-box wufoo-select {$ClassNames}\">";
        $html .=    "<label for=\"{$ID}\" {$requried['requiredLabel']}>{$fieldTitle} {$requried['requiredStar']}</label>";
        $html .=    "<select id=\"{$ID}\" name=\"{$ID}\" class=\"field select {$requried['requiredInput']}\" >";
        $html .=    "<option selected></option>";
        // print option list or loop through options

        foreach($choices as $key => $option) {            
            $selected = ( $defaultVal == $option ? "selected='selected'" : '' );
            $value  = ( is_object($option) == true ? $option->Label : $option );
            $keyVal = ( is_object($option) == true ? $option->Label : $key );
            $html .= "<option class=\"input-{$Type}\" value=\"{$keyVal}\" $selected>{$value}</option>";
        };
        
        $html .=    "</select>";
        $html .=    "<span class=\"instructions\">{$Instructions}</span>";
        $html .= "</div>";

        // return input html
        return $html;
    }

    /**
    * Input Type: Address input wufoo adv field
    * handles address and shortname - first/last names
    */
    public function wufooInputAddress($field, $requried) {
        // generate textarea html
        $html = '';

        foreach($field->SubFields as $key => $input) {
            // checks for specific class names so we can do stuff
            $classes = $field->ClassNames;
            if ( strpos($field->ClassNames, 'force-street' ) !== false && $key <= 1 ) {
                $classes = $this->wufooClassCleanup('grid-', $classes, '');
            };

            // create html elements
            $html .= "<div class=\"input-box wufoo-{$field->Type} {$classes}\">";
            $html .=    ( $input->Label == 'Country' ?
                            // load country dropdown list
                            $this->wufooInputSelect($input, $requried, $field, Mage::helper('custom_wufoo/array')->wufooCountryList())
                            :
                            // load default text inputs
                            $this->wufooInputDefault($field, $requried, $input)
                        );
            $html .=    "<span class=\"instructions\">{$field->Instructions}</span>";
            $html .= "</div>";

        };
        
        // return input html
        return $html;
    }

    /**
    * Input Type: Textarea
    */
    public function wufooInputTextarea($field, $requried) {
        // generate textarea html
        $html = '';
        $html .= "<div class=\"input-box wufoo-textarea {$field->ClassNames}\">";
        $html .=    "<label for=\"{$field->ID}\" {$requried['requiredLabel']}>{$field->Title}: {$requried['requiredStar']}</label>";
        $html .=    "<textarea type=\"{$field->Type}\" name=\"{$field->ID}\" id=\"{$field->ID}\" class=\"input-{$field->Type} {$requried['requiredInput']}\" spellcheck=\"true\" ></textarea>";
        $html .=    "<span class=\"instructions\">{$field->Instructions}</span>";
        $html .= "</div>";

        // return input html
        return $html;
    }

    /**
    * Input Type: Checkbox
    */
    public function wufooInputCheckbox($field, $requried) {
        // get wufoo array type
        $array = ( $field->SubFields ? $field->SubFields : $field->Choices );

        // validation rules
        $requried['requiredInput'] = ( $requried['requiredInput'] ? 'validate-checked' : '' );

        // generate textarea html
        $html = '';
        $html .= "<div class=\"input-box wufoo-{$field->Type} {$field->ClassNames}\">";
        $html .=    "<label for=\"{$field->ID}\" {$requried['requiredLabel']}>{$field->Title}: {$requried['requiredStar']}</label>";
        $html .=    "<input type=\"hidden\" name=\"{$field->ID}-validate\" id=\"{$field->ID}-validate\" class=\"input-date-hidden {$requried['requiredInput']}\" data-serialize=\"false\" >";

        $html .= "<div>";
        foreach($array as $key => $subfield) {
            // pre-selected
            $typeID = ( $subfield->ID ? $subfield->ID : $field->ID );
            $checked = ( $field->Type == 'checkbox' && $subfield->DefaultVal == 1 ? "checked=\"checked\"" : '' );
            $checked = ( $field->Type == 'radio' && $field->DefaultVal == $subfield->Label ? "checked=\"checked\"" : $checked );

            // radio/checkbox input
            $html .=    "<label for=\"{$typeID}_{$key}\">";
            $html .=        "<input id=\"{$typeID}_{$key}\" name=\"{$typeID}\" type=\"{$field->Type}\" {$checked} class=\"input-{$field->Type}\" value=\"{$subfield->Label}\" />";
            $html .=        "{$subfield->Label}";
            $html .=    "</label>";
        };
        $html .= "</div>";

        $html .=    "<span class=\"instructions\">{$field->Instructions}</span>";
        $html .= "</div>";

        // return input html
        return $html;
    }

    /**
    * Input Type: Likert
    * Autoforming table with rating system 1 to how many column options
    */
    public function wufooInputLikert($field, $requried) {
        // validation rules
        $requried['requiredInput'] = ( $requried['requiredInput'] ? 'validate-likert' : '' );

        // html markup
        $html = '';
        $html .= "<div class=\"input-box wufoo-likert {$field->ClassNames}\">";
        $html .=    "<label for=\"{$field->ID}\" {$requried['requiredLabel']}>{$field->Title} {$requried['requiredStar']}</label>";
        $html .=    "<table>";
                    // create table heads
                    $html .=    "<thead>";
                    $html .=    "<tr>";
                    $html .=    "<td></td>";
                                $thead = '';
                                foreach( $field->Choices as $choice ) {
                                    $thead .= "<td>$choice->Label</td>";
                                };
                    $html .=    $thead;
                    $html .=    "</tr>";
                    $html .=    "</thead>";

                    // create table rows
                    $html .=    "<tbody>";
                    foreach( $field->SubFields as $item ) {
                        $trow = '';
                        $trow .= "<tr>";
                        $trow .=    "<td class=\"{$requried['requiredInput']}\">";
                        $trow .=        $item->Label;
                        $trow .=        "<input type=\"hidden\" name=\"{$item->ID}-validate\" id=\"{$item->ID}-validate\" class=\"input-date-hidden {$requried['requiredInput']}\" data-serialize=\"false\" >";
                        $trow .=    "</td>";

                        // row radio options
                        foreach( $field->Choices as $key =>$radio ) {
                            $trow .=    "<td>";
                            $trow .=        "<label for=\"{$item->ID}_{$radio->Score}\">";
                            $trow .=            "<input id=\"{$item->ID}_{$radio->Score}\" name=\"{$item->ID}\" type=\"radio\" class=\"input-radio\" value=\"{$radio->Label}\" />";
                            $trow .=            $key+1;
                            $trow .=        "</label>";
                            $trow .=    "</td>";
                        };

                        $trow .= "</tr>";
                        $html .= $trow;
                    }
                    $html .=    "</tbody>";

        $html .=    "</table>";
        $html .=    "<span class=\"instructions\">{$field->Instructions}</span>";
        $html .= "</div>";

        // return input html
        return $html;
    }

    /**
    * Input Type: Rating
    * Generates 5 radio options that can be converted to stars with CSS
    */
    public function wufooInputRating($field, $requried) {

        // validation rules
        $requried['requiredInput'] = ( $requried['requiredInput'] ? 'validate-checked' : '' );

        // generate textarea html
        $html = '';
        $html .= "<div class=\"input-box wufoo-ratings {$field->ClassNames}\">";
        $html .=    "<label for=\"{$field->ID}\" {$requried['requiredLabel']}>{$field->Title}: {$requried['requiredStar']}</label>";
        $html .=    "<input type=\"hidden\" name=\"{$field->ID}-validate\" id=\"{$field->ID}-validate\" class=\"input-date-hidden {$requried['requiredInput']}\" data-serialize=\"false\" >";

        $i = 1;
        $html .= "<div>";
        while( $i <= 5 ) {
            $html .=        "<label for=\"{$field->ID}_{$i}\">";
            $html .=            "<input id=\"{$field->ID}_{$i}\" name=\"{$field->ID}\" type=\"radio\" class=\"input-radio\" value=\"{$i}\" />";
            $html .=            "<span>&#9733;</span>";
            $html .=            "<span>&#9734;</span>";
            $html .=        "</label>";
            $i++;
        }
        $html .= "</div>";

        $html .=    "<span class=\"instructions\">{$field->Instructions}</span>";
        $html .= "</div>";

        // return input html
        return $html;
    }
}
