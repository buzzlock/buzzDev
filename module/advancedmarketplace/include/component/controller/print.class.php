<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Controller_Print extends Phpfox_Component
{

    public function process() 
	{
		if(phpfox::getParam('advancedmarketplace.can_print_a_listing') !== true)
		{
			return Phpfox_Error::display(Phpfox::getPhrase('advancedmarketplace.the_listing_you_are_looking_for_either_does_not_exist_or_has_been_removed'));
		}
        Phpfox::getUserParam('advancedmarketplace.can_access_advancedmarketplace', true);
        if (!($iListingId = $this->request()->get('req3'))) {
            $this->url()->send('advancedmarketplace');
        }
        if (!($aListing = Phpfox::getService('advancedmarketplace')->getListing($iListingId))) {
            return Phpfox_Error::display(Phpfox::getPhrase('advancedmarketplace.the_listing_you_are_looking_for_either_does_not_exist_or_has_been_removed'));
        }

        $this->setParam('aListing', $aListing);

        if (Phpfox::isUser() && $aListing['invite_id'] && !$aListing['visited_id'] && $aListing['user_id'] != Phpfox::getUserId()) {
            Phpfox::getService('advancedmarketplace.process')->setVisit($aListing['listing_id'], Phpfox::getUserId());
        }

        if (Phpfox::isUser() && Phpfox::isModule('notification')) {
            Phpfox::getService('notification.process')->delete('comment_advancedmarketplace', $this->request()->getInt('req3'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('advancedmarketplace_like', $this->request()->getInt('req3'), Phpfox::getUserId());
        }

        if (Phpfox::isModule('notification') && $aListing['user_id'] == Phpfox::getUserId()) {
            Phpfox::getService('notification.process')->delete('advancedmarketplace_approved', $aListing['listing_id'], Phpfox::getUserId());
        }

        Phpfox::getService('core.redirect')->check($aListing['title'], 'req4');
        if (Phpfox::isModule('privacy')) {
            Phpfox::getService('privacy')->check('advancedmarketplace', $aListing['listing_id'], $aListing['user_id'], $aListing['privacy'], $aListing['is_friend']);
        }

        $aFollower = phpfox::getLib('database')->select('*')
                ->from(phpfox::getT('advancedmarketplace_follow'))
                ->where('user_id = ' . $aListing['user_id'] . ' and  user_follow_id = ' . phpfox::getUserId())
                ->execute('getSlaveRow');
        $bFollow = 1;
        if (!empty($aFollower)) {
            $bFollow = 0;
        }

        $this->setParam('aRatingCallback', array(
            'type' => 'user',
            'default_rating' => $aListing['total_score'],
            'item_id' => $aListing['user_id'],
            'stars' => range(1, 10)
                )
        );

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
            'feed_link' => $this->url()->permalink('advancedmarketplace', $aListing['listing_id'], $aListing['title']),
            'feed_title' => $aListing['title'],
            'feed_display' => 'view',
            'feed_total_like' => $aListing['total_like'],
            'report_module' => 'advancedmarketplace',
            'report_phrase' => Phpfox::getPhrase('advancedmarketplace.report_this_listing_lowercase')
                )
        );

        $this->template()->setTitle($aListing['title'] . ($aListing['view_id'] == '2' ? ' (' . Phpfox::getPhrase('advancedmarketplace.sold') . ')' : ''))
                ->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.advancedmarketplace'), $this->url()->makeUrl('advancedmarketplace'))
                ->setMeta('description', $aListing['description'])
                ->setMeta('keywords', $this->template()->getKeywords($aListing['title'] . $aListing['description']))
                ->setMeta('og:image', Phpfox::getService('advancedmarketplace.helper')->display(array(
                            'server_id' => $aListing['listing_id'],
                            'source' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/" . PHPFOX::getService("advancedmarketplace")->proccessImageName($aListing["image_path"], "_120"),
                            'return_url' => true,
                            "max_width" => "",
                            "max_height" => "",
                                )
                        )
                )
                ->setBreadcrumb($aListing['title'] . ($aListing['view_id'] == '2' ? ' (' . Phpfox::getPhrase('advancedmarketplace.sold') . ')' : ''), $this->url()->permalink('advancedmarketplace', $aListing['listing_id'], $aListing['title']), true)
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
                        )
                )
                ->setEditor(array(
                    'load' => 'simple'
                        )
                )
                ->assign(array(
                    'aListing' => $aListing,
                    'iFollower' => phpfox::getUserId(),
                    'bFollow' => $bFollow,
                    'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
                        )
        );

        if (Phpfox::isModule('rate')) {
            $this->template()
                    ->setPhrase(array(
                        'rate.thanks_for_rating'
                            )
                    )
                    ->setHeader(array(
                        'rate.js' => 'module_rate',
                        '<script type="text/javascript">$Behavior.rateMarketplaceUser = function() {$Core.rate.init({display: true}); }</script>',
                        'view.js' => 'module_advancedmarketplace'
                            )
            );
        }
		$iPage = 0;
		$iSize = 2;
		List($iCount, $aRating) = PHPFOX::getService("advancedmarketplace")->frontend_getListingReview($iListingId, $iSize, $iPage);

		$this->template()->assign(array(
			"aRating" => $aRating,
			"iCount" => $iCount,
			"iPage" => $iPage,
			"iSize" => $iSize
		));

        //increase view count
        //PHPFOX::getService("advancedmarketplace.process")->updateViewCounter($iListingId);
        PHPFOX::getService("advancedmarketplace.process")->updateRecentView($iListingId);

        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_view_process_end')) ? eval($sPlugin) : false);
    }

}

?>