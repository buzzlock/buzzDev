<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdvancedMarketplace_Component_Block_SponsoredListing extends Phpfox_Component {

    public function process() {
    	return false;
        list($count, $aSponsoredListing) = PHPFOX::getService("advancedmarketplace")->frontend_getListings(array(
            "is_sponsor = 1"
        ), 'listing_id desc', $iPage = 0, $iLimit = 2);

        if (count($aSponsoredListing) <= 0) {
            $this->template()->assign(array(
                'aSponsoredListing' => NULL,
            ));
        } else {
            $this->template()->assign(array(
                'sHeader'                       => Phpfox::getPhrase('advancedmarketplace.sponsored_listing'),
                'corepath'                      => phpfox::getParam('core.path'),
                'aSponsoredListing'                => $aSponsoredListing,
                'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
            ));
        }
        return 'block';
    }

}

?>
