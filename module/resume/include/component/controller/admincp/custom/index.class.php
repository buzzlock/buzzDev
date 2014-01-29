<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Resume_Component_Controller_Admincp_Custom_Index extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        
       if ($aOrder = $this->request()->getArray('order'))
		{
			if (Phpfox::getService('resume.category.process')->updateOrder($aOrder))
			{
				$this->url()->send('admincp.resume.categories', null, Phpfox::getPhrase('resume.category_order_successfully_updated'));
			}
		}
		
		if ($iDelete = $this->request()->getInt('delete'))
		{
			$bHasData = Phpfox::getService('resume.category')->hasData($iDelete);
			if (!$bHasData)
			{
				Phpfox::getService('resume.category.process')->delete($iDelete);
				$this->url()->send('admincp.resume.categories', null, Phpfox::getPhrase('resume.category_successfully_deleted'));
			}
			else
			{
				Phpfox_Error::set(Phpfox::getPhrase('resume.cannot_delete_category_that_currently_has_related_data'));
			}
		}
		
		$aCategories = Phpfox::getService('resume.custom')->display();
	
		
		$this->template()->setTitle(Phpfox::getPhrase('resume.manage_categories'))
			->setBreadcrumb(Phpfox::getPhrase('resume.manage_categories'), $this->url()->makeUrl('admincp.resume.categories'))
			->setPhrase(array(
					'resume.are_you_sure_this_will_remove_this_category_from_all_related_resumes_and_cannot_be_undone',
					'resume.are_you_sure'
				)
			)
			->setHeader(array(
					'jquery/ui.js' => 'static_script',
					'admin.js' => 'module_custom',
                    'custom.js' => 'module_resume',
				)
			)
			->assign(array(
					'sCategories' => $aCategories,
					'iCount'	  => Phpfox::getService('resume.category')->getItemCount(array())
				)
			);	
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('custom.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
    }
}

?>