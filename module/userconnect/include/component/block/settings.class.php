<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class userconnect_component_block_settings extends Phpfox_Component {

    public function process() {
        $user_group_id = $this->getParam('user_group_id');
        $settings = phpfox::getService('userconnect')->getSettings($user_group_id);
        if (count($settings) == 0) {
            $settings['max_level_setting'] = 3;
        }
        $this->template()->assign(array(
            'settings' => $settings,
                )
        );
    }

}

?>
