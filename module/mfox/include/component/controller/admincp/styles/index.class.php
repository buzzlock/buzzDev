<?php

class Mfox_Component_Controller_Admincp_Styles_Index extends Phpfox_Component {

    function process()
    {
        $oService = Phpfox::getService('mfox.style');
        /**
         * @var bool
         */
        $bIsEdit = false;
        if ($iStyleId = $this->request()->getInt('id'))
        {
            $aDefaultStyles = $oService->getForEdit($iStyleId);
            if (!$aDefaultStyles)
            {
                $this->url()->send('admincp.mfox.styles.manage', null, Phpfox::getPhrase('mfox.style_is_not_valid'));
            }
            $bIsEdit = true;
        }
        else
        {
            $aDefaultStyles = $oService->getDefaultStyles();
        }
        
        // Post data to add or edit.
        if ($aVals = $this->request()->get('val'))
        {
            if ($bIsEdit)
            {
                if ($oService->edit($iStyleId, $aVals))
                {
                    $this->url()->send('admincp.mfox.styles.manage', null, Phpfox::getPhrase('mfox.style_successfully_edited'));
                }
            }
            else
            {
                if ($oService->add(date('l, F j, o', (int) PHPFOX_TIME) . ' at ' . date('h:i:s a', (int) PHPFOX_TIME), $aVals))
                {
                    $this->url()->send('admincp.mfox.styles.manage', null, Phpfox::getPhrase('mfox.style_successfully_added'));
                }
            }
        }
        /**
         * @var array
         */
        $aStyles = array();
        foreach ($aDefaultStyles as $name => $value)
        {
            $aStyles[] = array(
                'name' => Phpfox::getPhrase('mfox.' . $name),
                'value' => $value,
            );
        }
        $this->template()
                ->setTitle(Phpfox::getPhrase('mfox.custom_styles'))
                ->setBreadcrumb(Phpfox::getPhrase('mfox.custom_styles'), $this->url()->makeUrl('admincp.mfox.styles'))
                ->assign(array('bIsEdit' => $bIsEdit, 'iStyleId' => $iStyleId, 'aStyles' => $aStyles));
    }

}
