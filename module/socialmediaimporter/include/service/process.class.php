<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		YouNet Company
 * @package 		Phpfox_SocialMediaImporter
 */

class SocialMediaImporter_Service_Process extends Phpfox_Service
{
	/**
	 * Class constructor
	 */
	public function __construct()
	{

	}

	public function addQueue($aParams, $aFoxAlbums = array(), $aTempAlbumIds = array())
	{
		$iUserId = Phpfox::getUserId();
		$iPrivacy = isset($aParams['privacy']) ? $aParams['privacy'] : 0;
		$iPrivacyComment = isset($aParams['privacy_comment']) ? $aParams['privacy_comment'] : 0;
		$sPrivacyList = isset($aParams['privacy_list']) ? $aParams['privacy_list'] : null;
		$sServiceName = isset($aParams['service']) ? $aParams['service'] : '';
		$sMediaType = isset($aParams['media_type']) ? $aParams['media_type'] : 'photo';
		$aMedias = isset($aParams['media']) ? $aParams['media'] : array();
		$iTotalMedia = count($aMedias);
		$iTimeStamp = PHPFOX_TIME;
		$iQueueId = 0;
		if ($iUserId && $iTotalMedia)
		{
			$sFoxAlbums = '';
			if (count($aFoxAlbums) > 0)
			{
				$sFoxAlbums = implode(',', $aFoxAlbums);
			}
			$sTempAlbums = '';
			if (count($aTempAlbumIds) > 0)
			{
				$sTempAlbums = implode(',', $aTempAlbumIds);
			}
			$iQueueId = $this->database()->insert(Phpfox::getT('socialmediaimporter_queue'), array(
					'user_id' => $iUserId,
					'album_ids' => $sFoxAlbums,
					'temp_album_ids' => $sTempAlbums,
					'service_name' => $sServiceName,
					'total_media' => $iTotalMedia,
					'privacy' => $iPrivacy,
					'privacy_comment' => $iPrivacyComment,
					'privacy_list' => $sPrivacyList,
					'time_stamp' => $iTimeStamp
				)
			);
			if ($iQueueId)
			{
				$aInsert = array();
				foreach ($aMedias as $aMedia)
				{
					$aInsert = array ('media_type' => $sMediaType, 'album_id' => $aMedia['album_id'], 'media_id' => $aMedia['photo_id'], 'is_cover' => $aMedia['is_cover'], 'media_path' => $aMedia['photo_large'], 'status' => 'pending', 'queue_id' => $iQueueId, 'time_stamp' => $iTimeStamp);
					$this->database()->insert(Phpfox::getT('socialmediaimporter_queue_media'), $aInsert);
				}
			}
		}
		return $iQueueId;
	}

	public function deleteQueue($iQueueId)
	{
		if (!$iQueueId) return false;
		$aRows = $this->database()->select('album_id')
			->from(Phpfox::getT('socialmediaimporter_queue_media'))
			->where('queue_id = '. $iQueueId)
			->execute('getRows');
		if (count($aRows) > 0)
		{
			foreach ($aRows as $aRow)
			{
				$iAlbumId = $aRow['album_id'];
				if ($iAlbumId)
				{
					$iTotalPhoto = $this->database()->select('count(*)')
						->from(Phpfox::getT('photo'))
						->where("album_id = " . $iAlbumId)
						->execute('getSlaveField');
					if ($iTotalPhoto <= 0)
					{
						$this->database()->delete(Phpfox::getT('photo_album'), 'album_id = ' . $iAlbumId);
					}
					$this->database()->delete(Phpfox::getT('socialmediaimporter_tracking'), "type_id = 'album' AND id = '$iAlbumId'");
				}
			}
		}
		$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_id = ' . $iQueueId);
		$this->database()->delete(Phpfox::getT('socialmediaimporter_queue'), 'queue_id = ' . $iQueueId);
		return true;
	}

	public function setAutoQueue($iQueueId)
	{
		if (!$iQueueId) return false;
		$this->database()->update(Phpfox::getT('socialmediaimporter_queue'), array('status' => 1), 'queue_id=' . $iQueueId);
		return true;
	}

	public function addTempAlbum($aVals)
	{
		$iUserId = Phpfox::getUserId();
		$oParseInput = Phpfox::getLib('parse.input');
		$iAlbumId = $this->database()->insert(Phpfox::getT('socialmediaimporter_album'), array(
					'user_id' => $iUserId,
					'name' => $oParseInput->clean($aVals['name'], 255),
					'description' => $oParseInput->clean($aVals['description'], 255),
					'privacy' => $aVals['privacy'],
					'privacy_comment' => $aVals['privacy_comment'],
					'time_stamp' => PHPFOX_TIME
				)
			);
		return $iAlbumId;
	}

	public function getAlbum($iAlbumId = 0)
	{
		if (!$iAlbumId) return array();
		return $this->database()->select('*')
			->from(Phpfox::getT('photo_album'))
			->where('album_id = ' . $iAlbumId)
			->execute('getRow');
	}

	public function addPhotoAlbum($aVals)
	{
		// Get the parser object.
		$oParseInput = Phpfox::getLib('parse.input');
		// Create the fields to insert
		$aFields = array (
			'name',
			'module_id',
			'group_id' => 'int',
			'privacy' => 'int',
			'privacy_comment' => 'int'
		);

		// Create the fields to insert
		$aFieldsInfo = array(
			'description'
		);

		// Clean album name
		$aVals['name'] = $oParseInput->clean($aVals['name'], 255);

		// Prepare description.
		if (!empty($aVals['description']))
		{
			$aVals['description'] = $oParseInput->clean($aVals['description'], 255);
		}

		$aFields['user_id'] = 'int';
		$aFields[] = 'time_stamp';

		// Add the users ID to the fields array
		if (!isset($aVals['user_id']) || !$aVals['user_id'])
		{
			$aVals['user_id'] = Phpfox::getUserId();
		}

		// Add a time_stamp
		$aVals['time_stamp'] = PHPFOX_TIME;

		// Insert the data into the database.
		$iId = $this->database()->process($aFields, $aVals)->insert(Phpfox::getT('photo_album'));

		$aFieldsInfo['album_id'] = 'int';

		$aVals['album_id'] = $iId;

		// Insert album info.
		$this->database()->process($aFieldsInfo, $aVals)->insert(Phpfox::getT('photo_album_info'));

		if (Phpfox::isModule('privacy'))
		{
			if (isset($aVals['privacy']) && $aVals['privacy'] == '4')
			{
				Phpfox::getService('privacy.process')->add('photo_album', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
			}
		}
		return $iId;
	}

	public function deleteQueueMedia($iQueueId, $iQueueMediaId)
	{
		$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_media_id = ' . $iQueueMediaId);
		$this->database()->query("UPDATE " . Phpfox::getT('socialmediaimporter_queue') . " SET total_media = total_media - 1 WHERE queue_id = '$iQueueMediaId'");
	}

	public function updateQueueCounter($iId, $sCounter, $bMinus = false)
	{
		$this->database()->update(Phpfox::getT('socialmediaimporter_queue'), array(
				$sCounter => array('= ' . $sCounter . ' ' . ($bMinus ? '-' : '+'), 1)
			), 'queue_id = ' . (int) $iId
		);
	}

	public function addAlbumFromTemp($iQueueId, $iTempAlbumId = 0, $sTempAlbumIds = '', $sAlbumIds = '')
	{
		if ($iQueueId && $iTempAlbumId && $sTempAlbumIds)
		{
			$aTempAlbum = $this->database()->select('*')
				->from(Phpfox::getT('socialmediaimporter_album'))
				->where("album_id = " . $iTempAlbumId)
				->execute('getRow');
			if ($aTempAlbum)
			{
				$iAlbumId = $this->addPhotoAlbum($aTempAlbum);
				if ($iAlbumId)
				{
					$aTempAlbumIds = explode(',', $sTempAlbumIds);
					$aNewTempAlbumIds = array();
					for ($i = 0; $i < count($aTempAlbumIds); $i++)
					{
						if ($aTempAlbumIds[$i] != $aTempAlbum['album_id'])
						{
							$aNewTempAlbumIds[] = $aTempAlbumIds[$i];
						}
					}
					$sTempAlbumIds = $aNewTempAlbumIds ? implode(',', $aNewTempAlbumIds) : '';
					if ($sAlbumIds != '')
					{
						$sAlbumIds = $sAlbumIds . ',' . $iAlbumId;
					}
					else
					{
						$sAlbumIds = $iAlbumId;
					}
					$this->database()->delete(Phpfox::getT('socialmediaimporter_album'), 'album_id = ' . $iTempAlbumId);
					$this->database()->update(Phpfox::getT('socialmediaimporter_queue'), array('temp_album_ids' => $sTempAlbumIds, 'album_ids' => $sAlbumIds), 'queue_id = ' . $iQueueId);
					$this->database()->update(Phpfox::getT('socialmediaimporter_queue_media'), array('album_id' => $iAlbumId), 'album_id = ' . $iTempAlbumId . ' AND queue_id = ' . $iQueueId);
					return array ($iAlbumId, $sTempAlbumIds, $sAlbumIds);
				}
			}
		}
		return array (0, $sTempAlbumIds, $sAlbumIds);
	}

	private $_sService = NULL;
	public function setPrividerName($sProviderName) {
		$this->_sService = $sProviderName;
	}

	public function importPhoto($iQueueId = 0)
    {
		Phpfox_Error::skip(true);
		$iUserId = Phpfox::getUserId();
		if (!$iUserId || !$iQueueId)
		{
			return false;
		}

		$aRow = $this->database()->select('A.album_ids, A.temp_album_ids, A.total_media, A.total_success, A.total_fail, A.privacy, A.privacy_comment, A.privacy_list, B.*')
			->from(Phpfox::getT('socialmediaimporter_queue'), 'A')
			->join(Phpfox::getT('socialmediaimporter_queue_media'), 'B', 'A.queue_id = B.queue_id')
			->where("A.status = 0 AND B.queue_id = '$iQueueId' AND B.status = 'pending'")
			->limit('1')
			->execute('getRow');

		if (!Phpfox::getService('socialmediaimporter.userspace')->isAllowedToUpload($iUserId))
		{
			$iCountFail = $this->database()->select('count(*)')
				->from(Phpfox::getT('socialmediaimporter_queue'))
				->where("queue_id = " . $iQueueId . ' AND status = "pending"')
				->execute('getSlaveField');
			$this->database()->update(Phpfox::getT('socialmediaimporter_queue'), array ('error_code' => 1, 'total_fail' => $iCountFail), 'queue_id = ' . $iQueueId);
			$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_id = ' . $iQueueId);
		}

		if (isset($aRow['temp_album_ids']) && $aRow['temp_album_ids'])
		{
			list($iAlbumId, $sTempAlbumIds, $sAlbumIds) = $this->addAlbumFromTemp($iQueueId, $aRow['album_id'], $aRow['temp_album_ids'], $aRow['album_ids']);
			if ($iAlbumId > 0)
			{
				$aRow['album_id'] = $iAlbumId;
				$aRow['temp_album_ids'] = $sTempAlbumIds;
				$aRow['album_ids'] = $sAlbumIds;
			}
		}

		if (Phpfox_Error::isPassed())
		{
			$iPhotoId = $this->addPhoto($iUserId, $aRow, Phpfox::getUserParam('photo.photo_must_be_approved'));
			if ($iPhotoId > 0)
			{
				$aRow['total_success'] = $aRow['total_success'] + 1;
				$this->addTracking($iUserId, $aRow['media_id'], $iPhotoId, 'photo', $this->_sService);
				$this->updateQueueCounter($iQueueId, 'total_success');
				$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_media_id = ' . $aRow['queue_media_id']);
			}
			else
			{
				$aRow['total_fail'] = $aRow['total_fail'] + 1;
				$this->updateQueueCounter($iQueueId, 'total_fail');
				$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_media_id = ' . $aRow['queue_media_id']);
			}
		}
		$iTotalImported = $aRow['total_success'] + $aRow['total_fail'];
		$sUrlRedirect = Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name') . '.photo');
		if ($iTotalImported >= $aRow['total_media'])
		{
			$aConds = array();
			if ($aRow['album_ids'])
			{
				$aConds[] = "album_id IN (" . $aRow['album_ids'] . ")";
			}

			$aPhotos = $this->database()->select('photo_id')
				->from(Phpfox::getT('photo'))
				->where($aConds)
				->limit($aRow['total_success'] > 3 ? 3 : $aRow['total_success'])
				->order('time_stamp DESC')
				->execute('getSlaveRows');

			if (is_array($aPhotos) && count($aPhotos) > 0)
			{
				$aPhotoIds = Phpfox::getService('socialmediaimporter.common')->arrayField($aPhotos, 'photo_id');
				$sPhotoIds = implode(',', $aPhotoIds);
				$this->database()->update(Phpfox::getT('socialmediaimporter_queue'), array('feed_photo_ids' => $sPhotoIds), 'queue_id = ' . $iQueueId);
			}

			$aAlbumIds = explode(',', $aRow['album_ids']);
			if (count($aAlbumIds) == 1 && $aAlbumIds[0])
			{
				$iAlbumId = $aAlbumIds[0];
				$aAlbum = $this->database()->select('*')
					->from(Phpfox::getT('photo_album'))
					->where("album_id = " . $iAlbumId)
					->execute('getRow');
				$sUrlRedirect = Phpfox::getLib('url')->permalink('photo.album', $iAlbumId, $aAlbum['name']);
			}
			elseif (count($aAlbumIds) > 1)
			{
				$sUrlRedirect = Phpfox::getLib('url')->makeUrl('photo.albums', array('view' => 'myalbums'));
			}
			if (Phpfox::isModule('feed') && !Phpfox::getUserParam('photo.photo_must_be_approved'))
			{
				Phpfox::getService('feed.process')->add('socialmediaimporter', $iQueueId, $aRow['privacy'], $aRow['privacy_comment'], 0, $iUserId);
			}
		}
		Phpfox_Error::skip(false);
		$sPercent = ceil(($iTotalImported * 100) / $aRow['total_media']) . '%';
		return array ($sPercent, $aRow['total_media'], $iTotalImported, $aRow['total_media'] - $iTotalImported, $aRow['total_success'], $aRow['total_fail'], $sUrlRedirect);
	}

	public function getUserSetting($iUserGroupId, $sModule = 'photo')
	{
		$aUserParams = Phpfox::getService('user.group.setting')->get($iUserGroupId, $sModule);
		$aUserParams = $aUserParams['phpfox'][$sModule];
		$aParams = array();
		foreach ($aUserParams as $i => $aUserParam)
		{
			$aParams[$aUserParam['name']] = $aUserParam;
		}
		return $aParams;
	}

	public function addFeed($sType, $iItemId, $iPrivacy = 0, $iPrivacyComment = 0, $iParentUserId = 0, $iOwnerUserId = null, $bIsTag=0)
	{
		if ($iParentUserId === null)
        {
			$iParentUserId = 0;
		}
		if ($iOwnerUserId === null)
        {
			$iOwnerUserId = Phpfox::getUserId();
		}
		$aInsert = array(
			'privacy' => (int) $iPrivacy,
			'privacy_comment' => (int) $iPrivacyComment,
			'type_id' => $sType,
			'user_id' => $iOwnerUserId,
			'parent_user_id' => $iParentUserId,
			'item_id' => $iItemId,
			'feed_reference' => 0,
			'time_stamp' => PHPFOX_TIME
		);
		if (defined('PHPFOX_APP_ID'))
		{
			$aInsert['app_id'] = PHPFOX_APP_ID;
		}
		$iLastId = $this->database()->insert(Phpfox::getT('feed'), $aInsert);
		return $iLastId;
	}

	public function cronImportPhoto($sService = '', $iUserId = 0, $iLimit = 0)
    {
		Phpfox_Error::skip(true);
		$aConds[] = 'AND error_code = 0';
		$aConds[] = 'AND status = 1';
		$aConds[] = 'AND total_media > total_success + total_fail';
		if ($sService != '')
		{
			$aConds[] = 'AND service_name = "' . $sService . '"';
		}
		if ($iUserId > 0)
		{
			$aConds[] = 'AND user_id = "' . $iUserId . '"';
		}

		$aQueue = $this->database()->select('*')
			->from(Phpfox::getT('socialmediaimporter_queue'))
			->where($aConds)
			->limit(1)
			->order('queue_id ASC')
			->execute('getRow');

		if (!$aQueue)
		{
			return false;
		}

		$iUserId = $aQueue['user_id'];
		$iQueueId = $aQueue['queue_id'];
		$sAlbumIds = $aQueue['album_ids'];
		$sTempAlbumIds = $aQueue['temp_album_ids'];
		$iTotal = $aQueue['total_media'];
		$iSuccess = $aQueue['total_success'];
		$iFail = $aQueue['total_fail'];
		$iPrivacy = $aQueue['privacy'];
		$iPrivacyComment = $aQueue['privacy_comment'];

		$aUser = Phpfox::getService('user')->get($iUserId, true);
		$iUserGroupId = $aUser['user_group_id'];

		$aUserUserParams = $this->getUserSetting($iUserGroupId, 'user');
		$aPhotoUserParams = $this->getUserSetting($iUserGroupId, 'photo');

		$iUserTotalUploadSpace = intval($aUserUserParams['total_upload_space']['value_actual']);
		$bIsPhotoMustBeApproved = intval($aPhotoUserParams['photo_must_be_approved']['value_actual']);

		$aConds = array();
		$aConds[] = 'AND status = "pending"';
		$aConds[] = 'AND queue_id = ' . $iQueueId;
		if ($iLimit <= 0)
		{
			$iLimit = 5;
		}
		$aRows = $this->database()->select('*')
			->from(Phpfox::getT('socialmediaimporter_queue_media'))
			->where($aConds)
			->limit($iLimit)
			->order('queue_media_id ASC')
			->execute('getRows');

		if (!$aRows)
		{
			return false;
		}

		$aResult = array();
		$aFoxAlbumId = array();
		foreach ($aRows as $i => $aRow)
		{
			$bIsErrorSpace = 0;
			if (!Phpfox::getService('socialmediaimporter.userspace')->isAllowedToUpload($iUserId, $iUserTotalUploadSpace))
			{
				$bIsErrorSpace = 1;
				$iCountFail = $this->database()->select('count(*)')
					->from(Phpfox::getT('socialmediaimporter_queue'))
					->where("queue_id = " . $iQueueId . ' AND status = "pending"')
					->execute('getSlaveField');
				$this->database()->update(Phpfox::getT('socialmediaimporter_queue'), array ('error_code' => 1, 'total_fail' => $iCountFail), 'queue_id = ' . $iQueueId);
				$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_id = ' . $iQueueId);
				$aResult[] = 'Error user space';
			}

			if (!isset($aFoxAlbumId[$aRow['album_id']]) && $sTempAlbumIds && $bIsErrorSpace == 0)
			{
				list($iFoxAlbumId, $sTempAlbumIds, $sAlbumIds) = $this->addAlbumFromTemp($iQueueId, $aRow['album_id'], $sTempAlbumIds, $sAlbumIds);
				if ($iFoxAlbumId > 0)
				{
					$aFoxAlbumId[$aRow['album_id']] = $iFoxAlbumId;
				}
			}

			if (isset($aFoxAlbumId[$aRow['album_id']]) && $aFoxAlbumId[$aRow['album_id']])
			{
				$aRow['album_id'] = $aFoxAlbumId[$aRow['album_id']];
			}

			if ($bIsErrorSpace == 0)
			{
				$aRow['privacy'] = $iPrivacy;
				$aRow['privacy_comment'] = $iPrivacyComment;
				$iPhotoId = $this->addPhoto($iUserId, $aRow, $bIsPhotoMustBeApproved);
				if ($iPhotoId > 0)
				{
					$aResult[] = 'Add photo success . (' . $iPhotoId . '/' . $aRow['album_id'] . ')';
					$iSuccess++;
					$this->addTracking($iUserId, $aRow['media_id'], $iPhotoId, 'photo', $this->_sService);
					$this->updateQueueCounter($iQueueId, 'total_success');
					$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_media_id = ' . $aRow['queue_media_id']);
				}
				else
				{
					$aResult[] = 'Add photo fail';
					$iFail++;
					$this->updateQueueCounter($iQueueId, 'total_fail');
					$this->database()->delete(Phpfox::getT('socialmediaimporter_queue_media'), 'queue_media_id = ' . $aRow['queue_media_id']);
					//$this->database()->update(Phpfox::getT('socialmediaimporter_queue_media'), array('status' => 'fail'), 'queue_media_id=' . $aRow['queue_media_id']);
				}
			}
			$iTotalImported = $iSuccess + $iFail;
			if (($iTotalImported >= $iTotal) || ($bIsErrorSpace))
			{
				if (Phpfox::isModule('feed') && !$bIsPhotoMustBeApproved)
				{
					$iFeedId = $this->addFeed('socialmediaimporter', $iQueueId, $iPrivacy, $iPrivacyComment, 0, $iUserId, $iUserId);
					$aResult[] = "Add Feed success ($iFeedId, $iQueueId, $iPrivacy, $iPrivacyComment, 0, $iUserId)";
				}

				$aConds = $aAlbumIds = array();
				if ($sAlbumIds)
				{
					$aConds[] = "album_id IN (" . $sAlbumIds . ")";
					$aAlbumIds = explode(',', $sAlbumIds);
				}

				$aPhotos = $this->database()->select('photo_id')
					->from(Phpfox::getT('photo'))
					->where($aConds)
					->limit($iSuccess > 3 ? 3 : $iSuccess)
					->order('time_stamp DESC')
					->execute('getSlaveRows');

				if (is_array($aPhotos) && count($aPhotos) > 0)
				{
					$aPhotoIds = Phpfox::getService('socialmediaimporter.common')->arrayField($aPhotos, 'photo_id');
					$sPhotoIds = implode(',', $aPhotoIds);
					$this->database()->update(Phpfox::getT('socialmediaimporter_queue'), array('feed_photo_ids' => $sPhotoIds), 'queue_id = ' . $iQueueId);
					$aResult[] = 'Update Feed_Photo_Ids success';
				}

				if (count($aAlbumIds) == 1 && $aAlbumIds[0])
				{
					$iAlbumId = $aAlbumIds[0];
					$aAlbum = $this->database()->select('*')
						->from(Phpfox::getT('photo_album'))
						->where("album_id = " . $iAlbumId)
						->execute('getRow');
					$sLink = Phpfox::getLib('url')->permalink('photo.album', $iAlbumId, $aAlbum['name']);
				}
				elseif (count($aAlbumIds) > 1)
				{
					$sLink = Phpfox::getLib('url')->makeUrl('photo.albums', array('view' => 'myalbums'));
				}
				else
				{
					$sLink = Phpfox::getLib('url')->makeUrl($aUser['user_name'] . '.photo');
				}

				$aResult[] = 'Prepare send Mail and Notification';
				$sTo = $iUserId;
				$sSubject = Phpfox::getPhrase('socialmediaimporter.mail_subject_of_import_photos');
				if ($bIsErrorSpace == 1)
				{
					$sBody = Phpfox::getPhrase('socialmediaimporter.message_of_import_photos_error_space', array('success' => $iSuccess, 'total' => $iTotal, 'link' => $sLink));
				}
				else
				{
					$sBody = Phpfox::getPhrase('socialmediaimporter.mail_body_of_import_photos', array('success' => $iSuccess, 'total' => $iTotal, 'link' => $sLink));
				}
				$sNotify = Phpfox::getPhrase('socialmediaimporter.notification_of_import_photos', array('success' => $iSuccess, 'total' => $iTotal));
				$oMail = Phpfox::getLib('mail');
				$oMail->to($sTo);
				$oMail->subject($sSubject);
				$oMail->message($sBody);
				if (Phpfox::isModule('notification'))
				{
					Phpfox::getService('notification.process')->add('socialmediaimporter_import', $iQueueId, $iUserId, $iUserId);
					$oMail->notification($sNotify);
				}
				$oMail->send();
				$aResult[] = 'Finish send Mail and Notification';
				break;
			}
		}
		print_r($aResult);
		Phpfox_Error::skip(false);
	}

	public function addTracking($iUserId, $sPhotoServiceId, $iPhotoFoxId = 0, $sType = 'photo', $sProviderName = NULL)
	{
		$this->database()->delete(Phpfox::getT('socialmediaimporter_tracking'), "fid = '$sPhotoServiceId'");
		$iTrackingId = $this->database()->insert(Phpfox::getT('socialmediaimporter_tracking'), array (
				'user_id' => $iUserId,
				'type_id' => $sType,
				'fid' => $sPhotoServiceId,
				'service_name' => $sProviderName,
				'id' => $iPhotoFoxId
			)
		);
		return $iTrackingId;
	}

	private function file_get_contents_curl($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
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
    public function addPhoto($iUserId, $aVals, $bIsPhotoMustBeApproved = -1, $bIsUpdate = false, $bAllowTitleUrl = false)
    {
		if ($bIsPhotoMustBeApproved == -1)
		{
			$bIsPhotoMustBeApproved = Phpfox::getUserParam('photo.photo_must_be_approved');
		}
		$sMediaPath = isset($aVals['media_path']) ? $aVals['media_path'] : '';
		$oParseInput = Phpfox::getLib('parse.input');
		$oFile = Phpfox::getLib('file');
		$oImage = Phpfox::getLib('image');
		try
		{
			// $fImage = file_get_contents($sMediaPath);
			$fImage = $this->file_get_contents_curl($sMediaPath);
			$aFileInfo = pathinfo($sMediaPath);
			if (is_array($aFileInfo) && count($aFileInfo) >= 4)
			{
				$sFileName = md5($aFileInfo["filename"] . PHPFOX_TIME . uniqid());
				$aVals["name"] = $aFileInfo["filename"];
				$aVals["ext"] = strtolower($aFileInfo["extension"]);
				$sFilePath = $oFile->getBuiltDir(Phpfox::getParam('photo.dir_photo'));
				$sDest = $sFilePath . $sFileName . "%s." . $aVals["ext"];
				$aVals["destination"] = $sPathRefImage = str_replace(Phpfox::getParam('photo.dir_photo'), "", $sDest);
			}
			if (($iSize = file_put_contents(sprintf($sDest, ""), $fImage)) !== false)
			{
				if (Phpfox::getParam('core.allow_cdn'))
                {
                    Phpfox::getLib('cdn')->put(sprintf($sDest, ''));
                }
                
                $aVals["size"] = $iFileSizes = $iSize;
				$aImageType = array('jpg' => 'image/jpg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png');
				if (!isset($aImageType[$aVals["ext"]]))
				{
					return 0;
				}
				list($aVals["width"], $aVals["height"]) = @getimagesize($sFilePath . $sFileName . '.' . $aVals["ext"]);
				$aVals["type"] = $aImageType[$aVals["ext"]];
				foreach(Phpfox::getParam('photo.photo_pic_sizes') as $iSize)
				{
					if ($oImage->createThumbnail(sprintf($sDest, ""), Phpfox::getParam('photo.dir_photo') . sprintf($sPathRefImage, '_' . $iSize), $iSize, $iSize, true, ((Phpfox::getParam('photo.enabled_watermark_on_photos') && Phpfox::getParam('core.watermark_option') != 'none') ? (Phpfox::getParam('core.watermark_option') == 'image' ? 'force_skip' : true) : false)) === false)
					{
						$iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($sPathRefImage, '_' . $iSize));
						continue;
					}
					if (Phpfox::getParam('photo.enabled_watermark_on_photos'))
					{
						$oImage->addMark(Phpfox::getParam('photo.dir_photo') . sprintf($sPathRefImage, '_' . $iSize));
						$iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($sPathRefImage, '_' . $iSize));
					}
				}
				if ($iFileSizes > 0)
				{
					Phpfox::getService('user.space')->update($iUserId, 'photo', $iFileSizes, '+');
				}
			}
			else
			{
				return 0;
			}
		}
		catch (exception $e)
        {
			//Phpfox_Error::set($e->getMessage());
			return 0;
        }

		// Create the fields to insert.
		$aFields = array();
		// Make sure we are updating the album ID
		(!empty($aVals['album_id']) ? $aFields['album_id'] = 'int' : null);

		$iAlbumId = 0;
		if (!empty($aVals['album_id']) && $aVals['album_id'] > 0)
		{
			$iAlbumId = (int) $aVals['album_id'];
			$iTotalPhoto = $this->database()->select('count(photo_id)')
                ->from(Phpfox::getT('photo'))
				->where("is_cover = 1 AND album_id = " . $iAlbumId)
                ->execute('getSlaveField');
			if ((int) $iTotalPhoto == 0)
			{
				$aVals['is_cover'] = 1;
			}
			else
			{
				if ($aVals['is_cover'] == 1)
				{
					$this->database()->update(Phpfox::getT('photo'), array('is_cover' => 0), 'album_id=' . $iAlbumId);
				}
				else
				{
					$aVals['is_cover'] = 0;
				}
			}
		}
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
		$aFields['is_cover'] = 'int';
		$aFields[] = 'module_id';
		$aFields[] = 'title';
		$aFields[] = 'destination';

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
			'description',
			'width',
			'height'
		);

		// Clean and prepare the title and SEO title
		$aVals['title'] = $oParseInput->clean(rtrim(preg_replace("/^(.*?)\.(jpg|jpeg|gif|png)$/i", "$1", $aVals['name'])), 255);

		// Add the user_id
		$aVals['user_id'] = $iUserId;

		// Add the original server ID for LB.
		$aVals['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');

		// Add the time stamp.
		$aVals['time_stamp'] = PHPFOX_TIME;

		$aVals['view_id'] = ($bIsPhotoMustBeApproved ? '1' : '0');

		$aVals['is_cover'] = isset($aVals['is_cover']) ? $aVals['is_cover'] : 0;

		// Insert the data into the database.
		$iId = $this->database()->process($aFields, $aVals)->insert(Phpfox::getT('photo'));

		if ($iId > 0 && $iAlbumId > 0)
		{
			Phpfox::getService('photo.album.process')->updateCounter($iAlbumId, 'total_photo');
		}

		// Prepare the data to enter into the photo_info table
		$aInfo = array (
			'photo_id' => $iId,
			'file_name' => Phpfox::getLib('parse.input')->clean($aVals['name'], 100),
			'extension' => strtolower($aVals['ext']),
			'file_size' => $aVals['size'],
			'mime_type' => $aVals['type'],
			'description' => (empty($aVals['description']) ? null : $this->preParse()->clean($aVals['description'])),
			'width' => isset($aVals['width']) ? $aVals['width'] : 0,
			'height' => isset($aVals['height']) ? $aVals['height'] : 0
		);

		// Insert the data into the photo_info table
		$this->database()->process($aFieldsInfo, $aInfo)->insert(Phpfox::getT('photo_info'));

		if (!$bIsPhotoMustBeApproved)
		{
			// Update user activity
			Phpfox::getService('user.activity')->update($iUserId, 'photo');
		}

		// Make sure if we plan to add categories for this image that there is something to add
		if (isset($aVals['category_id']) && count($aVals['category_id']))
		{
			// Loop thru all the categories
			foreach ($aVals['category_id'] as $iCategory)
			{
				// Add each of the categories
				Phpfox::getService('photo.category.process')->updateForItem($iId, $iCategory);
			}
		}

		if (isset($aVals['privacy']))
		{
			if ($aVals['privacy'] == '4')
			{
				Phpfox::getService('privacy.process')->add('photo', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
			}
		}

		// Return the photo ID#
		return $iId;
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
		if ($sPlugin = Phpfox_Plugin::get('socialmediaimporter.service_process__call'))
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