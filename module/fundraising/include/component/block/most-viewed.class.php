<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Most_Viewed extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fundraising.most_viewed')
			)
		);
		return 'block';
	}

}

?>
