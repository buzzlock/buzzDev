<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_block_connectionsettings extends Phpfox_Component {

    public function process() {
        $settings = phpfox::getService("userconnect")->getMyConnectSettings(phpfox::getUserId());

        if (count($settings) == 0) {
            $settings = array();
            $settings['showconnectionpath'] = 1;
        }

        $this->template()->assign(array(
            'sHeader' => phpFox::getPhrase("userconnect.my_connection_settings"),
            'settings' => $settings,
        ));
        return 'block';
    }

}

?>
