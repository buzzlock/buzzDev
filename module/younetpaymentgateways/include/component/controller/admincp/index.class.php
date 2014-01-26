<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');


class Younetpaymentgateways_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
            $this->template()->setTitle(Phpfox::getPhrase('younetpaymentgateways.payment_gateways'))	
                ->setBreadcrumb(Phpfox::getPhrase('younetpaymentgateways.payment_gateways'), $this->url()->makeUrl('admincp.younetpaymentgateways'))
                ->assign(array(
                                'aGateways' => Phpfox::getService('younetpaymentgateways.gateway')->getForAdmin()
                        )
                );

	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('younetpaymentgateways.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>