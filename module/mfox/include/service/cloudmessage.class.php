<?php
/**
 * @package mfox
 * @version 3.01
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * @package mfox
 * @subpackage mfox.service
 * @author Nam Nguyen <namnv@younetco.com>
 * @version 3.01
 * @since May 13, 2013
 * @link http://developer.android.com/google/gcm/index.html
 */
class Mfox_Service_Cloudmessage extends Phpfox_Service
{

	/**
	 * Url of google could messge to send
	 */
	CONST GOOGLE_SEND_URL = 'https://android.googleapis.com/gcm/send';

	/**
	 * @param array $aData send data must be array to parse by json_decode
	 * @param int $iUserId
	 */
	function send($aData, $iUserId)
	{
		/**
		 * etc: AIzaSyB3un2VRYz6LHmTVl8AvWRd-R7udZgTYDU
		 * @var string
		 */
		$sServerApiKey = Phpfox::getParam('mfox.google_key');

		if (strlen($sServerApiKey) < 8)
		{
			return array('message' => 'google api key is empty!');
		}

		/**
		 * Registration Ids of devices, also called "devices id"
		 * @var array
		 */
		$aDeviceIds = Phpfox::getService('mfox.device') -> getIds($iUserId);

		if (empty($aDeviceIds))
		{
			return array('message' => 'no device associate with this user id ' . $iUserId);
		}

		$fields = array(
			'registration_ids' => $aDeviceIds,
			'data' => $aData,
		);

		$headers = array(
			'Authorization: key=' . $sServerApiKey,
			'Content-Type: application/json'
		);

		// Open connection
		$ch = curl_init();

		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, self::GOOGLE_SEND_URL);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		// Execute post
		$response = curl_exec($ch);

		// Close connection
		curl_close($ch);

		$result = json_decode($response, 1);

		return $response;

	}

}
