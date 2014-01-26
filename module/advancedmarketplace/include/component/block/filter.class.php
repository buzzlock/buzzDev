<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Filter extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$sUserLinkProfile = '';
		if (defined('PHPFOX_IS_USER_PROFILE'))
		{
			$aUser = $this->getParam('aUser');
			$sUserLinkProfile = $aUser['user_name'] . '.';
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('advancedmarketplace.browse_filter'),
				'sUserLinkProfile' => $sUserLinkProfile
			)
		);
		
		return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_filter_clean')) ? eval($sPlugin) : false);
	}
}

?>