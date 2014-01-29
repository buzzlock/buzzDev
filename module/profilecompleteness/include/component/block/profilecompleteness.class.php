<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class profilecompleteness_component_block_profilecompleteness extends phpfox_component {

    public function process() {
        $user_id = phpfox::getUserId();
        $profile_id = $this->getParam("user_id");
        if (!$profile_id) { // not in profile page
            $profile_id = $user_id;
        }
        $is_temp = 0;
        if ($user_id == 0 || $user_id != $profile_id) {
            $is_temp = 1;
        } else {
           
            
            //die(d($ListCustom));
            phpfox::getService("profilecompleteness.process")->ChangeTableWhenToProfile();
            //die('erert');
            $aRow_Settings = phpfox::getService("profilecompleteness.process")->getProfileCompletenessSettings();

            list($iGroup_id, $iPercent, $Key, $PercentValue, $isPhoTo, $PercentTotal) = phpfox::getService('profilecompleteness.process')->getPercentProfileCompleteness($user_id);
            if (!is_numeric($iGroup_id)) {
                $iGroup_id = 'basic';
            }
            
            if ($PercentTotal == 100) {

                $is_turnoff = $aRow_Settings['check_complete'];
                if ($is_turnoff == 1) {
                    $is_temp = 1;
                }
            }
            if ($PercentTotal != 100 || $is_temp == 0) {
                $this->template()->assign(array(
                    'sHeader' => Phpfox::getPhrase('profilecompleteness.profile_completeness'),
                    'iPercent' => $iPercent,
                    'Key' => $Key,
                    'PercentValue' => $PercentValue,
                    'isPhoTo' => $isPhoTo,
                    'PercentTotal' => $PercentTotal,
                    'colorbackground' => $aRow_Settings['gaugecolor'],
                    'iGroup_id' => $iGroup_id
                ));
            }
        }

        $this->template()->assign(array(
            'is_temp' => $is_temp,
        ));
        return 'block';
    }

}

?>
