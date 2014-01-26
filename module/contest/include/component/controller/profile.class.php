<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Controller_Profile extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$this->setParam('bIsProfile', true);
		
		$aUser = $this->getParam('aUser');
		
		// $this->template()->setMeta('keywords', Phpfox::getPhrase('fundraising.full_name_s_fundraisings', array('full_name' => $aUser['full_name'])));
		// $this->template()->setMeta('description', Phpfox::getPhrase('fundraising.full_name_s_fundraisings_on_site_title', array('full_name' => $aUser['full_name'], 'site_title' => Phpfox::getParam('core.site_title'))));
		
		Phpfox::getComponent('contest.index', array('bNoTemplate' => true,'view' => ''), 'controller');
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('contest.component_controller_profile_clean')) ? eval($sPlugin) : false);
	}
}

?>
