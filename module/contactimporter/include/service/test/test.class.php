<?php

/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Development
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');

class Contactimporter_Service_Test_Test extends Phpfox_Service
{
	
	private $_bInvitationResult = true;
	public function __construct()
	{
		$this->setSendingInvitationResult();
	}
	public function isTest()
	{
		return false;
	}

	public function setSendingInvitationResult($sType = '')
	{
		switch($sType)
		{
			case TRUE:
			case FALSE:
				$this->_bInvitationResult = $sType;
				break;
			default:
				$aRand = array(TRUE, FALSE, TRUE, TRUE);

				return $aRand[array_rand($aRand)];	
				break;
		}
			
	}

	public function getTestInvitationResult()
	{
		$aRand = array(TRUE, FALSE, TRUE, TRUE);

		$bResult = $aRand[array_rand($aRand)];
				
		return array(
			'result' => $bResult, 
			'message' => serialize( $this->_bInvitationResult)
		);
	}

	public function getTestQuota()
	{
		return 5;
	}
}
?>