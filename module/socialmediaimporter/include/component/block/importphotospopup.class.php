<?php
defined('PHPFOX') or exit('NO DICE!');
class SocialMediaImporter_Component_Block_ImportPhotosPopup extends Phpfox_Component
{
	public function process()
	{
		Phpfox::isUser(true);
		$aAlbums = Phpfox::getService('photo.album')->getAll(Phpfox::getUserId(), false);
		$sModule = $this->request()->get('module', false);
		$sService = $this->getParam('service', "facebook");
		$sServiceAlbumId = $this->getParam('service_album_id', "");
		$iItem = $this->request()->getInt('item', false);		
		$iTotalAlbums = Phpfox::getService('photo.album')->getAlbumCount(Phpfox::getUserId());
		$bAllowedAlbums = (Phpfox::getUserParam('photo.max_number_of_albums') == 'null' ? true : (!Phpfox::getUserParam('photo.max_number_of_albums') ? false : (Phpfox::getUserParam('photo.max_number_of_albums') <= $iTotalAlbums ? false : true)));
		if ($aSessionVals = Phpfox::getLib('session')->get('photo_album_form'))
		{		
			Phpfox::getLib('session')->remove('photo_album_form');		
			$this->template()->assign(array(
					'aForms' => $aSessionVals
				)
			);
		}
		$aValidation = array(
			'name' => Phpfox::getPhrase('photo.provide_a_name_for_your_album'),
			'privacy' => Phpfox::getPhrase('photo.select_a_privacy_setting_for_your_album')
		);
		$oValid = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'js_create_new_album',
				'aParams' => $aValidation
			)
		);
		$iDefaultPrivacy = 3;
		switch(Phpfox::getParam('socialmediaimporter.default_privacy'))
		{
			case "Everyone":
				$iDefaultPrivacy = 0;
				break;
			case "Friends":
				$iDefaultPrivacy = 1;
				break;
			case "Friends of Friends":
				$iDefaultPrivacy = 2;
				break;			
			default:				
				$iDefaultPrivacy = 3;				
		}
		$this->template()->assign(array(
				"sCorePath" => Phpfox::getParam('core.path'),
				"aForms" => array("privacy" => $iDefaultPrivacy),
				"iDefaultPrivacy" => $iDefaultPrivacy,
				'bAllowedAlbums' => $bAllowedAlbums,
				'aAlbums' => $aAlbums,
				'sCreateJs' => $oValid->createJS(),
				'sGetJsForm' => $oValid->getJsForm(false),
				'sModule' => $sModule,
				'iItem' => $iItem,
				'sService' => $sService,
				'sServiceAlbumId' => $sServiceAlbumId,
				"iMaxImport" => Phpfox::getParam('socialmediaimporter.max_import_per_time'),
			)
		);
	}
}
?>