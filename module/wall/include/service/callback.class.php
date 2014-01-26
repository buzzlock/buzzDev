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

class Wall_Service_Callback extends Phpfox_Service
{
	/**
	 * fix issue because 3.50 subsection wall is module name.
	 */
	public function getProfileLink()	
	{
		$url = Phpfox::getLib('url')->send('profile.advwall');			
	}
	
    public function getNotificationWallLink($aNotification)
    {
        $aOwner = $this->database()->select('*')
	        ->from(Phpfox::getT('user'))
	        ->where('user_id = ' . $aNotification['owner_user_id'])
	        ->execute('getSlaveRow');
        
        $sTitle = Phpfox::getPhrase('wall.someone_has_tagged_you_in_hisher_post');
        $sTitle = str_replace('{someone}', $aOwner['full_name'], $sTitle);
        return array(
            'link' => Phpfox::getLib('url')->makeUrl($aOwner['user_name']),
            'message' => $sTitle,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'tag.png', 'wall')
        );    
    }
    
    public function getNotificationStatus($aNotification)
    {
        $aOwner = $this->database()->select('*')
	        ->from(Phpfox::getT('user'))
	        ->where('user_id = ' . $aNotification['owner_user_id'])
	        ->execute('getSlaveRow');
        
        $sTitle = Phpfox::getPhrase('wall.someone_has_tagged_you_in_hisher_post');
        $sTitle = str_replace('{someone}', $aOwner['full_name'], $sTitle);
        return array(
            'link' => Phpfox::getLib('url')->makeUrl($aOwner['user_name'],array('status-id' => $aNotification['item_id'])),
            'message' => $sTitle,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'tag.png', 'wall')
        );
    }
    
	public function getNotificationPhoto($aNotification)
    {
        $aOwner = $this->database()->select('*')
	        ->from(Phpfox::getT('user'))
	        ->where('user_id = ' . $aNotification['owner_user_id'])
	        ->execute('getSlaveRow');
        
        $sTitle = Phpfox::getPhrase('wall.someone_has_tagged_you_in_hisher_post');
        $sTitle = str_replace('{someone}', $aOwner['full_name'], $sTitle);
        return array(
            'link' => Phpfox::getLib('url')->makeUrl($aOwner['user_name'],array('photo-id' => $aNotification['item_id'])),
            'message' => $sTitle,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'tag.png', 'wall')
        );
    }
	
    public function getNotificationComment($aNotification)
    {
    	$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name')
			->from(Phpfox::getT('feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.parent_user_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
        $aOwner = $this->database()->select('*')
	        ->from(Phpfox::getT('user'))
	        ->where('user_id = ' . $aNotification['owner_user_id'])
	        ->execute('getSlaveRow');
        
        $sTitle = Phpfox::getPhrase('wall.someone_has_tagged_you_in_hisher_comment');
        $sTitle = str_replace('{someone}', $aOwner['full_name'], $sTitle);
        return array(
            'link' => Phpfox::getLib('url')->makeUrl($aRow['user_name'], array('comment-id' => $aRow['feed_comment_id'])),
            'message' => $sTitle,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'tag.png', 'wall')
        );
    }
}