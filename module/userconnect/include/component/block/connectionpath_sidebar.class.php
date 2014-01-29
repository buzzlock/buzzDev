<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_block_connectionpath_sidebar extends Phpfox_Component {

    public function process() {
        if (!phpfox::isUser()) {
            return false;
        }
        phpfox::getLib('session')->remove('iSecondTime');
        phpfox::getLib('session')->remove('iFistIdPress');
        phpfox::getLib('session')->remove('flag');

        $this->setParam('bIsProfile', true);
        $aUser = $this->getParam('aUser');

        $user_id = phpfox::getUserId();
        $friend_id = $aUser['user_id'];
        if ($user_id == $friend_id) {
            $this->template()->assign('show_connection_path', false);
            return false;
        } else {
            $this->template()->assign('show_connection_path', true);
        }
        $settings = phpfox::getService("userconnect")->getMyConnectSettings($aUser['user_id']);

        if (count($settings) == 0) {
            $showconnectionpath = 1;
        } else {
            $showconnectionpath = $settings['showconnectionpath'];
        }
        if ($showconnectionpath != 1) {
            return false;
        }
        $level = Phpfox::getUserParam('userconnect.connection_levels');
        if ($level <= 0) {
            $level = 1;
        }
        $cacheID = "userconnection_get_full_path_lv_from_" . $friend_id . '_to_' . $user_id . '_level_' . $level;
        $sId = phpfox::getLib('cache')->set($cacheID);
        $aConnections = phpfox::getLib('cache')->get($sId);
        if ($aConnections === false) {

            $aConnections_path = phpfox::getService('userconnect.algorithm')->getConnectionPath(phpfox::getUserId(), $friend_id, 1, $level);
            $aConnections = phpfox::getService('userconnect.algorithm')->getInfoPath($aConnections_path, null);
            phpfox::getLib('cache')->save($sId, $aConnections);
        }

        $this->template()->assign(
                array(
                    'aConnections' => $aConnections,
                    'core_path' => phpfox::getParam('core.path'),
                    'user_id' => phpfox::getUserId(),
                    'level' => $level,
                    'from_id' => $friend_id,
                )
        );

        if (count($aConnections) > 1) {
            $this->template()->assign(array(
                'sHeader' => Phpfox::getPhrase('userconnect.connection_path')
            ));
        }
        return 'block';
    }

}

?>
