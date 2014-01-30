<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Videochannel_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if ($aOrder = $this->request()->getArray('order'))
		{
			if (Phpfox::getService('videochannel.category.process')->updateOrder($aOrder))
			{
				$this->url()->send('admincp.videochannel', null, Phpfox::getPhrase('videochannel.category_order_successfully_updated'));
			}
		}		
		
		if ($iDelete = $this->request()->getInt('delete'))
		{
			if (Phpfox::getService('videochannel.category.process')->delete($iDelete))
			{
				$this->url()->send('admincp.videochannel', null, Phpfox::getPhrase('videochannel.category_successfully_deleted'));
			}
		}
	
		$this->template()->setTitle(Phpfox::getPhrase('videochannel.manage_categories'))
			->setBreadcrumb(Phpfox::getPhrase('videochannel.manage_categories'), $this->url()->makeUrl('admincp.videochannel'))
			->setPhrase(array('videochannel.are_you_sure_this_will_delete_all_videos_that_belong_to_this_category_and_cannot_be_undone'))
			->setHeader(array(
					'jquery/ui.js' => 'static_script',
					'admin.js' => 'module_videochannel',
					'<script type="text/javascript">
                        $Behavior.VideoChannelAdminIndex = function() {
                            $Core.videochannel.url(\'' . $this->url()->makeUrl('admincp.videochannel') . '\');
                        }
                    </script>'
				)
			)
			->assign(array(
					'sCategories' => Phpfox::getService('videochannel.category')->display('admincp')->get()
				)
			);	
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('videochannel.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
