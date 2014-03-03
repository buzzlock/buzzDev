<?php
class Bizsignup_Component_Controller_Thankyou extends Phpfox_Component
{
	public function process()
	{
		$mod 	= 'Welcome to buzzBiz';
		$user 	= Phpfox::getService('membership.thankyou');
		$usr	= $user->get_usr();
		$xtd 	= $user->get_xtd();
		if (empty($usr['alertpay']))
		{
			$gName	= 'Paypal';
			$gMail	= $usr['paypal'];
		}
		else 
		{
			$gName	=  'Payza';
			$gMail	= $usr['alertpay'];
		}
		$this->template()
			->setTitle($mod)
			->setBreadcrumb($mod)
			->setMeta('keywords', 'thankyou', $mod)
			->assign	
				(array
					(	'firstname'	=> $xtd['firstname'],
						'lastname'	=> $xtd['lastname'],
						'fullname'	=> $usr['fullname'],
						'address'	=> $usr['address'],
						'apt'		=> $xtd['apt'],
						'city'		=> ucfirst($xtd['city']),
						'state'		=> $usr['state'],
						'zip'		=> $xtd['zip'],
						'country'	=> $usr['country'],
						'gate'		=> $gName,
						'gateMail'	=> $gMail,
						'ssn'		=> $user->ssn_secure($xtd['ssn'])
					)
				);
	}
}
?>