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
class SocialMediaImporter_Component_Ajax_Ajax extends Phpfox_Ajax
{	
	public function importAlbumsPopup()
	{
		$sService = $this->get("service");
		$aTitle = array(
			"facebook" => Phpfox::getPhrase('socialmediaimporter.import_your_albums_from_facebook'),
			"flickr" => Phpfox::getPhrase('socialmediaimporter.import_your_albums_from_flickr'),
			"instagr" => Phpfox::getPhrase('socialmediaimporter.import_your_albums_from_instagram'),
			"picasa" => Phpfox::getPhrase('socialmediaimporter.import_your_photos_from_picasa'),
		);
		$this->setTitle($aTitle[$sService]);    	
		Phpfox::isUser(true);		
		Phpfox::getUserParam('photo.can_create_photo_album', true);		
		Phpfox::getBlock('socialmediaimporter.importalbumspopup', array("service" => $sService));
		$this->call('<script type="text/javascript">$Core.loadInit();</script>');
	}
	
	public function importPhotosPopup()
	{
		$sService = $this->get("service");
		$sServiceAlbumId = $this->get("service_album_id");
		$aTitle = array (
			"facebook" => Phpfox::getPhrase('socialmediaimporter.import_your_photos_from_facebook'),
			"flickr" => Phpfox::getPhrase('socialmediaimporter.import_your_photos_from_flickr'),
			"instagram" => Phpfox::getPhrase('socialmediaimporter.import_your_albums_from_instagram'),
			"picasa" => Phpfox::getPhrase('socialmediaimporter.import_your_photos_from_picasa'),
		);
		$this->setTitle($aTitle[$sService]);    	
		Phpfox::isUser(true);		
		Phpfox::getUserParam('photo.can_create_photo_album', true);		
		Phpfox::getBlock('socialmediaimporter.importphotospopup', array("service" => $sService, 'service_album_id' => $sServiceAlbumId));
		$this->call('<script type="text/javascript">$Core.loadInit();</script>');
	}
	
	public function addNewAlbum()
	{
		Phpfox::isUser(true);
		$sName = $this->get('name', '');		
		$sDescription = $this->get('description', '');		
		$iPrivacy = $this->get('privacy', 0);
		$iPrivacyComment = $this->get('privacy_comment', 0);		
		Phpfox::getUserParam('photo.can_create_photo_album', true);
		$iTotalAlbums = Phpfox::getService('photo.album')->getAlbumCount(Phpfox::getUserId());
		$bAllowedAlbums = (Phpfox::getUserParam('photo.max_number_of_albums') == 'null' ? true : (!Phpfox::getUserParam('photo.max_number_of_albums') ? false : (Phpfox::getUserParam('photo.max_number_of_albums') <= $iTotalAlbums ? false : true)));
		if (!$bAllowedAlbums)
		{
			$this->alert(Phpfox::getPhrase('photo.you_have_reached_your_limit_you_are_currently_unable_to_create_new_photo_albums'));
			return false;
		}
		$aVals['name'] = $sName;			
		$aVals['description'] = $sDescription;			
		$aVals['privacy'] = $iPrivacy;			
		$aVals['privacy_comment'] = $iPrivacyComment;			
		if ($iId = Phpfox::getService('photo.album.process')->add($aVals))
		{					
			echo ('<option value="' . $iId. '" selected="selected">' . Phpfox::getLib('parse.output')->clean(Phpfox::getLib('parse.input')->clean($aVals['name'])) . '</option>');
		}
	}
	
	public function loadAlbums()
	{		
		set_time_limit(15*60*60);
		$iAuto = $this->get('auto', 0);		
		$iPage = $this->get('page', 1);		
		$aParams['service'] = $sService = $this->get('service');		 
		$aParams['limit'] = $iLimit = $this->get('limit', Phpfox::getParam('socialmediaimporter.display_limit'));
		$aParams['page'] = $iPage;
		$aParams['offset'] = $iOffset = ($iPage - 1) * $iLimit;		
		$aParams['type'] = 'load_album';
		$oService = Phpfox::getService('socialmediaimporter.services')->getObject($sService);
		$oService->setServiceProvider($aParams['service']);
		list($iCount, $aAlbums) = $oService->getAlbums($aParams);		
		$sLogoutURL = Phpfox::getLib('url')->makeUrl("socialmediaimporter.facebook.disconnect");
		if($iCount === -1) {
			$this->call("window.location = \"{$sLogoutURL}\";");
		}
		Phpfox::getBlock('socialmediaimporter.albums', array (
            "oService" => $oService,
			"aAlbums" => $aAlbums,
            "iCount" => $iCount,
            "iPage" => $iPage,
        ));
		$html = $this->getContent(false);
		if ($html)
		{
			if ($iPage == 1)
			{
				$this->html('#list_albums', $html);
			}
			else
			{
				$this->append('#list_albums', $html);
			}			
		}
		$this->hide('#feed_view_more_loader');
		$this->call('yn_albums_page = yn_albums_page + 1;');
		$this->call('yn_load_albums_init = true;');		
		if ($iAuto == 1 && count($aAlbums) == $iLimit)
		{			
			$this->call("setTimeout('loadMoreAlbums($iAuto);', 1000);");
		}
		if ($iAuto == 0 && count($aAlbums) == $iLimit)
		{			
			$this->show('#global_view_more_album');
		}
		if (count($aAlbums) < $iLimit)
		{
			$this->call("loadMoreAlbums(-1);");
		}
		$this->show('#action_buttons');		
		$this->call('$Core.loadInit();');		
	}

	public function loadPhotos()
	{
		set_time_limit(15*60*60);
		$iAuto = $this->get('auto', 0);		
		$iPage = $this->get('page', 1);
		$aParams['service_album_id'] = $aParams['album_id'] = $iAlbumId = $this->get('album_id', '');	
		$aParams['service'] = $sService = $this->get('service');		 
		$aParams['limit'] = $iLimit = $this->get('limit', Phpfox::getParam('socialmediaimporter.display_limit'));
		$aParams['page'] = $iPage;
		$aParams['offset'] = $iOffset = ($iPage - 1) * $iLimit;						
		$oService = Phpfox::getService('socialmediaimporter.services')->getObject($sService);
		if ($iAlbumId) 
		{		
			$aParams['type'] = 'load_photo';
			list($iCount, $aPhotos, $iQueueId) = $oService->getPhotos($aParams);
		}
		else
		{
			$aParams['type'] = 'load_photo_tag';
			list($iCount, $aPhotos) = $oService->getPhotoTags($aParams);
		}		
		Phpfox::getBlock('socialmediaimporter.photos', array (
            "oService" => $oService,
            "aPhotos" => $aPhotos,
            "iCount" => $iCount,
			"iPage" => $iPage,
        ));
		$html = $this->getContent(false);
		if ($html)
		{
			if ($iPage == 1)
			{
				$this->html('#list_photos', $html);
			}
			else
			{
				$this->append('#list_photos', $html);
			}			
		}
		$this->hide('#feed_view_more_loader');
		$this->call('yn_photos_page = yn_photos_page + 1;');
		$this->call('yn_load_photos_init = true;');
		
		if ($iAuto == 1 && count($aPhotos) == $iLimit)
		{			
			$this->call("setTimeout('loadMorePhotos($iAuto);', 1000);");
		}
		if ($iAuto == 0 && count($aPhotos) == $iLimit)
		{			
			$this->show('#global_view_more_photo');			
			$this->show('#action_buttons');
			if ($iAlbumId) $this->show('.backAL');
		}
		if (count($aPhotos) < $iLimit)
		{
			$this->call("loadMorePhotos(-1);");
		}		
		$this->show('#action_buttons');		
		$this->call('$Core.loadInit();');
	}
	
	public function setAutoQueue()
	{
		$iQueueId = $this->get('queue', 0);
		Phpfox::getService('socialmediaimporter.process')->setAutoQueue($iQueueId);
	}
	
	public function cancelQueue()
	{
		$iQueueId = $this->get('queue', 0);
		Phpfox::getService('socialmediaimporter.process')->deleteQueue($iQueueId);
	}
	
	public function importAlbums()
	{
		set_time_limit(30*60*60);
		$iStep = $this->get('step', 1);
		$iPrivacy = $this->get('privacy', 0);
		$iPrivacyComment = $this->get('privacy_comment', 0);
		$sService = $this->get('service', '');
		$iQueueId = $this->get('queue', 0);
		$oService = Phpfox::getService('socialmediaimporter.services')->getObject($sService);
		$aParams['type'] = 'import_album';
		if ($iStep == 1 && $iQueueId == 0)
		{
			$sAlbumIds = $this->get('albumIds', '');
			$sAlbumNames = $this->get('albumNames', '');
			if (!$sService || !$sAlbumIds) exit;
			$sAlbumIds = rtrim($sAlbumIds, '","');
			$sAlbumNames = rtrim($sAlbumNames, '","');
			$aParams['limit'] = 1000;
			$aParams['offset'] = 0;
			$aParams['privacy'] = $iPrivacy;
			$aParams['privacy_comment'] = $iPrivacyComment;
			$aParams['album_id'] = $sAlbumIds;
			$aParams['album_name'] = $sAlbumNames;
			$aParams['is_import_album'] = 1;
			list($iCount, $aPhotos, $iQueueId, $sError) = $oService->getPhotos($aParams);
			echo json_encode(array('count' => $iCount, 'queue' => $iQueueId, 'error' => $sError));
		}
		if ($iStep == 2 && $iQueueId > 0)
		{
			Phpfox::getService('socialmediaimporter.process')->setPrividerName($sService);
			list ($sPercent, $iTotalPhoto, $iTotalImported, $iTotalCurrent, $iTotalSuccess, $iTotalFail, $sUrlRedirect) = Phpfox::getService('socialmediaimporter.process')->importPhoto($iQueueId);
			echo json_encode(array(
				'error' => Phpfox_Error::get(), 
				'total_percent' => $sPercent, 
				'total_photo' => $iTotalPhoto, 
				'total_imported' => $iTotalImported, 
				'total_current' => $iTotalCurrent, 
				'total_success' => $iTotalSuccess, 
				'total_fail' => $iTotalFail, 
				'url_redirect' => $sUrlRedirect, 
				'queue' => $iQueueId
			));			
		}
		exit;
	}	
	
	public function importPhotos()
	{		
		set_time_limit(30*60*60);
		$iStep = $this->get('step', 1);
		$iAlbumId = $this->get('album_id', 0);
		$sPhotoIds = rtrim($this->get('photo_id', 0), '","');		
		$iPrivacy = $iAlbumId > 0 ? 0 : $this->get('privacy', 0);
		$iPrivacyComment = $iAlbumId > 0 ? 0 : $this->get('privacy_comment', 0);
		$sService = $this->get('service', '');
		$sServiceAlbumId = $this->get('service_album_id', '');
		$iQueueId = $this->get('queue', 0);
		$oService = Phpfox::getService('socialmediaimporter.services')->getObject($sService);
		$aParams['type'] = $sServiceAlbumId ? 'import_photo' : 'import_photo_tag';
		if ($iStep == 1 && $iQueueId == 0)
		{			
			if (!$sService) exit;	
			if ($iAlbumId > 0)
			{
				$aAlbum = Phpfox::getService('socialmediaimporter.process')->getAlbum($iAlbumId);
				$iPrivacy = isset($aAlbum['privacy']) ? $aAlbum['privacy'] : 0;
				$iPrivacyComment = isset($aAlbum['privacy_comment']) ? $aAlbum['privacy_comment'] : 0;
			}
			$aParams['privacy'] = $iPrivacy;
			$aParams['privacy_comment'] = $iPrivacyComment;			
			$aParams['limit'] = 10000;
			$aParams['offset'] = 0;			
			$aParams['album_id'] = $iAlbumId;			
			$aParams['service_album_id'] = $sServiceAlbumId;			
			$aParams['photo_id'] = $sPhotoIds;			
			$aParams['is_import_photo'] = 1;					
			list($iCount, $aPhotos, $iQueueId) = $oService->getPhotos($aParams);
			echo json_encode(array('count' => $iCount, 'queue' => $iQueueId));			
		}
		if ($iStep == 2 && $iQueueId > 0)
		{			
			Phpfox::getService('socialmediaimporter.process')->setPrividerName($sService);
			list ($sPercent, $iTotalPhoto, $iTotalImported, $iTotalCurrent, $iTotalSuccess, $iTotalFail, $sUrlRedirect) = Phpfox::getService('socialmediaimporter.process')->importPhoto($iQueueId);
			echo json_encode(array(
				'error' => Phpfox_Error::get(), 
				'total_percent' => $sPercent, 
				'total_photo' => $iTotalPhoto, 
				'total_imported' => $iTotalImported, 
				'total_current' => $iTotalCurrent, 
				'total_success' => $iTotalSuccess, 
				'total_fail' => $iTotalFail, 
				'url_redirect' => $sUrlRedirect, 
				'queue' => $iQueueId
			));
		}
		exit;	
	}
	
	public function refresh()
    {					
		$sService = $this->get('service', '');
		$oService = Phpfox::getService('socialmediaimporter.services')->getObject($sService);		
		$oService->removeCache();
		$this->call("window.location.reload();");
    }	
}
?>