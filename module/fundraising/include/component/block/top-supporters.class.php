<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Top_Supporters extends Phpfox_Component 
{
	

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if(!$this->getParam('bInHomepageFr'))
		{
			return false;	
		}

		$iLimit = Phpfox::getParam('fundraising.number_of_supporters_on_top_suporters_block');
		$aSupporters = Phpfox::getService('fundraising.user')->getTopSupporters($iLimit);
		if(count($aSupporters) == 0|| defined('PHPFOX_IS_USER_PROFILE') || defined('PHPFOX_IS_PAGES_VIEW'))
		{
			return false;
		}
		$this->template()->assign(array(
				'aSupporters' => $aSupporters,
				'sHeader' => Phpfox::getPhrase('fundraising.top_supporters')
			)
		);
		return 'block';

	}
	
}

?>
