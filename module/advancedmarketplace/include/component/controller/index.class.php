<?php

defined('PHPFOX') or exit('NO DICE!');


class advancedmarketplace_Component_Controller_Index extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::getUserParam('advancedmarketplace.can_access_advancedmarketplace', true);
        
        $sSort = $this->request()->get('sort');
        $sShow = $this->request()->get('show');
        $sWhen = $this->request()->get('when');
        if (!empty($sSort) || !empty($sShow) || !empty($sWhen))
        {
            return Phpfox::getLib('module')->setController('advancedmarketplace.search');
        }
        
        $sLocation = $this->request()->get('location');
        if (!empty($sLocation))
        {
            return Phpfox::getLib('url')->send('advancedmarketplace.search.' . "country_{$sLocation}");
        }

        $sView = $this->request()->get('view');
        if($sView)
        {
            return Phpfox::getLib('module')->setController('advancedmarketplace.all');
        }

        if (($sLegacyTitle = $this->request()->get('req2')) && !empty($sLegacyTitle))
        {
            if ($this->request()->get('req3') != '')
            {
                $sLegacyTitle = $this->request()->get('req3');
            }

            $aLegacyItem = Phpfox::getService('core')->getLegacyItem(array(
                'field' => array('category_id', 'name'),
                'table' => 'advancedmarketplace_category',
                'redirect' => 'advancedmarketplace.category',
                'title' => $sLegacyTitle,
                'search' => 'name_url'));
        }

        // certain conditions need to apply to sponsor a listing
        if ($this->request()->get('sponsor') == 'help')
        {
            // check if the user can sponsor items
            if (!Phpfox::getUserParam('advancedmarketplace.can_purchase_sponsor') && !Phpfox::getUserParam('advancedmarketplace.can_sponsor_advancedmarketplace'))
            {
                $this->url()->forward($this->url()->makeUrl('advancedmarketplace.'), Phpfox::getPhrase('subscribe.the_feature_or_section_you_are_attempting_to_use_is_not_permitted_with_your_membership_level'));
            }
            else
            {
                Phpfox::addMessage(Phpfox::getPhrase('advancedmarketplace.sponsor_help'));
            }
        }

        if (($iDeleteId = $this->request()->getInt('delete')))
        {
            if (Phpfox::getService('advancedmarketplace.process')->delete($iDeleteId))
            {
                $this->url()->send('advancedmarketplace', null, Phpfox::getPhrase('advancedmarketplace.listing_successfully_deleted'));
            }
        }

        if (($iRedirectId = $this->request()->getInt('redirect')) && ($aListing = Phpfox::getService('advancedmarketplace')->getListing($iRedirectId, true)))
        {
            $this->url()->send('advancedmarketplace.view', array($aListing['title_url']));
        }

        $bIsProfile = false;
        if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
        {
            $bIsProfile = true;
            $aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
            $this->setParam('aUser', $aUser);
        }
        else
        {
            $bIsProfile = $this->getParam('bIsProfile');
            if ($bIsProfile === true)
            {
                $aUser = $this->getParam('aUser');
            }
        }

        $oServiceAdvancedMarketplaceBrowse = Phpfox::getService('advancedmarketplace.browse');
        $sCategoryUrl = null;

        $bIsUserProfile = false;
        if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
        {
            $bIsUserProfile = true;
            $aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
            $this->setParam('aUser', $aUser);
        }

        if (defined('PHPFOX_IS_USER_PROFILE'))
        {
            $bIsUserProfile = true;
            $aUser = $this->getParam('aUser');
            return Phpfox::getLib('module')->setController('advancedmarketplace.all');
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
                    'field' => array('l.title', 'mt.description_parsed')),
                'sort' => array(
                    'latest' => array('l.time_stamp', Phpfox::getPhrase('advancedmarketplace.latest')),
                    'most-viewed' => array('l.total_view', Phpfox::getPhrase('advancedmarketplace.most_viewed')),
                    'most-liked' => array('l.total_like', Phpfox::getPhrase('advancedmarketplace.most_liked')),
                    'most-talked' => array('l.total_comment', Phpfox::getPhrase('advancedmarketplace.most_discussed')),
                    'most-reviewed' => array('l.total_rate desc, l.total_score', Phpfox::getPhrase('advancedmarketplace.most_reviewed')),
                    'recent-viewed' => array('review_time', Phpfox::getPhrase('advancedmarketplace.recent_viewed'))),
                'show' => array(
                    10,
                    15,
                    18,
                    21)));

        $aStatus = array(
            array('link' => "all", 'phrase' => PHPFOX::getPhrase("advancedmarketplace.all")),
            array('link' => "pending", 'phrase' => PHPFOX::getPhrase("advancedmarketplace.pending")),
            array('link' => "approved", 'phrase' => PHPFOX::getPhrase("advancedmarketplace.approved")),
            );

        $this->search()->set($aSearchFields);

        $aBrowseParams = array(
            'module_id' => 'advancedmarketplace',
            'alias' => 'l',
            'field' => 'listing_id',
            'table' => Phpfox::getT('advancedmarketplace'),
            'hide_view' => array('pending', 'my'));

        switch ($sView)
        {
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
                if (Phpfox::getUserParam('advancedmarketplace.can_approve_listings'))
                {
                    $this->search()->setCondition('AND l.view_id = 1');
                    $this->template()->assign('bIsInPendingMode', true);
                }
                break;
			case 'expired':
				if (Phpfox::getParam('advancedmarketplace.days_to_expire_listing') > 0 && Phpfox::getUserParam('advancedmarketplace.can_view_expired'))
				{
					$iExpireTime = (PHPFOX_TIME - (Phpfox::getParam('advancedmarketplace.days_to_expire_listing') * 86400));
					$this->search()->setCondition('AND l.time_stamp < ' . $iExpireTime);
					break;
				}
            default:
                if ($bIsProfile === true)
                {
                    $aUser = $this->getParam('aUser');
                    $this->search()->setCondition("AND l.view_id IN(" . ($aUser['user_id'] == Phpfox::getUserId() ? '0,1' : '0') . ") AND l.privacy IN(" . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ") AND l.user_id = " . $aUser['user_id'] . "");
                    $this->search()->setCondition('AND l.user_id = ' . $aUser["user_id"]);
                    if ($aUser["user_id"] != PHPFOX::getUserId())
                    {
                        $this->search()->setCondition(' AND l.post_status != 2 ');
                    }
                }
                else
                {
                    switch ($sView)
                    {
                        case 'invites':
                            Phpfox::isUser(true);
                            $oServiceAdvancedMarketplaceBrowse->seen();
                            break;
                    }

                    if (($sLocation = $this->request()->get('location')))
                    {
                        phpfox::getLib('url')->send('advancedmarketplace.search.location_' . Phpfox::getLib('database')->escape($sLocation));
                        $this->search()->setCondition('AND l.country_iso = \'' . Phpfox::getLib('database')->escape($sLocation) . '\'');
                    }

                    $this->search()->setCondition('AND l.view_id = 0 AND l.privacy IN(%PRIVACY%)');
                }
                $this->search()->setCondition('AND l.post_status = 1');
                break;
        }

        if ($this->request()->get('req2') == 'category')
        {
            $sCategoryUrl = $this->request()->getInt('req3');
            $this->search()->setCondition('AND mcd.category_id = ' . (int)$sCategoryUrl);
        }
        elseif ($this->request()->get(($bIsProfile === true ? 'req3' : 'req2')) == 'tag')
        {
            if (($aTag = Phpfox::getService('tag')->getTagInfo('advancedmarketplace', $this->request()->get(($bIsProfile === true ? 'req4' : 'req3')))))
            {
                $this->template()->setBreadCrumb(Phpfox::getPhrase('tag.topic') . ': ' . $aTag['tag_text'] . '', $this->url()->makeUrl('current'), true);
                $this->search()->setCondition('AND tag.tag_text = \'' . Phpfox::getLib('database')->escape($aTag['tag_text']) . '\'');
            }
        }
        
        $this->setParam('sCategory', $sCategoryUrl);

        $oServiceAdvancedMarketplaceBrowse->category($sCategoryUrl);

        if (Phpfox::getParam('advancedmarketplace.days_to_expire_listing') > 0 && $sView != 'my' && $sView != 'expired')
        {
            $iExpireTime = (PHPFOX_TIME - (Phpfox::getParam('advancedmarketplace.days_to_expire_listing') * 86400));
            $this->search()->setCondition(' AND l.time_stamp >=' . $iExpireTime);
        }

        // if its a user trying to buy sponsor space he should get only his own listings
        if ($this->request()->get('sponsor') == 'help')
        {
            $this->search()->setCondition(' AND l.is_sponsor != 1');
        }

        $this->search()->browse()->params($aBrowseParams)->execute();

        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_index_process_filter')) ? eval($sPlugin) : false);

        $aFilterMenu = array();
        if (!defined('PHPFOX_IS_USER_PROFILE'))
        {
            $sInviteTotal = '';
            if (Phpfox::isUser() && ($iTotalInvites = Phpfox::getService('advancedmarketplace')->getTotalInvites()))
            {
                $sInviteTotal = '<span class="invited">' . $iTotalInvites . '</span>';
            }

            $aFilterMenu = array(
                Phpfox::getPhrase('advancedmarketplace.all_listings') => '',
                Phpfox::getPhrase('advancedmarketplace.my_listings') => 'my',
                Phpfox::getPhrase('advancedmarketplace.listing_invites') . $sInviteTotal => 'invites');

			if (Phpfox::getUserParam('advancedmarketplace.can_view_expired'))
			{
				$aFilterMenu[Phpfox::getPhrase('advancedmarketplace.expired')] = 'expired';
			}
            if (Phpfox::isModule('friend') && !Phpfox::getParam('core.friends_only_community'))
            {
                $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.friends_listings')] = 'friend';
            }

            if (Phpfox::getUserParam('advancedmarketplace.can_approve_listings'))
            {
                $iPendingTotal = Phpfox::getService('advancedmarketplace')->getPendingTotal();

                if ($iPendingTotal)
                {
                    $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.pending_listings') . '<span class="pending">' . $iPendingTotal . '</span>'] = 'pending';
                }
            }
            $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.google_map')] = 'gmap';
        }
        $core_url = phpfox::getParam('core.path');

        $this->template()->setTitle(($bIsProfile ? Phpfox::getPhrase('advancedmarketplace.full_name_s_listings', array('full_name' => $aUser['full_name'])) : Phpfox::getPhrase('advancedmarketplace.advanced_advancedmarketplace')))
            ->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.advanced_advancedmarketplace'), ($bIsUserProfile ? $this->url()->makeUrl($aUser['user_name'], 'advancedmarketplace.') : $this->url()->makeUrl('advancedmarketplace.')))
            ->setHeader('cache', array(
                'pager.css' => 'style_css',
                'all.css' => 'module_advancedmarketplace',
                //'country.js' => 'module_core',
                'jhslide.js' => 'module_advancedmarketplace',
                'browse.css' => 'module_advancedmarketplace',
                'comment.css' => 'style_css',
                'jhslide.css' => 'module_advancedmarketplace',
                'feed.js' => 'module_feed',
                'jquery.cycle.all.js' => 'module_advancedmarketplace',
                'index.js' => 'module_advancedmarketplace',
            ))
            ->assign(array(
                'aListings' => $this->search()->browse()->getRows(),
                'sCategoryUrl' => $sCategoryUrl,
                "isIndex" => true,
                'sListingView' => $sView,
                'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
                'error_img_path' => Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/item.png'
                ));

        $this->template()->setPhrase(array(
            'advancedmarketplace.view_this_listing',
            'advancedmarketplace.address',
            'advancedmarketplace.listing',
            'advancedmarketplace.location',
            ));

        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_process_end')) ? eval($sPlugin) : false);

        $this->template()->buildSectionMenu('advancedmarketplace', $aFilterMenu);

        if ($sCategoryUrl !== null)
        {
            $aCategories = Phpfox::getService('advancedmarketplace.category')->getParentBreadcrumb($sCategoryUrl);
            $iCnt = 0;
            foreach ($aCategories as $aCategory)
            {
                $iCnt++;

                $this->template()->setTitle($aCategory[0]);

                if ($bIsUserProfile)
                {
                    $aCategory[1] = str_replace('/advancedmarketplace/', '/' . $aUser['user_name'] . '/advancedmarketplace/', $aCategory[1]);
                }

                $this->template()->setBreadcrumb($aCategory[0], $aCategory[1], ($iCnt === count($aCategories) ? true : false));
            }
        }

        $this->setParam('global_moderation', array(
            'name' => 'advancedmarketplace.', //jh: recheck
            'ajax' => 'advancedmarketplace.moderation',
            'menu' => array(
                array('phrase' => Phpfox::getPhrase('advancedmarketplace.delete'), 'action' => 'delete'),
                array('phrase' => Phpfox::getPhrase('advancedmarketplace.approve'), 'action' => 'approve'),
                array('phrase' => Phpfox::getPhrase('advancedmarketplace.feature'), 'action' => 'feature'),
                array('phrase' => Phpfox::getPhrase('advancedmarketplace.un_feature'), 'action' => 'un-feature'))));

        Phpfox::getLib('pager')->set(array(
            'page' => $this->search()->getPage(),
            'size' => $this->search()->getDisplay(),
            'count' => $this->search()->browse()->getCount()));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_index_clean')) ? eval($sPlugin) : false);
    }

}

?>
