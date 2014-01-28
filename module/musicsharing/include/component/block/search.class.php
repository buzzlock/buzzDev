<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_Search extends Phpfox_Component
{	
	public function process()
	{
        $music = $this->getParam('onmusicsharingpage');
        if(!$music || $music != 1)
        {
            return false;
        }
		 $this->template()->assign(array(
                'sHeader' => phpFox::getPhrase('musicsharing.search_filter')
            )
        );
		return 'block';
	}

}

?>