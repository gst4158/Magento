<?php
class Custom_Geolocation_Model_Nocache extends Enterprise_PageCache_Model_Container_Abstract
{

	protected function _getIdentifier()
	{
	return $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '');
	}

	protected function _getCacheId()
	{
	return 'geolocation_nocache' . md5($this->_placeholder->getAttribute('cache_id') . $this->_getIdentifier());
	}

	protected function _renderBlock()
	{
		$blockClass = $this->_placeholder->getAttribute('block');
		$template = $this->_placeholder->getAttribute('template');

		$block = new $blockClass;
		$block->setTemplate($template);
		return $block->toHtml();
	}
}
