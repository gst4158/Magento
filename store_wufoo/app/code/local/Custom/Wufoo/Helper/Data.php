<?php
/**
* // EXAMPLE MARKUP TO ADD FORM TO PAGE
* // Generate wufoo form from json
* echo Mage::helper('custom_wufoo')->getWufooForm(
*     $options = array(
*         'wufooID'         => '123456789012345',
*         'formID'          => 'team-sales-quote-form',
*         'fillfields'  => array(
*             'Field219' => Mage::helper('core/url')->getCurrentUrl(),
*         )
*     )
* );
** TODO
* Add custom classes to auto format form based on wrapping classes
* Style complex table
* Figure out IE/Edge validation bug
*/
// Standard Magento helper class
class Custom_Wufoo_Helper_Data extends Mage_Core_Helper_Abstract {

    protected function _construct() {

    }

    public function getWufooForm($options) {
        // set up variables
        $wufooID = $options['wufooID'];
        $defaultfields = $options['fillfields'];

        // get base form information
        $json = '';
        $curl = curl_init("https://YOURWUFOONAME.wufoo.com/api/v3/forms/{$wufooID}.json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, '939R-CPNU-HT2J-2SXG:YOURWUFOONAME');
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'YOURWUFOONAME');

        $response = curl_exec($curl);
        $resultStatus = curl_getinfo($curl);

        if($resultStatus['http_code'] == 200) {
            $json = json_decode($response);
            $formInfo = $json;
            //echo '<pre>', print_r($json, true), '</pre>';
        } else {
            echo 'Call Failed '.print_r($resultStatus);
            return false;
        }

        // get form fields
        $json = '';
        $curl = curl_init("https://YOURWUFOONAME.wufoo.com/api/v3/forms/{$wufooID}/fields.json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, '939R-CPNU-HT2J-2SXG:YOURWUFOONAME');
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'YOURWUFOONAME');

        $response = curl_exec($curl);
        $resultStatus = curl_getinfo($curl);

        if($resultStatus['http_code'] == 200) {
            $json = json_decode($response);
            $json = ( $defaultfields ? $this->wufooDefaultFormValues($json->Fields, $defaultfields) : $json->Fields );
            return $this->formatWufooHTML($formInfo, $json, $options);
            //echo '<pre>', print_r($json, true), '</pre>';
        } else {
            echo 'Call Failed '.print_r($resultStatus);
            return false;
        }
    }


    public function formatWufooHTML($formArray, $array, $options) {
        // set up variables
        $formArray = $formArray->Forms[0];
        $wufooID = $options['wufooID'];
        $formID = $options['formID'];
        $formClasses = ( isset($options['formClassList']) ? $options['formClassList'] : 'wufoo-ajax-form wufoo-form form well' );

        // reset html
        $html = '';

        // create form wrapper
        $html .= "<form id=\"{$formID}\" class=\"{$formClasses}\" method=\"post\" action=\"\">";

        // non-generated fields
        $html .= "<input type=\"hidden\" name=\"wufooformID\" id=\"wufooformID\" value=\"{$wufooID}\">";

        $html .= ( $formArray->Name ? "<h3>{$formArray->Name}</h3>" : '' );
        $html .= ( $formArray->Description ? "<p>{$formArray->Description}</p>" : '' );

        // goreach loop
        // generates form inputs from json
        foreach($array as $item) {
            // echo '<pre>', print_r($array, true), '</pre>';

            // skip the following indexs:
            if ($item->ID === 'EntryId') continue;
            if ($item->ID === 'DateCreated') continue;
            if ($item->ID === 'CreatedBy') continue;
            if ($item->ID === 'LastUpdated') continue;
            if ($item->ID === 'UpdatedBy') continue;

            // dictate which input type we'll be using
            switch ($item->Type) {
                case "textarea" :
                    $html .= Mage::helper('custom_wufoo/inputs')->wufooInputTextarea( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                case "checkbox" :
                case "radio" :
                    $html .= Mage::helper('custom_wufoo/inputs')->wufooInputCheckbox( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                case "select" :
                    $html .= Mage::helper('custom_wufoo/inputs')->wufooInputSelect( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                case "address" :
                case "shortname" :
                    $html .= Mage::helper('custom_wufoo/inputs')->wufooInputAddress( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                case "likert" :
                    $html .= Mage::helper('custom_wufoo/inputs')->wufooInputLikert( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                case "rating" :
                    $html .= Mage::helper('custom_wufoo/inputs')->wufooInputRating( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                case "time" :
                $html .= Mage::helper('custom_wufoo/inputs')->wufooInputSelect( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item), null, Mage::helper('custom_wufoo/array')->wufooClock12HourList() );
                    break;

                case "date" :
                $html .= Mage::helper('custom_wufoo/inputs')->wufooInputDate( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                case "phone" :
                $html .= Mage::helper('custom_wufoo/inputs')->wufooInputPhone( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;

                default :
                    $html .= Mage::helper('custom_wufoo/inputs')->wufooInputDefault( $item, Mage::helper('custom_wufoo/inputs')->wufooRequired($item->IsRequired, $item) );
                    break;
            }; // END switch

        } // END foreach loop

        // success message button
        $html .= "<div class=\"wufoo-success\" style=\"display: none;\">";
        $html .=    "<p>Thank you for your submission</p>";
        $html .=    "<button type=\"button\" class=\"btn\"  data-dismiss=\"modal\">Close</button>";
        $html .= "</div>";

        // failure message button
        $html .= "<div class=\"wufoo-fail validation-advice\" style=\"display: none;\">";
        $html .=    "<p style=\"font-weight: bold;\">Opps!</p>";
        $html .=    "<p>Something went wrong with this submission.<br />Try again or come back at a later time.</p>";
        $html .= "</div>";

        // error message button
        $html .= "<div class=\"wufoo-error validation-advice\" style=\"display: none;\">";
        $html .=    "<p>Make sure fields are properly filled out.</p>";
        $html .= "</div>";

        // submit button
        $html .= "<div class=\"buttons-set\">";
        $html .=    "<button type=\"submit\" class=\"btn btn-primary\">";
        $html .=        "<span><span>Submit</span></span>";
        $html .=    "</button>";
        $html .= "</div>";

        // close form
        $html .= "</form>";

        // return new markup
        return $html;
    }

    /**
    * Allows user to enter default values for form inputs
    */
    public function wufooDefaultFormValues($array, $defaultValues) {
        // look for keys to set default values
        foreach($defaultValues as $key => $item) {
            // loop through each input to see if value exist
            foreach( $array as $formvalue ) {
                if ( $key == $formvalue->ID ) {
                    $formvalue->DefaultVal = $item;
                }
            }
        }

        return $array;
    }

}
