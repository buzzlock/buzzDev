<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fblike_Component_Block_Fblike extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$sUrl = Phpfox::getParam('fblike.fb_url');
		if(Phpfox::getParam('fblike.block_header'))
		{
			$this->template()->assign('sHeader',Phpfox::getPhrase('fblike.facebook_social_like'));
		}
		
		$this->template()->assign(array(
				'sUrl' => $sUrl,
				'sColor' => Phpfox::getParam('fblike.color'),
				'sFace' => Phpfox::getParam('fblike.show_face') ? 'true' : 'false',
				'sStream' => Phpfox::getParam('fblike.show_stream') ? 'true' : 'false',
				'sForceWall' => Phpfox::getParam('fblike.force_wall') ? 'true' : 'false',
				'sShowHeader' => Phpfox::getParam('fblike.header') ? 'true' : 'false',
				'iHeight' => Phpfox::getParam('fblike.height'), 
				'iWidth' => Phpfox::getParam('fblike.width')
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
		(($sPlugin = Phpfox_Plugin::get('fblike.component_block_fblike_clean')) ? eval($sPlugin) : false);
	}
}

?>