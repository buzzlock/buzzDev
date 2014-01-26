<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdvancedMarketplace_Component_Controller_MyListing extends Phpfox_Component {

    public function process() {
        $aFilterMenu = array();
        if (!defined('PHPFOX_IS_USER_PROFILE')) {
            $sInviteTotal = '';
            if (Phpfox::isUser() && ($iTotalInvites = Phpfox::getService('advancedmarketplace')->getTotalInvites())) {
                $sInviteTotal = '<span class="invited">' . $iTotalInvites . '</span>';
            }

            $aFilterMenu = array(
                Phpfox::getPhrase('advancedmarketplace.all_listings') => '',
                Phpfox::getPhrase('advancedmarketplace.my_listings') => 'my',
                Phpfox::getPhrase('advancedmarketplace.listing_invites') . $sInviteTotal => 'invites'
            );

            if (Phpfox::isModule('friend') && !Phpfox::getParam('core.friends_only_community')) {
                $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.friends_listings')] = 'friend';
            }

            if (Phpfox::getUserParam('event.can_approve_events')) {
                $iPendingTotal = Phpfox::getService('advancedmarketplace')->getPendingTotal();

                if ($iPendingTotal) {
                    $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.pending_listings') . '<span class="pending">' . $iPendingTotal . '</span>'] = 'pending';
                }
            }
        }

        //  query 
		$bIsProfile = false;
        if (defined('PHPFOX_IS_AJAX_CONTROLLER')) {
            $bIsProfile = true;
            $aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
            $this->setParam('aUser', $aUser);
        } else {
            $bIsProfile = $this->getParam('bIsProfile');
            if ($bIsProfile === true) {
                $aUser = $this->getParam('aUser');
            }
        }

        $bIsUserProfile = false;
        if (defined('PHPFOX_IS_AJAX_CONTROLLER')) {
            $bIsUserProfile = true;
            $aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
            $this->setParam('aUser', $aUser);
        }

        if (defined('PHPFOX_IS_USER_PROFILE')) {
            $bIsUserProfile = true;
            $aUser = $this->getParam('aUser');
        }

        $aCountriesValue = array();
        $aCountries = Phpfox::getService('core.country')->get();
        foreach ($aCountries as $sKey => $sValue) {
            $aCountriesValue[] = array(
                'link' => $sKey,
                'phrase' => $sValue
            );
        }
        $aSearchFields = array(
            'type' => 'advancedmarketplace.',
            'field' => 'l.listing_id',
            'search_tool' => array(
			'table_alias' => 'l',
                'search' => array(
                    'action' => ($bIsProfile === true ? $this->url()->makeUrl($aUser['user_name'], array('advancedmarketplace.search', 'view' => $this->request()->get('view'))) : $this->url()->makeUrl('advancedmarketplace.search.', array('view' => $this->request()->get('view')))),
                    'default_value' => Phpfox::getPhrase('advancedmarketplace.search_listings'),
                    'name' => 'search',
                    'field' => array('l.title', 'mt.description_parsed')
                ),
                'sort' => array(
                    'latest' => array('l.time_stamp', Phpfox::getPhrase('advancedmarketplace.latest')),
                    'most-viewed' => array('l.total_view', Phpfox::getPhrase('advancedmarketplace.most_viewed')),
                    'most-liked' => array('l.total_like', Phpfox::getPhrase('advancedmarketplace.most_liked')),
                    'most-talked' => array('l.total_comment', Phpfox::getPhrase('advancedmarketplace.most_discussed'))
                ),
                'show' => array(10, 15, 18, 21)
            )
        );

		$aStatus = array(
			array(
				'link'=> "all",
				'phrase'=> PHPFOX::getPhrase("advancedmarketplace.all")
			),
			array(
				'link'=> "pending",
				'phrase'=> PHPFOX::getPhrase("advancedmarketplace.pending")
			),
			array(
				'link'=> "approved",
				'phrase'=> PHPFOX::getPhrase("advancedmarketplace.approved")
			),
		);

        if (!$bIsUserProfile) {
            $aSearchFields['search_tool']['custom_filters'] = array(
                Phpfox::getPhrase('advancedmarketplace.location') => array(
                    'param' => 'location',
                    'default_phrase' => Phpfox::getPhrase('advancedmarketplace.anywhere'),
                    'data' => $aCountriesValue,
                    'height' => '300px',
                    'width' => '150px'
                ),
				PHPFOX::getPhrase("advancedmarketplace.status")=>array(
					'param'=> "status",
					'default_phrase'=> PHPFOX::getPhrase("advancedmarketplace.all"),
					'data'=> $aStatus
				)
            );
        }

        $this->search()->set($aSearchFields);
		
		//  mylisting...
		Phpfox::isUser(true);
		$this->search()->setCondition('AND l.user_id = ' . Phpfox::getUserId());
		/// mylisting...
		
        $aBrowseParams = array(
            'module_id' => 'advancedmarketplace',
            'alias' => 'l',
            'field' => 'listing_id',
            'table' => Phpfox::getT('advancedmarketplace'),
            'hide_view' => array('pending', 'my')
        );
		$this->search()->browse()->params($aBrowseParams)->execute();
        /// query
        list($count, $aListings) = PHPFOX::getService("advancedmarketplace")->frontend_getListings(NULL, 'listing_id desc', $iPage = 0);
        $this->template()->buildSectionMenu('advancedmarketplace', $aFilterMenu);
		$asListings = $this->search()->browse()->getRows();
		foreach ($asListings as $iKey => $aListing)
		{
			$fAVGRating = PHPFOX::getLib("database")
				->select("AVG(rating)")
				->from(PHPFOX::getT("advancedmarketplace_rate"))
				->where(sprintf("listing_id = %d", $aListing['listing_id']))
				->execute("getSlaveField");
			$iRatingCount = PHPFOX::getLib("database")
				->select("count(*)")
				->from(PHPFOX::getT("advancedmarketplace_rate"))
				->where(sprintf("listing_id = %d", $aListing['listing_id']))
				->execute("getSlaveField");
				
			$asListings[$iKey]['rating'] = $fAVGRating;
			$asListings[$iKey]['rating_count'] = $iRatingCount;
		}

        $this->template()->assign(array(
            'corepath' => phpfox::getParam('core.path'),
            "aListings" => $asListings,
            'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
        ));
        $this->template()->setBreadcrumb("Marketplace", "advancedmarketplace");
    }

}

?>
