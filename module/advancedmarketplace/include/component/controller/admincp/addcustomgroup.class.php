<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Admincp_AddCustomGroup extends Phpfox_Component
{
	public function process()
	{
		phpfox::isUser(true);

		$bIsEdit = false;
		
		$sCategories = Phpfox::getService('advancedmarketplace.category')->get();
		
		if (($iEditId = $this->request()->getInt('id')))
		{	
			if (($aGroup = Phpfox::getService('advancedmarketplace.custom.group')->getGroupForEdit($iEditId)) && isset($aGroup['group_id']))
			{
				$bIsEdit = true;
				$this->template()->assign(array(
						'aForms' => $aGroup
					)
				);
			}
		}
		
		$aGroupValidation = array(
			'product_id' => Phpfox::getPhrase('custom.select_a_product_this_custom_field_will_belong_to'),
			'module_id' => Phpfox::getPhrase('custom.select_a_module_this_custom_field_will_belong_to'),
			'type_id' => Phpfox::getPhrase('custom.select_where_this_custom_field_should_be_located')			
		);
		
		$oGroupValidator = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'js_group_field', 
				'aParams' => $aGroupValidation,
				'bParent' => true
			)
		);
		
		if (($aVals = $this->request()->getArray('val')))
		{
			$aVals['module_id'] = 'advancedmarketplace';
			$aVals['product_id'] = 'advanced_marketplace';
			if ($bIsEdit === true)
			{
				if (Phpfox::getService('advancedmarketplace.custom.process')->updateGroup($aGroup['group_id'], $aVals))
				{
					$this->url()->send('admincp.advancedmarketplace.addcustomgroup', array('id' => $aGroup['group_id']), Phpfox::getPhrase('custom.group_successfully_updated'));
				}
			}
			else
			{
				if (Phpfox::getService('advancedmarketplace.custom.process')->addCustomGroup($aVals))
				{
					$this->url()->send('admincp.advancedmarketplace.addcustomgroup', null, Phpfox::getPhrase('custom.group_successfully_added'));
				}
			}
		}

		$this->template()->setHeader(array('add.js' => 'module_advancedmarketplace'))
						 ->assign(array(
											'sCategories' => $sCategories,
						 					'bIsEdit' =>$bIsEdit
						 		))
		->setBreadcrumb('Add Custom Group', $this->url()->makeUrl('admincp.advancedmarketplace.addcustomgroup'));
	}
}

?>