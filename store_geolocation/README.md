# Magento Geolocation
This module useses MaxMind's GeoIP2 geolocation database to look at the users $_SERVER['REMOTE_ADDR'] IP address and return information pertaining to it.

*Module assumes you're working under a custom store and not inside default*

### Out of the Box:
- Custom modules are not cached
- Returns the users closest store within a 100 mile radius
- Includes example using AW_iSlider Magento module
- Include Custom Statck Blocks inside your Magento phtml files with full geolocation support

### Dependencies
- 1.9 Magento Enterprise edition
- ERP Plus Store Locator Module *returns a json array of store information - can be replaced by a google api or custom json*

---

### Include CMS block directly into phtml file:
- root/app/code/local/Custom/Geolocation/etc/config.xml
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
- root/design/frontend/bootstrapped/default/layout/geolocation.xml
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

- root/design/frontend/bootstrapped/customstore/template/geolocation/headerbanner.phtml
```
<?php
// get header banner geolocation block
echo Mage::getBlockSingleton('geolocation/geolocation')->getGeolocationBlocks('header_banner');
?>
```
