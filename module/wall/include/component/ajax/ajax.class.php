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
 * @package         YouNet_Wall
 */
class Wall_Component_Ajax_Ajax extends Phpfox_Ajax
{

    public function getHideInfo()
    {
        return array(
            'feed_id' => $this->get("id"),
            'view_id' => $this->get("view"),
            'viewer_id' => $this->get("userId"),
            'owner_id' => $this->get("ownerId")
        );
    }

    public function hide()
    {
        $aVals = $this->getHideInfo();
        Phpfox::getService('wall.process')->setFeedVisibility(false, $aVals);
        echo("$('#row_hidden_feed_" . $aVals['feed_id'] . "').show();");
        echo("$('#js_item_feed_" . $aVals['feed_id'] . "').hide();");
    }
	
	public function getFriendsData()
	{
		$iCurUserId = Phpfox::getUserId();
		$aRows = array();
		$sCorePath = Phpfox::getParam('core.path');
		
    	$sUrlUser = Phpfox::getParam('core.url_user');
    	
		if ($iCurUserId)
		{
			// Friends list for js
			$aFriends = Phpfox::getService('friend') -> get(array(), 'friend.time_stamp DESC', '', 500, $bCount = false, true, false, $iCurUserId, false);
			foreach ($aFriends as $aFriend)
			{
				if ($aFriend['full_name'])
				{
					$text = $aFriend['full_name'];
				}
				else
				{
					$text = $aFriend['user_name'];
				}

				if ($aFriend['user_image'])
				{
					$photo = $sUrlUser . sprintf($aFriend['user_image'], '_50_square');
				}
				else
				{
					$photo = $sCorePath . "theme/frontend/default/style/default/image/noimage/profile_50.png";
				}

				$aRows[] = array(
					'id' => $aFriend['user_id'],
					'type' => 'user',
					'photo' => $photo,
                    'text' => html_entity_decode(Phpfox::getLib('parse.output')->split($text, 20), null, 'UTF-8'),
				);
			}
			unset($aFriends);
		}
		echo 'Typeahead.setData('.json_encode($aRows).');';
	}

    public function show()
    {
        $aVals = $this->getHideInfo();
        Phpfox::getService('wall.process')->setFeedVisibility(true, $aVals);
        echo("$('#row_hidden_feed_" . $aVals['feed_id'] . "').hide();");
        echo("$('#js_item_feed_" . $aVals['feed_id'] . "').show();");
    }

    public function filterFeed()
    {
        $aCore = $this->get('core');
        $sViewId = $this->get('viewId');
        //$this->call("alert('$sViewId');");
        $iLimit = null; //$this->get('limit');
        $iUserId = $aCore['is_user_profile'] ? $aCore['profile_user_id'] : null;
		$bIsFilter =  $this->get('is_filter');

        // Load new display with additional params
        Phpfox::getBlock('wall.display', array(
            "user_id" => $iUserId,
            "sViewId" => $sViewId,
            "iLimit" => $iLimit,
            'bIsViewMore'=>1,
            'bIsFilter'=>$bIsFilter,
        ));

        $sYear = $this->get('year');
		
		$this->remove('#feed_view_more');
		
		if (!$this->get('forceview') && !$this->get('resettimeline'))
		{
			$this->append('#js_feed_content', $this->getContent(false));
		}
		else
		{
			// $this->html('#js_timeline_year_holder_' . $sYear . '', $this->getContent(false));
			//$this->call('$.scrollTo(\'.timeline_left\', 800);');
			$this->html('#js_feed_content', $this->getContent(false));
		}
		//$this->call('feed_filter_success();');
		
		$this->call('$Core.loadInit();');
    }

    public function viewMore()
    {
        $this->filterFeed();
    }

    public function reloadActivityFeed()
    {
        $aParts = explode(',', $this->get('reload-ids'));
        $sViewId = $this->get('viewId') ? $this->get('viewId') : 'all';
        $aRows = Phpfox::getService('feed')->get();//null, null, 0, false, $sViewId);
        //$aRows = Phpfox::getService('feed')->get(null, null, 0);
        $iNewCnt = 0;
        $sLoadIds = '';
        $aIds = array();
        foreach ($aParts as $sPart)
        {
            $iPart = (int) trim($sPart);
            $aIds[$iPart] = $iPart;
        }

        $iMaxId = max($aIds);
        foreach ($aRows as $aRow)
        {
            if (!in_array($aRow['feed_id'], $aIds) && $aRow['feed_id'] > $iMaxId)
            {            	
				if ($sViewId == 'all' || $aRow['type_id'] == $sViewId){
	                $iNewCnt++;
	                $sLoadIds .= $aRow['feed_id'] . ',';
				}
            }
        }

        //$this->call('if (reload_wall==1) $Core.rebuildActivityFeedCount(' . (int) $iNewCnt . ', \'' . $sLoadIds . '\');');
        $this->call('$Core.rebuildActivityFeedCount(' . (int) $iNewCnt . ', \'' . $sLoadIds . '\');');
        if (Phpfox::getParam('feed.refresh_activity_feed') > 0)
        {
            $this->call('setTimeout("$.ajaxCall(\'wall.reloadActivityFeed\', \'reload-ids=\' + $Core.getCurrentFeedIds() + \'&viewId=\' + $(\'#feed_type_id\').val(), \'GET\');", ' . (Phpfox::getParam('feed.refresh_activity_feed') * 1000) . ');');
        }
    }

    public function preview()
	{
		$this->error(false);
		
		Phpfox::getBlock('wall.preview');
		
		if (!Phpfox_Error::isPassed())
		{
			echo json_encode(array('error' => implode('', Phpfox_Error::get())));
		}
		else 
		{
			$this->call('<script text/javascript">$Core.loadInit();</script>');
		}
	}

    public function checkValue()
    {
        $sValue = Phpfox::getLib('parse.input')->convert($this->get('value'));
        $isValid = 1;
        if ($sValue == Phpfox::getPhrase('feed.what_s_on_your_mind'))
            $isValid = 0;
        if ($isValid == 1 && $sValue == Phpfox::getPhrase('feed.write_something'))
            $isValid = 0;
        if (Phpfox::isModule('photo') && $isValid == 1 && $sValue == Phpfox::getPhrase('photo.say_something_about_this_photo'))
            $isValid = 0;
        if (Phpfox::isModule('music') && $isValid == 1 && $sValue == Phpfox::getPhrase('music.say_something_about_this_song'))
            $isValid = 0;
        if (Phpfox::isModule('video') && $isValid == 1 && $sValue == Phpfox::getPhrase('video.say_something_about_this_video'))
            $isValid = 0;
        if (Phpfox::isModule('videochannel') && $isValid == 1 && $sValue == Phpfox::getPhrase('videochannel.say_something_about_this_video'))
            $isValid = 0;
        echo $isValid;
    }

    public function updateStatus()
    {
        Phpfox::isUser(true);
        $aVals = (array) $this->get('val');
        $sCheckText = Phpfox::getLib('parse.input')->convert($aVals['status_info']);
        if (isset($aVals['status_info']) && ($sCheckText == Phpfox::getPhrase('feed.what_s_on_your_mind') || $sCheckText == Phpfox::getPhrase('photo.say_something_about_this_photo')))
        {
            $aVals['status_info'] = '';
        }
        if (isset($aVals['user_status']) && ($iId = Phpfox::getService('wall.user')->updateStatus($aVals)))
        {
            (($sPlugin = Phpfox_Plugin::get('user.component_ajax_updatestatus')) ? eval($sPlugin) : false);
            Phpfox::getService('wall.feed')->processAjax($iId);
        }
        else
        {
            $this->call('$Core.activityFeedProcess(false);');
        }
    }

    public function addViaStatusUpdate()
    {
        Phpfox::isUser(true);

        define('PHPFOX_FORCE_IFRAME', true);

        $aVals = (array) $this->get('val');
        $aVals['status_info'] = $aVals['user_status'];
        $sCheckText = Phpfox::getLib('parse.input')->convert($aVals['status_info']);
        if (isset($aVals['status_info']) && ($sCheckText == Phpfox::getPhrase('feed.what_s_on_your_mind') || $sCheckText == Phpfox::getPhrase('photo.say_something_about_this_photo')))
        {
            $aVals['user_status'] = $aVals['status_info'] = '';
        }
        $aCallback = null;
        if (isset($aVals['callback_module']) && Phpfox::hasCallback($aVals['callback_module'], 'addLink'))
        {
            $aCallback = Phpfox::callback($aVals['callback_module'] . '.addLink', $aVals);
        }

        if (($iId = Phpfox::getService('wall.link')->add($aVals, false, $aCallback)))
        {
            (($sPlugin = Phpfox_Plugin::get('link.component_ajax_addviastatusupdate')) ? eval($sPlugin) : false);

            Phpfox::getService('wall.feed')->callback($aCallback)->processAjax($iId);
        }
    }

    public function addLinkViaStatusUpdate()
    {
        Phpfox::isUser(true);
        define('PHPFOX_FORCE_IFRAME', true);
        $aVals = (array) $this->get('val');
        $sCheckText = Phpfox::getLib('parse.input')->convert($aVals['status_info']);
        if (isset($aVals['status_info']) && ($sCheckText == Phpfox::getPhrase('feed.what_s_on_your_mind') || $sCheckText == Phpfox::getPhrase('photo.say_something_about_this_photo')))
        {
            $aVals['user_status'] = $aVals['status_info'] = '';
        }
        $aCallback = null;
        if (isset($aVals['callback_module']) && Phpfox::hasCallback($aVals['callback_module'], 'addLink'))
        {
            $aCallback = Phpfox::callback($aVals['callback_module'] . '.addLink', $aVals);
        }
        if (($iId = Phpfox::getService('wall.link')->add($aVals, false, $aCallback)))
        {
            (($sPlugin = Phpfox_Plugin::get('link.component_ajax_addviastatusupdate')) ? eval($sPlugin) : false);
            Phpfox::getService('wall.feed')->callback($aCallback)->processAjax($iId);
        }
    }

    public function addBlogViaStatusUpdate()
    {
        Phpfox::isUser(true);
        Phpfox::getUserParam('blog.add_new_blog', true);

        $aVals = $this->get('val');
        $sCheckText = Phpfox::getLib('parse.input')->convert($aVals['status_info']);
        if (isset($aVals['status_info']) && ($sCheckText == Phpfox::getPhrase('feed.what_s_on_your_mind') || $sCheckText == Phpfox::getPhrase('photo.say_something_about_this_photo')))
        {
            $aVals['user_status'] = $aVals['status_info'] = '';
        }
        $aVals['title'] = $aVals['blog_title'];
        $aVals['text'] = $aVals['status_info'];
        if (Phpfox::getLib('parse.format')->isEmpty($aVals['text']))
        {
            $this->call('$Core.resetActivityFeedError(\'Please provide some text for your blog.\');');
        }
        else
        {
            if (($iBlogId = Phpfox::getService('wall.blog')->add($aVals)))
            {
                $iId = Phpfox::getService('feed.process')->getLastId();

                (($sPlugin = Phpfox_Plugin::get('blog.component_ajax_addviastatusupdate')) ? eval($sPlugin) : false);

                Phpfox::getService('wall.feed')->processAjax($iId);
            }
        }
    }

    public function addComment()
    {
        Phpfox::isUser(true);

        $aVals = (array) $this->get('val');
        $aVals['status_info'] = $aVals['user_status'];
        $sCheckText = Phpfox::getLib('parse.input')->convert($aVals['status_info']);
        if (isset($aVals['status_info']) && ($sCheckText == Phpfox::getPhrase('feed.what_s_on_your_mind') || $sCheckText == Phpfox::getPhrase('photo.say_something_about_this_photo')))
        {
            $aVals['user_status'] = $aVals['status_info'] = '';
        }
        if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
        {
            $this->alert(Phpfox::getPhrase('user.add_some_text_to_share'));
            $this->call('$Core.activityFeedProcess(false);');
            return;
        }

        /* Check if user chose an egift */
        if (isset($aVals['egift_id']) && !empty($aVals['egift_id']))
        {
            /* is this gift a free one? */
            $aGift = Phpfox::getService('egift')->getEgift($aVals['egift_id']);
            if (!empty($aGift))
            {
                $bIsFree = true;
                foreach ($aGift['price'] as $sCurrency => $fVal)
                {
                    if ($fVal > 0)
                    {
                        $bIsFree = false;
                    }
                }
                /* This is an important change, in v2 birthday_id was the mail_id, in v3
                 * birthday_id is the feed_id
                 */
                $aVals['feed_type'] = 'feed_egift';
                $iId = Phpfox::getService('wall.process')->addComment($aVals);
                // Always make an invoice, so the feed can check on the state
                $iInvoice = Phpfox::getService('egift.process')->addInvoice($iId, $aVals['parent_user_id'], $aGift);

                if (!$bIsFree)
                {
                    Phpfox::getBlock('api.gateway.form', array('gateway_data' => array(
                            'item_number' => 'egift|' . $iInvoice,
                            'currency_code' => Phpfox::getService('user')->getCurrency(), //Phpfox::getService('core.currency')->getDefault(),
                            'amount' => $aGift['price'][Phpfox::getService('user')->getCurrency()],
                            'item_name' => 'egift card with message: ' . $aVals['user_status'] . '',
                            'return' => Phpfox::getLib('url')->makeUrl('friend.invoice'),
                            'recurring' => 0,
                            'recurring_cost' => '',
                            'alternative_cost' => 0,
                            'alternative_recurring_cost' => 0
                            )));
                    $this->call('$("#js_activity_feed_form").hide().after("' . $this->getContent(true) . '");');
                }
                else
                {
                    // egift is free
                    Phpfox::getService('wall.feed')->processAjax($iId);
                }
            }
        }
        else
        {
            if (isset($aVals['user_status']) && ($iId = Phpfox::getService('wall.process')->addComment($aVals)))
            {
                Phpfox::getService('wall.feed')->processAjax($iId);
            }
            else
            {
                $this->call('$Core.activityFeedProcess(false);');
            }
        }
    }

    public function appendMore()
    {
        $sViewId = $this->get('viewId') ? $this->get('viewId') : 'all';
        $aRows = Phpfox::getService('feed')->get();//(null, null, 0, false, $sViewId);

        $sCustomIds = '';
        foreach ($aRows as $aRow)
        {
            if ($sViewId != 'all')
            {
                if ($aRow['type_id'] != $sViewId)
                    continue;
            }

            $sCustomIds .= $aRow['feed_id'];
            $this->template()->assign(array(
                'aFeed' => $aRow
                    )
            );
            Phpfox_Error::skip(true);
            $this->template()->getTemplate('feed.block.entry');
            Phpfox_Error::skip(false);
        }

        $sIds = 'js_feed_' . md5($sCustomIds);
        $this->call('$(\'#activity_feed_updates_link_holder\').hide();');
        $this->call('$(\'.js_parent_feed_entry\').each(function(){$(this).removeClass(\'row_first\');});');
        $this->prepend('#js_new_feed_update', '<div id="' . $sIds . '" class="js_feed_view_more_entry" style="display:none;">' . $this->getContent(false) . '</div>');
        $this->slideDown('#' . $sIds);
        $this->call('$Core.loadInit();');
    }

    public function photo_process()
    {
        $aPostPhotos = $this->get('photos');

        if (is_array($aPostPhotos))
        {
            $aImages = array();
            foreach ($aPostPhotos as $aPostPhoto)
            {
                $aPart = unserialize(base64_decode(urldecode($aPostPhoto)));

                $aImages[] = $aPart[0];
            }
        }
        else
        {
            $aImages = unserialize(base64_decode(urldecode($this->get('photos'))));
        }

        $oImage = Phpfox::getLib('image');
        $iFileSizes = 0;
        $iGroupId = 0;
        $bProcess = false;

        foreach ($aImages as $iKey => $aImage)
        {
            if ($aImage['completed'] == 'false')
            {
                $aPhoto = Phpfox::getService('photo')->getForProcess($aImage['photo_id']);
                if (isset($aPhoto['photo_id']))
                {
                    if ($aPhoto['group_id'] > 0)
                    {
                        $iGroupId = $aPhoto['group_id'];
                    }

                    $sFileName = $aPhoto['destination'];

                    //$this->call('p(\'Processing photo: ' . $aPhoto['photo_id'] . '\');');

                    foreach (Phpfox::getParam('photo.photo_pic_sizes') as $iSize)
                    {
                        // Create the thumbnail
                        if ($oImage->createThumbnail(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''), Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize, true, ((Phpfox::getParam('photo.enabled_watermark_on_photos') && Phpfox::getParam('core.watermark_option') != 'none') ? (Phpfox::getParam('core.watermark_option') == 'image' ? 'force_skip' : true) : false)) === false)
                        {
                            //$this->call('p(\'Thumbnail failed: ' . $aPhoto['photo_id'] . ' (' . $iSize . ')\');');

                            continue;
                        }

                        //$this->call('p(\'Created thumbnail: ' . $aPhoto['photo_id'] . ' (' . $iSize . ')\');');

                        if (Phpfox::getParam('photo.enabled_watermark_on_photos'))
                        {
                            $oImage->addMark(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, '_' . $iSize));
                        }

                        // Add the new file size to the total file size variable
                        $iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, '_' . $iSize));
                    }

                    if (Phpfox::getParam('photo.enabled_watermark_on_photos'))
                    {
                        $oImage->addMark(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''));
                    }

                    $aImages[$iKey]['completed'] = 'true';

                    break;
                }
            }
        }

        // Update the user space usage
        Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'photo', $iFileSizes);

        $iNotCompleted = 0;
        foreach ($aImages as $iKey => $aImage)
        {
            if ($aImage['completed'] == 'false')
            {
                $iNotCompleted++;
            }
        }

        if ($iNotCompleted === 0)
        {
            //$this->call('p(\'Photo process completed.\');');

            $aCallback = ($this->get('callback_module') ? Phpfox::callback($this->get('callback_module') . '.addPhoto', $this->get('callback_item_id')) : null);

            $iFeedId = 0;
            if (!Phpfox::getUserParam('photo.photo_must_be_approved'))
            {
                (Phpfox::isModule('feed') ? $iFeedId = Phpfox::getService('feed.process')->callback($aCallback)->add('photo', $aPhoto['photo_id'], $aPhoto['privacy'], $aPhoto['privacy_comment'], (int) $this->get('parent_user_id', 0)) : null);
                if (count($aImages) && !$this->get('callback_module'))
                {
                    $aExtraPhotos = array();
                    foreach ($aImages as $aImage)
                    {
                        if ($aImage['photo_id'] == $aPhoto['photo_id'])
                        {
                            continue;
                        }

                        Phpfox::getLib('database')->insert(Phpfox::getT('photo_feed'), array(
                            'feed_id' => $iFeedId,
                            'photo_id' => $aImage['photo_id']
                                )
                        );
                    }
                }
            }

            if ($this->get('action') == 'upload_photo_via_share')
            {
                // $aCallback = ($this->get('callback_module') ? Phpfox::callback($this->get('callback_module') . '.addPhoto', $this->get('callback_item_id')) : null);

                Phpfox::getService('wall.feed')->callback($aCallback)->processAjax($iFeedId);

                (($sPlugin = Phpfox_Plugin::get('photo.component_ajax_process_done')) ? eval($sPlugin) : false);

                $this->call('$Core.resetActivityFeedForm();');
            }
            else
            {
                // Only display the photo block if the user plans to upload more pictures
                if ($this->get('action') == 'view_photo')
                {
                    Phpfox::addMessage((count($aImages) == 1 ? Phpfox::getPhrase('photo.photo_successfully_uploaded') : Phpfox::getPhrase('photo.photos_successfully_uploaded')));

                    $this->call('window.parent.location.href = \'' . Phpfox::getLib('url')->permalink('photo', $aPhoto['photo_id'], $aPhoto['title']) . 'userid_' . Phpfox::getUserId() . '/\';');
                }
                elseif ($this->get('action') == 'view_album' && isset($aImages[0]['album']))
                {
                    Phpfox::addMessage((count($aImages) == 1 ? Phpfox::getPhrase('photo.photo_successfully_uploaded') : Phpfox::getPhrase('photo.photos_successfully_uploaded')));

                    $this->call('window.location.href = \'' . Phpfox::getLib('url')->permalink('photo.album', $aImages[0]['album']['album_id'], $aImages[0]['album']['name']) . '\';');
                }
                else
                {
                    Phpfox::addMessage((count($aImages) == 1 ? Phpfox::getPhrase('photo.photo_successfully_uploaded') : Phpfox::getPhrase('photo.photos_successfully_uploaded')));

                    $this->call('window.location.href = \'' . Phpfox::getLib('url')->permalink('photo', $aPhoto['photo_id'], $aPhoto['title']) . 'userid_' . Phpfox::getUserId() . '/\';');
                }

                $this->call('completeProgress();');
            }
        }
        else
        {
            $this->call('$(\'#js_progress_cache_holder\').html(\'\' + $.ajaxProcess(\'' . Phpfox::getPhrase('photo.processing_image_current_total', array('phpfox_squote' => true, 'current' => (count($aImages) - $iNotCompleted), 'total' => count($aImages))) . '\', \'large\') + \'\');');
            $this->html('#js_photo_upload_process_cnt', (count($aImages) - $iNotCompleted));

            $sExtra = '';
            if ($this->get('callback_module'))
            {
                $sExtra .= '&callback_module=' . $this->get('callback_module') . '&callback_item_id=' . $this->get('callback_item_id') . '';
            }
            if ($this->get('parent_user_id'))
            {
                $sExtra .= '&parent_user_id=' . $this->get('parent_user_id');
            }

            $this->call('$.ajaxCall(\'wall.photo_process\', \'&action=' . $this->get('action') . '&js_disable_ajax_restart=true&photos=' . urlencode(base64_encode(serialize($aImages))) . $sExtra . '\');');
        }
    }

}
