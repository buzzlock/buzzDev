<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: share.class.php 2510 2011-04-07 19:13:26Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_Share extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
		$bIsInPage = false;
		if(defined('PHPFOX_IS_PAGES_VIEW'))
		{
			$bIsInPage = true;
		}
		$this->template()->assign(array(
			'bIsInPage' => $bIsInPage
		));

    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_share_clean')) ? eval($sPlugin) : false);
    }
}

?>