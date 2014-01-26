<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Service_Helper extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('advancedmarketplace');
	}
	
	public function display($aParam = array()) {
		/*
			'server_id' => $aRow['server_id'],
			'source' => 'xxx',
			'max_width' => 120,
			'max_height' => 120
		*/
		return sprintf("<img server_id=\"%s\" src=\"%s\" max-width=\"%d\" max-height=\"%d\" />",
			$aParam["server_id"],
			$aParam["source"],
			$aParam["max_width"],
			$aParam["max_height"]
		);
	}

	/**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('advancedmarketplace.service_helper__call'))
		{
			return eval($sPlugin);
		}

		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}
}

?>