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

class Wall_Service_Photo_Process extends Phpfox_Service
{
	public function __construct()
    {
		$this->_sTable = Phpfox::getT('photo');
    }
	
    public function add($iUserId, $aVals, $bIsUpdate = false, $bAllowTitleUrl = false)
	{		
		$oParseInput = Phpfox::getLib('parse.input');
	
		// Create the fields to insert.
		$aFields = array();
	
		// Make sure we are updating the album ID
		(!empty($aVals['album_id']) ? $aFields['album_id'] = 'int' : null);
	
		// Is this an update?
		if ($bIsUpdate)
		{
		    // Make sure we only update the fields that the user is allowed to
		    (Phpfox::getUserParam('photo.can_add_mature_images') ? $aFields['mature'] = 'int' : null);
		    (Phpfox::getUserParam('photo.can_control_comments_on_photos') ? $aFields['allow_comment'] = 'int' : null);
		    ((Phpfox::getUserParam('photo.can_add_to_rating_module') && Phpfox::getParam('photo.can_rate_on_photos')) ? $aFields['allow_rate'] = 'int' : null);
		    (!empty($aVals['destination']) ? $aFields[] = 'destination' : null);
		    $aFields['allow_download'] = 'int';
		    $aFields['server_id'] = 'int';

	
		    // Check if we really need to update the title
		    if (!empty($aVals['title']))
		    {
				$aFields[] = 'title';

				// http://www.phpfox.com/tracker/view/14353/
				$bWindows = false;
				if (stristr(PHP_OS, "win"))
				{
					$bWindows = true;
				}
				else
				{
					$aVals['original_title'] = $aVals['title'];
				}

				// Clean the title for any sneaky attacks
				$aVals['title'] = $oParseInput->clean($aVals['title'], 255);

				if (Phpfox::getParam('photo.rename_uploaded_photo_names'))
				{
				    $aFields[] = 'destination';
		
				    $aPhoto = $this->database()->select('destination')
					    ->from($this->_sTable)
					    ->where('photo_id = ' . $aVals['photo_id'])
					    ->execute('getRow');
		
				    $sNewName = preg_replace("/^(.*?)-(.*?)%(.*?)$/", "$1-" . str_replace('%', '', ($bWindows ? $aVals['title'] : $aVals['original_title'])) . "%$3", $aPhoto['destination']);
		
				    $aVals['destination'] = $sNewName;
		
				    Phpfox::getLib('file')->rename(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], ''), Phpfox::getParam('photo.dir_photo') . sprintf($sNewName, ''));
		
				    // Create thumbnails with different sizes depending on the global param.
				    foreach(Phpfox::getParam('photo.photo_pic_sizes') as $iSize)
				    {
						Phpfox::getLib('file')->rename(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize), Phpfox::getParam('photo.dir_photo') . sprintf($sNewName, '_' . $iSize));
				    }
				}				

		    }
		    
		    $iAlbumId = (int) (empty($aVals['move_to']) ? (isset($aVals['album_id']) ? $aVals['album_id'] : 0) : $aVals['move_to']);
	
		    if (!empty($aVals['set_album_cover']))
		    {
		    	$aFields['is_cover'] = 'int';	
		    	$aVals['is_cover'] = '1';		
		    	
		    	$this->database()->update(Phpfox::getT('photo'), array('is_cover' => '0'), 'album_id = ' . (int) $iAlbumId);    
		    }
		    
		    if (!empty($aVals['move_to']))
		    {
		    	$aFields['album_id'] = 'int';
		    	$iOldAlbumId = $aVals['album_id'];
		    	$aVals['album_id'] = (int) $aVals['move_to'];
		    }
		    
		    if (isset($aVals['privacy']))
		    {
		    	$aFields['privacy'] = 'int';	
		    	$aFields['privacy_comment'] = 'int';	
		    }
		    
		    // Update the data into the database.
		    $this->database()->process($aFields, $aVals)->update($this->_sTable, 'photo_id = ' . (int) $aVals['photo_id']);
	
		    // Check if we need to update the description of the photo
		    if (!empty($aVals['description']))
		    {
				$aFieldsInfo = array(
					'description'
				);
		
				// Clean the data before we add it into the database
				//$aVals['description'] = $oParseInput->clean($aVals['description'], 255);
				$aVals['description'] = Phpfox::getService('wall.process')->compile($aVals['description'], $aVals['tagging']);
		    }    
	
		    (!empty($aVals['width']) ? $aFieldsInfo[] = 'width' : 0);
		    (!empty($aVals['height']) ? $aFieldsInfo[] = 'height' : 0);
	
		    // Check if we have anything to add into the photo_info table
		    if (isset($aFieldsInfo))
		    {
				$this->database()->process($aFieldsInfo, $aVals)->update(Phpfox::getT('photo_info'), 'photo_id = ' . (int) $aVals['photo_id']);
		    }
	
		    // Add tags for the photo
			if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support') && Phpfox::getUserParam('photo.can_add_tags_on_photos') && !empty($aVals['description']))
			{
				Phpfox::getService('tag.process')->update('photo', $aVals['photo_id'], $iUserId, $aVals['description'], true);
			}
			else
			{
				if (Phpfox::isModule('tag') && isset($aVals['tag_list']) && !empty($aVals['tag_list']) && Phpfox::getUserParam('photo.can_add_tags_on_photos'))
				{
					Phpfox::getService('tag.process')->update('photo', $aVals['photo_id'], $iUserId, $aVals['tag_list']);
				}
			}
	
		    // Make sure if we plan to add categories for this image that there is something to add
		    if (isset($aVals['category_id']) && count($aVals['category_id']))
		    {
				// Loop thru all the categories
				$this->database()->delete(Phpfox::getT('photo_category_data'), 'photo_id = ' . (int) $aVals['photo_id']);
				foreach ($aVals['category_id'] as $iCategory)
				{
				    // Add each of the categories
				    Phpfox::getService('photo.category.process')->updateForItem($aVals['photo_id'], $iCategory);
				}
		    }
		
		    $iId = $aVals['photo_id'];
		    
		    if (Phpfox::isModule('privacy') && isset($aVals['privacy']))
		    {
				if ($aVals['privacy'] == '4')
				{
					Phpfox::getService('privacy.process')->update('photo', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
				}
				else 
				{
					Phpfox::getService('privacy.process')->delete('photo', $iId);
				}	
		    }

			if (!empty($aVals['move_to']))
			{
				Phpfox::getService('photo.album.process')->updateCounter($iOldAlbumId, 'total_photo');
				Phpfox::getService('photo.album.process')->updateCounter($aVals['move_to'], 'total_photo');		
				$aCoverPhoto = $this->database()->select('photo_id')
					->from(Phpfox::getT('photo'))
					->where('album_id = ' . $iOldAlbumId)
					->order('time_stamp DESC')
					->execute('getRow');	
				if (isset($aCoverPhoto['photo_id']))
				{
					$this->database()->update(Phpfox::getT('photo'), array('is_cover' => '1'), 'photo_id = ' . (int) $aCoverPhoto['photo_id']);
				}					
			}								    
		}
		else
		{
		    if (!empty($aVals['callback_module']))
		    {
		    	$aVals['module_id'] = $aVals['callback_module'];
		    }
			
			// Define all the fields we need to enter into the database
		    $aFields['user_id'] = 'int';
		    $aFields['parent_user_id'] = 'int';
		    $aFields['type_id'] = 'int';
		    $aFields['time_stamp'] = 'int';
		    $aFields['server_id'] = 'int';
		    $aFields['view_id'] = 'int';
		    $aFields['group_id'] = 'int';
		    $aFields[] = 'module_id';
		    $aFields[] = 'title';
		    
		    if (isset($aVals['privacy']))
		    {
		    	$aFields['privacy'] = 'int';	
		    	$aFields['privacy_comment'] = 'int';	
		    }		    
	
		    // Define all the fields we need to enter into the photo_info table
		    $aFieldsInfo = array(
			    'photo_id' => 'int',
			    'file_name',
			    'mime_type',
			    'extension',
			    'file_size' => 'int',
			    'description'
		    );
	
		    // Clean and prepare the title and SEO title
		    $aVals['title'] = $oParseInput->clean(rtrim(preg_replace("/^(.*?)\.(jpg|jpeg|gif|png)$/i", "$1", $aVals['name'])), 255);
	
		    // Add the user_id
		    $aVals['user_id'] = $iUserId;
	
		    // Add the original server ID for LB.
		    $aVals['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
	
		    // Add the time stamp.
		    $aVals['time_stamp'] = PHPFOX_TIME;
	
		    $aVals['view_id'] = (Phpfox::getUserParam('photo.photo_must_be_approved') ? '1' : '0');
	
		    // Insert the data into the database.
		    if(empty($aVals['description']))
			{
				$aVals['description'] = '';
			}
			$aVals['description'] = Phpfox::getService('wall.process')->compile($aVals['description'], $aVals['tagging']);
		    $iId = $this->database()->process($aFields, $aVals)->insert($this->_sTable);
	
		    // Prepare the data to enter into the photo_info table
		    $aInfo = array(
			    'photo_id' => $iId,
			    'file_name' => Phpfox::getLib('parse.input')->clean($aVals['name'], 100),
			    'extension' => strtolower($aVals['ext']),
			    'file_size' => $aVals['size'],
			    'mime_type' => $aVals['type'],
			    'description' => $aVals['description']
		    );
	
		    // Insert the data into the photo_info table
		    $this->database()->process($aFieldsInfo, $aInfo)->insert(Phpfox::getT('photo_info'));
	
		    if (!Phpfox::getUserParam('photo.photo_must_be_approved'))
		    {
				// Update user activity
				Phpfox::getService('user.activity')->update($iUserId, 'photo');
		    }
		    
			if (isset($aVals['privacy']))
			{
				if ($aVals['privacy'] == '4')
				{
					Phpfox::getService('privacy.process')->add('photo', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));			
				}			    
			}

			if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support') && Phpfox::getUserParam('photo.can_add_tags_on_photos') && !empty($aVals['description']))
			{
				Phpfox::getService('tag.process')->add('photo', $iId, $iUserId, $aVals['description'], true);
			}
			
		}
	
		// Plugin call
		if ($sPlugin = Phpfox_Plugin::get('photo.service_process_add__end'))
		{
		    eval($sPlugin);
		}
	
		if($iId && !empty($aVals['tagging']))
        {
            $aTagging = json_decode($aVals['tagging'], true);
            $notified = array();
            foreach($aTagging as $iUserId => $aInfo)
            {
                if(in_array($iUserId, $notified))
                    continue;
                // Send notification
                Phpfox::getService('notification.process')->add('wall_photo', $iId, $iUserId);
                $notified[] = $iUserId;
            }
        }
		
		// Return the photo ID#
		return $iId;
    }

	public function update($iUserId, $iId, $aVals, $bAllowTitleUrl = false)
    {
		$aVals['photo_id'] = $iId;
	
        if (Phpfox::getParam('feed.cache_each_feed_entry') )
        {
            $this->cache()->remove(array('feeds', 'photo_' . $iId));
        }
        
		return $this->add($iUserId, $aVals, true, $bAllowTitleUrl);
    }
}