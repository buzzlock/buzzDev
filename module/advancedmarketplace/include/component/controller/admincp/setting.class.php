<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Admincp_Setting extends Phpfox_Component 
{
	public function process()
	{
		$aSettings = phpfox::getService('advancedmarketplace')->getSettings();
		
		if(isset($_POST['submit']) && $_POST['submit'])
		{
			$aVals = $this->request()->get('val');
			
			//if($this->isValid($aVals))
			{
				phpfox::getService('advancedmarketplace.process')->updateSetting($aVals);
				$this->url()->send($this->url()->makeUrl('admincp.advancedmarketplace.setting'));
			}
			
		}
		
		$aCoords = Phpfox::getService('advancedmarketplace')->getListingCoordinates();
		
		$oFilter = Phpfox::getLib('parse.input');
		$lat=10;
		$lng=3;
		$zoom=8;
		if(isset($aSettings['location_setting']) && $aSettings['location_setting']!="")
		{
			$aCoordinates=array();
			
			list($aCoordinates, $sGmapAddress) = phpfox::getService("advancedmarketplace.process")->address2coordinates($aSettings['location_setting']);
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
			//'aRow' => $aRow,
		));
		/*$this->template()->setHeader('cache', array(
                    'pager.css' => 'style_css',
					'all.css' => 'module_advancedmarketplace',
                    'country.js' => 'module_core',
                    'jhslide.js' => 'module_advancedmarketplace',
                    'browse.css' => 'module_advancedmarketplace',
                    'comment.css' => 'style_css',
                    'jhslide.css' => 'module_advancedmarketplace',
                    'feed.js' => 'module_feed',
                    'jquery.cycle.all.js' => 'module_advancedmarketplace',
                    'index.js' => 'module_advancedmarketplace',
                        )
                );*/
		$this->template()->setPhrase(array('advancedmarketplace.view_this_listing',
											'advancedmarketplace.address',
											'advancedmarketplace.listing',
											'advancedmarketplace.location',

									));
		$this->template()->assign(array(
					'aSettings'=>$aSettings
					));
					
		$this->template()->setBreadcrumb('Setting', $this->url()->makeUrl('admincp.advancedmarketplace.setting'));
		
	}
	
	public function isValid($aVals)
	{
		$strTime = '';
		$regexp = "/^\d+$/";
		$aVals['time'] = trim($aVals['time_setting']);
		$atime = explode(" ", $aVals['time']);
		if(preg_match($regexp, $aVals['time']) == 1)
		{
			$aVals['time'] = (int)$aVals['time'];
			if($aVals['time'] > 999)
			{
				Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
				return false;
			}
			else
			{
				$strTime = $aVals['time']."d";
			}

		}
		else
		{

			$bCheck = explode("m", $aVals['time']);
			if(count($bCheck) > 2)
			{
				Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
				return false;
			}
			$bCheck = explode("w", $aVals['time']);
			if(count($bCheck) > 2)
			{
				Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
				return false;
			}
			$bCheck = explode("d", $aVals['time']);

			if(count($bCheck) > 2)
			{
				Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
				return false;
			}
			/*$bCheck = explode("h", $aVals['time']);
			if(count($bCheck) > 2)
			{
				Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
				die('15');
				return false;
			}*/
				
			foreach($atime as $time)
			{
				$b = substr($time, strlen($time)-1, 1);
				$a = substr($time, 0, strlen($time)-1);
				if($b != "m" && $b != "w" && $b != "d")
				{
					Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
					return false;
					break;
				}
				else if(preg_match($regexp, $a) == 0)
				{
					Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
					return false;
					break;
				}
				else
				{
					$a = (int)$a;
					if($a > 999)
					{
						Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.time_is_invalid'));
						return false;
						break;
					}
					else
					{
						$strTime .= (int)$a."".$b." ";
					}
				}
			}
		}
		return true;
	}
}
?>
