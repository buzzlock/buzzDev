<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Purchase extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bInvoice = ($this->request()->get('invoice') ? true : false);		
		$iId = $this->request()->get('id');
		if ($bInvoice)
		{
			if (($aInvoice = Phpfox::getService('advancedmarketplace')->getInvoice($this->request()->get('invoice'))))
			{
				if ($aInvoice['user_id'] != Phpfox::getUserId())
				{
					return Phpfox_Error::display(Phpfox::getPhrase('advancedmarketplace.unable_to_purchase_this_item'));
				}
				
				$iId = $aInvoice['listing_id'];
				$aUserGateways = Phpfox::getService('api.gateway')->getUserGateways($aInvoice['advancedmarketplace_user_id']);
				$aPurchaseDetails = array(
					'item_number' => 'advancedmarketplace|' . $aInvoice['invoice_id'],
					'currency_code' => $aInvoice['currency_id'],
					'amount' => $aInvoice['price'],
					'item_name' => $aInvoice['title'],
					'return' => $this->url()->makeUrl('advancedmarketplace.invoice', array('payment' => 'done')),
					'recurring' => '',
					'recurring_cost' => '',
					'alternative_cost' => '',
					'alternative_recurring_cost' => ''						
				);				
				
				if (is_array($aUserGateways) && count($aUserGateways))
				{
					foreach ($aUserGateways as $sGateway => $aData)
					{						
						if (is_array($aData['gateway']))
						{
							foreach ($aData['gateway'] as $sKey => $mValue)
							{
								$aPurchaseDetails['setting'][$sKey] = $mValue;
							}
						}
						else 
						{
							$aPurchaseDetails['fail_' . $sGateway] = true;
						}
					}
				}
				
				$this->setParam('gateway_data', $aPurchaseDetails);								
			}
			else 
			{
				
			}
		}		
		
		if (!($aListing = Phpfox::getService('advancedmarketplace')->getForEdit($iId, true)))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('advancedmarketplace.unable_to_find_the_listing_you_are_looking_for'));
		}
		
		if ($this->request()->get('process'))
		{
			if (($iInvoice = Phpfox::getService('advancedmarketplace.process')->addInvoice($aListing['listing_id'], $aListing['currency_id'], $aListing['price'])))
			{
				$this->url()->send('advancedmarketplace.purchase', array('invoice' => $iInvoice));
			}
			else 
			{
				
			}
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('advancedmarketplace.review_and_confirm_purchase'))
			->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.advancedmarketplace'), $this->url()->makeUrl('advancedmarketplace'))
			->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.review_and_confirm_purchase'), null, true)
			->assign(array(
					'aListing' => $aListing,
					'bInvoice' => $bInvoice
				)			
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_purchase_clean')) ? eval($sPlugin) : false);
	}
}

?>