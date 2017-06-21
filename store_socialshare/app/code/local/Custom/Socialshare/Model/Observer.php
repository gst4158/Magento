<?php
class Custom_Socialshare_Model_Observer {

    /**
    * Generates an image uploader field into the cms_page 'Page Information' tab
    */
    public function prepareForm(Varien_Event_Observer $observer) {
        $form = $observer->getEvent()->getForm();

        $fieldset = $form->addFieldset(
            'custom_socialshare_identifier_fieldset',
            array('legend'=>Mage::helper('cms')->__('Social Media'),'class'=>'fieldset-wide')
        );

        //add new field
        $fieldset->addField('social_image', 'image', array(
            'name'      => 'social_image',
            'label'     => Mage::helper('cms')->__('Social Image'),
            'title'     => Mage::helper('cms')->__('Social Image'),
            'disabled'  => false,
            "note"      => "Images should be 1200x600px large and no less then 2MB.",
        ));
    }

    /**
    * Processes uploaded images and saves them to a folder
    */
    public function savePage(Varien_Event_Observer $observer) {
        $model = $observer->getEvent()->getPage();
        $request = $observer->getEvent()->getRequest();

        if (isset($_FILES['social_image']['name']) && $_FILES['social_image']['name'] != '') {
            $uploader = new Varien_File_Uploader('social_image');

            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);

            // Set media as the upload dir
            $media_path  = Mage::getBaseDir('media') . DS . 'social_image'. DS;

            // Set thumbnail name
            $file_name = '';

            // Upload the image
            $uploader->save($media_path, $file_name . $_FILES['social_image']['name']);

            $data['social_image'] = 'social_image' . DS . $file_name . $_FILES['social_image']['name'];

            // Set thumbnail name
            $data['social_image'] = $data['social_image'];
            $model->setsocial_image($data['social_image']);
        } else {
            $data = $request->getPost();
            if($data['social_image']['delete'] == 1) {
                $data['social_image'] = '';
                $model->setsocial_image($data['social_image']);
            } else {
                unset($data['social_image']);
                $model->setsocial_image(implode($request->getPost('social_image')));
            }
        }
    }

}
