<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		younet
 * @package 		Phpfox_Component
 * @version 		3.01
 */
class Resume_Component_Controller_Language extends Phpfox_Component
{
	public function process()
	{
		
		// User login requirement
		Phpfox::isUser(true);
		// Edit mode
		$bIsEdit = false;
		//Init variable
		$aRows = array();
		$lang_id = 0;
		
		$iId = $this->request()->get("id");
		
		if($iEditId = $this->request()->getInt("id"))
		{
			$bIsEdit = true;
			$aRows = Phpfox::getService("resume.language")->getAllLanguage($iEditId);
			$aBasic = Phpfox::getService("resume.basic")->getBasicInfo($iEditId);
			if($aBasic['user_id']!=Phpfox::getUserId())
			{
				if(!Phpfox::getUserParam('resume.can_edit_other_resume'))
					Phpfox::getLib("url")->send("subscribe");
			}
			if($lang_id = $this->request()->getInt("exp"))
			{
				$aExp = Phpfox::getService("resume.language")->getLanguage($lang_id);
				$this->template()->assign(array(
					'aForms' => $aExp,
				));			
			}
		}
		
		$aValidation = array(
			'name' => array(
				'def' => 'required',
				'title' => Phpfox::getPhrase('resume.add_language_name_to_your_resume')
			),	
		);
		
		$oValid = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'js_resume_add_form', 
				'aParams' => $aValidation
			)
		);
		
		if($iId==0)
		{
			Phpfox::getLib("url")->send("resume.add");
		}
		
    	$this->template()->assign(array(
    		'id' => $iEditId,
			'iExp' => $lang_id,
			'bIsEdit' => $bIsEdit,
			'aRows' => $aRows,
    		'typesession' => Phpfox::getService("resume.process")->typesesstion($iEditId),
		))
		->setHeader(array(	
			'resume.css' => 'module_resume',
			'resume.js' => 'module_resume'
		))
        ->setBreadcrumb(Phpfox::getPhrase('resume.resume'), $this->url()->makeUrl('resume'))
		->setBreadcrumb((!empty($iEditId) ? Phpfox::getPhrase('resume.editing_resume') . ': ' . Phpfox::getLib('parse.output')->shorten($aBasic['full_name'], Phpfox::getService('core')->getEditTitleSize(), '...') : Phpfox::getPhrase('resume.create_new_resume')), ($iEditId > 0 ? $this->url()->makeUrl('resume', array('add', 'id' => $iEditId)) : $this->url()->makeUrl('resume', array('add'))), true)
		->setFullSite()	;
		
		if ($aVals = $this->request()->getArray('val'))
		{
			if ($oValid->isValid($aVals))
			{
				if($lang_id==0)
				{
					$aVals['resume_id'] = $iEditId;
					Phpfox::getService("resume.language.process")->add($aVals);
					Phpfox::getService('resume')->updateStatus($iEditId);
					Phpfox::getLib("url")->send("resume.language",array('id' => $iId),Phpfox::getPhrase('resume.your_language_added_successfully'));
				}
				else {
					$aVals['lang_id'] = $lang_id;
					Phpfox::getService("resume.language.process")->update($aVals);
					Phpfox::getService('resume')->updateStatus($iEditId);
					Phpfox::getLib("url")->send("resume.language",array('id' => $iId,'exp' =>$lang_id),Phpfox::getPhrase('resume.your_language_updated_successfully'));
				}
			}
			else
			{
				$this->template()->assign(array(
				'aForms' => $aVals
				));
			}
		} 
	}
	
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('resume.component_controller_index_clean')) ? eval($sPlugin) : false);
	}
}

?>