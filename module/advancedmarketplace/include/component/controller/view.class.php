<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_View extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process() {
        Phpfox::getUserParam('advancedmarketplace.can_access_advancedmarketplace', true);
		
		switch($this->request()->get('req3')){
			case "recent-listing":
				echo "recent-listing";
				break;
		}
		
        if ($this->request()->getInt('req2') > 0) {
            return Phpfox::getLib('module')->setController('advancedmarketplace.view');
        }

        if (($sLegacyTitle = $this->request()->get('req2')) && !empty($sLegacyTitle)) {
            if ($this->request()->get('req3') != '') {
                $sLegacyTitle = $this->request()->get('req3');
            }

            $aLegacyItem = Phpfox::getService('core')->getLegacyItem(array(
                'field' => array('category_id', 'name'),
                'table' => 'advancedmarketplace_category',
                'redirect' => 'advancedmarketplace.category',
                'title' => $sLegacyTitle,
                'search' => 'name_url'
                    )
            );
        }

        // certain conditions need to apply to sponsor a listing
        if ($this->request()->get('sponsor') == 'help') {
            // check if the user can sponsor items
            if (!Phpfox::getUserParam('advancedmarketplace.can_purchase_sponsor') &&
                    !Phpfox::getUserParam('advancedmarketplace.can_sponsor_advancedmarketplace')) {
                $this->url()->forward($this->url()->makeUrl('advancedmarketplace.'), Phpfox::getPhrase('subscribe.the_feature_or_section_you_are_attempting_to_use_is_not_permitted_with_your_membership_level'));
            } else {
                Phpfox::addMessage(Phpfox::getPhrase('advancedmarketplace.sponsor_help'));
            }
        }

        if (($iDeleteId = $this->request()->getInt('delete'))) {
            if (Phpfox::getService('advancedmarketplace.process')->delete($iDeleteId)) {
                $this->url()->send('advancedmarketplace', null, Phpfox::getPhrase('advancedmarketplace.listing_successfully_deleted'));
            }
        }

        if (($iRedirectId = $this->request()->getInt('redirect')) && ($aListing = Phpfox::getService('advancedmarketplace')->getListing($iRedirectId, true))) {
            $this->url()->send('advancedmarketplace.view', array($aListing['title_url']));
        }

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

        $oServiceAdvancedMarketplaceBrowse = Phpfox::getService('advancedmarketplace.browse');
        $sCategoryUrl = null;
        $sView = $this->request()->get('view');

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
        $aBrowseParams = array(
            'module_id' => 'advancedmarketplace',
            'alias' => 'l',
            'field' => 'listing_id',
            'table' => Phpfox::getT('advancedmarketplace'),
            'hide_view' => array('pending', 'my')
        );

        switch ($sView) {
            case 'sold':
                Phpfox::isUser(true);
                $this->search()->setCondition('AND l.user_id = ' . Phpfox::getUserId());
                $this->search()->setCondition('AND l.is_sell = 1');

                break;
            case 'featured':
                $this->search()->setCondition('AND l.is_featured = 1');
                $this->search()->setCondition('AND l.post_status = 1');
                break;
            case 'my':
                Phpfox::isUser(true);
                $this->search()->setCondition('AND l.user_id = ' . Phpfox::getUserId());
                break;
            case 'pending':
                if (Phpfox::getUserParam('advancedmarketplace.can_approve_listings')) {
                    $this->search()->setCondition('AND l.view_id = 1');
                    $this->template()->assign('bIsInPendingMode', true);
                }
                break;
            default:
                if ($bIsProfile === true) {
                    $this->search()->setCondition("AND l.view_id IN(" . ($aUser['user_id'] == Phpfox::getUserId() ? '0,1' : '0') . ") AND l.privacy IN(" . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ") AND l.user_id = " . $aUser['user_id'] . "");
                } else {
                    switch ($sView) {
                        case 'invites':
                            Phpfox::isUser(true);
                            $oServiceAdvancedMarketplaceBrowse->seen();
                            break;
                    }

					// custom search...
                    if (($sLocation = $this->request()->get('location'))) {
                        $this->search()->setCondition('AND l.country_iso = \'' . Phpfox::getLib('database')->escape($sLocation) . '\'');
                    }

                    if (($sStatus = $this->request()->get('status'))) {
						switch($sStatus){
							case "pending":
								$this->search()->setCondition('AND (l.post_status = 0 OR l.post_status IS NULL)');
								break;
							case "approved":
								$this->search()->setCondition('AND l.post_status = 1');
								break;
						}
                        // $this->search()->setCondition('AND l.country_iso = \'' . Phpfox::getLib('database')->escape($sLocation) . '\'');
                    }

                    $this->search()->setCondition('AND l.view_id = 0 AND l.privacy IN(%PRIVACY%)');
                }
                // $this->search()->setCondition('AND l.post_status = 1');
                break;
        }

        if ($this->request()->get('req2') == 'category') {
            $sCategoryUrl = $this->request()->getInt('req3');
            $this->search()->setCondition('AND mcd.category_id = ' . (int) $sCategoryUrl);
        } elseif ($this->request()->get(($bIsProfile === true ? 'req3' : 'req2')) == 'tag') {
            if (($aTag = Phpfox::getService('tag')->getTagInfo('advancedmarketplace', $this->request()->get(($bIsProfile === true ? 'req4' : 'req3'))))) {
                $this->template()->setBreadCrumb(Phpfox::getPhrase('tag.topic') . ': ' . $aTag['tag_text'] . '', $this->url()->makeUrl('current'), true);
                $this->search()->setCondition('AND tag.tag_text = \'' . Phpfox::getLib('database')->escape($aTag['tag_text']) . '\'');
            }
        }
        $this->setParam('sCategory', $sCategoryUrl);

        $oServiceAdvancedMarketplaceBrowse->category($sCategoryUrl);



        // if its a user trying to buy sponsor space he should get only his own listings
        if ($this->request()->get('sponsor') == 'help') {
            $this->search()->setCondition(' AND l.is_sponsor != 1');
        }
		// $this->search()->setCondition(' JH ');
        $this->search()->browse()->params($aBrowseParams)->execute();
		// var_dump($this->search()->getConditions());exit;
        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_index_process_filter')) ? eval($sPlugin) : false);

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

            if (Phpfox::getUserParam('advancedmarketplace.can_approve_listings')) {
                $iPendingTotal = Phpfox::getService('advancedmarketplace')->getPendingTotal();

                if ($iPendingTotal) {
                    $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.pending_listings') . '<span class="pending">' . $iPendingTotal . '</span>'] = 'pending';
                }
            }
        }
        $core_url = phpfox::getParam('core.path');
        $this->template()->setTitle(($bIsProfile ? Phpfox::getPhrase('advancedmarketplace.full_name_s_listings', array('full_name' => $aUser['full_name'])) : Phpfox::getPhrase('advancedmarketplace.advanced_advancedmarketplace')))
                ->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.advanced_advancedmarketplace'), ($bIsUserProfile ? $this->url()->makeUrl($aUser['user_name'], 'advancedmarketplace.') : $this->url()->makeUrl('advancedmarketplace.')))
                ->setHeader('cache', array(
                    'pager.css' => 'style_css',
                    'country.js' => 'module_core',
                    'jhslide.js' => 'module_advancedmarketplace',
                    'browse.css' => 'module_advancedmarketplace',
                    'comment.css' => 'style_css',
                    'jhslide.css' => 'module_advancedmarketplace',
                    'feed.js' => 'module_feed'
                        )
                )
                ->assign(array(
                    'aListings' => $this->search()->browse()->getRows(),
                    'sCategoryUrl' => $sCategoryUrl,
					'corepath'=>phpfox::getParam('core.path'),
                    'sListingView' => $sView,
                    'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
                    'error_img_path' => Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/item.png'
                        )
        );

        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_process_end')) ? eval($sPlugin) : false);

        $this->template()->buildSectionMenu('advancedmarketplace', $aFilterMenu);

        if ($sCategoryUrl !== null) {
            $aCategories = Phpfox::getService('advancedmarketplace.category')->getParentBreadcrumb($sCategoryUrl);
            $iCnt = 0;
            foreach ($aCategories as $aCategory) {
                $iCnt++;

                $this->template()->setTitle($aCategory[0]);

                if ($bIsUserProfile) {
                    $aCategory[1] = str_replace('/advancedmarketplace/', '/' . $aUser['user_name'] . '/advancedmarketplace/', $aCategory[1]);
                }

                $this->template()->setBreadcrumb($aCategory[0], $aCategory[1], ($iCnt === count($aCategories) ? true : false));
            }
        }

        $this->setParam('global_moderation', array(
            'name' => 'advancedmarketplace.', //jh: recheck
            'ajax' => 'advancedmarketplace.moderation',
            'menu' => array(
                array(
                    'phrase' => Phpfox::getPhrase('advancedmarketplace.delete'),
                    'action' => 'delete'
                ),
                array(
                    'phrase' => Phpfox::getPhrase('advancedmarketplace.approve'),
                    'action' => 'approve'
                ),
                array(
                    'phrase' => Phpfox::getPhrase('advancedmarketplace.feature'),
                    'action' => 'feature'
                ),
                array(
                    'phrase' => Phpfox::getPhrase('advancedmarketplace.un_feature'),
                    'action' => 'un-feature'
                )
            )
                )
        );

        Phpfox::getLib('pager')->set(array('page' => $this->search()->getPage(), 'size' => $this->search()->getDisplay(), 'count' => $this->search()->browse()->getCount()));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean() {
        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_view_clean')) ? eval($sPlugin) : false);
    }

}

?>
