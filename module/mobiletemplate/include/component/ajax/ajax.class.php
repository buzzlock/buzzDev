<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
$sPathFunctions = PHPFOX_DIR . 'module' . PHPFOX_DS . 'mobiletemplate' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'extras' . PHPFOX_DS . 'functions.php';

if (file_exists($sPathFunctions))
{
        require_once($sPathFunctions);
}

class MobileTemplate_Component_Ajax_Ajax extends Phpfox_Ajax
{
    public function updateMTActiveThemeStyleStatus()
    {
    	$active = $this->get('active');
		$styleID = $this->get('id');
		Phpfox::getService('mobiletemplate.process')->deleteAllMTActiveThemeStyle();
		if($active == 1){
			$aActiveStyle = Phpfox::getService('mobiletemplate')->getStyle($styleID);
			if(isset($aActiveStyle) && isset($aActiveStyle['style_id'])){
				Phpfox::getService('mobiletemplate.process')->insertMTActiveThemeStyle($aActiveStyle);	
			}			
		}
    }
	
    public function updateMobileCustomStyleStatus()
    {
    	$active = $this->get('active');
		$styleID = $this->get('id');
		
		Phpfox::getService('mobiletemplate.process')->updateMobileCustomStyleStatus($styleID, $active);
    }
	
	public function initCheckIn()
	{
		Phpfox::isUser(true);
		//      init
		$type = $this -> get('type');
		$status = $this -> get('status');
		//      process
		Phpfox::getBlock('mobiletemplate.checkin');
		
		$aUserLocation = Phpfox::getUserBy('location_latlng');
		if (!empty($aUserLocation))
		{
			// $aVisitorLocation = json_decode($aUserLocation, true);
		}
		//      end
		echo json_encode(array(
			'result' => "SUCCESS",
			'sIPInfoDbKey' => Phpfox::getParam('core.ip_infodb_api_key'), 
			'sGoogleKey' => Phpfox::getParam('core.google_api_key'),
			'visitorLocationLat' => isset($aVisitorLocation) ? $aVisitorLocation['latitude'] : '',
			'visitorLocationLong' => isset($aVisitorLocation) ? $aVisitorLocation['longitude'] : '',
			'status' => $status,
			'content' => $this->getContent(false)
		));
	}
	
    public function loadEstablishments()
    {
		$aPages = array();
		if (Phpfox::isModule('pages'))
		{
			$aPages = Phpfox::getService('mobiletemplate')->getPagesByLocation( $this->get('latitude'), $this->get('longitude'), $this->get('keyword') );
		}
		
		if (count($aPages))
		{
			foreach ($aPages as $iKey => $aPage)
			{
				$aPages[$iKey]['geometry'] = array('latitude' => $aPage['location_latitude'], 'longitude' => $aPage['location_longitude']);
				$aPages[$iKey]['name'] = $aPage['title'];
				
				$photoFullPath = Phpfox::getLib('phpfox.image.helper')->display(array(
				    'server_id' => $aPages[$iKey]['image_server_id']
				    , 'title' => $aPages[$iKey]['title']
				    , 'path' => 'pages.url_image'
				    , 'file' => $aPages[$iKey]['image_path']
				    , 'suffix' => '_50_square'
				    , 'max_width' => '50'
				    , 'max_height' => '50'
				    , 'is_page_image' => '1'
					, 'return_url' => true 
				));
				$aPages[$iKey]['photoFullPath'] = $photoFullPath;
				
				unset($aPages[$iKey]['location_latitude']);
				unset($aPages[$iKey]['location_longitude']);	
			}
		}
		
		if (!empty($aPages))
		{
			if($this->get('callJS') && $this->get('callJS') == 'no'){
				echo json_encode(array(
					'result' => "SUCCESS",
					'sCallback' => $this->get('sCallback'), 
					'aPages' => $aPages
				));
			} else {
				$jPages = json_encode($aPages);
				$this->call('ynmtPlaces.storePlaces(\'' . $jPages .'\', \'1\');');
			}
		} else {
			if($this->get('callJS') && $this->get('callJS') == 'no'){
				echo json_encode(array(
					'result' => "SUCCESS",
					'sCallback' => $this->get('sCallback')  
				));
			}
		}
	}

    public function getStatusBlock()
    {
		$aUserLocation = Phpfox::getUserBy('location_latlng');
		if (!empty($aUserLocation))
		{
			// $aVisitorLocation = json_decode($aUserLocation, true);
		}
        Phpfox::getBlock('mobiletemplate.status');
        echo json_encode(array(
            'content' => $this->getContent(false), 
			'sIPInfoDbKey' => Phpfox::getParam('core.ip_infodb_api_key'), 
			'sGoogleKey' => Phpfox::getParam('core.google_api_key'),
			'visitorLocationLat' => isset($aVisitorLocation) ? $aVisitorLocation['latitude'] : '',
			'visitorLocationLong' => isset($aVisitorLocation) ? $aVisitorLocation['longitude'] : '',
            'result' => "SUCCESS"
        ));
    }
	
    public function getRequests()
    {
        if (!Phpfox::isUser())
        {
            $this->call('<script type="text/javascript">window.location.href = \'' . Phpfox::getLib('url')->makeUrl('user.login') . '\';</script>');
        }
        else
        {
            $_REQUEST['js_mobile_version']= true;
            Phpfox::getBlock('mobiletemplate.accept');
        }
	}
	
    public function getLatest()
    {
        if (!Phpfox::isUser())
        {
            $this->call('<script type="text/javascript">window.location.href = \'' . Phpfox::getLib('url')->makeUrl('user.login') . '\';</script>');
        }
        else
        {
            $_REQUEST['js_mobile_version']= true;
            Phpfox::getBlock('mobiletemplate.latest');
        }
	}
	
    public function getAll()
    {
        if (!Phpfox::isUser())
        {
            $this->call('<script type="text/javascript">window.location.href = \'' . Phpfox::getLib('url')->makeUrl('user.login') . '\';</script>');
        }
        else
        {
            $_REQUEST['js_mobile_version']= true;
            Phpfox::getBlock('mobiletemplate.link');
        }
    }
	
	public function likeAdd(){
		Phpfox::isUser(true);
        if (Phpfox::getService('like')->hasBeenMarked(2, $this->get('type_id'), $this->get('item_id')))
        {
			
            $this->removeAction();
        }
		if (Phpfox::getService('like.process')->add($this->get('type_id'), $this->get('item_id')))
		{
			if ($this->get('type_id') == 'feed_mini' && $this->get('custom_inline'))
			{
				$this->_loadCommentLikes();
			}
			else
			{
				/* When clicking "Like" from the Feed */
				$this->_loadLikes(true);
				if (!$this->get('counterholder'))
				{
				 //   $this->call('$("#js_like_body_'. $this->get('item_id') . '").parents().map( function() { $(this).show(); });');
				}
			}
			if (!$this->get('counterholder'))
			{
			    $this->call('window.location.href = window.location.href;');
			}
		}		
	}
	
	public function removeAction()
	{
	    $sTypeId = $this->get('type_id');
	    $sModuleId = $this->get('module_name');
	    // $sDeleteAction = $this->get('action_type_id');// for now dislike is the only available and = 2
	    
	    if (empty($sTypeId))
	    {
			$sTypeId = $this->get('like_type_id');
		}
	    
	    if (empty($sModuleId) && !empty($sTypeId))
	    {
            $this->set('module_name', $sTypeId);
            $sModuleId = $sTypeId;
	    }
	    if (empty($sTypeId) && $this->get('item_type_id') != '')
	    {
			$this->set('type_id', $this->get('item_type_id'));
			$sTypeId = $this->get('item_type_id');
		}
		
		// its not decrementing the total_dislike column
		
	    if (Phpfox::getService('like.process')->removeAction( 2, $sTypeId, $this->get('item_id'), $sModuleId ))
	    {
			if ($this->get('type_id') == 'feed_mini' || $this->get('item_type_id') == 'feed_mini')// && $this->get('custom_inline'))
			{
				$this->_loadCommentLikes(true);
			}
			else
			{
				$bIsLiked = Phpfox::getService('like')->didILike($sTypeId, $this->get('item_id'));
				$this->_loadLikes($bIsLiked);
			}
        }
    }

	private function _loadCommentLikes($bIsDislike = false)
	{
		if ($bIsDislike == true)
		{
			// get the total dislikes
			// $iDislikes = Phpfox::getService('like')->getDislikes($this->get('item_type_id'), $this->get('item_type_id'), true);
			$aComment = Phpfox::getService('comment')->getComment($this->get('item_id'));
			$iDislikes = $aComment['total_dislike'];
			$sCall = '$("#js_comment_' . $this->get('item_id') . '").find(".comment_mini_action:first").find(".js_dislike_link_holder").show();';
			
			if ($iDislikes > 1)
			{
				$sPhrase = Phpfox::getPhrase('like.total_people', array('total' => $iDislikes));				
			}
			else if ($iDislikes > 0)
			{
				$sPhrase = Phpfox::getPhrase('like.1_person');				
			}
			else
			{
				$sCall = '$(\'#js_comment_' . $this->get('item_id') . '\').find(\'.comment_mini_action:first\').find(\'.js_dislike_link_holder\').hide();';
				$sPhrase = '0';
			}
			$sCall .= '$("#js_dislike_mini_a_'. $this->get('item_id') .'").html("'. $sPhrase .'");';
			$this->call($sCall);
		}
		else
		{
			$aComment = Phpfox::getService('comment')->getComment($this->get('item_id'));
			if ($this->get('counterholder'))
			{
				$this->call('$("#' . $this->get('counterholder') . '_counter_' . $this->get('item_id') . '").html(' . $aComment['total_like'] . ');');
				return;
			}
			if ($aComment['total_like'] > 0)
			{
				$sPhrase = Phpfox::getPhrase('like.1_person');
				if ($aComment['total_like'] > 1)
				{
					$sPhrase = Phpfox::getPhrase('like.total_people', array('total' => $aComment['total_like']));
				}
				$this->call('$(\'#js_comment_' . $this->get('item_id') . '\').find(\'.comment_mini_action:first\').find(\'.js_like_link_holder\').show();');
				$this->call('$(\'#js_comment_' . $this->get('item_id') . '\').find(\'.comment_mini_action:first\').find(\'.js_like_link_holder_info\').html(\'' . $sPhrase . '\');');
			}
			else 
			{
				$this->call('$(\'#js_comment_' . $this->get('item_id') . '\').find(\'.comment_mini_action:first\').find(\'.js_like_link_holder\').hide();');
			}
		}
	}
	
	private function _loadLikes($bIsLiked)
	{
		if(Phpfox::VERSION < '3.6.0'){
			return $this->_loadLikes350($bIsLiked);
		}
		
		$sType = $this->get('type_id');
		if (empty($sType))
		{
			$sType = $this->get('item_type_id');
		}
		
		if (Phpfox::getParam('like.show_user_photos'))
		{
			// The block like.block.display works very different if this setting is enabled
			$aLikes = Phpfox::getService('like')->getLikes($sType, $this->get('item_id'));
			
			// The dislikes are fetched and displayed from the template
			$aFeed = array(
				'like_type_id' => $sType,
				'item_id' => $this->get('item_id'),
				'likes' => $aLikes,
				'feed_total_like' => count($aLikes),
				'call_displayactions' => true,
				'feed_id' => $this->get('parent_id')
			);			
		}
		else
		{
			// We get the dislikes and likes and the template only displays them
			$aFeed = Phpfox::getService('like')->getAll( $sType, $this->get('item_id') );
			
			// Fix for likes
			$aFeed['feed_like_phrase'] = $aFeed['likes']['phrase'];
			$aFeed['feed_id'] = $this->get('parent_id');
			
			// Fix for dislikes
			$aFeed['call_displayactions'] = true;
			$aFeed['type_id'] = $this->get('type_id');
			$aFeed['dislike_phrase'] = $aFeed['dislikes']['phrase'];		
		}
		
		$this->template()->assign(array('aFeed' => $aFeed));
		$this->template()->getTemplate('like.block.display');
		$sId = $this->get('item_id');
		$sParentId = str_replace('js_feed_like_holder','', $this->get('parent_id'));
		
		$sContent = $this->getContent(false);
		$sContent = str_replace("'", "\'", $sContent);

		$sType = str_replace('-', '_', $sType);		
		
		$sCall = ' $("#js_feed_like_holder_' . $sType . '_' . $sId . '").find(\'.js_comment_like_holder:first\').html(\'' . $sContent . '\');';
		$this->call($sCall);
				
		$this->call('$("#js_feed_like_holder_' . $sType . '_' . $sId . '").show();');
		
		if (Phpfox::getParam('photo.show_info_on_mouseover') && $this->get('item_type_id') == 'photo' && $this->get('item_id') > 0)
		{
			$iTotal = 0;
			if (isset($aFeed['feed_total_like']))
			{
				$iTotal = $aFeed['feed_total_like'];
			}
			else if (isset($aFeed['likes']['total']))
			{
				$iTotal = $aFeed['likes']['total'];
			}
			$this->call('$("#js_like_counter_' . $this->get('item_id') . '").html('. $iTotal .');');
		}
	}
	
	private function _loadLikes350($bIsLiked)
	{
		$aLikes = Phpfox::getService('like')->getLikesForFeed($this->get('type_id'), $this->get('item_id'), $bIsLiked, Phpfox::getParam('feed.total_likes_to_display'), true);
		if ($this->get('counterholder'))
		{
		    $this->call('$("#' . $this->get('counterholder') . '_counter_' . $this->get('item_id') . '").html(' . Phpfox::getService('like')->getTotalLikes() . ');');
		    return;
		}
		if (!Phpfox::getService('like')->getTotalLikes() && !Phpfox::getService('like')->hasBeenMarked(2, $this->get('type_id'), $this->get('item_id')))
		{
			$sId = '#js_like_body_' . str_replace('js_feed_like_holder_', '', $this->get('parent_id'));
			$this->html($sId, '');
			$this->call('$("'. $sId .'").parents(".comment_mini_content_holder").hide();');			
			return;
		}
		$this->template()->assign(array(
				'aFeed' => array(
					'feed_is_liked' => $bIsLiked,
					'feed_total_like' => Phpfox::getService('like')->getTotalLikes(),
					'like_type_id' => $this->get('type_id'),
					'item_id' => $this->get('item_id'),
					'likes' => $aLikes,
					'call_displayactions' => true
				)
			)			
		);
			
		$this->template()->getTemplate('like.block.display');				
		
		if (Phpfox::getService('like')->hasBeenMarked(2, $this->get('type_id'), $this->get('item_id')))
		{
            $this->call('$(".activity_like_holder").remove();');
		    $this->call('$("#js_like_body_' . str_replace('js_feed_like_holder_', '', $this->get('parent_id')) . ' .display_actions").before(\'' . $this->getContent(false) . '\');');
		}
		else
		{
		    $this->html('#js_like_body_' . str_replace('js_feed_like_holder_', '', $this->get('parent_id')), $this->getContent(false));
		}
		$this->call('$(\'#js_like_body_' . str_replace('js_feed_like_holder_', '', $this->get('parent_id')) . '\').parents(\'.comment_mini_content_holder:first\').show();');		
        $this->call('$(\'#js_like_body_' . str_replace('js_feed_like_holder_', '', $this->get('parent_id')) . '\').parents(\'.comment_mini_content_holder:first\').find(\'.comment_mini_content_holder_icon\').show();');		
        // $('#js_like_body_2').parents('.comment_mini_content_holder:first').find('.comment_mini_content_holder_icon').show();
	}	

	public function getSharePost(){
		Phpfox::isUser(true);
		//      init
		
		//      process
		Phpfox::getBlock('mobiletemplate.shareframepost', array(
				'type' => $this->get('type'),
				'url' => $this->get('url'),
				'title' => $this->get('title')
			)
		);
		
		//      end
		echo json_encode(array(
			'result' => "SUCCESS",
			'content' => $this->getContent(false)
		));		
	}
	
	public function feedshare(){
		$aPost = $this->get('val');		
		
		if ($aPost['post_type'] == '2')
		{
			if (!isset($aPost['friends']) || (isset($aPost['friends']) && !count($aPost['friends'])))
			{
				$this->call('alert(\''. str_replace("'", "\\'", Phpfox::getPhrase('mobiletemplate.select_friend_to_share')) . '\');');
			}
			else
			{
				$iCnt = 0;
				foreach ($aPost['friends'] as $iFriendId)
				{
					$aVals = array(
						'user_status' => $aPost['post_content'],
						'parent_user_id' => $iFriendId,
						'parent_feed_id' => $aPost['parent_feed_id'],
						'parent_module_id' => $aPost['parent_module_id']
					);
					
					if (Phpfox::getService('user.privacy')->hasAccess($iFriendId, 'feed.share_on_wall') && Phpfox::getUserParam('profile.can_post_comment_on_profile'))
					{	
						$iCnt++;
						
						Phpfox::getService('feed.process')->addComment($aVals);
					}				
				}			

				$sMessage = str_replace("'", "\\'", Phpfox::getPhrase('feed.successfully_shared_this_item_on_your_friends_wall'));
				if (!$iCnt)
				{
					$sMessage = str_replace("'", "\\'", Phpfox::getPhrase('user.unable_to_share_this_post_due_to_privacy_settings'));
				}
				$this->call('alert(\''. $sMessage . '\'); ynmtMobileTemplate.cancelStatusShareFromHomepage();');
				if ($iCnt)
				{
					//$this->call('setTimeout(\'tb_remove();\', 2000);');
				}
			}
			
			return;
		}
		
		$aVals = array(
			'user_status' => $aPost['post_content'],
			'privacy' => '0',
			'privacy_comment' => '0',
			'parent_feed_id' => $aPost['parent_feed_id'],
			'parent_module_id' => $aPost['parent_module_id']
		);		
		
		if (($iId = Phpfox::getService('user.process')->updateStatus($aVals)))
		{
			$this->call('alert(\''. str_replace("'", "\\'", Phpfox::getPhrase('feed.successfully_shared_this_item')) . '\'); ynmtMobileTemplate.cancelStatusShareFromHomepage();');
		}		
	}

    public function updateMenuNavigationOrdering()
    {
        $aVals = $this->get('val');
        Phpfox::getService('mobiletemplate.process')->updateMenuNavigationOrdering($aVals['ordering']);
    }

    public function updateMenuNavigationStatus()
    {
        Phpfox::getService('mobiletemplate.process')->updateMenuNavigationStatus($this->get('id'), $this->get('active'));
    }

	public function photoEditPhoto(){
		Phpfox::isUser(true);
		//      init
		
		//      process
		Phpfox::isUser(true);

		if (Phpfox::getService('user.auth')->hasAccess('photo', 'photo_id', $this->get('photo_id'), 'photo.can_edit_own_photo', 'photo.can_edit_other_photo'))
		{
	    	Phpfox::getBlock('photo.edit-photo', array('ajax_photo_id' => $this->get('photo_id')));
	    	// $this->setTitle(Phpfox::getPhrase('photo.editing_photo'));
	    	// $this->call('<script type="text/javascript">$Core.loadInit();</script>');

			echo json_encode(array(
				'result' => "SUCCESS",
				'content' => $this->getContent(false)
			));		
		} else {
			echo json_encode(array(
				'result' => "INVALID_PERMISSION",
				'msg' => Phpfox::getPhrase('mobiletemplate.txt_do_not_have_permission_for_action')
			));					
		}

		//      end
	}



}
?>