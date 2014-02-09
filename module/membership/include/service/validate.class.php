<?php
defined('PHPFOX') or exit('NO DICE!');
class Membership_Service_Validate extends Phpfox_Component
{
	public function __construct()
	{
	}	
	public function error($val)
	{
		if (!isset($val['firstname']) || empty($val['firstname']))
		{
			return '*First Name is required.';
		}
		elseif (strlen($val['firstname']) < 1 || strlen($val['firstname']) > 64)
		{
			return 'Invalid first name.';
		}
		if (!isset($val['lastname']) || empty($val['lastname']))
		{
			return '*Last Name is required.';
		}
		elseif (strlen($val['lastname']) < 1 || strlen($val['lastname']) > 64)
		{
			return 'Invalid last name.';
		}
		if (!isset($val['address']) || empty($val['address']))
		{
			return '*Address is required.';
		}
		elseif (strlen($val['address']) < 5 || strlen($val['address']) > 100)
		{
			return 'Invalid address.';
		}		
		if (strlen($val['apt']) > 10)
		{
			return 'Invalid Apartment.';
		}
		if (!isset($val['city']) || empty($val['city']))
		{
			return '*City name is required.';
		}
		elseif (strlen($val['city']) < 2 || strlen($val['city'] > 100))
		{
			return 'Invalid city name.';
		}
		if (!isset($val['zip']) || empty($val['zip']))
		{
			return '*Postal/Zip Code is required.';
		}
		elseif (strlen($val['zip']) != 5)
		{
			return 'Invalid Postal/Zip Code.';
		}
		if (!isset($val['password1']) || empty($val['password1']))
		{
			return '*Password is required.';
		}
		elseif (strlen($val['password1']) < 4 || strlen($val['password1']) > 64)
		{
			return 'Invalid password.';
		}
		elseif ($val['password1'] != $val['password2'])
		{
			return 'Passwords must match.';
		}
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
		if(!isset($val['ssn1']) || empty($val['ssn1']))
		{
			return '*SSN or TIN is required.';
		}
		elseif (strlen($val['ssn1']) < 9 || strlen($val['ssn1']) > 11)
		{
			return 'Invalid SSN or TIN.';
		}
		elseif ($val['ssn1'] != $val['ssn2'])
		{
			return 'SSN or TIN must match.';
		}
		if(!isset($val['isterms']))
		{
			return '*Agreeing to the Terms is required.';
		}
		return false;
    }
}
?>