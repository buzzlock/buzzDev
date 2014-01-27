<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_View extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {

        // check if form invite is submit here , get value continue after take this detail campaign
	// remove later
        if ($this->request()->getArray('val')) {
            $aVals = $this->request()->getArray('val');
        }

		if ($this->request()->getInt('id')) {
			return Phpfox::getLib('module')->setController('error.404');
		}

		if (Phpfox::isUser() && Phpfox::isModule('notification')) {
			Phpfox::getService('notification.process')->delete('comment_fundraising', $this->request()->getInt('req2'), Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('fundraising_notice_follower', $this->request()->getInt('req2'), Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('fundraising_invited', $this->request()->getInt('req2'), Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('fundraising_like', $this->request()->getInt('req2'), Phpfox::getUserId());
		}

		
		$aCallback = $this->getParam('aCallback', false);

		$iCampaignId = $this->request()->getInt(($aCallback !== false ? $aCallback['request'] : 'req2'));


		Phpfox::getService('fundraising.campaign.process')->checkAndUpdateStatusOfACampaign($iCampaignId);

		if(!Phpfox::getService('fundraising.permission')->canViewBrowseCampaign($iCampaignId, Phpfox::getUserId()))
		{
			$this->url()->send('fundraising.error', array('status' => Phpfox::getService('fundraising')->getErrorStatusNumber('invalid_permission')));
		}

		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_view_process_start')) ? eval($sPlugin) : false);

		$bIsProfile = $this->getParam('bIsProfile');
		if ($bIsProfile === true) {
			$this->setParam(array(
				'bViewProfileFundraising' => true,
				'sTagType' => 'fundraising'
					)
			);
		}

		$aItem = Phpfox::getService('fundraising.campaign')->callback($aCallback)->getCampaignById($iCampaignId);
		$aItem = Phpfox::getService('fundraising.campaign')->retrieveMoreInfoFromCampaign($aItem,  $bRetrievePermission = true);

        //begin invite friend after get this detail campaign
        if (isset($aVals['submit_invite'])) {
            Phpfox::getService('fundraising.campaign.process')->inviteFriends($aVals, $aItem);
        }

		Phpfox::getService('fundraising.campaign.process')->updateViewCounter($aItem['campaign_id']);

		if (!isset($aItem['campaign_id'])) {
			return Phpfox_Error::display(Phpfox::getPhrase('fundraising.fundraising_not_found'));
		}


		if (Phpfox::getUserId() == $aItem['user_id'] && Phpfox::isModule('notification')) {
			Phpfox::getService('notification.process')->delete('fundraising_approved', $this->request()->getInt('req2'), Phpfox::getUserId());
		}

//		Phpfox::getService('core.redirect')->check($aItem['title']);

		if (Phpfox::isModule('privacy')) {
			Phpfox::getService('privacy')->check('fundraising', $aItem['campaign_id'], $aItem['user_id'], $aItem['privacy'], $aItem['is_friend']);
		}

		if (!Phpfox::getUserParam('fundraising.can_approve_campaigns')) {
			if ($aItem['is_approved'] != '1' && $aItem['user_id'] != Phpfox::getUserId()) {
				return Phpfox_Error::display(Phpfox::getPhrase('fundraising.fundraising_not_found'));
			}
		}
//@todo: later
//		if (Phpfox::isModule('track') && Phpfox::isUser() && Phpfox::getUserId() != $aItem['user_id'] && !$aItem['is_viewed']) {
//			Phpfox::getService('track.process')->add('fundraising', $aItem['campaign_id']);
//			Phpfox::getService('fundraising.process')->updateView($aItem['campaign_id']);
//		}
//
//		if (Phpfox::isUser() && Phpfox::isModule('track') && Phpfox::getUserId() != $aItem['user_id'] && $aItem['is_viewed'] && !Phpfox::getUserBy('is_invisible')) {
//			Phpfox::getService('track.process')->update('fundraising_track', $aItem['campaign_id']);
//		}

		// Define params for "review views" block
		$this->setParam(array(
			'sTrackType' => 'fundraising',
			'iTrackId' => $aItem['campaign_id'],
			'iTrackUserId' => $aItem['user_id']
				)
		);

		$aCategories = Phpfox::getService('fundraising.category')->getCategoriesByCampaignId($aItem['campaign_id']);
		$aLastCategory = array();
		if($aCategories)
		{
			$aLastCategory = end($aCategories);
			$aItem['info'] = Phpfox::getPhrase('fundraising.posted_x_by_x_in_x', array('date' => Phpfox::getTime(Phpfox::getParam('fundraising.fundraising_time_stamp'), $aItem['time_stamp']), 'link' => Phpfox::getLib('url')->makeUrl('profile', array($aItem['user_name'])), 'user' => $aItem, 'categories' => $aLastCategory[0]));
		}
		else
		{
			$aItem['info'] = Phpfox::getPhrase('fundraising.posted_x_by_x', array('date' => Phpfox::getTime(Phpfox::getParam('fundraising.fundraising_time_stamp'), $aItem['time_stamp']), 'link' => Phpfox::getLib('url')->makeUrl('profile', array($aItem['user_name'])), 'user' => $aItem));	
		}

		

		$aItem['bookmark_url'] = Phpfox::permalink('fundraising', $aItem['campaign_id'], $aItem['title']);

		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_view_process_middle')) ? eval($sPlugin) : false);

		// Add tags to meta keywords
		if (!empty($aItem['tag_list']) && $aItem['tag_list'] && Phpfox::isModule('tag')) {
			$this->template()->setMeta('keywords', Phpfox::getService('tag')->getKeywords($aItem['tag_list']));
		}

		$this->setParam('aRatingCallback', array(
			'type' => 'fundraising',
			'total_rating' => Phpfox::getPhrase('fundraising.total_rating_ratings', array('total_rating' => $aItem['total_rating'])),
			'default_rating' => $aItem['total_score'],
			'item_id' => $aItem['campaign_id'],
			'stars' => array(
				'2' => Phpfox::getPhrase('fundraising.poor'),
				'4' => Phpfox::getPhrase('fundraising.nothing_special'),
				'6' => Phpfox::getPhrase('fundraising.worth_donating'),
				'8' => Phpfox::getPhrase('fundraising.pretty_cool'),
				'10' => Phpfox::getPhrase('fundraising.awesome')
			)
				)
		);


		$this->setParam('aFeed', array(
			'comment_type_id' => 'fundraising',
			'privacy' => $aItem['privacy'],
			'comment_privacy' => $aItem['privacy_comment'],
			'like_type_id' => 'fundraising',
			'feed_is_liked' => isset($aItem['is_liked']) ? $aItem['is_liked'] : false,
			'feed_is_friend' => $aItem['is_friend'],
			'item_id' => $aItem['campaign_id'],
			'user_id' => $aItem['user_id'],
			'total_comment' => $aItem['total_comment'],
			'total_like' => $aItem['total_like'],
			'feed_link' => $aItem['bookmark_url'],
			'feed_title' => $aItem['title'],
			'feed_display' => 'view',
			'feed_total_like' => $aItem['total_like'],
			'report_module' => 'fundraising',
			'report_phrase' => Phpfox::getPhrase('fundraising.report_this_fundraising'),
			'time_stamp' => $aItem['time_stamp']
				)
		);


		if ($aItem['module_id'] != 'fundraising' && ($aCallback = Phpfox::callback('fundraising.getFundraisingDetails', array('item_id' => $aItem['item_id'])))) {
			$this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
			$this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);
		}
			
		$this->setParam('aFrCampaign', $aItem);
		$this->template()->setTitle($aItem['title'])
				->setBreadCrumb(Phpfox::getPhrase('fundraising.fundraisings'), $aItem['module_id'] == 'fundraising' ? $this->url()->makeUrl('fundraising') : $this->url()->permalink('pages', $aItem['item_id'], 'fundraising') )
				->setBreadCrumb($aItem['title'], $this->url()->permalink('fundraising', $aItem['campaign_id'], $aItem['title']), true)
				->setMeta('description', $aItem['title'] . '.')
				->setMeta('description', $aItem['description'] . '.')
				->setMeta('description', $aItem['info'] . '.')
				->setMeta('keywords', $this->template()->getKeywords($aItem['title']))
				->setMeta('keywords', Phpfox::getParam('fundraising.fundraising_meta_keywords'))
				->setMeta('description', Phpfox::getParam('fundraising.fundraising_meta_description'))
				->assign(array(
					'aCampaign' => $aItem,
					'bFundraisingView' => true,
					'bIsProfile' => $bIsProfile,
					'sTagType' => ($bIsProfile === true ? 'fundraising_profile' : 'fundraising'),
					'corepath' => Phpfox::getParam('core.path'),
					'aCampaignStatus' => Phpfox::getService('fundraising.campaign')->getAllStatus(),
					'aLastCategory' => $aLastCategory
						)
				)->setHeader('cache', array(
					'jquery/plugin/jquery.highlightFade.js' => 'static_script',
					'jquery/plugin/jquery.scrollTo.js' => 'static_script',
					'quick_edit.js' => 'static_script',
					'switch_menu.js' => 'static_script',
					'comment.css' => 'style_css',
					'pager.css' => 'style_css',
					'feed.js' => 'module_feed',
					'pager.css' => 'style_css',
//					'map.js' => 'module_fundraising',
				)
		);

		$this->template()->setHeader(
				array(
					'global.css' => 'module_fundraising',
					'ynfundraising.css' => 'module_fundraising',
					'view.css' => 'module_fundraising',
					'jquery.rating.css' => 'style_css',
					'jquery/plugin/star/jquery.rating.js' => 'static_script',
					'rate.js' => 'module_rate',
					'ynfundraising.js' => 'module_fundraising',
					'homepageslider/slides.min.jquery.js' => 'module_fundraising',
				)
		);

		 //to make facebook know the image
            $sImageUrl = str_replace('%s', '_240',  Phpfox::getParam('core.path') . 'file' . PHPFOX_DS . 'pic' . PHPFOX_DS .  $aItem['image_path']);
            $this->template()->setHeader(array('<meta property="og:image" content="'. $sImageUrl . '" />'));
            $this->template()->setHeader(array('<link rel="image_src" href="'. $sImageUrl . '" />'));

			
		$aFrRatingParams = array();

		if (Phpfox::isModule('rate'))
		{
			if($aItem['status'] != Phpfox::getService('fundraising.campaign')->getStatusCode('ongoing') || !Phpfox::getUserParam('fundraising.can_rate_campaign'))
			{
				$aFrRatingParams = array(
					'display' => 'false',
					'error_message' => Phpfox::getPhrase('fundraising.can_not_rate_this_campaign')
				);
			}
			else if(!Phpfox::isUser())
			{
				$aFrRatingParams = array(
					'display' => 'false',
					'error_message' => Phpfox::getPhrase('fundraising.you_must_login_to_rate')
				);
			}
			else if($aItem['user_id'] == Phpfox::getUserId()){
				$aFrRatingParams = array(
					'display' => ($aItem['user_id'] == Phpfox::getUserId() ? 'false' : 'true'),
					'error_message' => Phpfox::getPhrase('fundraising.you_cannot_rate_your_own_campaign')
				);		
			}
			else if(!$aItem['has_donated']) {
				$aFrRatingParams = array(
					'display' => 'false',
					'error_message' => Phpfox::getPhrase('fundraising.you_need_to_donate_to_this_campaign_before_rating')
				);	
			}
			else if($aItem['has_rated']) {
				$aFrRatingParams = array(
					'display' => 'false',
					'error_message' => Phpfox::getPhrase('fundraising.you_have_already_voted')
				);	
			}
			else
			{
				$aFrRatingParams = array(
					'display' => 'true',
					'error_message' => ''
				);	
				
			}
		}	

		$this->template()->assign(array('aFrRatingParams' => $aFrRatingParams));

        $this->template()->setPhrase(array(
            'fundraising.select_all',
            'fundraising.un_select_all',
        ));

		if (Phpfox::getUserId()) {
			$this->template()->setEditor(array(
				'load' => 'simple',
				'wysiwyg' => ((Phpfox::isModule('comment') && Phpfox::getParam('comment.wysiwyg_comments')) && Phpfox::getUserParam('comment.wysiwyg_on_comments'))
					)
			);
		}



		if ($this->request()->get('req4') == 'comment') {
			$this->template()->setHeader('<script type="text/javascript">var $bScrollToFundraisingComment = false; $Behavior.scrollToFundraisingComment = function () { if ($bScrollToFundraisingComment) { return; } $bScrollToFundraisingComment = true; if ($(\'#js_feed_comment_pager_' . $aItem['campaign_id'] . '\').length > 0) { $.scrollTo(\'#js_feed_comment_pager_' . $aItem['campaign_id'] . '\', 800); } }</script>');
		}

		if ($this->request()->get('req4') == 'add-comment') {
			$this->template()->setHeader('<script type="text/javascript">var $bScrollToFundraisingComment = false; $Behavior.scrollToFundraisingComment = function () { if ($bScrollToFundraisingComment) { return; } $bScrollToFundraisingComment = true; if ($(\'#js_feed_comment_form_' . $aItem['campaign_id'] . '\').length > 0) { $.scrollTo(\'#js_feed_comment_form_' . $aItem['campaign_id'] . '\', 800); $Core.commentFeedTextareaClick($(\'.js_comment_feed_textarea\')); $(\'.js_comment_feed_textarea\').focus(); } }</script>');
		}

		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_view_process_end')) ? eval($sPlugin) : false);
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean() {
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_view_clean')) ? eval($sPlugin) : false);
	}

}

?>