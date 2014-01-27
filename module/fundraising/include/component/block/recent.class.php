<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Recent extends Phpfox_Component
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
         
         list($iTotal, $aRecent) = Phpfox::getService('fundraising')->getRecent();
         if (!$iTotal)
         {
               return false;
         }
         
         $this->template()->assign(array(
                     'sHeader' => Phpfox::getPhrase('fundraising.recent_fundraisings'),
                     'aRecent' => $aRecent,
                     'iTotal' => $iTotal
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
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_block_recent_clean')) ? eval($sPlugin) : false);
	}
}

?>
