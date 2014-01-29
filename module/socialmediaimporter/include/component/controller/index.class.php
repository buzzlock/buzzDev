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
class SocialMediaImporter_Component_Controller_Index extends Phpfox_Component 
{
	public function process() 
	{		
		Phpfox::isUser(true);
		$sService = ($this->request()->get('req2', ''));
		$aServiceSupports = array('facebook', 'flickr', 'instagram', 'picasa');
		if (in_array($sService, $aServiceSupports))
		{			
			$sUrlConnect = Phpfox::getLib('url')->makeUrl('socialmediaimporter.connect');
			if ($sService == 'facebook' && !Phpfox::getUserParam('socialmediaimporter.enable_facebook'))
			{
				Phpfox::getLib("url")->send($sUrlConnect);		
			}
			if ($sService == 'flickr' && !Phpfox::getUserParam('socialmediaimporter.enable_flickr'))
			{
				Phpfox::getLib("url")->send($sUrlConnect);		
			}
			if ($sService == 'instagram' && !Phpfox::getUserParam('socialmediaimporter.enable_instagram'))
			{
				Phpfox::getLib("url")->send($sUrlConnect);		
			}
			if ($sService == 'picasa' && !Phpfox::getUserParam('socialmediaimporter.enable_picasa'))
			{
				Phpfox::getLib("url")->send($sUrlConnect);		
			}
			$aAgent = Phpfox::getService('socialmediaimporter.agents')->get(Phpfox::getUserId(), $sService);
			if ($aAgent)
			{
				return Phpfox::getLib('module')->setController('socialmediaimporter.import');
			}
			else
			{				
				Phpfox::getLib("url")->send($sUrlConnect);	
			}
		}
		
		Phpfox::getService("socialmediaimporter.common")->buildAlbumSectionMenu();
		$this->template()->setTitle(Phpfox::getPhrase('socialmediaimporter.social_media_importer'))
			->setBreadcrumb(Phpfox::getPhrase('socialmediaimporter.social_media_importer'))
			->setFullSite()
			->setHeader(array(
				'fbstyle.css' => 'module_socialmediaimporter',
				'socialmediaimporter.js' => 'module_socialmediaimporter'
			))
			->assign(array(					
				'sCoreUrl' => Phpfox::getParam('core.path'),                                
			));
		return;				
	}
}