<?php
class Advancedphoto_Component_Block_Topmembers extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$sView = $this->request()->get('view');
		if($sView == 'myalbums' || $sView == 'friend' || defined('PHPFOX_IS_USER_PROFILE'))
		{
			return false;
		}

		$iNumberOfMembers = Phpfox::getParam('advancedphoto.member_on_top_member_block');
		$aUsers = Phpfox::getService('advancedphoto')->getTopUploadMembers($iNumberOfMembers);
		$this->template()->assign(array(
					'sHeader' => Phpfox::getPhrase('advancedphoto.top_members'),
					'corepath' => phpfox::getParam('core.path'),
					'aUsers' => $aUsers
				)
			);	
		return 'block';
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_topmembers_clean')) ? eval($sPlugin) : false);
	}
}
?>