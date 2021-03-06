<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Videochannel_Component_Block_Parent extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aVideoParent = $this->getParam('aCallbackVideo');
		
		$oServiceVideo = Phpfox::getService('videochannel')->getForParentBlock($aVideoParent['module'], $aVideoParent['item'], $aVideoParent);

		if (!$oServiceVideo->getCount() && !defined('PHPFOX_IN_DESIGN_MODE'))
		{
			return false;
		}
		
		if (!Phpfox::getService('group')->hasAccess($aVideoParent['item'], 'can_use_video'))
		{
			return false;
		}			
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('videochannel.videochannel'),
				'sBoxJsId' => 'parent_video',
				'aVideos' => $oServiceVideo->get(),
				'aVideoParent' => $aVideoParent,
				'sAddNewVideoLink' => $this->url()->makeUrl('videochannel.upload', array('module'=> $aVideoParent['module'], 'item'=>$aVideoParent['item']))
			)
		);
		
		if ($oServiceVideo->getCount() > 6)
		{
			$this->template()->assign('aFooter', array(
					Phpfox::getPhrase('videochannel.view_more') => $this->url()->makeUrl($aVideoParent['url'][0], $aVideoParent['url'][1])
				)
			);
		}
		
		return 'block';		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('videochannel.component_block_parent_clean')) ? eval($sPlugin) : false);
	}		
}

?>