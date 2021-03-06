# Magento Geolocation
This module useses MaxMind's GeoIP2 geolocation database to look at the users $_SERVER['REMOTE_ADDR'] IP address and return information pertaining to it.

*Module assumes you're working under a custom store and not inside default*

### Out of the Box:
- Custom modules are not cached
- Returns the users closest store within a 100 mile radius
- Includes example using AW_iSlider Magento module
- Include Custom Statck Blocks inside your Magento phtml files with full geolocation support

### How it works:
1. The function ```getGeolocationBlocks()``` returns the closest store to the user. The ```home_slider``` is part of the CMS static block identifier while the store name makes up the rest of it. There is always a ```-``` that seperates the first half of the identifier and the part generated by the function.

	* For example if you wanted to have a banner for Austin, TX you might give make its identifier ```home_slider-austin```.

	* The second parameter of the ```getGeolocationBlocks()``` function dictates what is going to be returned. By default it returns the static block HTML; but since some modules like AW iSlider needs an ID the optional parameter allows you to create a custom function to return different data.
```
echo Mage::getBlockSingleton('geolocation/geolocation')->getGeolocationBlocks('home_slider');
$sliderId = Mage::getBlockSingleton('geolocation/geolocation')->getGeolocationBlocks('homepage-ads', 'aw_islider');
```

2. There are three layers of conditionals for each geolocation block:
	* Each time a block is requested; it checks if that block exist prior to loading it to the page.
	* You can write a checker using the ```selectiveRange()``` function which will look at the user details, limit it by country and states.
	* The second layer checks if the user has a store assigned to them. This overwrites the selectiveRange block.
	* The default layer prints out the basic banner since no geoblock or selective range was identified.

### Dependencies
- 1.9 Magento Enterprise edition
- ERP Plus Store Locator Module *returns a json array of store information - can be replaced by a google api or custom json*

---

#### Include CMS block directly into phtml file:
###### 1. root/app/code/local/Custom/Geolocation/etc/config.xml
```
<?xml version="1.0" encoding="UTF-8"?>
<config>
	<global>
		<blocks>
            <geolocation>
                <class>Custom_Geolocation_Block</class>
            </geolocation>
        </blocks>
		<helpers>
			<custom_geolocation>
				<class>Custom_Geolocation_Helper</class>
			</custom_geolocation>
		</helpers>
	</global>
	<frontend>
         <layout>
            <updates>
                <geolocation>
		    <!-- append geolocation xml to site local.xml -->
                    <file>geolocation.xml</file>
                </geolocation>
            </updates>
        </layout>
    </frontend>
</config>
```
###### 2. root/design/frontend/bootstrapped/default/layout/geolocation.xml
```
<?xml version="1.0" encoding="UTF-8"?>
<layout version="0.1.0">
    <default>
         <reference name="header">
            <block type="geolocation/geolocation" name="geolocation.geolocation"  as="geolocation_geolocation" template="geolocation/headerbanner.phtml" />
        </reference>
    </default>
</layout>
```

###### 3. root/design/frontend/bootstrapped/default/template/geolocation/headerbanner.phtml
```
// get header banner geolocation block
echo Mage::getBlockSingleton('geolocation/geolocation')->getGeolocationBlocks('header_banner');
```

###### 4. root/design/frontend/bootstrapped/customstore/template/page/html/header.phtml
```
<div class="header-banner">
    <?php echo $this->getChildHtml('geolocation_geolocation');  ?>
</div>
```

#### Include CMS block from XML:
###### 1. root/app/code/local/Custom/Geolocation/etc/confcache.xml
```
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <placeholders>
        <!-- home page slider desktop/tablet cache -->
        <geolocation_nocache_slider>
            <block>core/template</block>
	    <name>slider</name>
            <placeholder>slider</placeholder>
            <container>Custom_Geolocation_Model_Nocache</container>
            <cache_lifetime>1</cache_lifetime>
        </geolocation_nocache_slider>
	
        <!-- home page ads - AW_iSlider -->
        <geolocation_nocache_homeads>
            <block>awislider/block</block>
            <name>home-ads</name>
            <placeholder>home-ads</placeholder>
            <container>Custom_Geolocation_Model_Nocache</container>
            <cache_lifetime>1</cache_lifetime>
        </geolocation_nocache_homeads>
    </placeholders>
</config>
```

###### 2. root/design/frontend/bootstrapped/customstore/layout/local.xml
```
<cms_index_index>
    <reference name="root">
        <remove name="global_messages" />
        <block type="core/template" name="slider" as="slider"  template="owlcarousel/slider.phtml" />
        <block type="awislider/block" name="home-ads" as="home-ads" template="aw_islider/home.phtml" />
    </reference>
</cms_index_index>
```

###### 3. root/design/frontend/bootstrapped/default/template/owlcarousel/slider.phtml
```
// home page desktop/tablet banner
echo Mage::getBlockSingleton('geolocation/geolocation')->getGeolocationBlocks('home_slider');
```

###### 4. root/design/frontend/bootstrapped/default/template/aw_islider/home.phtml
###### **Pay attention to the second parameter or the function 'aw_islider'. This returns an AW iSlider ID rather than a CMS block**
```
// Select slider ID from geolocation
$sliderId = Mage::getBlockSingleton('geolocation/geolocation')->getGeolocationBlocks('homepage-ads', 'aw_islider');
```
