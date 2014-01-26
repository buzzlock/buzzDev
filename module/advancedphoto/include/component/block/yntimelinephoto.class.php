<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 */
class Advancedphoto_Component_Block_Yntimelinephoto extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsUserProfile = false;
		if (defined('PHPFOX_IS_USER_PROFILE')) {
			$bIsUserProfile = true;
			$aUser = $this->getParam('aUser');
			$aYears = Phpfox::getService('advancedphoto')->getTimeLineYears($aUser['user_id'], $aUser['birthday_search'] );
		}
		else
		{
			$aUser = Phpfox::getService('advancedphoto')->getUserById(Phpfox::getUserId());
			$aYears = Phpfox::getService('advancedphoto')->getTimeLineYears(Phpfox::getUserId(), $aUser['birthday_search'] );
		}
		$iMaxPhotosPerLoad = Phpfox::getService('advancedphoto')->getMaxPhotosPerLoad();

		$iMostRecentYear = 0;
		if(count($aYears) > 0)
		{
			$iMostRecentYear = array_shift($aYears);
		}
		$this->template()->assign(array(
			'aYears' => $aYears,
			'iMostRecentYear' => $iMostRecentYear,
			'iMaxPhotosPerLoad' => $iMaxPhotosPerLoad,
			'corepath' => phpfox::getParam('core.path'),
			'bIsUserProfile' => $bIsUserProfile,
			'ynadvancedphoto_user_id' => $aUser['user_id']
		));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_yntimelinephoto_clean')) ? eval($sPlugin) : false);
	}
}

?>