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
class Advancedmarketplace_Component_Block_Search extends Phpfox_Component
{
	public function process()
	{
		$aParentModule = $this->getParam('aParentModule');
		if ($aParentModule !== null)
		{
			return false;
			//$aConds[] = 'l.module_id = "' . Phpfox::getLib('database')->escape($aParentModule['module_id']).'"';
			//$aConds[] = 'l.item_id = ' . (int) $aParentModule['item_id'];
		}
		else 
		{
			$aConds[] = 'l.module_id = "advancedmarketplace"';
			$aConds[] = 'l.item_id = 0';
		}
		
		$sType = $this->request()->get('type');
		$iType = 1;
		if(isset($sType) && $sType != '')
		{
			switch ($sType) {
				case 'product':
					$iType = 2;
					break;
				case 'coupon':
					$iType = 3;
					break;
				default:
					$iType = 1;
					break;
			}
		}
		
		$this->template()->assign('iType', $iType);
        $sSort = $this->request()->get('sort');
        $sWhen = $this->request()->get('when');
        $sShow = $this->request()->get('show');
		$sLocation= $this->request()->get('location');
		$sCity= $this->request()->get('city');
		$sCountry= $this->request()->get('country');
        $sBaseStr = Phpfox::getPhrase('advancedmarketplace.number_per_page');
        $aShows = array(
            array("value" => 10, "label" => str_replace('{number}', 10, $sBaseStr)),
            array("value" => 15, "label" => str_replace('{number}', 15, $sBaseStr)),
            array("value" => 18, "label" => str_replace('{number}', 18, $sBaseStr)),
            array("value" => 21, "label" => str_replace('{number}', 21, $sBaseStr))
        );
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('advancedmarketplace.search_filter'),
                'aShows' => $aShows,
                'sSort' => $sSort,
                'sWhen' => $sWhen,
                'sShow' => $sShow,
                'sLocation' => $sLocation,
                'sCity' => $sCity,
                'sCountry' => $sCountry,
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
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_search_clean')) ? eval($sPlugin) : false);
	}
}

?>