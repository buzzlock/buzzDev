<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class profilecompleteness_component_controller_admincp_editweightsettings extends Phpfox_Component {

    public function process() {
        $user_id = phpfox::getUserId();
        $aPhoto = PHPFOX::getService('profilecompleteness.process')->getProfileCompletenessSettings();
        $ListCustom = Phpfox::getService('custom')->getForListing();

        $aRow = phpfox::getService('profilecompleteness.process')->getProfileCompletenessWeight($user_id);

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
                        if (isset($aRow["cf_" . $Child['field_name']])) {
                            unset($aRow["cf_" . $Child['field_name']]);
                        }
                    }
                }
            }
        }

        $this->template()->setBreadCrumb(Phpfox::getPhrase('profilecompleteness.edit_weight_of_profile_fields'), $this->url()->makeurl('admincp.profilecompleteness.editweightsettings'));

        if ($this->request()->get('SaveChangesProfile')) {
            phpfox::getService("profilecompleteness.process")->CreateTableWeightSetting($ListCustom);
            $val = $this->request()->get('val');
            $is_success = 1;

            if (!is_numeric($this->request()->get('user_image')) || $this->request()->get('user_image') < 0) {
                $is_success = 0;
                Phpfox_Error::set(phpfox::getService("profilecompleteness.process")->ConvertIdToString('user_image') . " " . Phpfox::getPhrase('profilecompleteness.is_invalid'));
            }
            foreach ($val as $key => $value) {
                if (!is_numeric($value) || $value < 0) {
                    Phpfox_Error::set(phpfox::getService("profilecompleteness.process")->ConvertIdToString($key) . " " . Phpfox::getPhrase('profilecompleteness.is_invalid'));
                    $aRow[$key] = "";
                    $is_success = 0;
                }
            }
            if ($is_success == 1) {
               
                Phpfox::getService("profilecompleteness.process")->updateUserImageWeight($this->request()->get('user_image'));
                Phpfox::getService("profilecompleteness.process")->InsertProfileCompletenessWeight($val);
                $this->url()->send('admincp.profilecompleteness.weightsettings', null, Phpfox::getPhrase('profilecompleteness.update_weight_settings_successfully'));
            }
        }

        $settingdefault = phpfox::getService("profilecompleteness.process")->getSettingDefaultPhpfox();

        $this->template()->assign(array(
            'aRow' => $aRow,
            'ListCustom' => $ListCustom,
            'settingdefault' => $settingdefault,
            'aPhoto' => $aPhoto
        ));
    }

}

?>
