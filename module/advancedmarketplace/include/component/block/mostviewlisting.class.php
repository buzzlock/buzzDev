
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdvancedMarketplace_Component_Block_MostViewListing extends Phpfox_Component {

    public function process() {
		$bIsViewMore = false;
		$aConds[] = 'l.privacy = 0';
		$aConds[] = 'l.post_status != 2';
		$aConds[] = 'l.view_id = 0';
        list($count, $aMostViewListing) = PHPFOX::getService("advancedmarketplace")->frontend_getListings($aConds, 'total_view DESC, time_stamp DESC', $iPage = 0, $iLimit = 3);
		if($count > phpfox::getParam('advancedmarketplace.total_listing_more_from'))
		{
			$bIsViewMore = true;
		}

        if (count($aMostViewListing) <= 0) {
            $this->template()->assign(array(
                'aMostViewListing' => NULL,
            ));
        } else {
            $this->template()->assign(array(
                'sHeader'                       => Phpfox::getPhrase('advancedmarketplace.most_view_listing'),
                'corepath'                      => phpfox::getParam('core.path'),
                'aMostViewListing'                => $aMostViewListing,
                'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
				'bIsViewMore' => $bIsViewMore,
            ));
        }
        return 'block';
    }

}

?>
