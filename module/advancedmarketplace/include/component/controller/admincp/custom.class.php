<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Admincp_Custom extends Phpfox_Component
{
	public function process()
	{
		$iCatId = $this->request()->getInt('id');
		
		$bOrderUpdated = false;
		if (($iDeleteId = $this->request()->getInt('delete')) && Phpfox::getService('advancedmarketplace.custom.process')->deleteGroup($iDeleteId))
		{
			$this->url()->send('admincp.custom.id_'.$iCatId, null, Phpfox::getPhrase('custom.custom_group_successfully_deleted'));
		}

		if (($aFieldOrders = $this->request()->getArray('field')) && Phpfox::getService('advancedmarketplace.custom.process')->updateFieldOrder($aFieldOrders))
		{
			$bOrderUpdated = true;
		}

		if (($aGroupOrders = $this->request()->getArray('group')) && Phpfox::getService('advancedmarketplace.custom.process')->updateGroupOrder($aGroupOrders))
		{
			$bOrderUpdated = true;
		}

		if ($bOrderUpdated === true)
		{
			$this->url()->send('admincp.advancedmarketplace.custom.id_'.$iCatId, null, Phpfox::getPhrase('custom.custom_fields_successfully_updated'));
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('custom.manage_custom_fields'))
		->setBreadcrumb(Phpfox::getPhrase('custom.manage_custom_fields'), $this->url()->makeUrl('admincp.advancedmarketplace.custom'))
		->setPhrase(array(
					'custom.are_you_sure_you_want_to_delete_this_custom_option'
					)
					)
					->setHeader(array(
					'custom.js' => 'module_advancedmarketplace',
					'<script type="text/javascript">$Core.custom.url(\'' . $this->url()->makeUrl('admincp.advancedmarketplace') . '\');</script>',
					'jquery/ui.js' => 'static_script',
					'<script type="text/javascript">$(function(){$Core.custom.addSort();});</script>'
					)
					)
					->assign(array(
					'aGroups' => phpfox::getService('advancedmarketplace.custom.group')->getForListing($iCatId),
					'iCatId' => $iCatId
					)
					);
	}


}
?>