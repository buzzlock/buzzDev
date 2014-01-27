<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Most_Liked extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fundraising.most_liked')
			)
		);
		return 'block';
	}

}

?>
