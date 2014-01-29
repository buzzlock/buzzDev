<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
class Resume_Component_Controller_Admincp_Categories extends Phpfox_Component
{
	/*
	 * Process method which is used to process this component
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
		
		$this->template()->setTitle(Phpfox::getPhrase('resume.manage_categories'))
			->setBreadcrumb(Phpfox::getPhrase('resume.manage_categories'), $this->url()->makeUrl('admincp.resume.categories'))
			->setPhrase(array(
					'resume.are_you_sure_this_will_remove_this_category_from_all_related_resumes_and_cannot_be_undone',
					'resume.are_you_sure'
				)
			)
			->setHeader(array(
					'jquery/ui.js' => 'static_script',
					'admin.js' => 'module_resume',
					'<script type="text/javascript">$Behavior.setUrlResume = function(){$Core.resume.url(\'' . $this->url()->makeUrl('admincp.resume.categories') . '\');}</script>'
				)
			)
			->assign(array(
					'sCategories' => Phpfox::getService('resume.category')->display('admincp')->get(),
					'iCount'	  => Phpfox::getService('resume.category')->getItemCount(array())
				)
			);	
	 }
}