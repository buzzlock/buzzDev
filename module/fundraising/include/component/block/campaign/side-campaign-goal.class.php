<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Campaign_Side_Campaign_Goal extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
		$aCampaign  = $this->getParam('aFrCampaign');
		if(!$aCampaign)
		{
			return false;
		}

		$aCampaign = Phpfox::getService('fundraising.campaign')->retrieveMoreInfoFromCampaign($aCampaign);
                
		$this->template()->assign(array(
				'aCampaign' => $aCampaign
			)
		);
		return 'block';
    }
    
}

?>