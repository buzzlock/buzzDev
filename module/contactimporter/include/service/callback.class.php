<?php

defined('PHPFOX') or exit('NO DICE!');

class ContactImporter_Service_Callback extends Phpfox_Service
{

	/**
	 * Class constructor
	 */
	public function __construct()
	{

	}

	/**
	 * Action to take when user cancelled their account
	 * @param int $iUser
	 */
	public function onDeleteUser($iUser)
	{
		$this -> database() -> delete(Phpfox::getT('contactimporter_social_joined'), 'user_id = ' . (int)$iUser);
		$this -> database() -> delete(Phpfox::getT('contactimporter_queue'), 'user_id = ' . (int)$iUser);
		$this -> database() -> delete(Phpfox::getT('contactimporter_statistics'), 'user_id = ' . (int)$iUser);
		$this -> database() -> delete(Phpfox::getT('contactimporter_contact'), 'user_id = ' . (int)$iUser);
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
		if ($sPlugin = Phpfox_Plugin::get('contactimporter.service_callback__call'))
		{
			eval($sPlugin);
			return;
		}

		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}

}
