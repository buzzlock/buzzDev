<?php
defined('PHPFOX') or exit('NO DICE!');

class SocialMediaImporter_Service_Callback extends Phpfox_Service
{
	/**
	 * Class constructor
	 */
	public function __construct()
	{

	}

	public function getActivityFeed($aItem, $aCallback = null, $bIsChildItem = false)
	{
		$iQueueId = $aItem['item_id'];
		$this->database()->select('q.*, l.like_id AS is_liked')
			->from(Phpfox::getT('socialmediaimporter_queue'), 'q')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = q.user_id')
			->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'socialmediaimporter\' AND l.item_id = q.queue_id AND l.user_id = ' . Phpfox::getUserId())
			->where('queue_id = '. $iQueueId);
		if ($bIsChildItem)
		{
			$this->database()->select(", " . Phpfox::getUserField('u2'))->join(Phpfox::getT('user'), 'u2', 'u2.user_id = u.user_id');
			$aRow = $this->database()->execute('getSlaveRow');
			$aItem = $aRow;
		} else {
			$this->database()->select(", u.user_name");
			$aRow = $this->database()->execute('getSlaveRow');
		}
		if (!$aRow) return false;
		if (!$aRow['feed_photo_ids']) return false;

		$bIsPhotoAlbum = false;
		if ($aRow['album_ids'])
		{
			$bIsPhotoAlbum = true;
		}

		$aPhotos = $this->database()->select('photo_id, album_id, user_id, title, server_id, destination, mature')
			->from(Phpfox::getT('photo'))
			->where('photo_id IN (' . $aRow['feed_photo_ids'] . ')')
			->limit(3)
			->order('time_stamp DESC')
			->execute('getSlaveRows');

		$aListPhotos = array();
        
        $iMinSize = Phpfox::getService('socialmediaimporter.common')->getMinPicSize(100);

		foreach ($aPhotos as $aPhoto)
		{
			$aListPhotos[] = '<a href="' . Phpfox::permalink('photo', $aPhoto['photo_id'], $aPhoto['title']) . ($bIsPhotoAlbum ? 'albumid_' . $aPhoto['album_id'] : 'userid_' . $aRow['user_id']) . '/" class="thickbox photo_holder_image" rel="' . $aPhoto['photo_id'] . '">' . Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aPhoto['server_id'],
					'path' => 'photo.url_photo',
					'file' => Phpfox::getService('photo')->getPhotoUrl(array_merge($aPhoto, array('full_name' => $aItem['full_name']))),
					'suffix' => '_'.$iMinSize,
					'max_width' => 100,
					'max_height' => 100,
					'class' => 'photo_holder',
					'userid' => isset($aItem['user_id']) ? $aItem['user_id'] : ''
				)
			) . '</a>';
		}
		$sLink = Phpfox::getLib('url')->makeUrl($aRow['user_name']) . 'photo';
		$aReturn = array(
			'feed_title' => '',
			'feed_image' => $aListPhotos,
			'feed_info' => Phpfox::getPhrase('socialmediaimporter.imported_number_photos_from_service', array('link' => $sLink, 'number' => $aRow['total_success'], 'service' => ucfirst($aRow['service_name']))),
			'feed_link' => $sLink,
			'feed_content' => '',
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/photo.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],
			'feed_is_liked' => $aRow['is_liked'],
			'feed_total_like' => $aRow['total_like'],
			'enable_like' => true,
			'like_type_id' => 'socialmediaimporter',
		);
		
		if ($bIsChildItem)
		{
			$aReturn = array_merge($aReturn, $aItem);
		}	
		return $aReturn;
	}

	public function getNotificationImport($aNotification)
	{
		$iQueueId = $aNotification['item_id'];
		$aRow = $this->database()->select('q.*, u.user_name')
			->from(Phpfox::getT('socialmediaimporter_queue'), 'q')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = q.user_id')
			->where('queue_id = '. $iQueueId)
			->execute('getSlaveRow');
		if (!$aRow) return false;
		$aAlbumIds = explode(',', $aRow['album_ids']);
		$sLink = Phpfox::getLib('url')->makeUrl($aRow['user_name'] . '.photo');
		if (count($aAlbumIds) == 1 && $aAlbumIds[0])
		{
			$iAlbumId = $aAlbumIds[0];
			$aAlbum = $this->database()->select('*')
				->from(Phpfox::getT('photo_album'))
				->where("album_id = " . $iAlbumId)
				->execute('getSlaveRow');
			if ($aAlbum)
			{
				$sLink = Phpfox::getLib('url')->permalink('photo.album', $iAlbumId, $aAlbum['name']);
			}
		}
		elseif (count($aAlbumIds) > 1)
		{
			$sLink = Phpfox::getLib('url')->makeUrl('photo.albums', array('view' => 'myalbums'));
		}
		$sMessage = '';
		if ($aRow['error_code'] == 0)
		{
			$sMessage = Phpfox::getPhrase('socialmediaimporter.notification_of_import_photos', array('success' => $aRow['total_success'], 'total' => $aRow['total_media']));
		}
		elseif ($aRow['error_code'] == 1)
		{
			$sMessage = Phpfox::getPhrase('socialmediaimporter.message_of_import_photos_error_space', array('success' => $aRow['total_success'], 'total' => $aRow['total_media']));
		}
		return array (
            'link' => $sLink,
            'message' => $sMessage,
            'icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/photo.png', 'return_url' => true)),
        );
	}

	public function addLike($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('*')
			->from(Phpfox::getT('socialmediaimporter_queue'))
			->where('queue_id = ' . (int) $iItemId)
			->execute('getSlaveRow');

		$iUserId = $aRow['user_id'];

		if (!isset($aRow['queue_id']))
		{
			return false;
		}

		$this->database()->updateCount('like', 'type_id = \'socialmediaimporter\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'socialmediaimporter_queue', 'queue_id = ' . (int) $iItemId);
	}

	public function deleteLike($iItemId)
	{
		$this->database()->updateCount('like', 'type_id = \'socialmediaimporter\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'socialmediaimporter_queue', 'queue_id = ' . (int) $iItemId);
	}

	public function canShareItemOnFeed(){
		return true;
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
		if ($sPlugin = Phpfox_Plugin::get('socialmediaimporter.service_callback__call'))
		{
			eval($sPlugin);
			return;
		}

		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}
}

?>
