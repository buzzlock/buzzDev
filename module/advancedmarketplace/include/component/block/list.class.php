<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_List extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iPage = $this->request()->getInt('page');
		$iType = $this->request()->getInt('type', 1);
		$iPageSize = 6;
		
		if (PHPFOX_IS_AJAX)
		{
			$aListing = Phpfox::getService('advancedmarketplace')->getListing($this->request()->get('id'), true);
			$this->template()->assign('aListing', $aListing);
		}
		else 
		{
			$aListing = $this->getParam('aListing');			
		}
		
		list($iCnt, $aInvites) = Phpfox::getService('advancedmarketplace')->getInvites($aListing['listing_id'], $iType, $iPage, $iPageSize);
		
		Phpfox::getLib('pager')->set(array('ajax' => 'advancedmarketplace.listInvites', 'page' => $iPage, 'size' => $iPageSize, 'count' => $iCnt, 'aParams' => array('id' => $aListing['listing_id'])));
		
		$this->template()->assign(array(
				'aInvites' => $aInvites,
				'iType' => $iType	
			)
		);		
		
		if (!PHPFOX_IS_AJAX)
		{		
			$this->template()->assign(array(
					'sHeader' => Phpfox::getPhrase('advancedmarketplace.invites'),
					'sBoxJsId' => 'advancedmarketplace_members'
				)
			);			
			
			$this->template()->assign(array(
					'aMenu' => array(
						Phpfox::getPhrase('advancedmarketplace.visited') => '#advancedmarketplace.listInvites?type=1&amp;id=' . $aListing['listing_id'],
						Phpfox::getPhrase('advancedmarketplace.not_responded') => '#advancedmarketplace.listInvites?type=0&amp;id=' . $aListing['listing_id']
					)
				)
			);			
			
			return 'block';
		}			
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_list_clean')) ? eval($sPlugin) : false);
	}
}

?>