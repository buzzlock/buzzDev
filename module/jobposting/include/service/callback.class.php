<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright        [YOUNET_COPPYRIGHT]
 * @author           VuDP, AnNT
 * @package          Module_jobposting
 */

class JobPosting_Service_Callback extends Phpfox_service
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = Phpfox::getT('jobposting_job');
    }
    
    public function paymentApiCallback($aParam)
    {
        if(!$aParam['total_paid'])
        {
            return false;
        }
        
        $iTransactionId = $aParam['custom'];
        $aTransaction = Phpfox::getService('jobposting.transaction')->get($iTransactionId);
        if(!$aTransaction)
        {
            return false;
        }
        
        switch($aTransaction['payment_type'])
        {
            case 1: //sponsor
                if($aParam['status'] == 'completed')
                {
                    Phpfox::getService('jobposting.company.process')->sponsor($aTransaction['item_id']);
                }
                break;
            case 2: //package
                Phpfox::getService('jobposting.package.process')->updatePayStatus($aTransaction['invoice'], $aParam['status']);
                break;
            case 3: //package + publish
                Phpfox::getService('jobposting.package.process')->updatePayStatus($aTransaction['invoice'], $aParam['status']);
                if($aParam['status'] == 'completed')
				{
				    Phpfox::getService('user.auth')->setUserId($aTransaction['user_id']);
					Phpfox::getService("jobposting.job.process")->publish($aTransaction['invoice']['publish']);
                    Phpfox::getService('jobposting.package.process')->updateRemainingPost($aTransaction['invoice']['package_data'][0]);
				}
                break;
            case 4: //package + publish + feature
            	Phpfox::getService('jobposting.package.process')->updatePayStatus($aTransaction['invoice'], $aParam['status']);
				if($aParam['status'] == 'completed')
				{
				    Phpfox::getService('user.auth')->setUserId($aTransaction['user_id']);
					Phpfox::getService("jobposting.job.process")->publish($aTransaction['invoice']['publish']);
                    Phpfox::getService('jobposting.package.process')->updateRemainingPost($aTransaction['invoice']['package_data'][0]);
					Phpfox::getService("jobposting.job.process")->featureJobs($aTransaction['invoice']['feature'],1);
				}
                break;
            case 5: //feature
            	if($aParam['status'] == 'completed')
				{
					Phpfox::getService("jobposting.job.process")->featureJobs($aTransaction['invoice']['feature'],1);
				}
                break;
            default:
                #do nothing
        }
        
        Phpfox::getService('jobposting.transaction.process')->update($iTransactionId, $aParam);
    }
	
	public function getFeedDisplay($company_id)
	{
		return array(
			'module' => 'jobposting',
			'table_prefix' => 'jobposting_',
			'ajax_request' => 'jobposting.addFeedComment',
			'item_id' => $company_id
		);
	}

	public function getAjaxCommentVar()
	{
		return ;
	}	
	
	public function getActivityFeedComment($aItem)
	{

		$aRow = $this->database()->select('fc.*, l.like_id AS is_liked, e.company_id, e.name')
			->from(Phpfox::getT('jobposting_feed_comment'), 'fc')
			->join(Phpfox::getT('jobposting_company'), 'e', 'e.company_id = fc.parent_user_id')
			->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'jobposting_comment\' AND l.item_id = fc.feed_comment_id AND l.user_id = ' . Phpfox::getUserId())			
			->where('fc.feed_comment_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');		

		if (!isset($aRow['company_id']))
		{
			return false;
		}
		
		$sLink = Phpfox::getLib('url')->permalink(array('jobposting.company', 'comment-id' => $aRow['feed_comment_id']), $aRow['company_id'], $aRow['name']);
		
		$aReturn = array(
			'no_share' => true,
			'feed_status' => $aRow['content'],
			'feed_link' => $sLink,
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => $aRow['is_liked'],
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => true,			
			'comment_type_id' => 'jobposting',
			'like_type_id' => 'jobposting_comment'			
		);
		return $aReturn;		
	}	
	
	public function deleteComment($iId)
	{
		$this->database()->updateCounter('jobposting_company', 'total_comment', 'company_id', $iId, true);
		
	}
	
	public function addLikeComment($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('fc.feed_comment_id, fc.content, fc.user_id, e.company_id, e.name')
			->from(Phpfox::getT('jobposting_feed_comment'), 'fc')
			->join(Phpfox::getT('jobposting_company'), 'e', 'e.company_id = fc.parent_user_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->where('fc.feed_comment_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
			
		if (!isset($aRow['feed_comment_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'jobposting_comment\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'jobposting_feed_comment', 'feed_comment_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::getLib('url')->permalink(array('jobposting.company', 'comment-id' => $aRow['feed_comment_id']), $aRow['company_id'], $aRow['name']);
			$sItemLink = Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']);
			
			Phpfox::getLib('mail')->to($aRow['user_id'])
				->subject(array('jobposting.full_name_liked_a_comment_you_posted_on_the_jobposting_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['name'])))
				->message(array('jobposting.full_name_liked_your_comment_message_jobposting', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'content' => Phpfox::getLib('parse.output')->shorten($aRow['content'], 50, '...'), 'item_link' => $sItemLink, 'title' => $aRow['name'])))
				->notification('like.new_like')
				->send();
					
			Phpfox::getService('notification.process')->add('jobposting_comment_like', $aRow['feed_comment_id'], $aRow['user_id']);
		}
	}		
	
	public function deleteLikeComment($iItemId)
	{
		$this->database()->updateCount('like', 'type_id = \'jobposting_comment\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'jobposting_feed_comment', 'feed_comment_id = ' . (int) $iItemId);	
	}

	public function addPhoto($iId)
	{
		return array(
			'module' => 'jobposting',
			'item_id' => $iId,
			'table_prefix' => 'jobposting_'
		);
	}	

	public function addLink($aVals)
	{
		return array(
			'module' => 'jobposting',
			'item_id' => $aVals['callback_item_id'],
			'table_prefix' => 'jobposting_'
		);		
	}
	
	public function addLikeCompany($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('company_id, name, user_id')
			->from(Phpfox::getT('jobposting_company'))
			->where('company_id = ' . (int) $iItemId)
			->execute('getSlaveRow');		
			
		if (!isset($aRow['company_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'jobposting_company\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'jobposting_company', 'company_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::permalink('jobposting.company', $aRow['company_id'], $aRow['name']);
			
			Phpfox::getLib('mail')->to($aRow['user_id'])
				->subject(array('jobposting.full_name_liked_your_company_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['name'])))
				->message(array('jobposting.full_name_liked_your_company_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['name'])))
				->notification('like.new_like')
				->send();
					
			Phpfox::getService('notification.process')->add('jobposting_like', $aRow['company_id'], $aRow['user_id']);				
		}		
	}
	
	public function deleteLikeCompany($iItemId)
	{
		$this->database()->updateCount('like', 'type_id = \'jobposting_company\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'jobposting_company', 'company_id = ' . (int) $iItemId);
	}
	
	public function getNotificationLike($aNotification)
	{
		$aRow = $this->database()->select('e.company_id, e.name, e.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('jobposting_company'), 'e')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
			->where('e.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		if (!isset($aRow['company_id']))
		{
			return false;
		}			
			
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_gender_own_company_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_your_company_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_span_class_drop_data_user_row_full_name_s_span_company_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}	
	
	public function canShareItemOnFeed(){}	
		
	public function getActivityFeed($aItem, $aCallback = null, $bIsChildItem = false)
	{		
		if ($bIsChildItem)
		{
			$this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = e.user_id');
		}			

		$aRow = $this->database()->select('u.user_id, e.company_id, e.company_id, e.name, e.time_stamp, e.image_path, e.server_id as image_server_id, e.total_like, e.total_comment, et.description_parsed, l.like_id AS is_liked')
			->from(Phpfox::getT('jobposting_company'), 'e')
			->join(PHpfox::getT('user'),'u','u.user_id = e.user_id')
			->leftJoin(Phpfox::getT('jobposting_company_text'), 'et', 'et.company_id = e.company_id')
			->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'jobposting\' AND l.item_id = e.company_id AND l.user_id = ' . Phpfox::getUserId())
			->where('e.company_id = ' . (int) $aItem['item_id'].' and e.is_deleted = 0 and e.is_approved = 1')
			->execute('getSlaveRow');
	
		if (!isset($aRow['company_id']))
		{
			return false;
		}
			
		if ($bIsChildItem)
		{
			$aItem = $aRow;
		}			
		
		if ((defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages')->hasPerm(null, 'jobposting.view_browse_events'))
			|| (!defined('PHPFOX_IS_PAGES_VIEW') && $aRow['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aRow['item_id'], 'jobposint.view_browse_events'))			
		)
		{
			
			return false;
		}
		
		$aReturn = array(
			'feed_title' => $aRow['name'],
			'feed_info' => Phpfox::getPhrase('jobposting.created_an_company'),
			'feed_link' => Phpfox::permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'feed_content' => $aRow['description_parsed'],
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/company.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],	
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => $aRow['is_liked'],
			'enable_like' => true,			
			'like_type_id' => 'event',
			'total_comment' => $aRow['total_comment']			
		);
		
		if (!empty($aRow['image_path']))
		{
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aRow['image_server_id'],
					'path' => 'core.url_pic',
					'file' => 'jobposting/'.$aRow['image_path'],
					'suffix' => '_120',
					'max_width' => 120,
					'max_height' => 120					
				)
			);
			
			$aReturn['feed_image'] = $sImage;
		}		
		
		if ($bIsChildItem)
		{
			$aReturn = array_merge($aReturn, $aItem);
		}		
		
		(($sPlugin = Phpfox_Plugin::get('jobposting.component_service_callback_getactivityfeed__1')) ? eval($sPlugin) : false);
		
		return $aReturn;
	}	

	public function getFeedDetails($iItemId)
	{
		return array(
			'module' => 'jobposting',
			'table_prefix' => 'jobposting_',
			'item_id' => $iItemId
		);		
	}	
	
	public function getCommentItem($iId)
	{		
		$aRow = $this->database()->select('feed_comment_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
			->from(Phpfox::getT('jobposting_feed_comment'))
			->where('feed_comment_id = ' . (int) $iId)
			->execute('getSlaveRow');		
		
		$aRow['comment_view_id'] = '0';
		
		if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
		{
			Phpfox_Error::set(Phpfox::getPhrase('jobposting.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));
			
			unset($aRow['comment_item_id']);
		}		
		
		$aRow['parent_module_id'] = 'jobposting';
			
		return $aRow;
	}	
	
	
	
	public function addComment($aVals, $iUserId = null, $sUserName = null)
	{		
		$aRow = $this->database()->select('fc.feed_comment_id, fc.user_id, e.company_id, e.name, u.full_name, u.gender')
			->from(Phpfox::getT('jobposting_feed_comment'), 'fc')
			->join(Phpfox::getT('jobposting_company'), 'e', 'e.company_id = fc.parent_user_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->where('fc.feed_comment_id = ' . (int) $aVals['item_id'])
			->execute('getSlaveRow');
			
		// Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
		if (empty($aVals['parent_id']))
		{
			$this->database()->updateCounter('jobposting_feed_comment', 'total_comment', 'feed_comment_id', $aRow['feed_comment_id']);		
		}
		
		// Send the user an email
		$sLink = Phpfox::getLib('url')->permalink(array('jobposting.company', 'comment-id' => $aRow['feed_comment_id']), $aRow['company_id'], $aRow['name']);
		$sItemLink = Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']);
		
		Phpfox::getService('comment.process')->notify(array(
				'user_id' => $aRow['user_id'],
				'item_id' => $aRow['feed_comment_id'],
				'owner_subject' => Phpfox::getPhrase('jobposting.full_name_commented_on_a_comment_posted_on_the_company_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['name'])),
				'owner_message' => Phpfox::getPhrase('jobposting.full_name_commented_on_one_of_your_comments_you_posted_on_the_company', array('full_name' => Phpfox::getUserBy('full_name'), 'item_link' => $sItemLink, 'title' => $aRow['name'], 'link' => $sLink)),
				'owner_notification' => 'comment.add_new_comment',
				'notify_id' => 'jobposting_comment_feed',
				'mass_id' => 'jobposting',
				'mass_subject' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('jobposting.full_name_commented_on_one_of_gender_company_comments', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1))) : Phpfox::getPhrase('jobposting.full_name_commented_on_one_of_row_full_name_s_company_comments', array('full_name' => Phpfox::getUserBy('full_name'), 'row_full_name' => $aRow['full_name']))),
				'mass_message' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('jobposting.full_name_commented_on_one_of_gender_own_comments_on_the_company', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'item_link' => $sItemLink, 'title' => $aRow['name'], 'link' => $sLink)) : Phpfox::getPhrase('jobposting.full_name_commented_on_one_of_row_full_name_s', array('full_name' => Phpfox::getUserBy('full_name'), 'row_full_name' => $aRow['full_name'], 'item_link' => $sItemLink, 'title' => $aRow['name'], 'link' => $sLink)))
			)
		);
	}
	
	public function getNotificationComment_Feed($aNotification)
	{
		return $this->getCommentNotification($aNotification);	
	}
	
	public function uploadVideo($aVals)
	{
		return array(
			'module' => 'jobposting',
			'item_id' => $aVals['callback_item_id']
		);
	}
	
	public function convertVideo($aVideo)
	{
		return array(
			'module' => 'jobposting',
			'item_id' => $aVideo['item_id'],
			'table_prefix' => 'jobposing_'
		);			
	}
	
	public function getCommentNotification($aNotification)
	{
	
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.company_id, e.name')
			->from(Phpfox::getT('jobposting_feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->join(Phpfox::getT('jobposting_company'), 'e', 'e.company_id = fc.parent_user_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['feed_comment_id']))
		{
			return false;
		}
		
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
			{
				$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_span_class_drop_data_user_row_full_name_s_span_comment_on_the_company_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_gender_own_comment_on_the_company_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
			}
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_one_of_your_comments_on_the_company_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_one_of_span_class_drop_data_user_row_full_name_s_span_comments_on_the_company_tit', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink(array('jobposting.company', 'comment-id' => $aRow['feed_comment_id']), $aRow['company_id'], $aRow['company']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}		
	
	public function getNotificationComment($aNotification)
	{
		
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.company_id, e.name')
			->from(Phpfox::getT('jobposting_feed_comment'), 'fc')			
			->join(Phpfox::getT('jobposting_company'), 'e', 'e.company_id = fc.parent_user_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');

		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
			{
				$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_span_class_drop_data_user_row_full_name_s_span_company_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' =>  $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_gender_own_company_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
			}
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_your_company_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_span_class_drop_data_user_row_full_name_s_span_company_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}
	
	public function getNotificationComment_Like($aNotification)
	{
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.company_id, e.name')
			->from(Phpfox::getT('jobposting_feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->join(Phpfox::getT('jobposting_company'), 'e', 'e.company_id = fc.parent_user_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
			{
				$sPhrase = Phpfox::getPhrase('jobposting.users_liked_span_class_drop_data_user_row_full_name_s_span_comment_on_the_company_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('jobposting.users_liked_gender_own_comment_on_the_company_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
			}
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_one_of_your_comments_on_the_company_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_one_on_span_class_drop_data_user_row_full_name_s_span_comments_on_the_company_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink(array('jobposting_company', 'comment-id' => $aRow['feed_comment_id']), $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}

	public function getActions()
	{
		return array(
			'dislike' => array(
				'enabled' => true,
				'action_type_id' => 2, // 2 = dislike
				'phrase' => Phpfox::getPhrase('like.dislike'),
				'phrase_in_past_tense' => 'disliked',
				'item_type_id' => 'jobposting', // used to differentiate between photo albums and photos for example.
				'table' => 'jobposting_company',
				'item_phrase' => Phpfox::getPhrase('jobposting.item_phrase'),
				'column_update' => 'total_dislike',
				'column_find' => 'company_id'				
				)
		);
	}	



	
	//for commen box of job
	public function getActivityFeedCommentJob($aRow)
	{
		if (Phpfox::isUser())
		{
			$this->database()->select('l.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_mini\' AND l.item_id = c.comment_id AND l.user_id = ' . Phpfox::getUserId());
		}		
		
		$aItem = $this->database()->select('b.job_id, b.title, b.time_stamp, b.total_comment, b.total_like, c.total_like, ct.text_parsed AS text, ' . Phpfox::getUserField())
			->from(Phpfox::getT('comment'), 'c')
			->join(Phpfox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
			->join(Phpfox::getT('jobposting.job'), 'b', 'c.type_id = \'blog\' AND c.item_id = b.job_id AND c.view_id = 0')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('c.comment_id = ' . (int) $aRow['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aItem['job_id']))
		{
			return false;
		}
		
		$sLink = Phpfox::permalink('jobposting', $aItem['job_id'], $aItem['title']);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aItem['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') :50));
		$sUser = '<a href="' . Phpfox::getLib('url')->makeUrl($aItem['user_name']) . '">' . $aItem['full_name'] . '</a>';
		$sGender = Phpfox::getService('user')->gender($aItem['gender'], 1);
		
		if ($aRow['user_id'] == $aItem['user_id'])
		{
			$sMessage = Phpfox::getPhrase('jobposting.posted_a_comment_on_gender_job_a_href_link_title_a', array('gender' => $sGender, 'link' => $sLink, 'title' => $sTitle));
		}
		else
		{			
			$sMessage = Phpfox::getPhrase('jobposting.posted_a_comment_on_user_name_s_job_a_href_link_title_a', array('user_name' => $sUser, 'link' => $sLink, 'title' => $sTitle));
		}
		(($sPlugin = Phpfox_Plugin::get('job.component_service_callback_getactivityfeedcomment__1')) ? eval($sPlugin) : false);
		
		return array(
			'no_share' => true,
			'feed_info' => $sMessage,
			'feed_link' => $sLink,
			'feed_status' => $aItem['text'],
			'feed_total_like' => $aItem['total_like'],
			'feed_is_liked' => isset($aItem['is_liked']) ? $aItem['is_liked'] : false,
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/blog.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],
			'like_type_id' => 'feed_mini'
		);		
	}	

	public function canShareItemOnFeedJob(){}
	
	public function getActivityFeedJob($aRow, $aCallback = null, $bIsChildItem = false)
	{
		
		if (Phpfox::isUser())
		{
			$this->database()->select('l.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'jobposting_job\' AND l.item_id = b.job_id AND l.user_id = ' . Phpfox::getUserId());
		}
		
		if ($bIsChildItem)
		{
			$this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = b.user_id');
		}				
		
		$aRow = $this->database()->select('b.post_status, b.job_id, b.title, b.time_stamp, b.total_comment, b.total_like, bt.description_parsed AS text')
			->from(Phpfox::getT('jobposting_job'), 'b')
			->join(Phpfox::getT('jobposting_job_text'), 'bt', 'bt.job_id = b.job_id')
			->where('b.job_id = ' . (int) $aRow['item_id'].' and is_deleted = 0')
			->execute('getSlaveRow');
			
		if (empty($aRow))
		{
			return false;
		}
                
		(($sPlugin = Phpfox_Plugin::get('job.component_service_callback_getactivityfeed__1')) ? eval($sPlugin) : false);
		
		return array_merge(array(
			'feed_title' => $aRow['title'],
			'feed_info' => Phpfox::getPhrase('jobposting.posted_a_job'),
			'feed_link' => Phpfox::permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'feed_content' => $aRow['text'],
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => isset($aRow['is_liked']) ? $aRow['is_liked'] : false,
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/blog.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => true,			
			'comment_type_id' => 'jobposting_job',
			'like_type_id' => 'jobposting_job'			
		), $aRow);
	}

	public function addLikeJob($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('job_id, title, user_id')
			->from(Phpfox::getT('jobposting_job'))
			->where('job_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
			
		if (!isset($aRow['job_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'jobposting_job\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'jobposting_job', 'job_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::permalink('jobposting', $aRow['job_id'], $aRow['title']);
			
			Phpfox::getLib('mail')->to($aRow['user_id'])
				->subject(array('jobposting.full_name_liked_your_job_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
				->message(array('jobposting.full_name_liked_your_job_link_title', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['title'])))
				->notification('like.new_like')
				->send();
					
			Phpfox::getService('notification.process')->add('jobposting_job_like', $aRow['job_id'], $aRow['user_id']);
		}
	}

	public function getNotificationJob_Like($aNotification)
	{
		$aRow = $this->database()->select('b.job_id, b.title, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('jobposting_job'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_gender_own_event_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_your_event_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_liked_span_class_drop_data_user_row_full_name_s_span_job_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}

	public function deleteLikeJob($iItemId)
	{
		$this->database()->updateCount('like', 'type_id = \'jobposting_job\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'jobposting_job', 'job_id = ' . (int) $iItemId);	
	}
	
	public function getAjaxCommentVarJob()
	{
		return ;
	}
	
	public function addCommentJob($aVals, $iUserId = null, $sUserName = null)
	{
	
		$aJob = $this->database()->select('u.full_name, u.user_id, u.gender, u.user_name, b.title, b.job_id, b.privacy, b.privacy_comment')
			->from($this->_sTable, 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.job_id = ' . (int) $aVals['item_id'])
			->execute('getSlaveRow');
		
		if ($iUserId === null)
		{
			$iUserId = Phpfox::getUserId();
		}
		
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add($aVals['type'] . '_comment', $aVals['comment_id'], 0, 0, 0, $iUserId) : null);
		
		// Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
		if (empty($aVals['parent_id']))
		{
			
			$this->database()->updateCounter('jobposting_job', 'total_comment', 'job_id', $aVals['item_id']);
		}
		
		// Send the user an email
		$sLink = Phpfox::permalink('jobposting', $aJob['job_id'], $aJob['title']);
		
		Phpfox::getService('comment.process')->notify(array(
				'user_id' => $aJob['user_id'],
				'item_id' => $aJob['job_id'],
				'owner_subject' => Phpfox::getPhrase('jobposting.full_name_commented_on_your_job_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aJob['title'])),
				'owner_message' => Phpfox::getPhrase('jobposting.full_name_commented_on_your_job_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aJob['title'])),
				'owner_notification' => 'comment.add_new_comment',
				'notify_id' => 'comment_jobposting_job',
				'mass_id' => 'jobposting_job',
				'mass_subject' => (Phpfox::getUserId() == $aJob['user_id'] ? Phpfox::getPhrase('jobposting.full_name_commented_on_gender_job', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' =>  Phpfox::getService('user')->gender($aJob['gender'], 1))) : Phpfox::getPhrase('jobposting.full_name_commented_on_job_full_name_s_job', array('full_name' => Phpfox::getUserBy('full_name'), 'blog_full_name' => $aJob['full_name']))),
				'mass_message' => (Phpfox::getUserId() == $aJob['user_id'] ? Phpfox::getPhrase('jobposting.full_name_commented_on_gender_job_message', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aJob['gender'], 1), 'link' => $sLink, 'title' => $aJob['title'])) : Phpfox::getPhrase('jobposting.full_name_commented_on_job_full_name_s_job_message', array('full_name' => Phpfox::getUserBy('full_name'), 'blog_full_name' => $aJob['full_name'], 'link' => $sLink, 'title' => $aJob['title'])))
			)
		);
	
	}	

	public function updateCommentText($aVals, $sText)
	{
		
	}
	
	public function updateCommentTextJob($aVals, $sText)
	{
		
	}
	
	public function getCommentItemJob($iId)
	{
		$aRow = $this->database()->select('job_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
			->from($this->_sTable)
			->where('job_id = ' . (int) $iId)
			->execute('getSlaveRow');		
			
		$aRow['comment_view_id'] = '0';
		
		if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
		{
			Phpfox_Error::set(Phpfox::getPhrase('jobposting.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));
			
			unset($aRow['comment_item_id']);
		}
			
		return $aRow;
	}
	
	public function getCommentItemNameJob()
	{
		return 'jobposting_job';
	}

	public function deleteCommentJob($iId)
	{
		$this->database()->update($this->_sTable, array('total_comment' => array('= total_comment -', 1)), 'job_id = ' . (int) $iId);
	}
	
	public function getCommentNotificationJob($aNotification)
	{
		$aRow = $this->database()->select('b.job_id, b.title, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('jobposting_job'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['job_id']))
		{
			return false;
		}
		
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_gender_job_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_your_job_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('jobposting.users_commented_on_span_class_drop_data_user_row_full_name', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}

	public function getCommentNotificationFeedJob($aRow)
	{
		return array(
			'message' => Phpfox::getPhrase('jobposting.full_name_wrote_a_comment_on_your_job_job_title', array(
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
					'full_name' => $aRow['full_name'],
					'job_link' => Phpfox::getLib('url')->makeUrl('jobposting', array('redirect' => $aRow['item_id'])),
					'job_title' => Phpfox::getLib('parse.output')->shorten($aRow['item_title'], 20, '...')	
				)
			),
			'link' => Phpfox::getLib('url')->makeUrl('jobposting', array('redirect' => $aRow['item_id'])),
			'path' => 'core.url_user',
			'suffix' => '_50'
		);	
	}
		
	public function getCommentNotificationTagJob($aNotification)
	{
		$aRow = $this->database()->select('b.job_id, b.title, u.user_name, u.full_name')
					->from(Phpfox::getT('comment'), 'c')
					->join(Phpfox::getT('jobposting_job'), 'b', 'b.job_id = c.item_id')
					->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
					->where('c.comment_id = ' . (int)$aNotification['item_id'])
					->execute('getSlaveRow');
		
		
		$sPhrase = Phpfox::getPhrase('jobposting.user_name_tagged_you_in_a_comment_in_a_job', array('user_name' => $aRow['full_name']));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']) . 'comment_' .$aNotification['item_id'],
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}
	
	public function getSiteStatsForAdmin($iStartTime, $iEndTime)
	{
		$aCond = array();
		$aCond[] = 'is_approved = 1 AND post_status = 1';
		if ($iStartTime > 0)
		{
			$aCond[] = 'AND time_stamp >= \'' . $this->database()->escape($iStartTime) . '\'';
		}	
		if ($iEndTime > 0)
		{
			$aCond[] = 'AND time_stamp <= \'' . $this->database()->escape($iEndTime) . '\'';
		}			
		
		$iCnt = (int) $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('jobposting_job'))
			->where($aCond)
			->execute('getSlaveField');
		
		return array(
			'phrase' => 'jobposting.jobs',
			'total' => $iCnt
		);
	}	
	
	public function getSiteStatsForAdmins()
	{
		$iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		
		return array(
			'phrase' => Phpfox::getPhrase('jobposting.jobs'),
			'value' => $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('jobposting_job'))
				->where('post_status = 1 AND time_stamp >= ' . $iToday)
				->execute('getSlaveField')
		);
	}
	
	public function pendingApproval()
	{
		return array(
			'phrase' => Phpfox::getPhrase('jobposting.jobs'),
			'value' => Phpfox::getService('jobposting.job')->getPendingTotal(),
			'link' => Phpfox::getLib('url')->makeUrl('jobposting', array('view' => 'pending_jobs'))
		);
	}

	public function getActivityFeedCompany($aRow, $aCallback = null, $bIsChildItem = false)
	{
		
		if (Phpfox::isUser())
		{
			$this->database()->select('l.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'jobposting_company\' AND l.item_id = b.company_id AND l.user_id = ' . Phpfox::getUserId());
		}
		
		if ($bIsChildItem)
		{
			$this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = b.user_id');
		}				
		
		$aRow = $this->database()->select('b.company_id, b.name, b.image_path, b.server_id as image_server_id, b.time_stamp, b.total_comment, b.total_like, bt.description_parsed AS description')
			->from(Phpfox::getT('jobposting_company'), 'b')
			->join(Phpfox::getT('jobposting_company_text'), 'bt', 'bt.company_id = b.company_id')
			->where('b.company_id = ' . (int) $aRow['item_id'].' and b.is_deleted = 0 and b.is_approved = 1')
			->execute('getSlaveRow');
		
		if (empty($aRow))
		{
			return false;
		}
		
		$aReturn = array(
			'feed_title' => $aRow['name'],
			'feed_info' => Phpfox::getPhrase('jobposting.created_a_company'),
			'feed_link' => Phpfox::permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'feed_content' => $aRow['description'],
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => isset($aRow['is_liked']) ? $aRow['is_liked'] : false,
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/blog.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],
			'enable_like' => true,
			'comment_type_id' => 'jobposting_company',
			'like_type_id' => 'jobposting_company'			
		);
        
        $aReturn['feed_image'] = Phpfox::getLib('image.helper')->display(array(
				'server_id' => $aRow['image_server_id'],
				'path' => 'core.url_pic',
				'file' => 'jobposting/'.$aRow['image_path'],
				'suffix' => '_120',
				'max_width' => 120,
				'max_height' => 120			
			)
		);
        
        return $aReturn;
	}
	
	public function getNotificationInvite_Job($aNotification)
	{
		$aRow = $this->database()->select('j.job_id, j.title')	
			->from(Phpfox::getT('jobposting_job'), 'j')
			->where('j.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		if (!isset($aRow['job_id']))
		{
			return false;
		}
        
        $sPhrase = Phpfox::getPhrase('jobposting.full_name_invited_you_to_the_type_title', array(
            'full_name' => Phpfox::getService('notification')->getUsers($aNotification),
            'type' => 'job',
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));

		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}

	public function getNotificationInvite_Company($aNotification)
	{
		$aRow = $this->database()->select('c.company_id, c.name')	
			->from(Phpfox::getT('jobposting_company'), 'c')
			->where('c.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
			
		if (!isset($aRow['company_id']))
		{
			return false;
		}
        
        $sPhrase = Phpfox::getPhrase('jobposting.full_name_invited_you_to_the_type_title', array(
            'full_name' => Phpfox::getService('notification')->getUsers($aNotification),
            'type' => 'company',
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));

		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}

	public function getNotificationFavoriteJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_favorited_your_job_title', array(
            'users' => $sUsers,
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationFavoriteFollowedJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_favorited_your_followed_job_title', array(
            'users' => $sUsers,
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationFavoriteCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_favorited_your_company_name', array(
            'users' => $sUsers,
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationFavoriteFollowedCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_favorited_your_followed_company_name', array(
            'users' => $sUsers,
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationFollowJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_followed_your_job_title', array(
            'users' => $sUsers,
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationFollowCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_followed_your_company_name', array(
            'users' => $sUsers,
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationApplyJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_applied_your_job_title', array(
            'users' => $sUsers,
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company.manage', 'job_'.$aRow['job_id']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationJoinCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_joined_your_company_name', array(
            'users' => $sUsers,
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationJoinFollowedCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.users_joined_your_followed_company_name', array(
            'users' => $sUsers,
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationFeatureFollowedJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_followed_job_title_has_been_featured', array(
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationSponsorFollowedCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_followed_company_name_has_been_sponsored', array(
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationAddJobFollowedCompany($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title, c.company_id, c.name')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->join(Phpfox::getT('jobposting_company'), 'c', 'c.company_id = i.company_id')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_followed_company_name_posted_a_job_title', array(
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], 55, '...'),
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], 55, '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationDeleteFollowedJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_followed_job_title_has_been_deleted', array(
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationDeleteAppliedJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_applied_job_title_has_been_deleted', array(
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationDeleteFollowedCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_followed_company_name_has_been_deleted', array(
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationDeleteAppliedCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_applied_company_name_has_been_deleted', array(
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationDeleteCompanyFollowedJob($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.the_company_of_your_followed_job_name_has_been_deleted', array(
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationExpireJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_job_title_is_expired', array(
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationExpireFollowedJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_followed_job_title_is_expired', array(
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationExpireAppliedJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_applied_job_title_is_expired', array(
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationApproveJob($aNotification)
	{
		$aRow = $this->database()->select('i.job_id, i.title')	
			->from(Phpfox::getT('jobposting_job'), 'i')
			->where('i.job_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['job_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_job_title_has_been_approved', array(
            'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting', $aRow['job_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function getNotificationApproveCompany($aNotification)
	{
		$aRow = $this->database()->select('i.company_id, i.name')	
			->from(Phpfox::getT('jobposting_company'), 'i')
			->where('i.company_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        if (!isset($aRow['company_id']))
		{
			return false;
		}
		
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sPhrase = Phpfox::getPhrase('jobposting.your_company_name_has_been_approved', array(
            'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')
        ));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('jobposting.company', $aRow['company_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}	
	
	public function globalUnionSearch($sSearch)
	{
		$this->database()->select('item.job_id AS item_id, item.title AS item_title, item.time_stamp AS item_time_stamp, item.user_id AS item_user_id, \'jobposting\' AS item_type_id, jc.image_path AS item_photo, jc.server_id AS item_photo_server')
			->from(Phpfox::getT('jobposting_job'), 'item')
            ->join(Phpfox::getT('jobposting_company'), 'jc', 'jc.company_id = item.company_id')
			->where('item.post_status = 1 AND item.privacy = 0 AND item.is_approved = 1 AND item.is_deleted = 0 AND ' . $this->database()->searchKeywords('item.title', $sSearch))
			->union();
	}
	
	public function getSearchInfo($aRow)
	{
		$aInfo = array();
		$aInfo['item_link'] = Phpfox::getLib('url')->permalink('jobposting', $aRow['item_id'], $aRow['item_title']);
		$aInfo['item_name'] = Phpfox::getPhrase('jobposting.job');
		
		$aInfo['item_display_photo'] = Phpfox::getLib('image.helper')->display(array(
				'server_id' => $aRow['item_photo_server'],
				'file' => 'jobposting/'.$aRow['item_photo'],
				'path' => 'core.url_pic',
				'suffix' => '_120',
				'max_width' => '120',
				'max_height' => '120'				
			)
		);		
		
		return $aInfo;
	}
	
	public function getSearchTitleInfo()
	{
		return array(
			'name' => Phpfox::getPhrase('jobposting.job')
		);
	}
	
}