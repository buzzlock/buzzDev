<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Petition_Component_Controller_Admincp_Category_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$aValidation = array(
			'name' => Phpfox::getPhrase('petition.provide_petition_category')
		);		
		
		$oValid = Phpfox::getLib('validator')->set(array('sFormName' => 'js_form', 'aParams' => $aValidation));	

		if ($aVals = $this->request()->getArray('val'))
		{			
			if ($oValid->isValid($aVals))
			{
				if (Phpfox::getService('petition.category.process')->add($aVals['name'], '0'))
				{
					$this->url()->send('admincp.petition.category.add', null, Phpfox::getPhrase('petition.category_successfully_added'));
				}
			}
		}		
		
		$this->template()->setTitle(Phpfox::getPhrase('petition.add_category'))
			->setBreadCrumb(Phpfox::getPhrase('petition.add_category'), $this->url()->makeUrl('admincp.petition.category.add'))
			->assign(array(			
				'sCreateJs' => $oValid->createJS(),
				'sGetJsForm' => $oValid->getJsForm()
			)
		);		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('petition.component_controller_admincp_category_add_clean')) ? eval($sPlugin) : false);
	}
}

?>