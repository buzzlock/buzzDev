<?php

class Office_Component_Controller_Index extends Phpfox_Component
{
	public function process()
	{
		$this->template()
			->setTitle('Buzzbiz Office')
			->setBreadcrumb('Buzzbiz Office')
			->setMeta('keywords', 'back office', 'buzzbiz', 'affiliate')
			->setMeta('description', 'Buzzbiz back office.');			
	}
}

?>