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
class Fevent_Component_Block_Featured extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aParentModule = $this->getParam('aParentModule');
		$bIsPage = $aParentModule['module_id'] == 'pages' ? $aParentModule['item_id'] : 0;
		//$aUser = $this->getParam('aUser');
		//$bIsProfile = !empty($aUser['user_id']) ?  $aUser['user_id'] : false;
		list($iTotal, $aFeatured) = Phpfox::getService('fevent')->getFeatured($bIsPage, false);
		
		if (!$iTotal)
		{
			return false;
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.featured_events'),
				'aFeatured' => $aFeatured,
				'bViewMore' => $iTotal > 7
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
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_featured_clean')) ? eval($sPlugin) : false);
	}
}

?>