<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('PHPFOX') or exit('NO DICE!');

class userconnect_Component_Controller_Admincp_Settings extends Phpfox_Component {

    public function process() {
        $settings = phpfox::getService('userconnect')->getMyConnectSettings(0);

        $group_members = Phpfox::getLib('phpfox.database')->select('*')
                ->from(Phpfox::getT('user_group'), 'gr')
                ->execute('getRows');
        $default_group = $default_public = 1;
        if (!isset($settings['connection_layout'])) {
            $connection_layout = 1;
        } else {
            $connection_layout = $settings['connection_layout'];
        }

        if ($this->request()->get('save_change_global_setings')) {
            $val = $this->request()->get('val');

            phpfox::getService('userconnect')->saveMyConnectSettings(0, 'connection_layout', $val['connection_layout']);

            if (isset($val['connection_layout']) != null) {
                $value_layout = $val['connection_layout'];
                if ($value_layout == 4) {
                    phpfox::getService("userconnect")->updateMenuProfile(1);
                    phpfox::getService("userconnect")->updateBlockProfile(0);
                } else {
                    phpfox::getService("userconnect")->updateMenuProfile(0);
                    phpfox::getService("userconnect")->updateBlockProfile(1);
                }
            }
            $this->url()->send('current', null, 'Update Global settings successfully');
        }

        $this->template()->assign(
                array(
                    'connection_layout' => $connection_layout,
                    'group_members' => $group_members,
                    'default_view_group' => $default_group,
                )
        );
    }

}

?>
