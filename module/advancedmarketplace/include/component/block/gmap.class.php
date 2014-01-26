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
class Advancedmarketplace_Component_Block_Gmap extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$name="default_location";
		
		$aRow = phpfox::getService('advancedmarketplace')->getSettings();
		
		$this->template()->setBreadcrumb('Set default location of Google Map');
		$aCoords = Phpfox::getService('advancedmarketplace')->getListingCoordinates();
		
		$oFilter = Phpfox::getLib('parse.input');
		$lat=10;
		$lng=3;
		$zoom=8;
		$aCoords[0]['event_id'] = $aCoords[0]['listing_id'];
        
		if(isset($aRow['location_setting']) && $aRow['location_setting']!="")
		{
			$aCoordinates=array();
			
			list($aCoordinates, $sGmapAddress) = phpfox::getService("advancedmarketplace.process")->address2coordinates($aRow['location_setting']);
			$lat = $aCoordinates[1];
           	$lng = $aCoordinates[0];
			$zoom=13;
		}	
	
		$this->template()->assign(array(
			'aCoords' => $aCoords,
			'lat' => $lat,
			'lng' => $lng,	
			'zoom' => $zoom,
			'aRow' => $aRow,
		));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_gmap_clean')) ? eval($sPlugin) : false);
	}
}

?>