<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Fevent_Component_Block_Sponsored extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{
		if (!Phpfox::isModule('ad'))
		{
			return false;
		}	    
		
		if (defined('PHPFOX_IS_GROUP_VIEW'))
	    {
			return false;
	    }
	    
	    $aSponsorEvents = Phpfox::getService('fevent')->getRandomSponsored();
	    
	    if (empty($aSponsorEvents))
	    {
			return false;
	    }
	    
	    Phpfox::getService('ad.process')->addSponsorViewsCount($aSponsorEvents['sponsor_id'], 'fevent');
		
	    $this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.sponsored_event'),
				'aSponsorEvents' => $aSponsorEvents,
				'aFooter' => array(Phpfox::getPhrase('fevent.encourage_sponsor') => $this->url()->makeUrl('profile.fevent', array('sponsor' => 1)))//$this->url()->makeUrl('fevent', array('view' => 'my', 'sponsor' => 1)))
		    )
		);
		
	    return 'block';
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_sponsored_clean')) ? eval($sPlugin) : false);
	}
}

?>