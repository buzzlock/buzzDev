<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Admincp_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsEdit = false;
		if ($iEditId = $this->request()->getInt('id'))
		{
			if ($aCategory = Phpfox::getService('advancedmarketplace.category')->getForEdit($iEditId))
			{
				$bIsEdit = true;
				
				$this->template()->setHeader('<script type="text/javascript">$Behavior.initAdd = function() { $(\'#js_mp_category_item_' . $aCategory['parent_id'] . '\').attr(\'selected\', true); }</script>')->assign('aForms', $aCategory);
			}
		}		
		
		if ($aVals = $this->request()->getArray('val'))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('advancedmarketplace.category.process')->update($aCategory['category_id'], $aVals))
				{
					$this->url()->send('admincp.advancedmarketplace.add', array('id' => $aCategory['category_id']), Phpfox::getPhrase('advancedmarketplace.category_successfully_updated'));
				}
			}
			else 
			{
				if (Phpfox::getService('advancedmarketplace.category.process')->add($aVals))
				{
					$this->url()->send('admincp.advancedmarketplace.add', null, Phpfox::getPhrase('advancedmarketplace.category_successfully_added'));
				}
			}
		}
		
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('advancedmarketplace.edit_a_category') : Phpfox::getPhrase('advancedmarketplace.create_a_new_category')))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('advancedmarketplace.edit_a_category') : Phpfox::getPhrase('advancedmarketplace.create_a_new_category')), $this->url()->makeUrl('admincp.advancedmarketplace'))
			->assign(array(
					'sOptions' => Phpfox::getService('advancedmarketplace.category')->display('option')->get(),
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
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_admincp_add_clean')) ? eval($sPlugin) : false);
	}
}

?>
