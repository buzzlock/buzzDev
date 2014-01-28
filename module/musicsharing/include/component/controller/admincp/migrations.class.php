<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Admincp_Migrations extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $oSession = Phpfox::getLib('session');
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            $oSession->set('pages_msf', $aParentModule);
        }
        else
        {
            $oSession->remove('pages_msf');
        }
        $this->template()->setBreadCrumb(Phpfox::getPhrase('musicsharing.admin_menu_migrations'), null, true);
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_admincp_singer_clean')) ? eval($sPlugin) : false);
    }

}

?>