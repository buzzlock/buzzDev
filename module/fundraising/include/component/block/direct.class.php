<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Direct extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		return false;
            if (defined('PHPFOX_IS_USER_PROFILE') || defined('PHPFOX_IS_PAGES_VIEW'))
            {
               return false;
            }
		$aDirect = Phpfox::getService('fundraising')->getDirectSign();
		
		if (!$aDirect)
		{
			return false;
		}
		$aDirect['can_sign'] = Phpfox::getService('fundraising')->canSign($aDirect);					   
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fundraising.direct_sign'),
				'aDirect' => $aDirect,
                        'corepath'=>phpfox::getParam('core.path')
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
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_block_direct_clean')) ? eval($sPlugin) : false);
	}
}

?>