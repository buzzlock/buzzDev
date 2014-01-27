<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Campaign_User_Image_Entry extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {
		$this->template()->assign(array(
				'sNoimageUrl' => Phpfox::getLib('template')->getStyle('image', 'noimage/' . 'profile_50.png'),
			)
		);
	}

}

?>
