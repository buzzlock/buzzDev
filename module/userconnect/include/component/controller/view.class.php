<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_controller_view extends Phpfox_Component {

    public function process() {

        phpfox::getLib('session')->remove('uscnf_keysearch');
        Phpfox::isUser(true);
        $this->template()->setHeader(array(
                    'userconnect.css' => 'module_userconnect',
                    'userconnection.js' => 'module_userconnect',
                ))
                ->setEditor(array(
                    'load' => 'simple'
                ))
        ;
    }

}

?>