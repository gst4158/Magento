<?php

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

// needed to add attribute to cms_page
$conn = $installer->getConnection();
$table = $installer->getTable('cms_page');
$conn->addColumn($table, 'social_image', 'varchar(100)');


// add attribute to categories
$installer->addAttribute('catalog_category', 'social_image', array(
    'type'          => 'varchar',
    'label'         => 'Social Image',
    'input'         => 'image',
    'backend'       => 'catalog/category_attribute_backend_image',
    'required'      => false,
    'user_defined'  => false,
    'default'       => "",
    'sort_order'    => 99,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'         => 'General Information',
    "note"          => "Images should be 1200x600px large and no less then 2MB."
));

// add attribute to products
$installer->addAttribute('catalog_product', 'social_image', array(
    'type'          => 'varchar',
    'label'         => 'Social Image',
    'input'         => 'image',
    'backend'       => 'catalog/category_attribute_backend_image',
    'required'      => false,
    'user_defined'  => false,
    'default'       => "",
    'sort_order'    => 99,
    'visible_on_front'=> 1,
    'user_defined'    => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'         => 'Meta Information',
    "note"          => "Images should be 1200x600px large and no less then 2MB."
));

$installer->endSetup();
?>
