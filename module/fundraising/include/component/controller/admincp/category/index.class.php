<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_Admincp_Category_Index extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{		
		if ($aOrder = $this->request()->getArray('order'))
		{
			if (Phpfox::getService('fundraising.category.process')->updateOrder($aOrder))
			{
				$this->url()->send('admincp.fundraising.category', null, Phpfox::getPhrase('fundraising.category_order_successfully_updated'));
			}
		}		
		
		if ($iDelete = $this->request()->getInt('delete'))
		{
			if (Phpfox::getService('fundraising.category.process')->delete($iDelete))
			{
				$this->url()->send('admincp.fundraising.category', null, Phpfox::getPhrase('fundraising.category_successfully_deleted'));
			}
		}
	
		$this->template()->setTitle(Phpfox::getPhrase('fundraising.manage_categories'))
			->setBreadcrumb(Phpfox::getPhrase('fundraising.manage_categories'), $this->url()->makeUrl('admincp.fundraising'))
			->setPhrase(array('fundraising.are_you_sure_this_will_delete_all_fundraisings_that_belong_to_this_category_and_cannot_be_undone'))
			->setHeader(array(
					'jquery/ui.js' => 'static_script',
					'admin.js' => 'module_fundraising',
					'<script type="text/javascript">$Behavior.ffundAdmincpCategory = function() { $Core.fundraising.url(\'' . $this->url()->makeUrl('admincp.fundraising.category') . '\'); }</script>'
				)
			)
			->assign(array(
					'sCategories' => Phpfox::getService('fundraising.category')->display('admincp')->get()
				)
			);	
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_admincp_category_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
