<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Entry_Content_Blog extends Phpfox_component{

	public function process ()
	{
		$aEntry = $this->getParam('aYnEntry');
		$bIsPreview = $this->getParam('bIsPreview');
		$this->template()->assign(array(
				'aBlogEntry' => $aEntry,
				'bIsPreview' => $bIsPreview
				// 'sHeader' => Phpfox::getPhrase('contest.categories')
			)
		);	
			
	}
}