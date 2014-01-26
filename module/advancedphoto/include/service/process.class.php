<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Photo process class. Used to INSERT, UPDATE & DELETE photos.
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: process.class.php 4574 2012-07-31 09:35:22Z Miguel_Espinoza $
 */
class Advancedphoto_Service_Process extends Phpfox_Service
{
    /**
     * Class constructor
     */
    public function __construct()
    {
		$this->_sTable = Phpfox::getT('photo');
    }

	public function updateOrderOfAlbumPhoto($iPhotoId, $iOrder)
	{
		$iResult = $this->database()->update($this->_sTable, array('yn_ordering' => $iOrder), 'photo_id = ' . (int) $iPhotoId);

		if($iResult > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function makeProfilePicture($iId)
	{
		/* 1.Verify that $iId belongs to Phpfox::getUserId() */
		$aPhoto = $this->database()->select('p.destination, p.title, p.user_id, p.server_id')
			->from(Phpfox::getT('photo'), 'p')
			->where('p.user_id = ' . Phpfox::getUserId() . ' AND photo_id = ' . (int)$iId)
			->execute('getSlaveRow');
		
		if (empty($aPhoto) || !isset($aPhoto['destination']))
		{
			return false;
		}
		
		/* 2.copy the picture to <the folder that I need to find out> */
		$sTempName = PHPFOX_DIR_FILE .'pic' . PHPFOX_DS . 'photo' . PHPFOX_DS . sprintf($aPhoto['destination'],'');
		
		define('PHPFOX_USER_PHOTO_IS_COPY', true);
		
		$aRet = Phpfox::getService('user.process')->uploadImage(Phpfox::getUserId(), true, $sTempName);
		
		return (isset($aRet['user_image']) && !empty($aRet['user_image']));
	}
	
    /**
     * Adding a new photo.
     *
     * @param int $iUserId User ID of the user that the photo belongs to.
     * @param array $aVals Array of the post data being passed to insert.
     * @param boolean $bIsUpdate True if we plan to update the entry or false to insert a new entry in the database.
     * @param boolean $bAllowTitleUrl Set to true to allow the editing of the SEO url.
     *
     * @return int ID of the newly added photo or the ID of the current photo we are editing.
     */
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
			if(isset($aVals['yn_location']))
			{
				$aVals['yn_location'] = $oParseInput->clean($aVals['yn_location'], 255);		
				$aFields[] = 'yn_location';
			}

			if(isset($aVals['ynadvphoto_hour']))
			{
				$iDate = Phpfox::getLib('date')->mktime($aVals['ynadvphoto_hour'], $aVals['ynadvphoto_minute'], 0, $aVals['ynadvphoto_month'], $aVals['ynadvphoto_day'], $aVals['ynadvphoto_year']);	
				$aFields[] = 'time_stamp';
				$aVals['time_stamp'] = $iDate;
			}

		    (Phpfox::getUserParam('advancedphoto.can_add_mature_images') ? $aFields['mature'] = 'int' : null);
		    (Phpfox::getUserParam('advancedphoto.can_control_comments_on_photos') ? $aFields['allow_comment'] = 'int' : null);
		    ((Phpfox::getUserParam('advancedphoto.can_add_to_rating_module') && Phpfox::getParam('advancedphoto.can_rate_on_photos')) ? $aFields['allow_rate'] = 'int' : null);
		    (!empty($aVals['destination']) ? $aFields[] = 'destination' : null);
		    $aFields['allow_download'] = 'int';
	
		    // Check if we really need to update the title
		    if (!empty($aVals['title']))
		    {
				$aFields[] = 'title';
		
				// Clean the title for any sneaky attacks
				$aVals['title'] = $oParseInput->clean($aVals['title'], 255);
				
				if (Phpfox::getParam('advancedphoto.rename_uploaded_photo_names'))
				{
				    $aFields[] = 'destination';
		
				    $aPhoto = $this->database()->select('destination')
					    ->from($this->_sTable)
					    ->where('photo_id = ' . $aVals['photo_id'])
					    ->execute('getRow');
		
				    $sNewName = preg_replace("/^(.*?)-(.*?)%(.*?)$/", "$1-" . str_replace('%', '', $aVals['title']) . "%$3", $aPhoto['destination']);
		
				    $aVals['destination'] = $sNewName;
		
				    Phpfox::getLib('file')->rename(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], ''), Phpfox::getParam('photo.dir_photo') . sprintf($sNewName, ''));
		
				    // Create thumbnails with different sizes depending on the global param.
				    foreach(Phpfox::getParam('advancedphoto.photo_pic_sizes') as $iSize)
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
				$aVals['description'] = $oParseInput->clean($aVals['description']);
		    }    
	
		    (!empty($aVals['width']) ? $aFieldsInfo[] = 'width' : 0);
		    (!empty($aVals['height']) ? $aFieldsInfo[] = 'height' : 0);
	
		    // Check if we have anything to add into the photo_info table
		    if (isset($aFieldsInfo))
		    {
				$this->database()->process($aFieldsInfo, $aVals)->update(Phpfox::getT('photo_info'), 'photo_id = ' . (int) $aVals['photo_id']);
		    }
	
		    // Add tags for the photo
			if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support') && Phpfox::getUserParam('advancedphoto.can_add_tags_on_photos') && !empty($aVals['description']))
			{
				Phpfox::getService('tag.process')->update('advancedphoto', $aVals['photo_id'], $iUserId, $aVals['description'], true);
			}
			else
			{
			    if (Phpfox::isModule('tag') && isset($aVals['tag_list']) && !empty($aVals['tag_list']) && Phpfox::getUserParam('advancedphoto.can_add_tags_on_photos'))
			    {
					Phpfox::getService('tag.process')->update('advancedphoto', $aVals['photo_id'], $iUserId, $aVals['tag_list']);
					Phpfox::getService('tag.process')->deleteForItem($iUserId, $aVals['photo_id'], 'photo');
			    }
			}
	
		    // Make sure if we plan to add categories for this image that there is something to add
		    if (isset($aVals['category_id']) && count($aVals['category_id']))
		    {
				// Loop thru all the categories
				foreach ($aVals['category_id'] as $iCategory)
				{
				    // Add each of the categories
				    Phpfox::getService('advancedphoto.category.process')->updateForItem($aVals['photo_id'], $iCategory);
				}
		    }
		
		    $iId = $aVals['photo_id'];
		    
		    if (Phpfox::isModule('privacy') && isset($aVals['privacy']))
		    {
				if ($aVals['privacy'] == '4')
				{
					Phpfox::getService('privacy.process')->update('advancedphoto', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
				}
				else 
				{
					Phpfox::getService('privacy.process')->delete('advancedphoto', $iId);
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
			
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->update('advancedphoto', $iId, $aVals['privacy'], $aVals['privacy_comment'], 0, $iUserId) : null);			
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
	
		    $aVals['view_id'] = (Phpfox::getUserParam('advancedphoto.photo_must_be_approved') ? '1' : '0');


			//add 1 more field to distinguish between advphoto and photo module

//			$aVals['yn_is_advphoto'] = 1;
//			$aFields[] = 'yn_is_advphoto';
	
		    // Insert the data into the database.
		    $iId = $this->database()->process($aFields, $aVals)->insert($this->_sTable);
	
		    // Prepare the data to enter into the photo_info table
		    $aInfo = array(
			    'photo_id' => $iId,
			    'file_name' => Phpfox::getLib('parse.input')->clean($aVals['name'], 100),
			    'extension' => strtolower($aVals['ext']),
			    'file_size' => $aVals['size'],
			    'mime_type' => $aVals['type'],
			    'description' => (empty($aVals['description']) ? null : $this->preParse()->clean($aVals['description']))
		    );
	
		    // Insert the data into the photo_info table
		    $this->database()->process($aFieldsInfo, $aInfo)->insert(Phpfox::getT('photo_info'));
	
		    if (!Phpfox::getUserParam('advancedphoto.photo_must_be_approved'))
		    {
				// Update user activity
				Phpfox::getService('user.activity')->update($iUserId, 'advancedphoto');
		    }
			
		    // Make sure if we plan to add categories for this image that there is something to add
		    if (isset($aVals['category_id']) && count($aVals['category_id']))
		    {
				// Loop thru all the categories
				foreach ($aVals['category_id'] as $iCategory)
				{
				    // Add each of the categories
				    Phpfox::getService('advancedphoto.category.process')->updateForItem($iId, $iCategory);
				}
		    }			
		    
			if (isset($aVals['privacy']))
			{
				if ($aVals['privacy'] == '4')
				{
					Phpfox::getService('privacy.process')->add('advancedphoto', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));			
				}			    
			}

			if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support') && Phpfox::getUserParam('advancedphoto.can_add_tags_on_photos') && !empty($aVals['description']))
			{
				Phpfox::getService('tag.process')->add('advancedphoto', $iId, $iUserId, $aVals['description'], true);
			}

		}
	
		// Plugin call
		if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_process_add__end'))
		{
		    eval($sPlugin);
		}
	
		// Return the photo ID#
		return $iId;
    }

    /**
     * Updating a new photo. We piggy back on the add() method so we don't have to do the same code twice.
     *
     * @param int $iUserId User ID of the user that the photo belongs to.
     * @param array $aVals Array of the post data being passed to insert.
     * @param boolean $bAllowTitleUrl Set to true to allow the editing of the SEO url.
     *
     * @return int ID of the newly added photo or the ID of the current photo we are editing.
     */
    public function update($iUserId, $iId, $aVals, $bAllowTitleUrl = false)
    {
		$aVals['photo_id'] = $iId;
	
		return $this->add($iUserId, $aVals, true, $bAllowTitleUrl);
    }

    /**
     * Used to delete a photo.
     *
     * @param int $iId ID of the photo we want to delete.
     *
     * @return boolean We return true since if nothing fails we were able to delete the image.
     */
    public function delete($iId, $bPass = false)
    {
		// Get the image ID and full path to the image.
		$aPhoto = $this->database()->select('user_id, module_id, group_id, is_sponsor, album_id, photo_id, destination')
			->from($this->_sTable)
			->where('photo_id = ' . (int) $iId)
			->execute('getRow');
	
		if (!isset($aPhoto['user_id']))
		{
		    return false;
		}
		
		if ($aPhoto['module_id'] == 'pages' && Phpfox::getService('pages')->isAdmin($aPhoto['group_id']))
		{		
			$bPass = true;
		}
	
		if ($bPass === false && !Phpfox::getService('user.auth')->hasAccess('advancedphoto', 'photo_id', $iId, 'advancedphoto.can_delete_own_photo', 'advancedphoto.can_delete_other_photos', $aPhoto['user_id']))
		{
		    return false;
		}
	
		// Create the total file size var for all the images
		$iFileSizes = 0;
		// Make sure the original image exists
		if (!empty($aPhoto['destination']) && file_exists(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '')))
		{
		    // Add to the file size var
		    $iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], ''));
	
		    // Remove the image
		    unlink(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], ''));
		}
	
		// Loop thru all the other smaller images
		foreach(Phpfox::getParam('advancedphoto.photo_pic_sizes') as $iSize)
		{
		    // Make sure the image exists
		    if (!empty($aPhoto['destination']) && file_exists(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize)))
		    {
				// Add to the file size var
				$iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize));
		
				// Remove the image
				unlink(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize));
		    }
		}
	
		// Delete this entry from the database
		$this->database()->delete($this->_sTable, 'photo_id = ' . $aPhoto['photo_id']);
		$this->database()->delete(Phpfox::getT('photo_info'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the ratings for this photo
		$this->database()->delete(Phpfox::getT('photo_rating'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the photo tags
		$this->database()->delete(Phpfox::getT('photo_tag'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the category_data
		$this->database()->delete(Phpfox::getT('photo_category_data'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the battles
		$this->database()->delete(Phpfox::getT('photo_battle'), 'photo_1 = ' . $aPhoto['photo_id'] . ' OR photo_2 = ' . $aPhoto['photo_id']);
	
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.service_process_delete__1')) ? eval($sPlugin) : false);
		
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('advancedphoto', $iId) : null);
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('photo', $iId) : null);
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_photo', $iId) : null);
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_advancedphoto', $iId) : null);
		(Phpfox::isModule('tag') ? Phpfox::getService('tag.process')->deleteForItem($aPhoto['user_id'], $iId, 'photo') : null);
		(Phpfox::isModule('tag') ? Phpfox::getService('tag.process')->deleteForItem($aPhoto['user_id'], $iId, 'advancedphoto') : null);
	
		if (Phpfox::isUser() && Phpfox::isModule('notification'))
		{
			Phpfox::getService('notification.process')->delete('comment_advancedphoto', $iId, Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('advancedphoto_like', $iId, Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('advancedphoto_tag', $iId, Phpfox::getUserId());
		}	

		// Update user space usage
		if ($iFileSizes > 0)
		{
		    Phpfox::getService('user.space')->update($aPhoto['user_id'], 'photo', $iFileSizes, '-');
		}
	
		// Update user activity
		Phpfox::getService('user.activity')->update($aPhoto['user_id'], 'advancedphoto', '-');
	
		if ($aPhoto['album_id'] > 0)
		{
		    Phpfox::getService('advancedphoto.album.process')->updateCounter($aPhoto['album_id'], 'total_photo', true);
		}

		if ($aPhoto['is_sponsor'] == 1)
		{
			$this->cache()->remove('photo_sponsored');
		}
		return true;
    }

    /**
     * Update the photo counters.
     *
     * @param int $iId ID# of the photo
     * @param string $sCounter Field we plan to update
     * @param boolean $bMinus True increases to the count and false decreases the count
     */
    public function updateCounter($iId, $sCounter, $bMinus = false)
    {
	$this->database()->update($this->_sTable, array(
		$sCounter => array('= ' . $sCounter . ' ' . ($bMinus ? '-' : '+'), 1)
		), 'photo_id = ' . (int) $iId
	);
    }

    public function approve($iId)
    {
		$aPhoto = $this->database()->select('p.*, ' . Phpfox::getUserField())
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->where('p.photo_id = ' . (int) $iId)
			->execute('getSlaveRow');
	
		if (!isset($aPhoto['photo_id']))
		{
		    return false;
		}
		if ($aPhoto['view_id'] == '0')
		{
			return true;
		}
		
		$aCallback = (!empty($aPhoto['module_id']) ? Phpfox::callback($aPhoto['module_id'] . '.addPhoto', $aPhoto['photo_id']) : null);		
	
		$this->database()->update($this->_sTable, array('view_id' => 0, 'time_stamp' => PHPFOX_TIME), 'photo_id = ' . $aPhoto['photo_id']);
	
		Phpfox::getService('user.activity')->update($aPhoto['user_id'], 'photo');
	
		if ($aPhoto['album_id'] > 0)
		{
		    Phpfox::getService('advancedphoto.album.process')->updateCounter($aPhoto['album_id'], 'total_photo');
		}	
		
		if (Phpfox::isModule('notification'))
		{
			Phpfox::getService('notification.process')->add('advancedphoto_approved', $aPhoto['photo_id'], $aPhoto['user_id']);
		}		
		
		(Phpfox::isModule('feed') ? $iFeedId = Phpfox::getService('feed.process')->callback($aCallback)->add('advancedphoto', $aPhoto['photo_id'], $aPhoto['privacy'], $aPhoto['privacy_comment'], (!empty($aPhoto['group_id']) ? (int) $aPhoto['group_id'] : 0), $aPhoto['user_id']) : null);
		
		$sLink = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
		
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.service_process_approve__1')) ? eval($sPlugin) : false);
		
		Phpfox::getLib('mail')->to($aPhoto['user_id'])
			->subject(array('advancedphoto.your_photo_title_has_been_approved', array('title' => $aPhoto['title'])))
			->message( Phpfox::getPhrase('advancedphoto.your_photo_has_been_approved_message', array('sLink' => $sLink, 'title' => $aPhoto['title'])))
			->send();
	
		return true;
    }

    public function feature($iId, $sType)
    {
		$this->database()->update($this->_sTable, array('is_featured' => ($sType == '1' ? 1 : 0)), 'photo_id = ' . (int) $iId);
	
		$this->cache()->remove('photo_featured');
	
		return true;
    }

    public function sponsor($iId, $sType)
    {	    
	    if (!Phpfox::getUserParam('advancedphoto.can_sponsor_photo') && !Phpfox::getUserParam('advancedphoto.can_purchase_sponsor') && !defined('PHPFOX_API_CALLBACK'))
	    {
		    return Phpfox_Error::set('Hack attempt?');
	    }

	    $iType = (int)$sType;
	    if ($iType != 0 && $iType != 1)
	    {
		    return false;
	    }
	    // if it was featured it cannot be both and sponsored overrides featured. If it was sponsored it couldnt had been featured
	    $this->database()->update($this->_sTable, array('is_featured' => 0, 'is_sponsor' => $iType), 'photo_id = ' . (int)$iId);
	    $this->cache()->remove('photo_sponsored');
	    if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_process_sponsor__end'))
	    {
		    eval($sPlugin);
	    }
	    return true;
    }

    public function rotate($iId, $sCmd)
    {
		$aPhoto = $this->database()->select('user_id, title, photo_id, destination, server_id')
			->from($this->_sTable)
			->where('photo_id = ' . (int) $iId)
			->execute('getSlaveRow');
	
		if (!isset($aPhoto['photo_id']))
		{
		    return Phpfox_Error::set(Phpfox::getPhrase('advancedphoto.unable_to_find_the_photo_you_plan_to_edit'));
		}
	
		if (($aPhoto['user_id'] == Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.can_edit_own_photo')) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo'))
		{
		    foreach(array_merge(array(''), Phpfox::getParam('advancedphoto.photo_pic_sizes')) as $iSize)
		    {
				$sFile = Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], (empty($iSize) ? '' : '_') . $iSize);
				if (file_exists($sFile))
				{
				    Phpfox::getLib('image')->rotate($sFile, $sCmd);
				}
		    }
	
		    return $aPhoto;
		}
	
		return false;
    }
    
    public function massProcess($aAlbum, $aVals)
    {
    	if (isset($aVals['set_album_cover']))
    	{
    		$this->database()->update(Phpfox::getT('photo'), array('is_cover' => '0'), 'album_id = ' . $aAlbum['album_id']);
    		$this->database()->update(Phpfox::getT('photo'), array('is_cover' => '1'), 'photo_id = ' . $aVals['set_album_cover']);
    	}
    	
    	foreach ($aVals as $iPhotoId => $aVal)
    	{
    		if (!is_numeric($iPhotoId))
    		{
    			continue;
    		}
    		
    		if (isset($aVal['delete_photo']))
    		{
    			if (!$this->delete($iPhotoId))
    			{
    				return false;
    			}
    			
    			continue;
    		}
    		
    		$this->update($aAlbum['user_id'], $iPhotoId, $aVal);
    	}

    	return true;
    }

	/**
     * Resize and crop center image
     *
     * @param int $iImgId is id of photo (phpfox_photo)
     * @return: url of image with (hard) prefix "_slide1024"
     */
	public function cropCenterlizeByPhotoId($iImgId) {
		$oDb = Phpfox::getLib("database");
		$sPhotoUrl = Phpfox::getParam("photo.url_photo");
		$sPhotoDir = Phpfox::getParam("photo.dir_photo");
		$oImage = Phpfox::getLib('image');

		$aPhoto = $oDb->select("p.*, pi.*")
			->from(Phpfox::getT("photo"), "p")
			->join(Phpfox::getT("photo_info"), "pi", "pi.photo_id = p.photo_id")
			->where(sprintf("p.photo_id = %d ", $iImgId))
			->execute("getrow");

		if(file_exists($sPhotoDir . sprintf($aPhoto["destination"], "_slide1024"))) {
			return $sPhotoUrl . sprintf($aPhoto["destination"], "_slide1024");
		}

		$sImgRefUrl = sprintf($aPhoto["destination"], "");
		$iImgWidth = $aPhoto["width"];
		$iImgHeight = $aPhoto["height"];

		// $iImgNewHeight = ($iImgWidth < 1024 )?
			// ceil($iImgWidth * $iImgHeight / $iImgWidth):
			// ceil(1024 * $iImgHeight / $iImgWidth)
		// ;
		// $iImgNewWidth = ($iImgWidth < 1024 )?$iImgWidth:1024;

		$iImgENewHeight = ceil(1024 / sqrt(2));
		$iImgENewWidth = 1024;
		
		list($iImgNewWidth, $iImgNewHeight) = $this->_calcSize($iImgWidth, $iImgHeight, $iImgENewWidth, $iImgENewHeight);

		$iImgEHeight = ceil(1024 / sqrt(2));
		$iImgEWidth = 1024;

		$ret = $oImage->createThumbnail(
			$sPhotoDir . sprintf($aPhoto["destination"], ""),
			$sPhotoDir . sprintf($aPhoto["destination"], "_slide1024"),
			$iImgNewWidth,
			$iImgNewHeight,
			false
		);

		$oImage->cropImage(
			$sPhotoDir . sprintf($aPhoto["destination"], "_slide1024"),
			$sPhotoDir . sprintf($aPhoto["destination"], "_slide1024"),
			$iImgEWidth,
			$iImgEHeight,
			(-($iImgEWidth / 2) + ($iImgNewWidth / 2)),
			(-($iImgEHeight / 2) + ($iImgNewHeight / 2)),
			$iImgEWidth
		);
		return $sPhotoUrl . sprintf($aPhoto["destination"], "_slide1024");
	}

	/**
     * Resize and crop center image
     *
     * @param string $sPhotoUrlInfo is destination of photo (phpfox_photo)
	* $param int $iPhotoWith is with of photo (phpfox_photo_info)
	* $param int $iPhotoHeight is height of photo (phpfox_photo_info)
     * @return: url of image with (hard) prefix "_slide1024"
     */
	public function cropCenterlize($sPhotoUrlInfo, $iPhotoWith, $iPhotoHeight) {
		$oDb = Phpfox::getLib("database");
		$sPhotoUrl = Phpfox::getParam("photo.url_photo");
		$sPhotoDir = Phpfox::getParam("photo.dir_photo");
		$oImage = Phpfox::getLib('image');
        
		$aPhoto = array(
			"destination" => $sPhotoUrlInfo,
			"width" => $iPhotoWith,
			"height" => $iPhotoHeight,
		);

        $sRealFile = $sPhotoDir . sprintf($aPhoto["destination"], "");
        $sTempFile = $sPhotoDir . sprintf($aPhoto["destination"], "_slide1024_temp");
        $sDestination = $sPhotoDir . sprintf($aPhoto["destination"], "_slide1024");

		if(file_exists($sDestination)) {
			return $sPhotoUrl . sprintf($aPhoto["destination"], "_slide1024");
		}

		$iImgWidth = $aPhoto["width"];
		$iImgHeight = $aPhoto["height"];

		$iImgENewHeight = ceil(1024 / sqrt(2));
		$iImgENewWidth = 1024;
		
		list($iImgNewWidth, $iImgNewHeight) = $this->_calcSize($iImgWidth, $iImgHeight, $iImgENewWidth, $iImgENewHeight);

		$iImgEHeight = ceil(1024 / sqrt(2));
		$iImgEWidth = 1024;

		$ret = $oImage->createThumbnail($sRealFile, $sTempFile, $iImgNewWidth, $iImgNewHeight, false);
        
		$oImage->cropImage($sTempFile, $sDestination, $iImgEWidth, $iImgEHeight, (-($iImgEWidth / 2) + ($iImgNewWidth / 2)), (-($iImgEHeight / 2) + ($iImgNewHeight / 2)), $iImgEWidth);
        
        if (Phpfox::getParam('core.allow_cdn'))
		{
			Phpfox::getLib('cdn')->put($sDestination);
		}
        
        @unlink($sTempFile);
        
		return $sPhotoUrl . sprintf($aPhoto["destination"], "_slide1024");
	}
	
	/**
     * calculate new size base on max size
     *
     * @param int $nW is current width
     * @param int $nH is current height
	* $param int $nMaxW is max width
	* $param int $nMaxH is max height
     * @return: new size in format list<width, height>
     */
	protected function _calcSize($nW, $nH, $nMaxW, $nMaxH) {
		if($nMaxW > $nW && $nMaxH > $nH) {
			return array($nW, $nH);
		}
		
		$w  = $nMaxW;
		$h  = $nMaxH;

		if ($nW > $nMaxW) {
			$w  = $nMaxW;
			$h  = floor($nH * $nMaxW/$nW);
			if ($h > $nMaxH) {
				$h  = $nMaxH;
				$w  = floor($nW * $nMaxH/$nH);
			}
		} elseif ($nH > $nMaxH) {
			$h  = $nMaxH;
			$w  = floor($nW * $nMaxH/$nH);
		}

		return array($w, $h);
	}

    /**
     * If a call is made to an unknown method attempt to connect
     * it to a specific plug-in with the same name thus allowing
     * plug-in developers the ability to extend classes.
     *
     * @param string $sMethod is the name of the method
     * @param array $aArguments is the array of arguments of being passed
     */
    public function __call($sMethod, $aArguments)
    {
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_process__call'))
		{
		    return eval($sPlugin);
		}
	
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }
}

?>