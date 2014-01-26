<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Detail extends Phpfox_Component
{
    public function process()
    {
        /*
        if ($this->request()->get('req2') == 'detail' && ($sLegacyTitle = $this->request()->get('req4')) && !empty($sLegacyTitle))
        {
            Phpfox::getService('core')->getLegacyItem(array(
                'field' => array('listing_id', 'title'),
                'table' => 'advancedmarketplace',
                'redirect' => 'advancedmarketplace.detail',
                'title' => $sLegacyTitle
                )
            );
        }
        */
        
        Phpfox::getUserParam('advancedmarketplace.can_access_advancedmarketplace', true);
        
        if (!($iListingId = $this->request()->get('req3')))
        {
            $this->url()->send('advancedmarketplace');
        }

        if (!($aListing = Phpfox::getService('advancedmarketplace')->getListing($iListingId)))
        {
            return Phpfox_Error::display(Phpfox::getPhrase('advancedmarketplace.the_listing_you_are_looking_for_either_does_not_exist_or_has_been_removed'));
        }
        
        if ($aListing['post_status'] == 2)
        {
            if ($aListing['user_id'] == phpfox::getUserId() || phpfox::getUserParam('advancedmarketplace.can_view_draft_listings'))
            {

            }
            else
            {
                return Phpfox_Error::display(Phpfox::getPhrase('advancedmarketplace.the_listing_you_are_looking_for_either_does_not_exist_or_has_been_removed'));
            }
        }
        
        $this->setParam('aListing', $aListing);
        
        if (Phpfox::isUser() && $aListing['invite_id'] && !$aListing['visited_id'] && $aListing['user_id'] != Phpfox::getUserId())
        {
            Phpfox::getService('advancedmarketplace.process')->setVisit($aListing['listing_id'], Phpfox::getUserId());
        }

        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('comment_advancedmarketplace', $this->request()->getInt('req3'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('advancedmarketplace_like', $this->request()->getInt('req3'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('advancedmarketplace_follow', $aListing['listing_id'], Phpfox::getUserId());
        }

        if (Phpfox::isModule('notification') && $aListing['user_id'] == Phpfox::getUserId())
        {
            Phpfox::getService('notification.process')->delete('advancedmarketplace_approved', $aListing['listing_id'], Phpfox::getUserId());

        }

        Phpfox::getService('core.redirect')->check($aListing['title'], 'req4');
        if (Phpfox::isModule('privacy'))
        {
            Phpfox::getService('privacy')->check('advancedmarketplace', $aListing['listing_id'], $aListing['user_id'], $aListing['privacy'], $aListing['is_friend']);
        }

        $aFollower = phpfox::getLib('database')->select('*')->from(phpfox::getT('advancedmarketplace_follow'))->where('user_id = ' . $aListing['user_id'] . ' and  user_follow_id = ' . phpfox::getUserId())->execute('getSlaveRow');
        $bFollow = 1;
        if (!empty($aFollower))
        {
            $bFollow = 0;
        }

        $this->setParam('aRatingCallback', array(
            'type' => 'user',
            'default_rating' => $aListing['total_score'],
            'item_id' => $aListing['user_id'],
            'stars' => range(1, 10)));

        $this->setParam('aFeed', array(
            'comment_type_id' => 'advancedmarketplace',
            'privacy' => $aListing['privacy'],
            'comment_privacy' => $aListing['privacy_comment'],
            'like_type_id' => 'advancedmarketplace',
            'feed_is_liked' => $aListing['is_liked'],
            'feed_is_friend' => $aListing['is_friend'],
            'item_id' => $aListing['listing_id'],
            'user_id' => $aListing['user_id'],
            'total_comment' => $aListing['total_comment'],
            'total_like' => $aListing['total_like'],
            'feed_link' => $this->url()->permalink('advancedmarketplace.detail', $aListing['listing_id'], $aListing['title']),
            'feed_title' => $aListing['title'],
            'feed_display' => 'view',
            'feed_total_like' => $aListing['total_like'],
            'report_module' => 'advancedmarketplace',
            'report_phrase' => Phpfox::getPhrase('advancedmarketplace.report_this_listing_lowercase')));

        $this->template()->setTitle($aListing['title'] . ($aListing['view_id'] == '2' ? ' (' . Phpfox::getPhrase('advancedmarketplace.sold') . ')' : ''))
            ->setMeta('description', $aListing['description'])
            ->setMeta('keywords', $this->template()->getKeywords($aListing['title'] . $aListing['description']))
            ->setMeta('og:image', Phpfox::getService('advancedmarketplace.helper')->display(array(
                'server_id' => $aListing['listing_id'],
                'source' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/" . Phpfox::getService("advancedmarketplace")->proccessImageName($aListing["image_path"], "_120"),
                'return_url' => true,
                'max_width' => '',
                'max_height' => '',
            ))) 
            //->setBreadcrumb($aListing['title'] . ($aListing['view_id'] == '2' ? ' (' . Phpfox::getPhrase('advancedmarketplace.sold') . ')' : ''), $this->url()->permalink('advancedmarketplace', $aListing['listing_id'], $aListing['title']), true)
            ->setHeader('cache', array(
                'jquery/plugin/star/jquery.rating.js' => 'static_script',
                'jquery.rating.css' => 'style_css',
                'jquery/plugin/jquery.highlightFade.js' => 'static_script',
                'jquery/plugin/jquery.scrollTo.js' => 'static_script',
                'quick_edit.js' => 'static_script',
                'comment.css' => 'style_css',
                'pager.css' => 'style_css',
                'switch_legend.js' => 'static_script',
                'switch_menu.js' => 'static_script',
                'view.js' => 'module_advancedmarketplace',
                'view.css' => 'module_advancedmarketplace',
                'pager.css' => 'style_css',
                'feed.js' => 'module_feed'
            ))
            ->setEditor(array(
                'load' => 'simple'
            ))
            ->assign(array(
                'aListing' => $aListing,
                'iFollower' => phpfox::getUserId(),
                'bFollow' => $bFollow,
            ));
            
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
        }
        
        $this->template()->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.advanced_advancedmarketplace'), ($bIsUserProfile ? $this->url()->makeUrl($aUser['user_name'], 'advancedmarketplace.') : $this->url()->makeUrl('advancedmarketplace.')));
        
        $aCategories = $aListing["categories"];
        // if ($sCategoryUrl !== null)
        {
            list($allCates, $aCategories) = Phpfox::getService('advancedmarketplace.category')->getCategorieStructure(true);
            // var_dump($sCategoryUrl);
            $aCatesPathInvert = array();
            // var_dump($aListing);
            $iCurrentId = $aListing["category"]["category_id"];
            $oCurrentObj = $allCates[$iCurrentId];
            while ($iCurrentId != 0)
            {
                $aCatesPathInvert[] = $allCates[$iCurrentId];
                // var_dump($allCates[$iCurrentId]);
                $iCurrentId = $allCates[$iCurrentId]["parent_id"];
            }
            $aCatesPath = array_reverse($aCatesPathInvert);

            $iCnt = 0;
            foreach ($aCatesPath as $aCategory)
            {
                $iCnt++;

                $this->template()->setTitle(Phpfox::getLib("locale")->convert($aCategory["name"]));

                if ($bIsUserProfile)
                {
                    $aCategory["url"] = str_replace('/advancedmarketplace/', '/' . $aUser['user_name'] . '/advancedmarketplace/', $aCategory["url"]);
                }
                $this->template()->setBreadcrumb(Phpfox::getLib("locale")->convert($aCategory["name"]), $aCategory["url"], ($iCnt === count($aCatesPath) ? true : false));
            }

            //$this->template()->setBreadcrumb($aListing['title'] . ($aListing['view_id'] == '2' ? ' (' . Phpfox::getPhrase('advancedmarketplace.sold') . ')' : ''), NULL, true);
            $this->template()->setBreadcrumb($aListing['title'] . ($aListing['view_id'] == '2' ? ' (' . Phpfox::getPhrase('advancedmarketplace.sold') . ')' : ''), $this->url()->permalink('advancedmarketplace.detail', $aListing['listing_id'], $aListing['title']), true);
        }

        if (Phpfox::isModule('rate'))
        {
            $this->template()->setPhrase(array('rate.thanks_for_rating'))->setHeader(array(
                'rate.js' => 'module_rate',
                'all.css' => 'module_advancedmarketplace',
                '<script type="text/javascript">$Behavior.rateMarketplaceUser = function() {$Core.rate.init({display: true}); }</script>',
                'view.js' => 'module_advancedmarketplace'));
        }
        $iPage = 0;
        $iSize = 2;
        
        list($iCount, $aRating) = Phpfox::getService("advancedmarketplace")->frontend_getListingReview($iListingId, $iSize, $iPage);

        $this->template()->assign(array(
            "aRating" => $aRating,
            "iCount" => $iCount,
            "iPage" => $iPage,
            "iSize" => $iSize));

        if (Phpfox::isModule('track') && Phpfox::isUser() && Phpfox::getUserId() != $aListing['user_id'] && !$aListing['is_viewed'])
        {
            Phpfox::getService('track.process')->add('advancedmarketplace', $iListingId);
            Phpfox::getService("advancedmarketplace.process")->updateViewCounter($iListingId);
        }
        
        Phpfox::getService("advancedmarketplace.process")->updateRecentView($iListingId);

        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_view_process_end')) ? eval($sPlugin) : false);
    }

}

?>