<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class OpenSocialConnect_Component_Controller_Sync extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        if (Phpfox::getParam('core.url_rewrite') >= 2)
        {
            $params = $_REQUEST;
        }
        else
        {
            $cur_url = $_SERVER['REQUEST_URI'];
            $cur_url = str_replace('/index.php?do=', '', $cur_url);
            parse_str($cur_url, $params);
        }

        if (!isset($params['service']))
        {
            $params['service'] = Phpfox::getLib('request')->get('service');
        }

        $oService = Phpfox::getService('opensocialconnect');

        $bApiProvider = false;

        $sService = isset($params['service']) ? strtolower($params['service']) : null;

        $oBridge = Phpfox::getService('socialbridge');

        if ($oBridge->hasProvider($sService))
        {
            $oProvider = $oBridge->getProvider($sService);
            $data = $oProvider->getProfile();
            $params = array_merge($params, $data);
        }

        $sIdentity = isset($params['identity']) ? $params['identity'] : null;

        $aUser = $oService->getUserByIdentityAndService($sIdentity, $sService);

        if ($aUser)
        {

            list($bLoginOK, $aUser) = $oService->loginByEmail($aUser['email']);

            // user does not exists anymore, please visit to signup.
            if (isset($_SESSION['urlRedirect']))
            {
                $sUrlRedirect = $_SESSION['urlRedirect'];
            }
            else
            {
                $sUrlRedirect = Phpfox::getParam('core.path');
                Phpfox::getService('opensocialconnect.providers')->updateStatistics($sService, 'login');
            }

            $this->template()->assign(array(
                'step' => 'checksignon',
                'sUrlRedirect' => $sUrlRedirect,
            ));
        }
        else
        {
            if (!isset($params['user_name']) && isset($params['full_name']))
            {
                $params['user_name'] = strtolower($params['full_name']);
            }
            elseif (!isset($params['full_name']) && isset($params['user_name']))
            {
                $params['full_name'] = strtolower($params['user_name']);
            }

            // saved data to session
            $oService->setSignupSessionData($sService, array(
                'service' => $sService,
                'identity' => $sIdentity,
                'user' => $params
            ));

            $sUrlRedirect = $this->url()->makeUrl('opensocialconnect.quicksignup', array('service' => $params['service']));

            $this->template()->assign(array(
                'step' => 'checksignup',
                'sUrlRedirect' => $sUrlRedirect,
            ));
        }
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('opensocialconnect.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
    }

}
