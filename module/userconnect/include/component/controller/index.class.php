<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_controller_index extends Phpfox_Component {

    public function process() {

        $this->template()->setBreadCrumb(Phpfox::getPhrase("userconnect.user_connections"));
        $this->template()->setHeader(array(
            'userconnect.css' => 'module_userconnect'
        ));
    }

}

?>
