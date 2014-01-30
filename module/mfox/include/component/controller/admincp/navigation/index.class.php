<?php

class Mfox_Component_Controller_Admincp_Navigation_Index extends Phpfox_Component {

    function process()
    {
        // is it deleting navigations
		if ($this->request()->get('delete') && $aDeleteIds = $this->request()->getArray('id'))
		{
			if (Phpfox::getService('mfox.navigation')->deleteMultiple($aDeleteIds))
			{
				$this->url()->send('admincp.mfox.navigation', null, Phpfox::getPhrase('mfox.navigations_successfully_deleted'));
			}
		}
        
        // is it updating navigations
		if ($this->request()->get('update') && $aIds = $this->request()->getArray('id'))
        {
			$aUpdatedNavigations = array();
			foreach ($aIds as $iId)
			{
				$aUpdatedNavigations[] = array(
					'id' => (int)$iId,
					'label' => $this->request()->get('title_'.$iId)
				);
			}
            
			if (Phpfox::getService('mfox.navigation')->updateMultiple($aUpdatedNavigations))
			{
				$this->url()->send('admincp.mfox.navigation', null, Phpfox::getPhrase('mfox.navigations_successfully_edited'));
			}
			else
			{
				$this->url()->send('admincp.mfox.navigation', null, Phpfox::getPhrase('mfox.navigations_not_edited'));
			}
		}

        $this->template()
                ->setTitle(Phpfox::getPhrase('mfox.navigation'))
                ->setBreadcrumb(Phpfox::getPhrase('mfox.navigation'), $this->url()->makeUrl('admincp.mfox.navigation'))
                ->assign(array(
                    'aNavigations' => Phpfox::getService('mfox.navigation')->getNavigations()
                        )
                )
                ->setHeader('cache', array(
                    'drag.js' => 'static_script',
                    '<script type="text/javascript">Core_drag.init({table: \'#js_drag_drop\', ajax: \'mfox.manageNavigationOrdering\'});</script>'
                        )
        );
    }

}
