<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */

define('PHPFOX_IS_EVENT_VIEW', true);

class Fevent_Component_Controller_View extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		
		if ($this->request()->get('req2') == 'view' && ($sLegacyTitle = $this->request()->get('req3')) && !empty($sLegacyTitle))
		{				
			Phpfox::getService('core')->getLegacyItem(array(
					'field' => array('event_id', 'title'),
					'table' => 'fevent',		
					'redirect' => 'fevent',
					'title' => $sLegacyTitle
				)
			);
		}		
		
		Phpfox::getUserParam('fevent.can_access_event', true);		
		
		$sEvent = $this->request()->get('req2');
		
			if (Phpfox::isUser() && Phpfox::isModule('notification'))
		{
			
			if ($this->request()->getInt('comment-id'))
			{
				Phpfox::getService('notification.process')->delete('fevent_comment', $this->request()->getInt('comment-id'), Phpfox::getUserId());
				Phpfox::getService('notification.process')->delete('fevent_comment_feed', $this->request()->getInt('comment-id'), Phpfox::getUserId());
				Phpfox::getService('notification.process')->delete('fevent_comment_like', $this->request()->getInt('comment-id'), Phpfox::getUserId());
				
			}
			if ($this->request()->getInt('comment'))
			{
				Phpfox::getService('notification.process')->delete('comment_fevent_tag', $this->request()->getInt('comment'), Phpfox::getUserId());
			}
			Phpfox::getService('notification.process')->delete('fevent_like', $this->request()->getInt('req2'), Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('fevent_invite', $this->request()->getInt('req2'), Phpfox::getUserId());
		}			
		
		if (!($aEvent = Phpfox::getService('fevent')->getEvent($sEvent)))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('fevent.the_event_you_are_looking_for_does_not_exist_or_has_been_removed'));
		}
		
		Phpfox::getService('core.redirect')->check($aEvent['title']);
		if (Phpfox::isModule('privacy'))
		{
			Phpfox::getService('privacy')->check('fevent', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend']);
		}
        
        Phpfox::getService('fevent.process')->updateView($aEvent['event_id']);
		
		$this->setParam('aEvent', $aEvent);
		
		$bCanPostComment = true;
		if (isset($aEvent['privacy_comment']) && $aEvent['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
		{
			switch ($aEvent['privacy_comment'])
			{
				case 1:					
					if (isset($aEvent['r']) && (int) $aEvent['r'] <= 0)
					{
						$bCanPostComment = false;						
					}
					break;
				case 2:
					if (isset($aEvent['r']) && (int) $aEvent['r'] > 0)
					{
						$bCanPostComment = true;
					}
					else 
					{
						if (!Phpfox::getService('friend')->isFriendOfFriend($aEvent['user_id']))
						{
							$bCanPostComment = false;	
						}
					}
					break;
				case 3:
					$bCanPostComment = false;
					break;
			}
		}
		
		$aCallback = false;
		if ($aEvent['item_id'] && Phpfox::hasCallback($aEvent['module_id'], 'viewEvent'))
		{
			$aCallback = Phpfox::callback($aEvent['module_id'] . '.viewEvent', $aEvent['item_id']);
			$aCallback['url_home_pages'] = $aCallback['url_home'] . 'fevent/when_upcoming';
			$this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
			$this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);			
			if ($aEvent['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'fevent.view_browse_events'))
			{
				return Phpfox_Error::display('Unable to view this item due to privacy settings.');
			}				
		}		
		
		
		$this->setParam('aFeedCallback', array(
				'module' => 'fevent',
				'table_prefix' => 'fevent_',
				'ajax_request' => 'fevent.addFeedComment',
				'item_id' => $aEvent['event_id'],
				'disable_share' => ($bCanPostComment ? false : true)
			)
		);
		
		if ($aEvent['view_id'] == '1')
		{
			$this->template()->setHeader('<script type="text/javascript">$Behavior.eventIsPending = function(){ $(\'#js_block_border_feed_display\').addClass(\'js_moderation_on\').hide(); }</script>');
		}
		
		if (Phpfox::getUserId() == $aEvent['user_id'])
		{
			if (Phpfox::isModule('notification'))
			{
				Phpfox::getService('notification.process')->delete('event_approved', $this->request()->getInt('req2'), Phpfox::getUserId());			
			}
			
			define('PHPFOX_FEED_CAN_DELETE', true);
		}
		
		$bCanViewMap = Phpfox::getUserParam('fevent.can_view_gmap');
		$content_repeat="";
		$until="";
		if($aEvent['isrepeat']==0)
		{
			$content_repeat=Phpfox::getPhrase('fevent.daily');
		}
		else if($aEvent['isrepeat']==1)
		{
			$content_repeat=Phpfox::getPhrase('fevent.weekly');
		}
		else if($aEvent['isrepeat']==2)
        {
			$content_repeat=Phpfox::getPhrase('fevent.monthly');
		}
		if($content_repeat!="")
		{
			if($aEvent['timerepeat']!=0)
			{
				$sDefault = null;
                $until = Phpfox::getTime("M j, Y", $aEvent['timerepeat']);
                $content_repeat .= ", " . Phpfox::getPhrase('fevent.until') . " " . $until;
			}
		}
        
        //add count down
        $seconds_taken = $aEvent['start_time'] - PHPFOX_TIME;
        if($seconds_taken > 0) {
            $aEvent['time_left'] = Phpfox::getService('fevent.browse')->seconds2string($seconds_taken);
		} else {
            $aEvent['time_left'] = '';
		}
			
		$this->template()->setTitle($aEvent['title'])
			->setMeta('description', $aEvent['description'])
			->setMeta('keywords', $this->template()->getKeywords($aEvent['title']))
			->setBreadcrumb(Phpfox::getPhrase('fevent.events'), ($aCallback === false ? $this->url()->makeUrl('fevent', 'when_upcoming') : $this->url()->makeUrl($aCallback['url_home_pages'])))
			->setBreadcrumb($aEvent['title'], $this->url()->permalink('fevent', $aEvent['event_id'], $aEvent['title']), true)
			->setEditor(array(
					'load' => 'simple'					
				)
			)
			->setHeader('cache', array(
					'jquery/plugin/jquery.highlightFade.js' => 'static_script',	
					'jquery/plugin/jquery.scrollTo.js' => 'static_script',
					'comment.css' => 'style_css',
                    'view.css' => 'module_fevent',
                    'view.js' => 'module_fevent',
					'pager.css' => 'style_css',
					'feed.js' => 'module_feed'
				)
			)
			->assign(array(
					'aEvent' => $aEvent,
					'content_repeat' => $content_repeat,
					'aCallback' => $aCallback,
					'bCanViewMap' => $bCanViewMap
				)
			);			
            //d($aEvent,true);exit;
           
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_controller_view_clean')) ? eval($sPlugin) : false);
	}
}

?>