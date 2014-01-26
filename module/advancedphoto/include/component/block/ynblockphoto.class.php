<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 */
class Advancedphoto_Component_Ynblockphoto_Album extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aPhotos = $this->getParam('aPhotos', false) ? $this->getParam('aPhotos') : array();
		$this->template()->assign(array(
			'aPhotos' => $aPhotos
		));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_ynblockphoto_clean')) ? eval($sPlugin) : false);
	}
}

?>