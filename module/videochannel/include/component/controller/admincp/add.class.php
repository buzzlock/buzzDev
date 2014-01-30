<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Videochannel_Component_Controller_Admincp_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsEdit = false;
		if ($iEditId = $this->request()->getInt('id'))
		{
			if ($aCategory = Phpfox::getService('videochannel.category')->getForEdit($iEditId))
			{
				$bIsEdit = true;
				
				$this->template()->setHeader('<script type="text/javascript">$(function(){$(\'#js_mp_category_item_' . $aCategory['parent_id'] . '\').attr(\'selected\', true);});</script>')->assign('aForms', $aCategory);
			}
		}		
		
		if ($aVals = $this->request()->getArray('val'))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('videochannel.category.process')->update($aCategory['category_id'], $aVals))
				{
					$this->url()->send('admincp.videochannel.add', array('id' => $aCategory['category_id']), Phpfox::getPhrase('videochannel.category_successfully_updated'));
				}
			}
			else 
			{
				if (Phpfox::getService('videochannel.category.process')->add($aVals))
				{
					$this->url()->send('admincp.videochannel.add', null, Phpfox::getPhrase('videochannel.category_successfully_added'));
				}
			}
		}
		
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('videochannel.edit_a_category') : Phpfox::getPhrase('videochannel.create_a_new_category')))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('videochannel.edit_a_category') : Phpfox::getPhrase('videochannel.create_a_new_category')), $this->url()->makeUrl('admincp.videochannel'))
			->assign(array(
					'sOptions' => Phpfox::getService('videochannel.category')->display('option')->get(),
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
		(($sPlugin = Phpfox_Plugin::get('videochannel.component_controller_admincp_add_clean')) ? eval($sPlugin) : false);
	}
}

?>
