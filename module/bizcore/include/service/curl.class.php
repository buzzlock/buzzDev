<?php
class Bizcore_Service_Curl extends Phpfox_Service  
{
	public $api_userid		= '';
	public $api_username 	= '';
	public $api_name 		= '';
	public $api_referrer 	= '';
	public $api_email 		= '';
	public $api_password 	= '';
	public $api_amount 		= '';
	public $api_merchantid	= '';
	public $api_merchant 	= '';
	public $api_paidvia 	= '';
	public $api_batch 		= '';
	public $api_countryid 	= '';
	public $api_ipaddress 	= '';	
	public $api_planid 		= '';
	public $api_qryusers	= '';
	public $api_qryusersxtd	= '';
	public $task 			= '';	
	public $hasil 			= '';
	public $ch				= '';
	public $ip				= '';
	public $logged_in		= '';
	public function apiregister()
	{
       	$this->logged_in = false;
       	$data = array
		(	'api_username'		=>$this->api_username,
			'api_name'			=>$this->api_name,
			'api_referrer' 		=>$this->api_referrer,
			'api_email' 		=>$this->api_email,
			'api_password' 		=>$this->api_password,
			'api_amount' 		=>$this->api_amount,
			'api_merchantid'	=>$this->api_merchantid,
			'api_merchant' 		=>$this->api_merchant,
			'api_paidvia' 		=>$this->api_paidvia,
			'api_batch' 		=>$this->api_batch,
			'api_countryid' 	=>$this->api_countryid,
			'api_ipaddress' 	=>$this->api_ipaddress,
			'api_planid' 		=>$this->api_planid,
			'api_qryusers'		=>$this->api_qryusers,
			'api_qryusersxtd'	=>$this->api_qryusersxtd,
			'task' 				=>$this->task,
			'format' 			=>'xml',
			'apik' 				=>'8a15be644276d1fd263e52271445d79b'
		);		
        $data	= http_build_query($data);
        $res 	= $this->my_curl_post('http://www.buzzlock.net/biz/api/index.php?c=users&m=profile', $data);
        $this->hasil = $res['response'];
        if (preg_match('/o;/i', $res['response'])) 
		{
			return false;
		}
        return true;
    }
	public function ezygold_curlapi() 
	{
       	$this->ip 			= $_SERVER['REMOTE_ADDR'];
       	$this->logged_in	= false;
    }
    public function my_curl_post($url, $post_data, $ref = '') 
	{
        if ($this->ch == false) 
		{
			$this->my_curl_open();
		}
       	$ssl = false;
       	if (preg_match('/^https/i', $url)) 
		{
			$ssl = true;
		}
       	if ($ssl) 
		{
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		}
       	if ($ref == '') 
		{
			$ref = $url;
		}
       	curl_setopt($this->ch, CURLOPT_URL, $url);
       	curl_setopt($this->ch, CURLOPT_REFERER, $ref);
       	curl_setopt($this->ch, CURLOPT_POST, 1);
       	curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_data);
       	$res = curl_exec($this->ch);
       	$info = curl_getinfo($this->ch);
       	return array	(	'response'	=> trim($res),
							'info' 		=> $info
						);
    }   
    public function my_curl_open() 
	{
		$this->ch = curl_init();
       	curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
       	curl_setopt($this->ch, CURLOPT_AUTOREFERER, true);
       	@curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
       	curl_setopt($this->ch, CURLOPT_MAXREDIRS, 2);
       	curl_setopt($this->ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/curl_cookie.log');
       	curl_setopt($this->ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/curl_cookie.log');
       	curl_setopt($this->ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    }
    public function my_curl_close() 
	{
		if ($this->ch != false) 
		{
			curl_close($this->ch);
		}
    }	
}
?>