<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdvancedMarketplace_Component_Block_Todaylisting extends Phpfox_Component
{
	public function process()
	{
		$aTodayListing = PHPFOX::getService("advancedmarketplace")->frontend_getTodayListings(NULL, NULL, NULL);

        if (count($aTodayListing) <= 0) {
            $this->template()->assign(array(
                'aTodayListing' => NULL,
            ));
        } else {
            $this->template()->assign(array(
                'sHeader'                       => Phpfox::getPhrase('advancedmarketplace.today_listings'),
                'corepath'                      => phpfox::getParam('core.path'),
                'aTodayListing'            => $aTodayListing,
                'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
            ));
        }
        return 'block';
	}
}
?>