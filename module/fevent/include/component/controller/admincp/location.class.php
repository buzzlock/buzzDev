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
class Fevent_Component_Controller_Admincp_Location extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$name="default_location";
		if(isset($_POST['submit']))
		{
			if ($aVals = $this->request()->getArray('val'))
			{	
				$default_value=$aVals['location'];
				$phrase_location=Phpfox::getPhrase('fevent.location')."...";
				if($default_value==$phrase_location)
					$default_value="";
				phpfox::getService("fevent")->updateSetting($name,$default_value);
			}	
		}
		$aRow=phpfox::getService("fevent")->getSetting($name);
		
		$this->template()->setBreadcrumb(Phpfox::getPhrase('fevent.admin_menu_manage_location'));
		$aCoords = Phpfox::getService('fevent')->getEventCoordinates();
		
		$oFilter = Phpfox::getLib('parse.input');
		$lat=10;
		$lng=3;
		$zoom=8;
		if(isset($aRow['default_value']) && $aRow['default_value']!="")
		{
			$aCoordinates=array();
			$aCoords=array();
			list($aCoordinates, $sGmapAddress) = phpfox::getService("fevent.process")->address2coordinates($aRow['default_value']);
			$lat = $aCoords[0]['lat'] = $aCoordinates[1];
           	$lng = $aCoords[0]['lng'] = $aCoordinates[0];
			$zoom=13;
			$aCoords[0]['gmap_address'] = $oFilter->prepare($sGmapAddress);
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
		(($sPlugin = Phpfox_Plugin::get('fevent.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
