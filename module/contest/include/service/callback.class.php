<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Blog Callbacks
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Blog
 * @version 		$Id: callback.class.php 4545 2012-07-20 10:40:35Z Raymond_Benc $
 */
class Contest_Service_Callback extends Phpfox_Service 
{
	/**
	 * Class constructor
	 *
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('contest_entry');
	}

	public function paymentApiCallback($aParam)
	{
		$iDonorId = 0;
		$iTransactionId = $aParam['custom'];
		if($aParam['status'] == 'completed')
		{
			
			if(!$aParam['total_paid'])
			{
				return false;
			}

			// transaction is success we should proceed user's request
			Phpfox::getService('contest.contest.process')->processUserRequest($iTransactionId);
		}


		Phpfox::getService('contest.transaction.process')->updatePaypalTransaction($iTransactionId, $aParam);
	}
	
	public function getActivityFeedComment($aRow)
	{
		if (Phpfox::isUser())
		{
			$this->database()->select('l.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_mini\' AND l.item_id = c.comment_id AND l.user_id = ' . Phpfox::getUserId());
		}		
		
		$aItem = $this->database()->select('en.entry_id, en.title, en.time_stamp, en.total_comment, en.total_like, c.total_like, ct.text_parsed AS text, contest.contest_id, contest.contest_name, ' . Phpfox::getUserField())
			->from(Phpfox::getT('comment'), 'c')
			->join(Phpfox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
			->join(Phpfox::getT('contest_entry'), 'en', 'c.type_id = \'contest_entry\' AND c.item_id = en.entry_id AND c.view_id = 0')
            ->join(Phpfox::getT('contest'), 'contest', 'contest.contest_id = en.contest_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = en.user_id')
			->where('c.comment_id = ' . (int) $aRow['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aItem['entry_id']))
		{
			return false;
		}
		
		$sLink = Phpfox::permalink('contest', $aItem['contest_id'], $aItem['contest_name']).'entry_'.$aItem['entry_id'].'/';
		$sTitle = Phpfox::getLib('parse.output')->shorten($aItem['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') :50));
		$sUser = '<a href="' . Phpfox::getLib('url')->makeUrl($aItem['user_name']) . '">' . $aItem['full_name'] . '</a>';
		$sGender = Phpfox::getService('user')->gender($aItem['gender'], 1);
		
		if ($aRow['user_id'] == $aItem['user_id'])
		{
			$sMessage = Phpfox::getPhrase('contest.posted_a_comment_on_gender_entry_a_href_link_title_a', array('gender' => $sGender, 'link' => $sLink, 'title' => $sTitle));
		}
		else
		{			
			$sMessage = Phpfox::getPhrase('contest.posted_a_comment_on_user_name_s_entry_a_href_link_title_a', array('user_name' => $sUser, 'link' => $sLink, 'title' => $sTitle));
		}
		
		
		return array(
			'no_share' => true,
			'feed_info' => $sMessage,
			'feed_link' => $sLink,
			'feed_status' => $aItem['text'],
			'feed_total_like' => $aItem['total_like'],
			'feed_is_liked' => isset($aItem['is_liked']) ? $aItem['is_liked'] : false,
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'feed/contest.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],
			'like_type_id' => 'feed_mini'
		);		
	}	
		
	public function canShareItemOnFeed(){}
	
	//for contest
	public function getActivityFeed($aRow, $aCallback = null, $bIsChildItem = false)
	{
		//if (!Phpfox::getUserParam('blog.view_blogs'))
		{
			//return false;
		}
		
		if (Phpfox::isUser())
		{
			$this->database()->select('l.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'contest\' AND l.item_id = c.contest_id AND l.user_id = ' . Phpfox::getUserId());
		}
		
		if ($bIsChildItem)
		{
			$this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = c.user_id');
		}				
		
		$aRow = $this->database()->select('c.contest_id, c.contest_name, c.time_stamp, c.total_comment, c.total_like, c.image_path, c.server_id')
			->from(Phpfox::getT('contest'), 'c')
			->where('c.contest_id = ' . (int) $aRow['item_id'])
			->execute('getSlaveRow');	
		
		if(!isset($aRow['contest_id']))
		{
			return false;
		}
		$aFeed = array(
			'can_post_comment' => true,
			'feed_title' => $aRow['contest_name'],
			'feed_info' => Phpfox::getPhrase('contest.posted_a_contest'),
			'feed_link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']),
			'feed_content' => $aRow['contest_name'],
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => isset($aRow['is_liked']) ? $aRow['is_liked'] : false,
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'feed/contest.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => true,	
			'comment_type_id' => 'contest',
			'like_type_id' => 'contest'			
		);
		
		if(!empty($aRow['image_path']))
        {
           $aFeed['feed_image'] = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aRow['server_id'],
					'path' => 'core.url_pic',
					'file' => 'contest/' . $aRow['image_path'],
					'suffix' => '_100',
					'max_width' => 100,
					'max_height' => 100,
					'class' => 'photo_holder'
				)
			);
        }

		return array_merge($aFeed, $aRow);
	}
	
	//for entry
	public function getActivityFeedEntry($aRow, $aCallback = null, $bIsChildItem = false)
	{
		//if (!Phpfox::getUserParam('blog.view_blogs'))
		{
			//return false;
		}

		if (Phpfox::isUser())
		{
			$this->database()->select('l.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'contest_entry\' AND l.item_id = en.entry_id AND l.user_id = ' . Phpfox::getUserId());
		}
		
		if ($bIsChildItem)
		{
			$this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = en.user_id');
		}				
		
		$aRow = $this->database()->select('en.entry_id, ct.type, ct.contest_name, ct.contest_id, en.image_path,en.title,en.total_comment, en.total_like,en.time_stamp,en.image_path,u.*')
        	->from($this->_sTable, 'en')
            ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id=en.contest_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id=en.user_id')
            ->where('en.entry_id = ' . $aRow['item_id'])
            ->execute('getSlaveRow');
		
		if(!isset($aRow['entry_id']))
		{
			return false;
		}
		
		if($aRow['image_path']==""){
			$aRow['image_path'] = "user/".$aRow['user_image'];
		}
		
		$sContestLink = Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']);
		$aFeed = array(
			'can_post_comment' => true,
			'feed_title' => $aRow['title'],
			'feed_info' => Phpfox::getPhrase('contest.posted_an_entry_in_contest_title', array('title' => $aRow['contest_name'], 'link' => $sContestLink)),
			'feed_link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/',
			'feed_content' => $aRow['title'],
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => isset($aRow['is_liked']) ? $aRow['is_liked'] : false,
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'feed/contest.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => true,	
			'comment_type_id' => 'contest_entry',
			'like_type_id' => 'contest_entry'	
		);
		$suffix = '_200';
		if($aRow['type']==3)
		{
			$suffix = '_120';
		}
		if(!empty($aRow['image_path']))
        {
           $aFeed['feed_image'] = Phpfox::getLib('image.helper')->display(array(
					'server_id' => 0,
					'path' => 'core.url_pic',
					'file' => $aRow['image_path'],
					'suffix' => $suffix,
					'max_width' => 100,
					'max_height' => 100,
					'class' => 'photo_holder'
				)
			);
        }
		
		return array_merge($aFeed, $aRow);
	}

	/*public function getActions()
	{
		return array(
			'dislike' => array(
				'enabled' => true,
				'action_type_id' => 2, // sort of redundant given the key 
				'phrase' => 'Dislike',
				'phrase_in_past_tense' => 'disliked',
				'item_type_id' => 'contest', // used internally to differentiate between photo albums and photos for example.
				'item_phrase' => 'contest',
				'table' => 'contest',
				'column_update' => 'total_dislike',
				'column_find' => 'contest_id',
				'where_to_show' => array('contest', '')
				),
				'dislike-entry' => array(
				'enabled' => true,
				'action_type_id' => 3, // sort of redundant given the key 
				'phrase' => 'Dislike',
				'phrase_in_past_tense' => 'disliked',
				'item_type_id' => 'contest-entry', // used internally to differentiate between photo albums and photos for example.
				'item_phrase' => 'entry',
				'table' => 'contest-entry',
				'column_update' => 'total_dislike',
				'column_find' => 'entry_id',
				'where_to_show' => array('contest-entry', 'contest')
				)
		);
	}*/

	public function addLikeEntry($iItemId, $bDoNotSendEmail = false)
	{
      
		$aRow = $this->database()->select('en.entry_id, en.title, en.user_id, en.contest_id, ct.user_id as owner_contest, ct.contest_name')
			->from(Phpfox::getT('contest_entry'),'en')
            ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id = en.contest_id')
			->where('en.entry_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
		
		if (!isset($aRow['entry_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'contest_entry\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'contest_entry', 'entry_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/';
			
			$aList = Phpfox::getService('contest.participant')->getListFollowingByContestId($aRow['contest_id']);
			$aList[] = array('user_id' => $aRow['user_id']);
			$aList[] = array('user_id' => $aRow['owner_contest']);
			
			foreach($aList as $List){
				Phpfox::getLib('mail')->to($List['user_id'])
					->subject(array('contest.full_name_liked_your_entry_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
					->message(array('contest.full_name_liked_your_entry_link_title', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['title'])))
					->notification('like.new_like')
					->send();
						
				Phpfox::getService('notification.process')->add('contest_entry_like', $aRow['entry_id'], $List['user_id']);
			}
		}
	}

	//for contest
	public function addLike($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('ct.contest_id, ct.contest_name, ct.user_id, ct.user_id as owner_contest')
			 ->from(Phpfox::getT('contest'), 'ct')
			->where('ct.contest_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
		
		if (!isset($aRow['contest_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'contest\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'contest', 'contest_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']);
			
			$aList = Phpfox::getService('contest.participant')->getListFollowingByContestId($aRow['contest_id']);
			$aList[] = array('user_id' => $aRow['user_id']);
			if($aRow['user_id']!=$aRow['owner_contest'])
				$aList[] = array('user_id' => $aRow['owner_contest']);
			
			foreach($aList as $List){
				Phpfox::getLib('mail')->to($List['user_id'])
					->subject(array('contest.full_name_liked_your_contest_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['contest_name'])))
					->message(array('contest.full_name_liked_your_contest_link_title', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['contest_name'])))
					->notification('like.new_like')
					->send();
						
				Phpfox::getService('notification.process')->add('contest_like', $aRow['contest_id'], $List['user_id']);
			}
		}
	}
	
	public function getNotificationEntry_Like($aNotification)
	{
		$aRow = $this->database()->select('en.entry_id, en.title, en.user_id, u.gender, u.full_name, ct.contest_id, ct.contest_name')	
			->from(Phpfox::getT('contest_entry'), 'en')
            ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id = en.contest_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = en.user_id')
			->where('en.entry_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('contest.users_liked_gender_own_entry_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('contest.users_liked_your_entry_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('contest.users_liked_span_class_drop_data_user_row_full_name_s_span_entry_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/',
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}

	//for contest
	public function getNotificationLike($aNotification)
	{
		$aRow = $this->database()->select('ct.contest_id, ct.contest_name, ct.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('contest'), 'ct')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = ct.user_id')
			->where('ct.contest_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['contest_name'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('contest.users_liked_gender_own_contest_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('contest.users_liked_your_contest_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('contest.users_liked_span_class_drop_data_user_row_full_name_s_span_contest_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('contest', $aRow['contest_id'], $aRow['contest_name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}		

	public function getNotificationEntry_vote($aNotification)
	{
		$aRow = $this->database()->select('en.entry_id, en.title, en.user_id, u.gender, u.full_name, ct.contest_id, ct.contest_name')	
			->from(Phpfox::getT('contest_entry'), 'en')
            ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id = en.contest_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = en.user_id')
			->where('en.entry_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('contest.users_voted_gender_own_entry_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('contest.users_voted_your_entry_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('contest.users_voted_span_class_drop_data_user_row_full_name_s_span_entry_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/',
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}	
	
	public function deleteLikeEntry($iItemId)
	{

		$this->database()->updateCount('like', 'type_id = \'contest_entry\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'contest_entry', 'entry_id = ' . (int) $iItemId);	
	}	
	
	//for contest
	public function deleteLike($iItemId)
	{
		$this->database()->updateCount('like', 'type_id = \'contest\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'contest', 'contest_id = ' . (int) $iItemId);	
	}	
	
	//for entry
	public function getAjaxCommentVarEntry()
	{
            return;
	}
	
	//for contest
	public function getAjaxCommentVar()
	{
            return;
	}
	
	//for entry
	public function addCommentEntry($aVals, $iUserId = null, $sUserName = null)
	{	
		
		$aEntry = $this->database()->select('u.full_name, u.user_id, u.gender, u.user_name, en.title, en.entry_id, ct.privacy, ct.privacy_comment,ct.user_id as owner_contest, ct.contest_id, ct.contest_name')
			->from($this->_sTable, 'en')
            ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id = en.contest_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = en.user_id')
			->where('en.entry_id = ' . (int) $aVals['item_id'])
			->execute('getSlaveRow');
		
		if ($iUserId === null)
		{
			$iUserId = Phpfox::getUserId();
		}
		
		// Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
		if (empty($aVals['parent_id']))
		{
			$this->database()->updateCounter('contest_entry', 'total_comment', 'entry_id', $aVals['item_id']);
		}
		
		// Send the user an email
		$sLink = Phpfox::permalink('contest', $aEntry['contest_id'], $aEntry['contest_name']).'entry_'.$aEntry['entry_id'].'/';
		
		//sent for follower
		$aList = Phpfox::getService('contest.participant')->getListFollowingByContestId($aEntry['contest_id']);
		$aList[] = array('user_id' => $aEntry['user_id']);
		if($aEntry['user_id']!=$aEntry['owner_contest'])
			$aList[] = array('user_id' => $aEntry['owner_contest']);
		
				foreach($aList as $List){
		
		Phpfox::getLib('mail')->to($List['user_id'])
				->subject(Phpfox::getPhrase('contest.full_name_commented_on_your_entry_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aEntry['title'])))
				->message(Phpfox::getPhrase('contest.full_name_commented_on_your_entry_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aEntry['title'])))
				->notification('comment.add_new_comment')
				->send();			
		
				if (Phpfox::isModule('notification'))
				{
					Phpfox::getService('notification.process')->add('comment_contest_entry', $aEntry['entry_id'], $List['user_id']);
				}
		}
		
		(($sPlugin = Phpfox_Plugin::get('contest.component_service_callback_addcomment__end')) ? eval($sPlugin) : false);
	}	


	//for contest
	public function addComment($aVals, $iUserId = null, $sUserName = null)
	{	
		$aContest = $this->database()->select('u.full_name, u.user_id, u.gender, u.user_name, ct.contest_name, ct.contest_id, ct.privacy, ct.privacy_comment,ct.user_id as owner_contest')
            ->from(Phpfox::getT('contest'), 'ct')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = ct.user_id')
			->where('ct.contest_id = ' . (int) $aVals['item_id'])
			->execute('getSlaveRow');
		
		if ($iUserId === null)
		{
			$iUserId = Phpfox::getUserId();
		}
		
		// Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
		if (empty($aVals['parent_id']))
		{
			$this->database()->updateCounter('contest', 'total_comment', 'contest_id', $aVals['item_id']);
		}
		
		// Send the user an email
		$sLink = Phpfox::permalink('contest', $aContest['contest_id'], $aContest['contest_name']);
		
		//sent for follower
		$aList = Phpfox::getService('contest.participant')->getListFollowingByContestId($aContest['contest_id']);
		$aList[] = array('user_id' => $aContest['user_id']);
		if($aContest['user_id']!=$aContest['owner_contest'])
			$aList[] = array('user_id' => $aContest['owner_contest']);
	
		foreach($aList as $List){
				 
			Phpfox::getLib('mail')->to($List['user_id'])
				->subject(Phpfox::getPhrase('contest.full_name_commented_on_your_contest_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aContest['contest_name'])))
				->message(Phpfox::getPhrase('contest.full_name_commented_on_your_contest_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aContest['contest_name'])))
				->notification('comment.add_new_comment')
				->send();			
		
				if (Phpfox::isModule('notification'))
				{
					Phpfox::getService('notification.process')->add('comment_contest', $aContest['contest_id'], $List['user_id']);
				}
		}
		
		(($sPlugin = Phpfox_Plugin::get('contest.component_service_callback_addcomment__end')) ? eval($sPlugin) : false);
	}	
	
    
	public function updateCommentText($aVals, $sText)
	{
		
	}
	
	
	public function getCommentItemEntry($iId)
	{
		$aRow = $this->database()->select('en.entry_id AS comment_item_id, ct.privacy_comment, en.user_id AS comment_user_id')
			->from($this->_sTable,'en')
                        ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id = en.contest_id')
			->where('entry_id = ' . (int) $iId)
			->execute('getSlaveRow');		
			
		$aRow['comment_view_id'] = '0';
		
		if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
		{
			unset($aRow['comment_item_id']);
		}
			
		return $aRow;
	}

	//for contest
	public function getCommentItem($iId)
	{
		$aRow = $this->database()->select('ct.contest_id AS comment_item_id, ct.privacy_comment, ct.user_id AS comment_user_id')
            ->from(Phpfox::getT('contest'), 'ct')
			->where('contest_id = ' . (int) $iId)
			->execute('getSlaveRow');		
			
		$aRow['comment_view_id'] = '0';
		
		if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
		{
			unset($aRow['comment_item_id']);
		}
			
		return $aRow;
	}
	
	
	public function getRedirectComment($iId)
	{
		return $this->getFeedRedirect($iId);
	}
	
	public function getReportRedirect($iId)
	{
		return $this->getFeedRedirect($iId);
	}
	
	public function getReportRedirectEntry($iId)
	{
		return $this->getFeedRedirect($iId);
	}
        
    public function getFeedRedirect($iId, $iChild = 0)
	{
		(($sPlugin = Phpfox_Plugin::get('contest.component_service_callback_getfeedredirect__start')) ? eval($sPlugin) : false);
		
		$aContest = $this->database()->select('c.contest_id, c.contest_name AS title')
			->from(Phpfox::getT('contest'), 'c')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
			->where('c.contest_id = ' . (int) $iId)
			->execute('getSlaveRow');		
		
		if (!isset($aContest['contest_id']))
		{
			return false;
		}					

		(($sPlugin = Phpfox_Plugin::get('contest.component_service_callback_getfeedredirect__end')) ? eval($sPlugin) : false);
		
		return Phpfox::permalink('contest', $aContest['contest_id'], $aContest['title']);
	}
        
	
	public function getCommentItemName()
	{
		return 'contest.entry';
	}
	
	public function deleteCommentEntry($iId)
	{
		$this->database()->update($this->_sTable, array('total_comment' => array('= total_comment -', 1)), 'entry_id = ' . (int) $iId);
	}
	
	//for contest
	public function deleteComment($iId)
	{
		$this->database()->update(Phpfox::getT('contest'), array('total_comment' => array('= total_comment -', 1)), 'contest_id = ' . (int) $iId);
	}
	
	public function getCommentNotificationEntry($aNotification)
	{
		$aRow = $this->database()->select('en.entry_id, en.title, en.user_id, u.gender, u.full_name, ct.contest_id, ct.contest_name')	
			->from(Phpfox::getT('contest_entry'), 'en')
            ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id = en.contest_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = en.user_id')
			->where('en.entry_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['entry_id']))
		{
			return false;
		}
		
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
		{
			$sPhrase = Phpfox::getPhrase('contest.users_commented_on_gender_entry_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('contest.users_commented_on_your_entry_title', array('users' => $sUsers, 'title' => $sTitle));
			}
		else 
		{
			$sPhrase = Phpfox::getPhrase('contest.users_commented_on_span_class_drop_data_user_row_full_name', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/',
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}

	//for contest
	public function getCommentNotification($aNotification)
	{
		$aRow = $this->database()->select('ct.contest_id, ct.contest_name, ct.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('contest'), 'ct')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = ct.user_id')
			->where('ct.contest_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['contest_id']))
		{
			return false;
		}
		
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['contest_name'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
		{
			$sPhrase = Phpfox::getPhrase('contest.users_commented_on_gender_contest_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('contest.users_commented_on_your_contest_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('contest.users_commented_on_span_class_drop_data_user_row_full_name_contest', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('contest', $aRow['contest_id'], $aRow['contest_name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}
        
	public function getCommentNotificationFeedEntry($aRow)
	{
		return array(
			'message' => Phpfox::getPhrase('contest.full_name_wrote_a_comment_on_your_entry_entry_title', array(
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
					'full_name' => $aRow['full_name'],
					'entry_link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/',
					'entry_title' => Phpfox::getLib('parse.output')->shorten($aRow['item_title'], 20, '...')	
				)
			),
			'link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/',
			'path' => 'core.url_user',
			'suffix' => '_50'
		);	
	}
	
	//for contest
	public function getCommentNotificationFeed($aRow)
	{
		return array(
			'message' => Phpfox::getPhrase('contest.full_name_wrote_a_comment_on_your_contest_contest_title', array(
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
					'full_name' => $aRow['full_name'],
					'contest_link' => Phpfox::getLib('url')->makeUrl('contest', array('redirect' => $aRow['item_id'])),
					'contest_title' => Phpfox::getLib('parse.output')->shorten($aRow['item_title'], 20, '...')	
				)
			),
			'link' => Phpfox::getLib('url')->makeUrl('contest', array('redirect' => $aRow['item_id'])),
			'path' => 'core.url_user',
			'suffix' => '_50'
		);	
	}
	
	public function getNotificationInvited($aNotification)
	{
		$aRow = $this->database()->select('c.contest_id, c.contest_name, c.user_id, u.full_name')	
			->from(Phpfox::getT('contest'), 'c')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
			->where('c.contest_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		if (!isset($aRow['contest_id']))
		{
			return false;
		}			
			
		$sPhrase = Phpfox::getPhrase('contest.users_invited_you_to_the_contest_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['contest_name'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('contest', $aRow['contest_id'], $aRow['contest_name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}
	
	public function getProfileLink()
	{
		return 'profile.contest';
	}
	
	public function getAjaxProfileController()
	{
		return 'contest.index';
	}
	
	public function getProfileMenu($aUser)
	{
		$total_contest = PHpfox::getService('contest.contest')->getCountContestByType('profile',$aUser['user_id']);
		$aUser['total_contest'] = $total_contest;
		$this->database()->update(Phpfox::getT('user_field'), array('total_contest' => $total_contest), 'user_id = ' . $aUser['user_id']);
		$aMenus[] = array(
			'phrase' => Phpfox::getPhrase('contest.contest'),
			'url' => 'profile.contest',
			'total' => (int) (isset($aUser['total_contest']) ? $aUser['total_contest'] : 0),
			'icon' => 'feed/contest.png'
		);		
		
		return $aMenus;
	}


	public function getNotificationNotice_Close($aNotification)
	{
		return $this->getNotificationNotice_GeneralContest($aNotification, $sType = 'close_contest');
	}

	public function getNotificationNotice_Join($aNotification)
	{
		return $this->getNotificationNotice_GeneralParticipant($aNotification, $sType = 'join_contest');
	}

	public function getNotificationNotice_Leave($aNotification)
	{
		return $this->getNotificationNotice_GeneralParticipant($aNotification, $sType = 'leave_contest');
	}

	public function getNotificationNotice_Favorite($aNotification)
	{
		return $this->getNotificationNotice_GeneralParticipant($aNotification, $sType = 'favorite_contest');
	}

	public function getNotificationNotice_Follow($aNotification)
	{
		return $this->getNotificationNotice_GeneralParticipant($aNotification, $sType = 'follow_contest');
	}

	public function getNotificationNotice_ApproveEntry($aNotification)
	{
		return $this->getNotificationNotice_GeneralEntry($aNotification, $sType = 'approve_entry');
	}

	public function getNotificationNotice_WinningEntry($aNotification)
	{
		return $this->getNotificationNotice_GeneralContest($aNotification, $sType = 'inform_winning_entry');
	}

	// because item_id identify participant or contest -> we devide into 2 general callback
	// item_id = contest_id
	public function getNotificationNotice_GeneralContest($aNotification, $sType = '')
	{

		$aRow = $this->database()->select('c.contest_id, c.contest_name, c.user_id, u.full_name')	
			->from(Phpfox::getT('contest'), 'c')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
			->where('c.contest_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		if (!isset($aRow['contest_id']))
		{
			return false;
		}			
			
		$sMessage= Phpfox::getService('contest.contest')->getNotifyingMessage($sType, $aRow);
		return array(
			'link' => Phpfox::getLib('url')->permalink('contest', $aRow['contest_id'], $aRow['contest_name']),
			'message' => $sMessage,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}

	// this function is dedicated for notification related to contest participant 
	// item id will be corresponding participant_id
	public function getNotificationNotice_GeneralParticipant($aNotification, $sType = '')
	{


		// notification when a user donate

		$aParticipant =  $this->database()->select('*')
                ->from(Phpfox::getT('contest_participant'), 'ctp')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = ctp.user_id')
                ->where('ctp.participant_id = ' . $aNotification['item_id'])
                ->execute('getRow');

        if(!isset($aParticipant['participant_id']))
        {
        	return false;
        }
		
		$aContest = $this->database()->select('c.contest_id, c.contest_name, c.user_id, u.full_name')	
			->from(Phpfox::getT('contest'), 'c')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
			->where('c.contest_id = ' . (int) $aParticipant['contest_id'])
			->execute('getSlaveRow');
			
		$sMessage= Phpfox::getService('contest.contest')->getNotifyingMessage($sType, $aContest, $aParticipant);
		return array(
			'link' => Phpfox::getLib('url')->permalink('contest', $aContest['contest_id'], $aContest['contest_name']),
			'message' => $sMessage,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}

	public function getNotificationNotice_GeneralEntry($aNotification, $sType = '')
	{

		// notification when a user donate

		$aEntry =  $this->database()->select('ce.*, ct.contest_id, ct.contest_name ,' . Phpfox::getUserField())
                ->from(Phpfox::getT('contest_entry'), 'ce')
                ->join(Phpfox::getT('contest'), 'ct', 'ct.contest_id = ce.contest_id')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = ce.user_id')
                ->where('ce.entry_id = ' . $aNotification['item_id'])
                ->execute('getRow');

        if(!isset($aEntry['entry_id']))
        {
        	return false;
        }
		
		$aContest = $this->database()->select('c.contest_id, c.contest_name, c.user_id, u.full_name')	
			->from(Phpfox::getT('contest'), 'c')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
			->where('c.contest_id = ' . (int) $aEntry['contest_id'])
			->execute('getSlaveRow');
			
		$sMessage= Phpfox::getService('contest.contest')->getNotifyingMessage($sType, $aContest, $aParticipant = $aEntry, $aEntry);
		return array(
			'link' => Phpfox::permalink('contest', $aRow['contest_id'], $aRow['contest_name']).'entry_'.$aRow['entry_id'].'/',
			'message' => $sMessage,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}

	public function getActivityPointField()
	{
		return array(
			Phpfox::getPhrase('contest.contests') => 'activity_contest'
		);
	}

	public function getDashboardActivity()
	{
		$aUser = Phpfox::getService('user')->get(Phpfox::getUserId(), true);
		
		return array(
			Phpfox::getPhrase('contest.contests') => $aUser['activity_contest']
		);
	}

	public function globalUnionSearch($sSearch)
	{
		$this->database()->select('item.contest_id AS item_id, item.contest_name AS item_title, item.time_stamp AS item_time_stamp, item.user_id AS item_user_id, \'contest\' AS item_type_id, item.image_path AS item_photo, item.server_id AS item_photo_server')
			->from(Phpfox::getT('contest'), 'item')
			->where($this->database()->searchKeywords('item.contest_name', $sSearch) . ' AND item.privacy = 0 AND item.contest_status > 3')
			->union();
	}

	public function getSearchInfo($aRow)
	{

		$aInfo = array();
		$aInfo['item_link'] = Phpfox::getLib('url')->permalink('contest', $aRow['item_id'], $aRow['item_title']);
		$aInfo['item_name'] = Phpfox::getPhrase('contest.contests');
	
		$aInfo['item_display_photo'] = Phpfox::getLib('image.helper')->display(array(
				'server_id' => $aRow['item_photo_server'],
				'file' => "contest/".$aRow['item_photo'],
				'path' => 'core.url_pic',
				'suffix' => '_160',
				'max_width' => '120',
				'max_height' => '120'				
			)
		);	

		return $aInfo;
	}

	public function getSearchTitleInfo()
	{
		return array(
			'name' => Phpfox::getPhrase('contest.contests')
		);
	}

}

?>