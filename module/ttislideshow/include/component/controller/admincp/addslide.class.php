<?php

defined('PHPFOX') or exit('NO DICE!');

class Ttislideshow_Component_Controller_Admincp_Addslide extends Phpfox_Component
{

	public function process()
	{
		$bIsEdit = false;
		if (($iId = $this->request()->getInt('id')))
		{
			if ($aSlide = Phpfox::getService('ttislideshow')->getSlide($iId))
			{
				$bIsEdit = true;
				$this->template()->assign(array(
					'aForms' => $aSlide
					)
				);			
			}
		}

		$aSet = array(
				'ttislideshow.dir_image' => Phpfox::getParam('core.dir_pic') . 'ttislideshow' . PHPFOX_DS,
				'ttislideshow.url_image' => Phpfox::getParam('core.url_pic') . 'ttislideshow/');
		
		Phpfox::getLib('setting')->setParam($aSet);  
		
		if ($aVals = $this->request()->getArray('val'))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('ttislideshow.process')->update($aSlide['slide_id'], $aVals))
				{
					$this->url()->send('admincp.ttislideshow.addslide', array('id' => $aSlide['slide_id']), 'Slide successfuly updated');
				}				
			}
			else 
			{
				if (Phpfox::getService('ttislideshow.process')->add($aVals))
				{
						$this->url()->send('admincp.ttislideshow.addslide', null, 'Slide successfuly added');
				}
			}
		}

		$this->template()->setTitle(($bIsEdit ? 'Edting Slide' . ': ' . $aSlide['title'] : 'Create New Slide'))	
			->setBreadcrumb('Add Slide', $this->url()->makeUrl('admincp.ttislideshow.addslide'))
			->assign(array(					
					'bIsEdit' => $bIsEdit
				)
			);
		
	}
	
}

?>