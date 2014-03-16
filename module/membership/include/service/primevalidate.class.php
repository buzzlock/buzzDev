<?php
defined('PHPFOX') or exit('NO DICE!');
class Membership_Service_Primevalidate extends Phpfox_Component
{
	public function __construct()
	{}	
	public function error($val)
	{
		if ($val['gate'] == 'false')
		{
			return '*Must select gateway.';
		}
		if (!isset($val['gateMail']) || empty($val['gateMail']))
		{
			return '*Your acct. email address is required.';
		}
		elseif (strlen($val['gateMail']) < 6 || strlen($val['gateMail']) > 64)
		{
			return 'Invalid acct. email.';
		}
	}
}
?>