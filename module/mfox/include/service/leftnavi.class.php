<?php
/**
 * @package mfox
 * @version 3.01
 */
defined('PHPFOX') or exit('NO DICE!');
/**
 * @author ductc@younetco.com
 * @package mfox
 * @subpackage mfox.service
 * @version 3.01
 * @since June 10, 2013
 * @link Mfox Api v3.0
 */
class Mfox_Service_Leftnavi extends Phpfox_Service
{

	function __construct()
	{
		$this -> _sTable = Phpfox::getT('mfox_leftnavi');
	}

	/**
     * Input data: N/A
     * 
     * Output data:
	 * + sName : string
	 * + sLabel: string
	 * + sLayout: string
	 * + sIcon: string
	 * + sUrl: string
     * 
     * @see Mobile - API phpFox/Api V3.0 - Get method.
     * @see leftnavi
     * 
	 * @param array $aData optional
	 * @return array
	 */
	function getAction($aData)
	{
		$aRows = $this -> database() -> select('*') -> from($this -> _sTable) -> where('is_enabled=1') -> order('sort_order asc') -> execute('getSlaveRows');

		$aResult = array();

		foreach ($aRows as $aRow)
		{
			$aResult[] = array(
				'sName' => $aRow['name'],
				'sLabel' => $aRow['label'],
				'sLayout' => $aRow['layout'],
				'sIcon' => $aRow['icon'],
				'sUrl' => $aRow['url'],
			);
		}

		return $aResult;
	}
    /**
     * Input data: N/A
     * 
     * Output data:
	 * + sName : string
	 * + sLabel: string
	 * + sLayout: string
	 * + sIcon: string
	 * + sUrl: string
     * 
     * @see Mobile - API phpFox/Api V3.0 - Post method.
     * @see leftnavi
     * 
     * @param array $aData
     * @return array
     */
	function postAction($aData)
	{
		return $this -> getAction($aData);
	}

}
