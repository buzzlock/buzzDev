<?php
/*
 * Teamwurkz Technologies Inc.
 * package tti_components
 */

defined('PHPFOX') or exit('NO DICE!');

class Ttislideshow_Component_Block_Display extends Phpfox_Component
{
	
	public function process()
	{
		
		$aSlides= Phpfox::getService('ttislideshow')->getdisplay();

		$aSet = array(
				'ttislideshow.dir_image' => Phpfox::getParam('core.dir_pic') . 'ttislideshow' . PHPFOX_DS,
				'ttislideshow.url_image' => Phpfox::getParam('core.url_pic') . 'ttislideshow/');
		
		Phpfox::getLib('setting')->setParam($aSet);  
						
		$sStyle='<script type="text/javascript" src="module/ttislideshow/static/jscript/style_1.js"></script>
				<link rel="stylesheet" type="text/css" href="module/ttislideshow/static/css/default/default/style_1.css" />';
		
			$this->template()->assign(array(				
					'sStyle' => $sStyle,
					'iTotal' => count($aSlides),
					'aSlides' => $aSlides
				)
			);		
		return 'block';
	}

}

?>