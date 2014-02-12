<?php
class Membership_Service_Signup extends Phpfox_Service  
{
	public function curl_user($val)
	{
		$curl = Phpfox::getService('bizcore.curl');
		$curl->api_userid 		= Phpfox::getUserId();
		$curl->api_username		= Phpfox::getUserBy('user_name');
		$curl->api_qryusers 	= base64_encode($this->get_usrData($val));
		$curl->api_qryusersxtd 	= base64_encode($this->get_xtdData($val));
		$curl->task				= '9';
		$curl->apiregister();
	}
	public function get_usrData($val)
	{
		$usr = $this->usr_clean_data($val);
		return	"fullname = '{$usr['firstname']} {$usr['lastname']}',
				address = '{$usr['address']}',
				state = '{$usr['state']}',
				country = '{$usr['country']}',
				password = '{$usr['password']}',
				{$usr['gate']} = '{$usr['gateMail']}'";
	}
	public function get_xtdData($val)
	{
		$usr = $this->xtd_clean_data($val);
		return	"firstname = '{$usr['firstname']}',
				lastname = '{$usr['lastname']}',
				apt = '{$usr['apt']}',
				city = '{$usr['city']}',
				zip = '{$usr['zip']}',
				ssn = '{$usr['ssn']}'";
	}
	public function usr_clean_data($val)
	{		
		return array
		(	'firstname'	=> preg_replace('/[^a-zA-Z]/', '', $val['firstname']),
			'lastname'	=> preg_replace('/[^a-zA-Z]/', '', $val['lastname']),
			'address'	=> $val['address'],
			'state'		=> $this->state_name($val['country_child_id']),
			'country'	=> $val['country_iso'],
			'password'	=> string (md5(string ($val['password1']))),
			'gate'		=> $val['gate'],
			'gateMail'	=> $val['gateMail']
		);
	}
	public function xtd_clean_data($val)
	{		
		return array
		(	'username'	=> Phpfox::getUserBy('user_name'),
			'firstname'	=> preg_replace('/[^a-zA-Z]/', '', $val['firstname']),
			'lastname'	=> preg_replace('/[^a-zA-Z]/', '', $val['lastname']),
			'apt'		=> $val['apt'],
			'city'		=> preg_replace('/[^a-zA-Z]/', '', $val['city']),
			'zip'		=> preg_replace('/[^0-9]/', '', $val['zip']),
			'ssn'		=> preg_replace('/[^0-9]/', '', $val['ssn1'])
		);
	}
	public function state_name($id)
	{
		return Phpfox::getLib('database')
			->select('c.name')
			->from(Phpfox::getT('country_child'), 'c')
			->where('c.child_id='.$id)
			->execute('getField');
	}
	public function set_foxGrp($grp)
	{
		Phpfox::getLib('database')->update
		(	'phpfox_user',
			array('user_group_id' => $grp),
			'user_id='.Phpfox::getUserId()			
		);
	}
	public function set_bizGrp($grp)
	{
		$id 	= Phpfox::getUserId();
		$adm	= Phpfox::getService('bizcore.admin')->get_admin();
		$refid	= Phpfox::getLib('database')
			->select('u.ref_id')
			->from(Phpfox::getT('user'), 'u')
			->where('u.user_id='.$id)
			->execute('getField');
		Phpfox::getLibClass('phpfox.database.dba');
		$sDriver = 'phpfox.database.driver.mysql';
		Phpfox::getLibClass($sDriver);
		$oDb2 = Phpfox::getLib($sDriver);
		$oDb2->connect($adm['host'], $adm['user'], $adm['pass'], $adm['dbase']);
		$oDb2->update
		(	'ezygold_usersplan',
			array
			(	'paymid'	=> $grp,
				'idref'		=> $refid,
				'idspr'		=> $refid
			),	'idmbr='.$id
		);
		$oDb2->update
		(	'ezygold_usersxtd',
			array('paymid' => $grp),
			'idmbr='.$id
		);
		$oDb2->close();
	}
}
?>