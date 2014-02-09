<?php
class Membership_Component_Controller_Index extends Phpfox_Component
{
	public function process()
	{
		$grp = Phpfox::getUserBy('user_group_id');
		if ($grp == 5)
		{
			$bizNew = 3;
			$foxNew = 7;
		}
		elseif ($grp == 6)
		{
			$bizNew = 4;
			$foxNew = 8;
		}
		elseif ($grp == 1)
		{
			$bizNew = 5;
			$foxNew = 1;
		}
		else
		{
			$this->url()->send('office');
		}
		$mod	= 'Buzzbiz Sign-up';
		$img	= 'http://buzzlock.net/module/membership/static/image/';
		$this->template()
			->setTitle($mod)
			->setBreadcrumb($mod)
			->setMeta('keywords', $mod, 'join buzzbiz', 'buzzbiz member')
			->setHeader	(array	(	'signup.css'	=> 'module_membership', 
									'msg.js'		=> 'module_membership'
								)
						)
			->assign	(array	(	'buzzbizBG'		=> $img.'buzzbizBG.png',
									'buzzbiz1'		=> $img.'buzzbiz1.png',
									'buzzbiz2'		=> $img.'buzzbiz2.png',
									'buzzbiz3'		=> $img.'buzzbiz3.png',
									'buzzbizLogo'	=> $img.'buzzbizLogo.png',
									'paypalLogo'	=> $img.'paypal.gif',
									'payzaLogo'		=> $img.'payza.gif',
									'irsLogo'		=> $img.'irsGov.png',
									'paypal'		=> 'https://www.paypal.com/',
									'payza'			=> 'https://secure.payza.com/signup/signup.aspx',
									'irs'			=> 'http://www.irs.gov/Individuals/International-Taxpayers/Taxpayer-Identification-Numbers-%28TIN%29',
									'error'			=> ''
								)
						);
		if (($aVal = $this->request()->getArray('val'))) 
        { 
			if (($err = Phpfox::getService('membership.validate')->error($aVal)))
			{
				$this->template()->assign('error', $err);
			}
			elseif (isset($aVal['update']) && !empty($aVal['update']) && !$err)
			{
				$user = Phpfox::getService('membership.signup');
				$user->curl_user($aVal);
				$user->set_foxGrp($foxNew);
				$user->set_bizGrp($bizNew);
				$this->url()->send('membership.thankyou', null, ' Welcome to Buzzbiz. Your information has been submitted successfully.');
			}
		}
	}
}
?>	