<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_Admincp_Help extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{
		if (($iId = $this->request()->getInt('delete')))
		{
			if (Phpfox::getService('fundraising.help.process')->delete($iId))
			{
				$this->url()->send('admincp.fundraising.help', null, Phpfox::getPhrase('fundraising.fundraising_successfully_deleted'));
			}
		}
		
		$bIsEdit = false;
		if (($iEditId = $this->request()->getInt('id')))
		{
			$aRow = Phpfox::getService('fundraising.help')->getHelpForEdit($iEditId);
			$bIsEdit = true;
			$this->template()->assign(array('aForms' => $aRow));			
		}
		$iPage = $this->request()->getInt('page') ? $this->request()->getInt('page')  : 1;		
		$iLimit = 5;
	 	list($iTotal, $aHelps) = Phpfox::getService('fundraising.help')->get($iPage-1, $iLimit,'ASC');
		
		$aValidation = array(
			'title' => array(
				'def' => 'required',
				'title' => Phpfox::getPhrase('fundraising.fill_in_a_title_for_help')
			),
			'content' => array(
				'def' => 'required',
				'title' => Phpfox::getPhrase('fundraising.fill_in_a_content_for_your_help')
			)			
		);
				
		$oValid = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'ynfr_edit_campaign_form', 
				'aParams' => $aValidation
			)
		);
		
		
		if ($aVals = $this->request()->getArray('val'))
		{
			if ($oValid->isValid($aVals))
			{
				if($iId = Phpfox::getService('fundraising.help.process')->add($aVals)) //Add & Edit help
				{					
					$this->url()->send('admincp.fundraising.help', array() ,isset($aVals['help_id']) ? Phpfox::getPhrase('fundraising.fundraising_help_has_been_updated') : Phpfox::getPhrase('fundraising.your_fundraising_has_been_added'));					
				}
			}
		}
		
		Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $iTotal));
		
		$this->template()->setTitle(Phpfox::getPhrase('fundraising.helps'))
			->setBreadcrumb(Phpfox::getPhrase('fundraising.manage_helps'), $this->url()->makeUrl('admincp.fundraising.help'))
			->assign(array(
				'sCreateJs' => $oValid->createJS(),
				'sGetJsForm' => $oValid->getJsForm(),
				'aHelps' => $aHelps,
				'iMaxFileSize' => Phpfox::getParam('fundraising.help_icon_file_size_limit')
				)
			)
			->setEditor()
			->setHeader(array(
					'drag.js' => 'static_script',
					'<script type="text/javascript">Core_drag.init({table: \'#js_drag_drop\', ajax: \'fundraising.helpOrdering\'});</script>'		
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_admincp_help_clean')) ? eval($sPlugin) : false);
	}
}

?>