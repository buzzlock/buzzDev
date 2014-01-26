<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdvancedMarketplace_Component_Block_RecentListing extends Phpfox_Component {

    public function process() {
    	$aConds[] = 'l.post_status != 2';
		$aConds[] = 'l.view_id = 0';
		$aConds[] = 'l.privacy = 0';
        list($count, $aRecentListing) = PHPFOX::getService("advancedmarketplace")->frontend_getRecentListings($aConds, 'time_stamp desc', $iPage = 0, $iLimit = 5);
        if (count($aRecentListing) <= 0) {
            $this->template()->assign(array(
                'aRecentListing' => NULL,
            ));
        } else {
            $this->template()->assign(array(
                'sHeader'        => Phpfox::getPhrase('advancedmarketplace.recent_listing'),
                'corepath'       => phpfox::getParam('core.path'),
                'aRecentListing' => $aRecentListing,
				'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
            ));
        }
        return 'block';
    }

}

?>
