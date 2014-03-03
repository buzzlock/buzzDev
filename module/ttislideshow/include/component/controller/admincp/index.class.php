<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * author		Teamwurkz Technologies Inc.
 * package		tti_components
 */
 
class Ttislideshow_Component_Controller_Admincp_Index extends Phpfox_Component
{

	public function process()
	{
		if (($iDeleteId = $this->request()->getInt('delete')))
		{
			if (Phpfox::getService('ttislideshow.process')->delete($iDeleteId))
			{
				$this->url()->send('admincp.ttislideshow', null, 'Slide successfully deleted.');
			}
		}				

		$aSet = array(
				'ttislideshow.dir_image' => Phpfox::getParam('core.dir_pic') . 'ttislideshow' . PHPFOX_DS,
				'ttislideshow.url_image' => Phpfox::getParam('core.url_pic') . 'ttislideshow/');
		
		Phpfox::getLib('setting')->setParam($aSet);  
		
		$this->template()->setTitle('Slideshow')	
			->setBreadcrumb('Slideshow', $this->url()->makeUrl('admincp.ttislideshow'))
			->assign(array(
					'aSlides' => Phpfox::getService('ttislideshow')->get()
				)
			);		
	}
	
}

?>