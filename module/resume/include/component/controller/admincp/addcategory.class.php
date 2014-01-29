<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
class Resume_Component_Controller_Admincp_Addcategory extends Phpfox_Component
{
	/*
	 * Process method which is used to process this component
	 */
	 public function process()
	 {
	 	// Add validation for form elements
	 	$aValidation = array(
			'name' => Phpfox::getPhrase('resume.provide_resume_category')
		);	
		$oValid = Phpfox::getLib('validator')->set(array('sFormName' => 'js_form', 'aParams' => $aValidation));
		
		// In edit category mode or in add category mode
		$bIsEdit = false;
		
		if ($iEditId = $this->request()->getInt('id'))
		{
			
			if ($aCategory = Phpfox::getService('resume.category')->getForEdit($iEditId))
			{
				$bIsEdit = true;
				$this->template()
				     ->setHeader('<script type="text/javascript">$(function(){$(\'#js_mp_category_item_' . $aCategory['parent_id'] . '\').attr(\'selected\', true);});</script>')
					 ->assign('aForms', $aCategory);
			}
		}		
		
		if ($aVals = $this->request()->getArray('val'))
		{
			// Edit category
			if ($bIsEdit)
			{
				if ($oValid->isValid($aVals))
				{				
					if (Phpfox::getService('resume.category.process')->update($aCategory['category_id'], $aVals))
					{
						$this->url()->send('admincp.resume.categories', array('id' => $aCategory['category_id']), Phpfox::getPhrase('resume.category_successfully_updated'));
					}
				}
			}
			// Add new category
			else 
			{
				if ($oValid->isValid($aVals))
				{
					if (Phpfox::getService('resume.category.process')->add($aVals))
					{
						$this->url()->send('admincp.resume.categories', null, Phpfox::getPhrase('resume.category_successfully_added'));
					}
				}
			}
		}
		
		// Add page title, breadcrumb and variable to layout view 
		$this->template()->setTitle( $bIsEdit ? Phpfox::getPhrase('resume.edit_category') : Phpfox::getPhrase('resume.add_category'))
			->setBreadCrumb( $bIsEdit ? Phpfox::getPhrase('resume.edit_category') : Phpfox::getPhrase('resume.add_category'), $this->url()->makeUrl('admincp.resume.addcategory'))
			->assign(array(			
				'sCreateJs'  => $oValid->createJS(),
				'sGetJsForm' => $oValid->getJsForm(),
				'sOptions' 	 => Phpfox::getService('resume.category')->display('option')->get(),
				'bIsEdit' 	 => $bIsEdit
			)
		);		
 	}
}
?>