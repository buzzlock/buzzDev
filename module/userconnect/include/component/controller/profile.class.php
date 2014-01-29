<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('PHPFOX') or exit('NO DICE!');

class Userconnect_Component_Controller_Profile extends Phpfox_Component {

    public function process() {

        $this->setParam('bIsProfile', true);
        $aUser = $this->getParam('aUser');
        phpfox::isUser(true);

        $user_id = phpfox::getUserId();
        $friend_id = $aUser['user_id'];

        $iLevel = Phpfox::getUserParam('userconnect.connection_levels');

        $list_connection_path = phpfox::getService("userconnect")->MinLine($iLevel, $user_id, $friend_id);
        $end_line = 0;
        if (count($list_connection_path) > 0) {

            if ($list_connection_path[0] != $user_id) {
                $list_connection_path = array_reverse($list_connection_path);
            }

            $end_line = $list_connection_path[count($list_connection_path) - 1];
        }
        $array_temp = array();
        if (count($list_connection_path) > 0)
            foreach ($list_connection_path as $iKey => $list) {
                $array_temp[] = phpfox::getService("userconnect")->getUser($list_connection_path[$iKey]);
            }

        if (count($aUser) >= 0) {
            $titleBread = "How you're connected to " . $aUser['full_name'];
            if (count($array_temp) > 2) {
                $titleBread = "How to connect to " . $aUser['full_name'];
            }
            if (count($array_temp) >= 2) {
                $this->template()->assign(array('friend_id' => $array_temp[1]['user_id']));
            }
        }
        $this->template()->setBreadcrumb($titleBread, null, true);

        $settings = phpfox::getService("userconnect")->getMyConnectSettings($aUser['user_id']);

        if (count($settings) == 0) {
            $showconnectionpath = 1;
        } else {
            $showconnectionpath = $settings['showconnectionpath'];
        }
        $this->template()->assign(array(
            'array_temp' => $array_temp,
            'corepath' => phpfox::getParam("core.path"),
            'end_line' => $end_line,
            'showconnectionpath' => $showconnectionpath,
        ));
    }

}

?>