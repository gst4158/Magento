<?php
class Custom_Wufoo_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction($formID = '', $data = '') {

        $accountID  = 'YOURWUFOONAMEHERE';
        $accountAPI = 'enter-your-api-number-key:YOURWUFOONAMEHERE';
        $formID     = $this->getRequest()->getParam('wufooformID');
        $data       = $this->getRequest()->getParam('data');

        //echo '<pre>', print_r($data, true), '</pre>';
        //echo $formID.'<br />';
        //echo "https://{$accountID}.wufoo.com/api/v3/forms/{$formID}/entries.json<br />";

        // fake data
        //$accountID  = 'fishbowl';
        //$accountAPI = 'AOI6-LFKL-VM1Q-IEX9:footastic';
        //$formID     = 's1afea8b1vk0jf7';

        $curl = curl_init("https://{$accountID}.wufoo.com/api/v3/forms/{$formID}/entries.json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, $accountAPI);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $accountID);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);
        $resultStatus = curl_getinfo($curl);
        $jsonResult = json_encode($resultStatus);

        // if($resultStatus['http_code'] == 201) {
        //     $json = json_decode($response);
        //     echo '<pre>', print_r($json, true), '</pre>';
        // } else {
        //     echo 'Call Failed '.print_r($resultStatus);
        // }

        if($resultStatus['http_code'] == 201) {
            echo $jsonResult;
        } else {
            echo $jsonResult;
        }

    }

}
