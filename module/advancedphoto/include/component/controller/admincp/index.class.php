<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: index.class.php 1522 2010-03-11 17:56:49Z Miguel_Espinoza $
 */
class Advancedphoto_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aValidation = array(
			'name' => Phpfox::getPhrase('advancedphoto.provide_a_name_for_your_photo_category')
		);		
		
		$oValid = Phpfox::getLib('validator')->set(array('sFormName' => 'js_form', 'aParams' => $aValidation));	

		if (($aOrder = $this->request()->getArray('order')) && Phpfox::getUserParam('advancedphoto.can_edit_photo_categories', true) && Phpfox::getService('advancedphoto.category.process')->updateOrder($aOrder))
		{
			$this->url()->send('admincp.photo', null, Phpfox::getPhrase('advancedphoto.photo_category_order_successfully_updated'));
		}		
		
		if (!Phpfox::getUserParam('advancedphoto.can_add_public_categories') && !Phpfox::getUserParam('advancedphoto.can_edit_photo_categories'))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('advancedphoto.invalid_section'));
		}
		
		if ($aVals = $this->request()->getArray('val'))
		{			
			if ($oValid->isValid($aVals))
			{
				if (isset($aVals['delete']) && Phpfox::getUserParam('advancedphoto.can_edit_photo_categories', true))
				{
					if (Phpfox::getService('advancedphoto.category.process')->delete($aVals['edit_id']))
					{
						$this->url()->send('admincp.photo', null, Phpfox::getPhrase('advancedphoto.photo_category_successfully_deleted'));
					}
				}
				else 
				{
					if (isset($aVals['edit_id']))
					{
						Phpfox::getUserParam('advancedphoto.can_edit_photo_categories', true);
						
						if (Phpfox::getService('advancedphoto.category.process')->update($aVals))
						{
							$this->url()->send('admincp.photo', null, Phpfox::getPhrase('advancedphoto.photo_category_successfully_updated'));
						}
					}
					else 
					{
						Phpfox::getUserParam('advancedphoto.can_add_public_categories', true);
						
						if (Phpfox::getService('advancedphoto.category.process')->add($aVals))
						{
							$this->url()->send('admincp.photo', null, Phpfox::getPhrase('advancedphoto.photo_category_successfully_added'));
						}
					}
				}
			}
		}				
		
		$this->template()->setTitle(Phpfox::getPhrase('advancedphoto.manage_photo_categories'))
			->setBreadCrumb(Phpfox::getPhrase('advancedphoto.manage_photo_categories'), $this->url()->makeUrl('admincp.photo'))
			->setHeader('cache', array(
					'admin.js' => 'module_photo',
					'jquery/ui.js' => 'static_script',
					'sort.js' => 'module_photo'
				)
			)
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
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>