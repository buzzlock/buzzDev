<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Donation
 * @version 		$Id: sample.class.php 1 2012-02-15 10:33:17Z YOUNETCO $
 */
class Donation_Component_Controller_Admincp_Managecurrencies extends Phpfox_Component {

    /**
     * Class process method which is used to execute this component.
     */
    public function process()
    {
        $oPaypal = Phpfox::getService('younetpaymentgateways')->load('paypal');
        $aCurrencies = $oPaypal->getSupportedCurrencies();
        $aVals = $this->request()->getArray('aVals');
        if ($aVals)
        {
            if (Phpfox::getService('donation.process')->updateCurrencies('paypal'))
            {
                $this->url()->send('admincp.donation.managecurrencies', null, Phpfox::getPhrase('donation.currencies_are_updated_successfully'));
            }
        }
        else
        {
            $aCurrentCurrencies = Phpfox::getService('donation')->getCurrentCurrencies('paypal');
        }
        $this->template()
                ->assign(array(
                    'aCurrencies' => $aCurrencies,
                    'aCurrentCurrencies' => $aCurrentCurrencies
                        )
                )
                ->setTitle(Phpfox::getPhrase('donation.manage_currencies'))
                ->setBreadcrumb(Phpfox::getPhrase('donation.manage_currencies'), $this->url()->makeUrl('admincp.donation.managecurrencies'))
                ->setHeader(array('general.css' => 'module_donation'));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('donation.component_controller_index_clean')) ? eval($sPlugin) : false);
    }

}

?>
