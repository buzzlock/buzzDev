<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_Admincp_Category_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$bIsEdit = false;
		if ($iEditId = $this->request()->getInt('id'))
		{
			if ($aCategory = Phpfox::getService('fundraising.category')->getForEdit($iEditId))
			{
				$bIsEdit = true;
				
				$this->template()->setHeader('<script type="text/javascript">$Behavior.ffundAdmincpCategoryAdd = function() { $(function(){$(\'#js_mp_category_item_' . $aCategory['parent_id'] . '\').attr(\'selected\', true);}); }</script>')->assign('aForms', $aCategory);
			}
		}		
		
		if ($aVals = $this->request()->getArray('val'))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('fundraising.category.process')->update($aCategory['category_id'], $aVals))
				{
					$this->url()->send('admincp.fundraising.category.add', array('id' => $aCategory['category_id']), Phpfox::getPhrase('fundraising.category_successfully_updated'));
				}
			}
			else 
			{
				if (Phpfox::getService('fundraising.category.process')->add($aVals))
				{
					$this->url()->send('admincp.fundraising.category.add', null, Phpfox::getPhrase('fundraising.category_successfully_added'));
				}
			}
		}
		
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('fundraising.edit_a_category') : Phpfox::getPhrase('fundraising.create_a_new_category')))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('fundraising.edit_a_category') : Phpfox::getPhrase('fundraising.create_a_new_category')), $this->url()->makeUrl('admincp.fundraising'))
			->assign(array(
					'sOptions' => Phpfox::getService('fundraising.category')->display('option')->get(),
					'bIsEdit' => $bIsEdit
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_admincp_category_add_clean')) ? eval($sPlugin) : false);
	}
}

?>