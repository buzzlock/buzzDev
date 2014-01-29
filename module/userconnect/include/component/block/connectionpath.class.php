<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_block_connectionpath extends Phpfox_Component {

    public function process() {
        $from_1 = $this->getParam('from_1');
        $from_id = $this->getParam('from_id');
        $level = $this->getParam('level');
        $cacheID = "userconnection_get_full_path_lv_from_" . $from_id . '_to_' . phpfox::getUserId() . '_level_' . $level;
        $sId = phpfox::getLib('cache')->set($cacheID);
        $aConnections = phpfox::getLib('cache')->get($sId);
        if ($aConnections === false) {
            $aConnections_path = phpfox::getService('userconnect.algorithm')->getConnectionPath(phpfox::getUserId(), $from_1, 1, $level - 1);
            $aConnections = phpfox::getService('userconnect.algorithm')->getInfoPath($aConnections_path, $from_id);
            phpfox::getLib('cache')->save($sId, $aConnections);
        }

        $this->template()->assign(
                array(
                    'aConnections' => $aConnections,
                    'core_path' => phpfox::getParam('core.path'),
                    'user_id' => phpfox::getUserId(),
                    'level' => $level,
                    'from_id' => $from_id,
                    'from_1' => $from_1,
                )
        );
        return 'block';
    }

}

?>
