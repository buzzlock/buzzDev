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
class Donation_Component_Block_Donate extends Phpfox_Component {

    /**
     * Class process method which is used to execute this component.
     */
    public function process()
    {
        Phpfox::isUser();
        $oDonation = Phpfox::getService('donation');
        $aConfig = $oDonation->getDonationOfPage(-1);
        $bNeedToBeConfig = false;
        if ($aConfig)
        {
            // have config and it is active
            if ($aConfig['is_active'] == 0)
            {
                if (Phpfox::isAdmin())
                {
                    $bNeedToBeConfig = true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            if (Phpfox::isAdmin())
            {
                $bNeedToBeConfig = true;
            }
            else
            {
                return false;
            }
        }
        if ($bNeedToBeConfig === false && !$oDonation->checkPermissions('can_donate'))
        {
            //if not admin config and this user group cannot donate
            return false;
        }
        $sUrl = urlencode($this->url()->getFullUrl());
        $sImg = $oDonation->getDonationButtonImagePath();
        $this->template()
                ->assign(array(
                    'iPageId' => -1,
                    'sUrl' => $sUrl,
                    'sImg' => $sImg,
                    'bNeedToBeConfig' => $bNeedToBeConfig
                ))
                ->setHeader(array(
                    'donation.js' => 'module_donation'
                        )
        );
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('donation.component_block_donate_clean')) ? eval($sPlugin) : false);
    }

}

?>