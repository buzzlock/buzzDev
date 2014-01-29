<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		YOUNETCO
 * @author  		AnNT
 * @package 		YouNet SocialStream
 * @version 		3.03
 */
class SocialStream_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$this->url()->send('admincp.socialstream.statdate');
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('socialstream.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>