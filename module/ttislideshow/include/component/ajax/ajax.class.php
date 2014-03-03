<?php

defined('PHPFOX') or exit('NO DICE!');
/**
 * author		Teamwurkz Technologies Inc.
 * package		tti_components
 */
 
class Ttislideshow_Component_Ajax extends Phpfox_Ajax
{
	public function deleteImage()
	{
		Phpfox::getService('ttislideshow.process')->deleteImage($this->get('slide_id'));
	}
	
}

?>