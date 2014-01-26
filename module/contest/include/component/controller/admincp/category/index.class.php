<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Controller_Admincp_Category_Index extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{		
		if ($aOrder = $this->request()->getArray('order'))
		{
			if (Phpfox::getService('contest.category.process')->updateOrder($aOrder))
			{
				$this->url()->send('admincp.contest.category', null, Phpfox::getPhrase('contest.category_order_successfully_updated'));
			}
		}		
		
		if ($iDelete = $this->request()->getInt('delete'))
		{
			if (Phpfox::getService('contest.category.process')->delete($iDelete))
			{
				$this->url()->send('admincp.contest.category', null, Phpfox::getPhrase('contest.category_successfully_deleted'));
			}
		}
	
		$this->template()->setTitle(Phpfox::getPhrase('contest.manage_categories'))
			->setBreadcrumb(Phpfox::getPhrase('contest.manage_categories'), $this->url()->makeUrl('admincp.contest'))
			->setPhrase(array('contest.are_you_sure_this_will_delete_all_fundraisings_that_belong_to_this_category_and_cannot_be_undone'))
			->setHeader(array(
					'jquery/ui.js' => 'static_script',
					'admin.js' => 'module_contest',
					'<script type="text/javascript">$Core.contest.url(\'' . $this->url()->makeUrl('admincp.contest.category') . '\');</script>'
				)
			)
			->assign(array(
					'sCategories' => Phpfox::getService('contest.category')->display('admincp')->get()
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
