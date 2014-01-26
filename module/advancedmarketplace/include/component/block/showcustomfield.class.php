<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_ShowCustomField extends Phpfox_Component
{
	public function process()
	{
		$iListingId = phpfox::getLib('request')->get('req2');

		if(!isset($iListingId))
		{
			$iListingId = 0;
		}
		$sHtmlField = phpfox::getService('advancedmarketplace.custom.group')->getCustomFieldInfo($iListingId);
			
		$this->template()->assign(array('sHtml' => $sHtmlField));
		return 'block';
    	
	}
}

?>
