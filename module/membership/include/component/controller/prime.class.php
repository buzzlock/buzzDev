<?php
class Membership_Component_Controller_Prime extends Phpfox_Component
{
	public function process()
	{
		$mod = 'Prime Membership';
		$this->template()
			->setTitle($mod)
			->setBreadcrumb($mod)
			->setMeta('keywords', 'prime', 'membership', $mod);
	}
}
?>