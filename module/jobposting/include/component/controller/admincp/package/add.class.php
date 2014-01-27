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

class Jobposting_Component_Controller_Admincp_Package_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	*/
	public function process()
	{
		$bIsEdit = false;
		
		$aValidation = array(
			'name' => array(
				'def' => 'required',
				'title'=> Phpfox::getPhrase('jobposting.name_of_package_cannot_be_empty')
			),
			'post_number' => array(
				'def' => 'number',
				'title'=> Phpfox::getPhrase('jobposting.post_job_number_have_to_be_a_number')
			),
			'expire_number' => array(
				'def' => 'number',
				'title' => Phpfox::getPhrase('jobposting.valid_peried_have_to_be_a_number')
			),
			'fee' => array(
				'def' => 'number',
				'title' => Phpfox::getPhrase('jobposting.package_fee_have_to_be_a_number')
			)
		);	
		
		$oValid = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'js_add_package_form', 
				'aParams'   => $aValidation
		));
		
		if ($iEditId = $this->request()->getInt('id'))
		{
			if ($aPackage = Phpfox::getService('jobposting.package')->getById($iEditId))
			{
				$bIsEdit = true;
				$this->template()->assign(array(
					'aForms' => $aPackage,
				));
			}
		}		
		
		if ($aVals = $this->request()->getArray('val'))
		{
			
			if ($oValid->isValid($aVals)){
				if($bIsEdit){
					if (Phpfox::getService('jobposting.package.process')->update($aPackage['package_id'], $aVals))
					{
						$this->url()->send('admincp.jobposting.package.add', array('id' => $aPackage['package_id']), Phpfox::getPhrase('jobposting.package_successfully_updated'));
					}
				}
				else{
					if (Phpfox::getService('jobposting.package.process')->add($aVals))
					{
						$this->url()->send('admincp.jobposting.package.add', null, Phpfox::getPhrase('jobposting.package_successfully_added'));
					}
				}
			}
		}
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('jobposting.edit_a_package') : Phpfox::getPhrase('jobposting.create_a_new_package')))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('jobposting.edit_a_package') : Phpfox::getPhrase('jobposting.create_a_new_package')), $this->url()->makeUrl('admincp.jobposting.package.add'))
			->assign(array(
					'bIsEdit' => $bIsEdit,
					'sCreateJs'   => $oValid -> createJS(),
				)
		);
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