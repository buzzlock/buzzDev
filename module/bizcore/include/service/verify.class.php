<?php
class Bizcore_Service_Verify extends Phpfox_Service  
{
	public function get_biz()
	{
		return Phpfox::getLib('database')
			->select('u.is_biz')
			->from(Phpfox::getT('user'), 'u')
			->where('u.user_id='.Phpfox::getLib('session')->get('cache_user_id'))
			->execute('getField');
	}
	public function get_usr()
	{
		return Phpfox::getLib('database')
		->select
		('	u.user_name,
			u.full_name,
			u.password,
			u.email,
			u.country_iso,
			u.last_ip_address			
		')
		->from(Phpfox::getT('user'), 'u')
		->where('u.user_id='.Phpfox::getLib('session')->get('cache_user_id'))
		->execute('getRow');
	}
	public function curl_new_user($refName)
	{
		$usr	= $this->get_usr();
		$curl	= Phpfox::getService('bizcore.curl');
		$curl->api_username	= $usr['user_name'];
		$curl->api_name			= $usr['full_name'];
		$curl->api_email			= $usr['email'];
		$curl->api_countryid		= $usr['country_iso'];
		$curl->api_ipaddress	= $usr['last_ip_address'];
		$curl->api_password	= $usr['password'];
		$curl->api_referrer 		= $refName;
		$curl->api_merchantid	= 'pending';
		$curl->api_merchant	= 'pending';
		$curl->api_paidvia		= 'pending';
		$curl->api_amount		= '0';
		$curl->api_batch			= '1';
		$curl->api_planid			= '1';
		$curl->task						= '2';
		$curl->apiregister();
	}
	public function set_ref($refName)
	{
		$refid = Phpfox::getLib('database')
		->select('u.user_id')
		->from(Phpfox::getT('user'), 'u')
		->where("u.user_name='{$refName}'") 
		->execute('getField');
		Phpfox::getLib('database')->update
		(	'phpfox_user',
			array
			(	'ref_id'		=> $refid,
				'ref_name' 	=> $refName,
				'is_biz'		=> 1
			),
			'user_id='.Phpfox::getLib('session')->get('cache_user_id')
		);
	}
}
?>