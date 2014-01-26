<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Feed
 * @version 		$Id: comment.class.php 4545 2012-07-20 10:40:35Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_Yncomment extends Phpfox_Component 
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aFeed = $this->getParam('aFeed');
		$sFeedType = (isset($aFeed['feed_display']) ? $aFeed['feed_display'] : null);
		$aFeed['feed_id'] = $aFeed['item_id'];
		$aFeed['is_view_item'] = true;		
		
		if (Phpfox::getUserParam('comment.can_delete_comment_on_own_item') && ($iOwnerDeleteCmt = $this->request()->getInt('ownerdeletecmt')) && isset($aFeed['user_id']) && $aFeed['user_id'] == Phpfox::getUserId())
		{
			if (Phpfox::getService('comment.process')->deleteInline($iOwnerDeleteCmt, $aFeed['comment_type_id'], true))
			{
				$this->url()->forward($aFeed['feed_link'], Phpfox::getPhrase('comment.comment_successfully_deleted'));
			}
			exit('failed');
		}

		$bCanPostComment = true;
		if (isset($aFeed['comment_privacy']) && $aFeed['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
		{
			switch ($aFeed['comment_privacy'])
			{
				case 1:					
					if ((int) $aFeed['feed_is_friend'] <= 0)
					{
						$bCanPostComment = false;						
					}
					break;
				case 2:
					if ((int) $aFeed['feed_is_friend'] > 0)
					{
						$bCanPostComment = true;
					}
					else 
					{
						if (!Phpfox::getService('friend')->isFriendOfFriend($aFeed['user_id']))
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
		$aFeed['can_post_comment'] = $bCanPostComment;
		
		if ((int) $aFeed['total_like'] > 0 && Phpfox::isModule('like'))
		{
			$aFeed['likes'] = Phpfox::getService('like')->getLikesForFeed($aFeed['like_type_id'], $aFeed['item_id'], ((int) $aFeed['feed_is_liked'] > 0 ? true : false), Phpfox::getParam('feed.total_likes_to_display'));
		}		
		
		$iPageLimit = 2;
		$mPager = null;
		$iCommentId = null;
		$bIsViewingComments = false;
		if (Phpfox::isModule('comment') && $sFeedType != 'mini')
		{	
			if ((int) $aFeed['total_comment'] > 0)
			{					
				if ($sFeedType == 'view')
				{
					$iPageLimit = Phpfox::getParam('comment.comment_page_limit');
					$mPager = $aFeed['total_comment'];
				}
				
				if ($this->request()->getInt('comment'))
				{
					$iCommentId = $this->request()->getInt('comment');
					$bIsViewingComments = true;
				}
							
				if($aFeed['comment_type_id'] == 'advancedphoto')
				{
					$aComments = array_merge(Phpfox::getService('comment')->getCommentsForFeed($aFeed['comment_type_id'], $aFeed['item_id'], $iPageLimit, $mPager, $iCommentId), Phpfox::getService('comment')->getCommentsForFeed('photo', $aFeed['item_id'], $iPageLimit, $mPager, $iCommentId));
					$aFeed['comments'] = $aComments;
				}
				else if($aFeed['comment_type_id'] == 'advancedphoto_album')
				{
					$aComments = array_merge(Phpfox::getService('comment')->getCommentsForFeed($aFeed['comment_type_id'], $aFeed['item_id'], $iPageLimit, $mPager, $iCommentId), Phpfox::getService('comment')->getCommentsForFeed('photo_album', $aFeed['item_id'], $iPageLimit, $mPager, $iCommentId));
					$aFeed['comments'] = $aComments;
				}
				else
				{
					$aFeed['comments'] = Phpfox::getService('comment')->getCommentsForFeed($aFeed['comment_type_id'], $aFeed['item_id'], $iPageLimit, $mPager, $iCommentId);
				}
			}
		}
		
		if ($sFeedType == 'view')
		{
			Phpfox::getLib('pager')->set(array(
					'ajax' => 'comment.viewMoreFeed', 
					'page' => Phpfox::getLib('request')->getInt('page'), 
					'size' => $iPageLimit, 
					'count' => $mPager,
					'phrase' => Phpfox::isModule('comment') ? Phpfox::getPhrase('comment.view_previous_comments') : '',
					'icon' => 'misc/comment.png',
					'aParams' => array(
						'comment_type_id' => $aFeed['comment_type_id'],
						'item_id' => $aFeed['item_id'],
						'append' => true,
						'pagelimit' => $iPageLimit,
						'total' => $mPager
					)
				)
			);
		}

		$aFeed['type_id'] = (!empty($aFeed['type_id']) ? $aFeed['type_id'] : (isset($aFeed['report_module']) ? $aFeed['report_module'] : ''));
		
		if (empty($sFeedType))
		{
			$sFeedType = 'default';
		}
		$this->template()->assign(array(
				'aFeed' => $aFeed,
				'sFeedType' => $sFeedType,
				'bIsViewingComments' => $bIsViewingComments
			)
		);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_yncomment_clean')) ? eval($sPlugin) : false);
	}	
}

?>