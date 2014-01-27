<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[YOUNET_COPPYRIGHT]
 * @author  		VuDP, AnNT
 * @package  		Module_jobposting
 */

class Jobposting_Component_Controller_Admincp_Package_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iPage 		= $this->request()->getInt('page');
		$iPageSize 	= 10;
		
		$iCount = Phpfox::getService('jobposting.package')->getItemCount();
		$aPackages = Phpfox::getService('jobposting.package')->getPackages($iPage, $iPageSize, $iCount);
		
		phpFox::getLib('pager')->set(array(
				'page'  => $iPage, 
				'size'  => $iPageSize, 
				'count' => $iCount
		));
		
		
		$this->template()->setTitle(Phpfox::getPhrase('jobposting.manage_package'))
			->setBreadcrumb(Phpfox::getPhrase('jobposting.manage_package'))
			->assign(array(
					'aPackages' => $aPackages,
				)
		)->setHeader(array(
			'jobposting_backend.css' => 'module_jobposting'
		));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		
	}
}

?>