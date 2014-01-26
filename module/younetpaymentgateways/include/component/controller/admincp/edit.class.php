<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: add.class.php 979 2009-09-14 14:05:38Z Raymond_Benc $
 */
class Younetpaymentgateways_Component_Controller_Admincp_Edit extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$this->_setMenuName('admincp.younetpaymentgateways');
		
		if (!($aGateway = Phpfox::getService('younetpaymentgateways.gateway')->getForEdit($this->request()->get('id'))))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('api.unable_to_find_the_payment_gateway'));
		}
		
		if (($aVals = $this->request()->getArray('val')))
		{
			if (Phpfox::getService('younetpaymentgateways.gateway.process')->update($aGateway['gateway_id'], $aVals))
			{
				$this->url()->send('admincp.younetpaymentgateways.edit', array('id' => $aGateway['gateway_id']), Phpfox::getPhrase('younetpaymentgateways.gateway_successfully_updated'));
			}
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('younetpaymentgateways.payment_gateways'))	
			->setBreadcrumb(Phpfox::getPhrase('younetpaymentgateways.payment_gateways'), $this->url()->makeUrl('admincp.younetpaymentgateways'))
			->setBreadcrumb(Phpfox::getPhrase('younetpaymentgateways.editing') . ': ' . $aGateway['title'], null, true)
			->assign(array(
					'aForms' => $aGateway
				)
			);	
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('api.component_controller_admincp_gateway_add_clean')) ? eval($sPlugin) : false);
	}
}

?>