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

class Wall_Service_Blog extends Phpfox_Service
{
	public function add($aVals)
	{
		(($sPlugin = Phpfox_Plugin::get('blog.service_process__start')) ? eval($sPlugin) : false);
		$oFilter = Phpfox::getLib('parse.input');		

		// check if the user entered a forbidden word
		Phpfox::getService('ban')->checkAutomaticBan($aVals['text'] . ' ' . $aVals['title']);

		if (!Phpfox::getParam('blog.allow_links_in_blog_title'))
		{
			if (!Phpfox::getLib('validator')->check($aVals['title'], array('url')))
			{
				return Phpfox_Error::set(Phpfox::getPhrase('blog.we_do_not_allow_links_in_titles'));
			}
		}		
		if (!isset($aVals['privacy']))
		{
			$aVals['privacy'] = 0;
		}
		if (!isset($aVals['privacy_comment']))
		{
			$aVals['privacy_comment'] = 0;
		}
		$sTitle = $oFilter->clean($aVals['title'], 255);
		$bHasAttachments = (!empty($aVals['attachment']) && Phpfox::getUserParam('attachment.can_attach_on_blog'));		
		if (!isset($aVals['post_status']))
		{
			$aVals['post_status'] = 1;
		}
		
		$aVals['text'] = Phpfox::getService('wall.process')->compile($aVals['text'], $aVals['tagging']);

		$aInsert = array(
			'user_id' => Phpfox::getUserId(),
			'title' => $sTitle,
			'time_stamp' => PHPFOX_TIME,
			'is_approved' => 1,
			'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
			'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
			'post_status' => (isset($aVals['post_status']) ? $aVals['post_status'] : '1'),
			'total_attachment' => ($bHasAttachments ? Phpfox::getService('attachment')->getCount($aVals['attachment']) : 0)
		);		
		
		$bIsSpam = false;
		if (Phpfox::getParam('blog.spam_check_blogs'))
		{
			if (Phpfox::getLib('spam')->check(array(
						'action' => 'isSpam',										
						'params' => array(
							'module' => 'blog',
							'content' => $oFilter->prepare($aVals['text'])
						)
					)
				)
			)
			{
				$aInsert['is_approved'] = '9';
				$bIsSpam = true;				
			}
		}
		
		if (Phpfox::getUserParam('blog.approve_blogs'))
		{
			$aInsert['is_approved'] = '0';
			$bIsSpam = true;
		}
		
		(($sPlugin = Phpfox_Plugin::get('blog.service_process_add_start')) ? eval($sPlugin) : false);

		$iId = $this->database()->insert(Phpfox::getT('blog'), $aInsert);		
		
		(($sPlugin = Phpfox_Plugin::get('blog.service_process_add_end')) ? eval($sPlugin) : false);
		
		$this->database()->insert(Phpfox::getT('blog_text'), array(
				'blog_id' => $iId,
				'text' => $aVals['text'],
				'text_parsed' => $oFilter->prepare($aVals['text'])
			)
		);
		
		if (!empty($aVals['selected_categories']))
		{
			Phpfox::getService('blog.category')->addCategoryForBlog($iId, explode(',', rtrim($aVals['selected_categories'], ',')), ($aVals['post_status'] == 1 ? true : false));
		}

		if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support') && Phpfox::getUserParam('tag.can_add_tags_on_blogs'))
		{
			Phpfox::getService('tag.process')->add('blog', $iId, Phpfox::getUserId(), $aVals['text'], true);
		}
		else
		{
			if (Phpfox::getUserParam('tag.can_add_tags_on_blogs') && Phpfox::isModule('tag') && isset($aVals['tag_list']) && ((is_array($aVals['tag_list']) && count($aVals['tag_list'])) || (!empty($aVals['tag_list']))))
			{
				Phpfox::getService('tag.process')->add('blog', $iId, Phpfox::getUserId(), $aVals['tag_list']);
			}
		}
		
		// If we uploaded any attachments make sure we update the 'item_id'
		if ($bHasAttachments)
		{
			Phpfox::getService('attachment.process')->updateItemId($aVals['attachment'], Phpfox::getUserId(), $iId);
		}		
	
		if ($bIsSpam === true)
		{			
			return $iId;
		}		
		
		if ($aVals['post_status'] == 1)
		{
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('blog', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0)) : null);
			
			// Update user activity
			Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'blog', '+');
		}		
		
		if ($aVals['privacy'] == '4')
		{
			Phpfox::getService('privacy.process')->add('blog', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));			
		}		
		
		// $this->cache()->remove(array('user/' . Phpfox::getUserId(), 'blog_browse'), 'substr');
		
		(($sPlugin = Phpfox_Plugin::get('blog.service_process__end')) ? eval($sPlugin) : false);
		
		return $iId;
	}
}
