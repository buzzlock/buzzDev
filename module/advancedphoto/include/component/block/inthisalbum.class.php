<?php
class Advancedphoto_Component_Block_Inthisalbum extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$this->template()->assign(array(
					'sHeader' => Phpfox::getPhrase('advancedphoto.in_this_album'),
					'corepath' => phpfox::getParam('core.path')
			));	
		return 'block';
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_inthisalbum_clean')) ? eval($sPlugin) : false);
	}
}
?>