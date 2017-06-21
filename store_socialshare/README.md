# Magento Social Share
This module includes OG tags for facebook and twitter into the head.phtml file for every page. Categories, Products, and CMS Pages have an additional ```social_share``` media uploader to allow customized share images.

*Module assumes you're working under a custom store and not inside default*

### Where to find new media uploader:
- Products: Production Information > Meta Information > last item in module
  * ###### Note: Image will not show in admin side; but will load correctly on frontend
- Categories: Select your Category from store list > last item in module > uncheck 'Use Default View' if checked
- CMS Pages: Select your CMS Page > Page Information > last item in module under ```Social Media```

### How to update home page/default image
- The home page uses a default image if one is present. If not, image string will be passed to social OG tags.

### Where are files saved:
- Default/Home page: ```root/media/social_image/{$storeNameClean}/1200_600.jpg```
- Category/Products: ```root/media/catalog/category/```
- CMS Pages: ```/media/social_image/```

### How it works:
- In the head.phtml file a new function is used on page load to identify the magento module being used. This will return a string similar to ```category``` or ```product```
```
echo $socialMetaTags =  $this->createSocialMetaTags( Mage::app()->getFrontController()->getRequest()->getControllerName() );
```

- ```createSocialMetaTags()``` creates an array containing title, description, image url, page url, and page type. Based on controller name the module will attempt to grab product/category information or default to a standard page. 

- During this process; there will be a conditionalize check to see if a social image has been uploaded. Next there will be a check if there is a product/category image - note that social image uploads take precedent over product/category images. If no social or product/category image found; than the page will use a default image. 
```
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
```

- The last step creates the actual HTML printed to the page
```
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
```
