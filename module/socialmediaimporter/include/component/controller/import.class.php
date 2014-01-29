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
class SocialMediaImporter_Component_Controller_Import extends Phpfox_Component 
{
	public function process() 
	{		
		Phpfox::isUser(true);	
		$bIsRedirect = $this->request()->get('redirect');		
		$sService = ($this->request()->get('req2', 'facebook'));
		$oService = Phpfox::getService('socialmediaimporter.services')->getObject($sService);
		$aAgent = Phpfox::getService('socialmediaimporter.agents')->get(Phpfox::getUserId(), $sService);
		$sType = $this->request()->get('req3', 'album');				
		if ($sType == 'album' && !$bIsRedirect && $sService == 'instagram')
		{
			$sUrl = Phpfox::getLib('url')->makeUrl('socialmediaimporter.instagram.photo', array());
			Phpfox::getLib("url")->send($sUrl);
			exit;			
		}
		$sLinkDisconnect = Phpfox::getService('socialmediaimporter.services')->getLinkDisconnect($sService); 
		if ($sType == 'disconnect')
		{
			if (Phpfox::getService('socialmediaimporter.agents')->delete($sService)) 
			{				
				$oService->disconnect();
				$sUrl = Phpfox::getLib('url')->makeUrl('socialmediaimporter.' . $sService, array());			
				Phpfox::getLib("url")->send($sUrl);
				exit;
			}
		}		
		if (isset($bIsRedirect) && $bIsRedirect != "")
		{
			$sUrl = Phpfox::getService('socialmediaimporter.agents')->getAuthUrl($sService, 1);
			Phpfox::getLib("url")->send($sUrl);
			exit;
		}
		
		$aUser = Phpfox::getService('user')->get(Phpfox::getUserId(), true);
		$this->template()->setTitle(Phpfox::getPhrase('socialmediaimporter.social_media_importer'))
            ->setBreadcrumb(Phpfox::getPhrase('socialmediaimporter.social_media_importer'))
			->setFullSite()
			->setHeader(array(
				'fbstyle.css' => 'module_socialmediaimporter',
				'socialmediaimporter.js' => 'module_socialmediaimporter',
                'import.js' => 'module_socialmediaimporter'
			))
            ->assign(array(
				'sType' => $sType,
				'aUser' => $aUser,               
                'aAgent' => $aAgent,
                'sService' => $sService,
                'sLinkDisconnect' => $sLinkDisconnect,
                'sLinkGetAlbums' => Phpfox::getLib('url')->makeUrl("socialmediaimporter." . $sService),
                'sLinkGetPhotos' => Phpfox::getLib('url')->makeUrl("socialmediaimporter." . $sService . ".photo"),
                'bIsHaveGetAlbums' => $oService->hasGetAlbums(),                                
                'bIsHaveGetPhotos' => $oService->hasGetPhotos(),                                
                'sCoreUrl' => Phpfox::getParam('core.path'),                                
            ));
		
		$this->setParam('default_privacy', Phpfox::getParam('socialmediaimporter.default_privacy'));
		Phpfox::getService("socialmediaimporter.common")->buildAlbumSectionMenu($sType);
		$this->template()->setPhrase(array(
				'socialmediaimporter.please_select_photo_s_to_import',
				'socialmediaimporter.please_select_album_s_to_import',
				'socialmediaimporter.album',
			)
		);
	}
}