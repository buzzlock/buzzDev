<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Admincp_Migration extends Phpfox_Component
{
	public function process()
	{
		$sRequest = $this->request()->get('isclone');
		if($sRequest == 1)
		{
			phpfox::getService('advancedmarketplace.process')->migrateMarketplaceData();
		}
		$sUrl = $this->url()->makeUrl('admincp.advancedmarketplace.migration.isclone_1');
		$this->template()->assign(array(
			'sUrl'=>$sUrl
		));
		$this->template()->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.migration'), $this->url()->makeUrl('admincp.advancedmarketplace.migration'));
		
	}
}
?>