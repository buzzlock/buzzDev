<?php
class Membership_Component_Controller_Prime extends Phpfox_Component
{
	public function process()
	{
		$mod = 'Buzzlock Prime';
		$this->template()
			->setTitle($mod)
			->setBreadcrumb($mod)
			->setMeta('keywords', 'prime', 'membership', $mod)
			->setHeader(array('prime.css' => 'module_membership'))
			->assign
			(array
				(	'paypal'	=> 'https://www.paypal.com/',
					'payza'		=> 'https://secure.payza.com/signup/signup.aspx'
				)
			);
		
	}
}
?>