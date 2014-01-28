<?php
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Mobile_Mobile_Homepage extends Phpfox_Component
{
    public function process()
    {
		if(!phpfox::isMobile()){
			return false;
		}
		return 'block';
    }
  
}

?>