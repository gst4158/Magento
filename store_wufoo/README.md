# Magento Wufoo API Form Generate & Ajax Submit
This module hooks into the Wufoo API to dynamically generate forms you create through the 3rd party service and allows for user submission.

*Module assumes you're working under a custom store and not inside default*

### What is Wufoo?:
- Read about it here: https://www.wufoo.com/

### Where can I find my form API information?:
- In your Wufoo Form Manager hover your mouse over ```More``` and select ```API information```.
- You will see your account Wufoo API key here.
- Your form HASH ID, near the bottom of the page, is used to pull form fields into this module.
- Each field has an API ID assigned to it.

### My Form is not Submitting:
- Make sure you are only submitting data your form accepts. If any extra ID's are submitted; the API will fail.
- Make sure Magento's validation is turned on. This module validation.js with some custom rules built to accommodate Wufoo.

### How it works:
- Attach your API key and Wufoo account name into the IndexController.php
```
$accountID  = 'YOURWUFOONAMEHERE';
$accountAPI = 'enter-your-api-number-key:YOURWUFOONAMEHERE';
$formID     = $this->getRequest()->getParam('wufooformID');
$data       = $this->getRequest()->getParam('data');
```

- On the ```catalog/product/view.phtml``` file add the following markup:
```
<?php
  // Generate wufoo form from json
  echo Mage::helper('custom_wufoo')->getWufooForm(
      $options = array(
          'wufooID'         => '123456789012345',
          'formID'          => 'customformid',
          'fillfields'  => array(
              'Field219' => Mage::helper('core/url')->getCurrentUrl(),
          )
      )
  );
?>
```

- If you wish to pre-fill fields get the field ID and add it to the ```fillfields``` array():
```
'fillfields'  => array(
    'Field219' => Mage::helper('core/url')->getCurrentUrl(),
)
```
