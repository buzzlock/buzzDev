<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class profilecompleteness_component_controller_admincp_weightsettings extends Phpfox_Component {

    public function process() {
        $user_id = phpfox::getUserId();
        //Get image weight
        $aPhoto = PHPFOX::getService('profilecompleteness.process')->getProfileCompletenessSettings();

        $ListCustom = Phpfox::getService('custom')->getForListing();

        $aRow = phpfox::getService('profilecompleteness.process')->getProfileCompletenessWeight($user_id);

        phpfox::getService("profilecompleteness.process")->ChangeTableWhenToProfile();
        //die(d($ListCustom));

        foreach ($ListCustom as $KeyCustom => $Custom) {
            if (!empty($Custom['child'])) {                
                foreach ($Custom['child'] as $Key => $Child) {
                    if ($Child['is_active'] == 1) {
                        $temp = phpfox::getService("profilecompleteness.process")->is_value_profilecomplteteness_weight("cf_" . $Child['field_name']);
                        if ($temp == 2)
                            $valuetemp = 0;
                        else
                            $valuetemp = $temp;
                        if (isset($aRow["cf_" . $Child['field_name']]) != null)
                            $valuetemp = $aRow["cf_" . $Child['field_name']];
                        $ListCustom[$KeyCustom]['child'][$Key]['weight'] = $valuetemp;
                    }
                    else {
                        //unset($L)
                        //unset($ListCustom[$KeyCustom]['child'][$Key]);
                        if (isset($aRow["cf_" . $Child['field_name']])) {
                            unset($aRow["cf_" . $Child['field_name']]);
                        }
                    }
                }
            }            
        }
        //die(d($ListCustom));
        //die(d($aRow));
        $settingdefault = phpfox::getService("profilecompleteness.process")->getSettingDefaultPhpfox();

        $this->template()->setBreadCrumb(Phpfox::getPhrase('profilecompleteness.admin_menu_weight_settings'), $this->url()->makeurl('admincp.profilecompleteness.weightsettings'));
        $this->template()->assign(array(
            'aRow' => $aRow,
            'ListCustom' => $ListCustom,
            'settingdefault' => $settingdefault,
            'aPhoto' => $aPhoto
        ));
    }

}

?>
