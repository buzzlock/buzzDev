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
class Resume_Component_Controller_Admincp_Addlevel extends Phpfox_Component
{
	/*
	 * Process method which is used to process this component
	 */
	 public function process()
	 {
	 	// Add validation for form elements
	 	$aValidation = array(
			'title' => phpFox::getPhrase('resume.please_insert_level_title')
		);	
		$oValid = phpFox::getLib('validator')->set(array('sFormName' => 'resume_add_level_form', 'aParams' => $aValidation));
		
		// Set breadcrumb
		$this->template()
			 ->setTitle(Phpfox::getPhrase('resume.add_new_level'))
			 ->setBreadCrumb(Phpfox::getPhrase('resume.add_new_level'), $this->url()->makeUrl('admincp.resume.addlevel'));
		
		// Add singer
		if ($aVals = $this->request()->getArray('val'))
        {
           if ($oValid->isValid($aVals))
				{
	                if (phpFox::getService('resume.level.process')->add($aVals))
	                {
	                    $this->url()->send('admincp.resume.levels', null, phpFox::getPhrase('resume.level_successfully_added'));
	                }
           		}
        }
	 }
}
?>
	