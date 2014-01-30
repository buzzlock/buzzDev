<?php

class Mfox_Component_Controller_Admincp_Navigation_Edit extends Phpfox_Component {

    function process()
    {
        /**
         * @var bool
         */
        $bIsEdit = false;
        if (($iEditId = $this->request()->getInt('id')) && ($aNavigation = Phpfox::getService('mfox.navigation')->getForEdit($iEditId)))
        {
            $bIsEdit = true;
            $this->template()->assign('aForms', $aNavigation);
        }

        if (($aVals = $this->request()->getArray('val')))
        {
            // Add validate here.
            $aValidation = array(
                'name' => Phpfox::getPhrase('mfox.provide_a_name_for_this_navigation'),
                'label' => Phpfox::getPhrase('mfox.provide_a_label_for_this_navigation'),
                'layout' => Phpfox::getPhrase('mfox.provide_a_layout_for_this_navigation'),
                'icon' => Phpfox::getPhrase('mfox.provide_a_icon_for_this_navigation')
            );

            $oValidator = Phpfox::getLib('validator')->set(array(
                'sFormName' => 'js_navigation_form',
                'aParams' => $aValidation
                    )
            );
            
            if ($oValidator->isValid($aVals))
            {
                if ($bIsEdit)
                {
                    if (Phpfox::getService('mfox.navigation')->updateNavigation($aNavigation['id'], $aVals))
                    {
                        $this->url()->send('admincp.mfox.navigation', null, Phpfox::getPhrase('mfox.navigation_successfully_updated'));
                    }
                }
                else
                {
                    if (Phpfox::getService('mfox.navigation')->addNavigation($aVals))
                    {
                        $this->url()->send('admincp.mfox.navigation', null, Phpfox::getPhrase('mfox.navigation_successfully_added'));
                    }
                }
            }
        }

        $this->template()
                ->setTitle(Phpfox::getPhrase('mfox.edit_navigation'))
                ->setBreadcrumb(Phpfox::getPhrase('mfox.navigation'), $this->url()->makeUrl('admincp.mfox.navigation'))
                ->setBreadcrumb(Phpfox::getPhrase('mfox.edit_navigation'), null, true)
                ->assign(array('bIsEdit' => $bIsEdit));	
    }

}
