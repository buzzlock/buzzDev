<?php
class Bizsignup_Service_Thankyou extends Phpfox_Service  
{
	public function get_refid($refName)
	{
		return Phpfox::getLib('database')
		->select('u.user_id')
		->from(Phpfox::getT('user'), 'u')
		->where("u.user_name='{$refName}'")
		->execute('getField');
	}
	public function get_ref_name()
	{
		return Phpfox::getLib('database')
		->select('u.ref_name')
		->from(Phpfox::getT('user'), 'u')
		->where('u.user_id='.Phpfox::getUserId())
		->execute('getField');
	}
	public function get_usr()
	{ 
		$id 			= Phpfox::getUserId();
		$adm 		= Phpfox::getService('bizcore.admin')->get_admin();
		$sDriver	= 'phpfox.database.driver.mysql';
		Phpfox::getLibClass('phpfox.database.dba');
		Phpfox::getLibClass($sDriver);
		$oDb2 = Phpfox::getLib($sDriver);
		$oDb2->connect($adm['host'], $adm['user'], $adm['pass'],	$adm['dbase']);
		$val =  $oDb2->select	
		('	fullname,
			address,
			state,
			country,
			alertpay,
			paypal
		')
		->from('ezygold_users')
		->where('id='.$id)
		->execute('getRow');
		$oDb2->close();
		return $val;
	}
	public function get_xtd()
	{
		$id 			= Phpfox::getUserId();
		$adm 		= Phpfox::getService('bizcore.admin')->get_admin();
		$sDriver	= 'phpfox.database.driver.mysql';
		Phpfox::getLibClass('phpfox.database.dba');
		Phpfox::getLibClass($sDriver);
		$oDb2 = Phpfox::getLib($sDriver);
		$oDb2->connect($adm['host'], $adm['user'], $adm['pass'], $adm['dbase']);
		$val = $oDb2->select
		('	firstname,
			lastname,
			city,
			zip,
			apt,
			ssn
		')
		->from('ezygold_usersxtd')
		->where('idmbr='.$id)
		->execute('getRow');
		$oDb2->close();
		return $val;
	}
	public function ssn_secure($ssn)
	{		
		$rep	= array(' ', '-', '.', '_', ':', ',', '/');
		$val	= str_replace($rep, '', $ssn);
		$len 	= strlen($val) - 4;
		$spl 	= str_split($val);
		return "&#149&#149&#149 - &#149&#149 - {$spl[$len]}{$spl[$len+1]}{$spl[$len+2]}{$spl[$len+3]}";
	}
}
?>