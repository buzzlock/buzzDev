<?php

defined('PHPFOX') or exit('NO DICE!');

// This plugin is used to display 3 buttons in profile-timeline.

if (Phpfox::isModule('musicsharing'))
{
    $sModule = $this->request()->get('req3');

    if ($sModule == 'musicsharing')
    {
        if (defined('PHPFOX_IS_PAGES_ADD'))
        {
            return false;
        }
        $aUser = $this->getParam('aUser');

        if ($aUser === null)
        {
            $aUser = $this->getParam('aPage');
        }

        $aUser['is_header'] = true;
        $aUser['is_liked'] = (!isset($aUser['is_liked']) || $aUser['is_liked'] === null || ($aUser['is_liked'] < 1) ) ? false : true;
        if (!isset($aUser['user_id']))
        {
            return false;
        }

        if (!defined('PAGE_TIME_LINE') && !defined('PHPFOX_IS_PAGES_VIEW'))
        {
            
        }
        else if ((isset($aUser['use_timeline']) && $aUser['use_timeline']) || defined('PHPFOX_IS_PAGES_VIEW'))
        {
            $this->request()->set('req3', '');

            if (Phpfox::isModule($sModule) && Phpfox::hasCallback($sModule, 'getPageSubMenu'))
            {
                if (defined('PHPFOX_IS_PAGES_VIEW'))
                {
                    $aPage = $this->getParam('aPage');
                }

                $aMenu = Phpfox::callback($sModule . '.getPageSubmenu', (defined('PHPFOX_IS_PAGES_VIEW') ? $aPage : $aUser));

                foreach ($aMenu as $iKey => $aSubMenu)
                {
                    $aMenu[$iKey]['module'] = $sModule;
                }

                $this->template()->assign(array(
                    'aSubMenus' => $aMenu
                ));
            }
        }
    }
}
?>
