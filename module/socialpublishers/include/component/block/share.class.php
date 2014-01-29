<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialPublishers_Component_Block_Share extends Phpfox_Component
{

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::isUser(true);
		
		$iUserId = Phpfox::getUserId();
		
		$sIdCache = Phpfox::getLib('cache') -> set("socialpublishers_feed_" . $iUserId);
		
		$aFeed = Phpfox::getLib('cache') -> get($sIdCache);
		
		if (!isset($aFeed['params']) || !$aFeed['params'])
		{
			return false;
		}

		$sIdCache = Phpfox::getLib('cache') -> set("socialpublishers_feed_" . $iUserId);
		
		$aFeed['is_show'] = 1;
		
		Phpfox::getLib('cache') -> save($sIdCache, $aFeed);

		$aParams = $aFeed['params'];
		
		$aParams['url'] = urldecode($aParams['url']);
		
		$aUser = Phpfox::getService('user') -> getUser($iUserId, "u.user_name,u.full_name,u.user_image,u.user_id");
		
		$aPublisherProviders = Phpfox::getService('socialbridge') -> getAllProviderData($iUserId);
		
		$aModuleSetting = Phpfox::getService('socialpublishers.modules') -> getModule($aParams['type']);
		
		$aExistSettings = Phpfox::getService('socialpublishers.modules') -> getUserModuleSettings($iUserId, $aParams['type']);
		
		foreach ($aPublisherProviders as $iKey => $aPublisherProvider)
		{
			if (isset($aModuleSetting[$iKey]) && $aModuleSetting[$iKey] == 0)
			{
				unset($aPublisherProviders[$iKey]);
			}
			else
			{
				if (isset($aExistSettings[$iKey]))
				{
					if ($aExistSettings[$iKey] == 1)
					{
						$aPublisherProviders[$iKey]['is_checked'] = 1;
					}
					else
					{
						$aPublisherProviders[$iKey]['is_checked'] = 0;
					}
				}
			}
		}
		
		if (isset($aParams['content']))
		{
			$aParams['content'] = html_entity_decode($aParams['content'], ENT_QUOTES, 'UTF-8');
		}
		if (isset($aParams['title']))
		{
			$aParams['title'] = html_entity_decode($aParams['title'], ENT_QUOTES, 'UTF-8');
		}
		
		$sPostMessage = Phpfox::getService('socialpublishers') -> getPostMessage($aParams);
		
		$this -> template() -> assign(array(
			'aUser' => $aUser,
			'aPublisherProviders' => $aPublisherProviders,
			'aModuleSetting' => $aModuleSetting,
			'aExistSettings' => $aExistSettings,
			'sCoreUrl' => Phpfox::getParam('core.path'),
			'aParams' => $aParams,
			'sPostMessage' => $sPostMessage
		));
		
		return 'block';
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('core.component_block_news_clean')) ? eval($sPlugin) : false);
	}

}
?>
