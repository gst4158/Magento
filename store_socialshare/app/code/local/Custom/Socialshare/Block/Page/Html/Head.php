<?php
/**
 * Html page block
 *
 * @category   Mage
 * @package    Mage_Page
 * @author     Gregory Thomason 6-6-2017
 *
**/
class Custom_Block_Page_Html_Head extends Mage_Page_Block_Html_Head {

    /*
    * Master Social Media OG Tag function:
    *   1) gets page type to pull product/category ID if available
    *   2) Uses ID to pull custom meta information from CMS
    *   3) Generates the social media facebook/twitter OG tags
    */
    public function createSocialMetaTags($pageTemplageName) {
        // kill function if no Id found for some reason
        if (!$pageTemplageName ) return false;

        // determine what kind of page we're on
        switch ($pageTemplageName) {
            case 'category':
                $product_id = Mage::registry('current_category')->getId();
                break;
            case 'product':
                $product_id = Mage::registry('current_product')->getId();
                break;
            default:
                $product_id = null;
                break;
        }

        // get product information
        $product_array = $this->getProductMeta($pageTemplageName, $product_id);

        // generate the HTML
        $socialHTML = $this->getSocialHTML($product_array);

        // return html
        return $socialHTML;
    }

    /**
    * Creates an array from the page type
    * If the page is a category/product; will attempt to load custom meta info from the CMS
    */
    public function getProductMeta($template_type, $product_id) {

        // grab product/category getters if needed
        $_product = null;
        if ( $product_id ) {
            $obj = Mage::getModel('catalog/'.$template_type);

            // kill function if no product found
            if ( !$obj ) return false;

            // grab product and create a custom array of the good stuff
            $_product = $obj->load($product_id);
        };

        // determine what kind of page we're on and get data from CMS if available
        $storeNameClean = str_replace(' ', '-', strtolower( Mage::app()->getWebsite()->getName() ));

        // set a default image to use as final backup
        $filePath = Mage::getBaseDir('media').DS.'social_image'.DS.$storeNameClean.DS.'1200_600.jpg';
        $defaultImage = ( file_exists( $filePath ) ? Mage::getBaseUrl('media').DS.'social_image'.DS.$storeNameClean.DS.'1200_600.jpg' : '' );

        switch (true) {
            case isset($_product) && !empty($_product) :
                $title          = ( $_product->getMetaTitle()       ? $_product->getMetaTitle()         : $this->getLayout()->getBlock('head')->getTitle() );
                $description    = ( $_product->getMetaDescription() ? $_product->getMetaDescription()   : $this->getLayout()->getBlock('head')->getDescription() );

                // if social image uploaded; than use that.
                $imageURL       = ( $_product->getSocialImage() ? Mage::getBaseUrl('media')."catalog/category/".$_product->getSocialImage() : null );
                // if no social image; but there is a category/product image use that
                $imageURL       = ( $imageURL == null && $_product->getImageUrl() ? $_product->getImageUrl() : $imageURL );
                // if no social or category image then default to store social
                $imageURL       = ( $imageURL ? $imageURL : $defaultImage );
                break;

            default:
                // if social image uploaded; than use that.
                $title          = $this->getLayout()->getBlock('head')->getTitle();
                $description    = $this->getLayout()->getBlock('head')->getDescription();
                $imageURL       = ( Mage::getBlockSingleton('cms/page')->getPage()->getSocialImage() ? Mage::getBaseUrl('media').Mage::getBlockSingleton('cms/page')->getPage()->getSocialImage() : $defaultImage );
                break;
        }

        // build array
        $array = array(
            //"product"           => $_product,
            "title"             => $title,
            "description"       => $description,
            "imageURL"          => $imageURL,
            "pageURL"           => Mage::helper('core/url')->getCurrentUrl(),
            "type"              => 'website'
        );
        // return array to be used elsewhere
        return $array;
    }

    /*
    * Generate the Social Media OG tag HTML
    */
    public function getSocialHTML($array) {
        $html = '';
        $html .= "
            <!-- facebook OG tags -->
            <meta property=\"og:url\" content=\"{$array['pageURL']}\" />
            <meta property=\"og:type\" content=\"{$array['type']}\" />
            <meta property=\"og:title\" content=\"{$array['title']}\" />
            <meta property=\"og:description\" content=\"{$array['description']}\" />
            <meta property=\"og:image\" content=\"{$array['imageURL']}\" />

            <!-- twitter cards -->
            <meta name=\"twitter:card\" content=\"summary\" />
            <meta name=\"twitter:title\" content=\"{$array['title']}\" />
            <meta name=\"twitter:description\" content=\"{$array['description']}\" />
            <meta name=\"twitter:image\" content=\"{$array['imageURL']}\" />
        ";
        return $html;
    }

}
