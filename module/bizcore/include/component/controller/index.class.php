<?php

class Bizcore_Component_Controller_Index extends Phpfox_Component
{
	public function process()
	{
		$this -> template()
			-> setTitle('Admin')
			-> setBreadcrumb('Admin');
	}

}
?>