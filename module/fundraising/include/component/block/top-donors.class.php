<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Top_Donors extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {

		if(!$this->getParam('bInHomepageFr'))
		{
			return false;	
		}
		$iLimit = Phpfox::getParam('fundraising.number_of_donors_on_top_donors_block');
		$aDonors = Phpfox::getService('fundraising.user')->getTopDonors($iLimit);

		if(count($aDonors) == 0 || defined('PHPFOX_IS_USER_PROFILE') || defined('PHPFOX_IS_PAGES_VIEW')) 
		{
			return false;
		}

		foreach($aDonors as &$aDonor)
		{
			$aDonor['amount_text'] = Phpfox::getService('fundraising')->getCurrencyText($aDonor['amount'], $aDonor['currency']);
		}

		$this->template()->assign(array(
				'aDonors' => $aDonors,
				'sHeader' => Phpfox::getPhrase('fundraising.top_donors')
			)
		);
		return 'block';
	}

}

?>
