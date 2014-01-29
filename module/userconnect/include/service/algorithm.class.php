<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'userconnect' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs.class.php');

class userconnect_service_algorithm extends YouNet_service {

    private $_data;
    private $_inorgeList;
    private $_limitQueryFriend;
    private $_friendList;

    public function __construct() {
        $this->_data = array();
        $this->_inorgeList = array();
        $this->_friendList = array();
        $this->_limitQueryFriend = 100;
        $this->_sTable = phpfox::getT('friend');
        $this->_oCache = $this->cache();
    }

    public function getFriendsByLevel($user_id = null, $level = 1, $start = 1, $limit = 10, $keyword = "", $find = false, $to_id = null) {
        $hidenUsersID = "userconnection_get_hidden_path_user";
        $sId = $this->_oCache->set($hidenUsersID);
        $aHiddenUsers = $this->_oCache->get($sId);
        if ($aHiddenUsers === false) {
            $aHiddenUsers = $this->database()
                    ->select('user_id')
                    ->from(phpfox::getT('userconnection_settings'), 'us')
                    ->where('us.name = "showconnectionpath" AND us.value = 0')
                    ->execute('getSlaveRows');
            $aTmp = array();
            foreach ($aHiddenUsers as $iIndex => $aUser) {
                $aTmp[] = $aUser['user_id'];
            }
            $aHiddenUsers = $aTmp;
            $this->_oCache->save($sId, $aHiddenUsers);
        }
        if ($user_id == null) {
            return false;
        }
        if ($find == true) {
            $limit = 0;
            $start = 0;
        }
        $this->_inorgeList = array($user_id);
        $this->_friendList = array();
        $result = array();

        if ($level == 1) {
            $this->_limitQueryFriend = $limit;
        }

        $i = 1;
        $querysearch = "AND 1 = 1";
        if ($keyword != false) {
            //$keyword = $this->database()->escape($keyword);
            if ($keyword === true) {
                $keyword = "";
            }
            $querysearch = " AND ( ur.user_name LIKE '%" . $keyword . "%' or ur.full_name LIKE '%" . $keyword . "%' )";
            $keyword = true;
        } else {
            $keyword = false;
            $querysearch = "";
        }
        $query = "";
        if (count($aHiddenUsers) > 0 && is_array($aHiddenUsers)) {
            $query = " AND (fr.friend_user_id NOT IN(" . implode(',', $aHiddenUsers) . "))";
            $this->_inorgeList = array_merge($this->_inorgeList, $aHiddenUsers);
        }

        for ($i = 1; $i <= $level; $i++) {
            $cacheID = "userconnection_get_friends_lv_" . $i . '_by_user_' . $user_id;
            $cacheIDCount = "userconnection_count_friends_lv_" . $i . '_by_user_' . $user_id;
            if ($i == $level) {
                $this->_limitQueryFriend = $limit;
            }
            if ($i == 1) {
                $cacheIDCount = $this->_oCache->set($cacheIDCount);
                $iCnt = $this->_oCache->get($cacheIDCount);
                if ($iCnt === false || ($level == $i && $keyword != false)) {
                    $iCnt = $this->database()->select(" count( DISTINCT fr.friend_user_id ) ")
                            ->from(Phpfox::getT('friend'), 'fr')
                            ->leftjoin(Phpfox::getT('user'), 'ur', 'ur.user_id = fr.friend_user_id')
                            ->where('fr.is_page = 0 AND fr.user_id = ' . $user_id . $query . $querysearch)
                            ->execute('getSlaveField');
                    if ($iCnt <= 0) {
                        $iCnt = -1;
                    }
                    $cacheIDCount = $this->_oCache->set($cacheIDCount);
                    $this->_oCache->save($cacheIDCount, $iCnt);
                }
                if ($iCnt <= 0) {
                    $iCnt = 0;
                }
                if ($iCnt <= 0) {
                    return false;
                }
                if ($level == 1) {
                    $aRows = $this->database()
                            ->select('DISTINCT fr.user_id as owner_id,fr.friend_user_id as user_id,ur.user_image,ur.user_name,ur.full_name,ur.user_group_id,ur.gender')
                            ->from($this->_sTable, 'fr')
                            ->leftjoin(Phpfox::getT('user'), 'ur', 'ur.user_id = fr.friend_user_id')
                            ->where('fr.is_page = 0 AND fr.user_id = ' . $user_id . ' AND ur.user_name !=""' . $query . $querysearch)
                            ->limit($start, $this->_limitQueryFriend, $iCnt)
                            ->execute('getRows');
                } else {
                    $sId = $this->_oCache->set($cacheID);
                    $aRows = $this->_oCache->get($sId);
                    if ($aRows === false) {
                        $aRows = $this->database()
                                ->select('DISTINCT fr.friend_user_id as user_id,fr.user_id as owner_id')
                                ->from($this->_sTable, 'fr')
                                ->where('fr.is_page = 0 AND fr.user_id = ' . $user_id . $query)
                                ->execute('getRows');
                        $this->_oCache->save($sId, $aRows);
                    }
                }
                if (count($aRows) <= 0) {
                    return false;
                }
            } else {

                if (count($this->_friendList) > 0 && count($aRows) > 0) {
                    if ($i == $level) {
                        $cacheIDCount = $this->_oCache->set($cacheIDCount);
                        $iCnt = $this->_oCache->get($cacheIDCount);
                        if ($iCnt === false || ($level == $i && $keyword != false)) {
                            $iCnt = $this->database()->select(" count( DISTINCT fr.friend_user_id ) ")
                                    ->from(Phpfox::getT('friend'), 'fr')
                                    ->leftjoin(Phpfox::getT('user'), 'ur', 'ur.user_id = fr.friend_user_id')
                                    ->where('fr.is_page = 0 AND fr.user_id IN (' . implode(',', $this->_friendList) . ') AND fr.friend_user_id NOT IN(' . implode(',', $this->_inorgeList) . ')' . $query . $querysearch)
                                    ->execute('getSlaveField');
                            if ($iCnt <= 0) {
                                $iCnt = -1;
                            }
                            $cacheIDCount = $this->_oCache->set($iCnt);
                            $this->_oCache->save($cacheIDCount, $iCnt);
                        }

                        if ($iCnt <= 0) {
                            $iCnt = 0;
                        }

                        $aRows = $this->database()
                                ->select('DISTINCT fr.friend_user_id as user_id,fr.user_id as owner_id,ur.user_image,ur.user_name,ur.full_name,ur.user_group_id,ur.gender')
                                ->from($this->_sTable, 'fr')
                                ->leftjoin(Phpfox::getT('user'), 'ur', 'ur.user_id = fr.friend_user_id')
                                ->where('fr.is_page = 0 AND fr.user_id IN (' . implode(',', $this->_friendList) . ') AND fr.friend_user_id NOT IN(' . implode(',', $this->_inorgeList) . ') AND ur.user_name !=""' . $query . $querysearch)
                                ->group('fr.friend_user_id')
                                ->limit($start, $this->_limitQueryFriend, $iCnt)
                                ->execute('getRows');
                    } else {
                        $sId = $this->_oCache->set($cacheID);
                        $aRows = $this->_oCache->get($sId);
                        if ($aRows === false) {
                            $aRows = $this->database()
                                    ->select('DISTINCT fr.friend_user_id as user_id,fr.user_id as owner_id')
                                    ->from($this->_sTable, 'fr')
                                    ->where('fr.is_page = 0 AND fr.user_id IN (' . implode(',', $this->_friendList) . ') AND fr.friend_user_id NOT IN(' . implode(',', $this->_inorgeList) . ')')
                                    ->group('fr.friend_user_id')
                                    ->execute('getRows');
                            $this->_oCache->save($sId, $aRows);
                        }
                    }
                } else {
                    return false;
                }
            }
            $rebuildRows = array();
            if ($iCnt > 0 && is_array($aRows) > 0) {

                foreach ($aRows as $k => $r) {
                    $friend_user_id = isset($r['friend_user_id']) ? $r['friend_user_id'] : $r['user_id'];
                    $this->_friendList[] = $friend_user_id;
                    $rebuildRows[$r['user_id']] = $r;
                }
            } else {
                $iCnt = -1;
                $iCnt = $this->_oCache->set($cacheIDCount);
                $this->_oCache->save($iCnt, $iCnt);
                $iCnt = 0;
            }

            $result[$user_id][$i]['friends'] = $rebuildRows;
            $result[$user_id][$i]['number'] = $iCnt;
            if ($find == true) {

                if (array_key_exists($to_id, $rebuildRows)) {
                    $item = $result[$user_id][$i]['friends'][$to_id];
                    $result[$user_id][$i]['friends'] = array();
                    $result[$user_id][$i]['friends'][$to_id] = $item;
                    return $result;
                }
            }

            $this->_inorgeList = array_merge($this->_inorgeList, $this->_friendList);
        }
        return $result;
    }

    public function getConnectionPath($from_id = null, $to_id = null, $rand = 1, $level = 5, $start = 1) {
        if ($from_id == $to_id) {
            return false;
        }
        $user_id = $from_id;
        $aConnections = array();
        $cacheID = "userconnection_get_path_lv_from_" . $from_id . '_to_' . $to_id;
        $sId = $this->_oCache->set($cacheID);
        $aConnections = $this->_oCache->get($sId);
        if ($aConnections === false) {
            $aConnections = $this->getFriendsByLevel($from_id, $level, 0, 0, "", true, $to_id);
            $this->_oCache->save($sId, $aConnections);
        }
        if ($aConnections == false || count($aConnections[$user_id]) <= 0) {
            return false;
        } else {
            $level = count($aConnections[$user_id]);
        }
        $path = array(
        );
        $end = false;
        $pathview = array();
        while ($level >= 0) {

            if (isset($aConnections[$user_id][$level]['friends'][$to_id])) {

                $pathview[] = $to_id;
                $path[$to_id] = $aConnections[$user_id][$level]['friends'][$to_id]['owner_id'];
                $to_id = $aConnections[$user_id][$level]['friends'][$to_id]['owner_id'];
            } else {
                
            }
            $level--;
        }
        $pathview[] = $from_id;
        return array_reverse($pathview);
    }

    public function getInfoPath($aConnections, $from_id = null, $rand = 1) {
        if ($aConnections === false) {
            return array();
        }

        if ($from_id != null) {
            $aConnections[] = $from_id;
        }
        $aRows = $this->database()
                ->select('ur.user_id,ur.user_image,ur.user_name,ur.full_name,ur.user_group_id,ur.gender')
                ->from(Phpfox::getT('user'), 'ur')
                ->where('ur.user_id IN (' . implode(',', $aConnections) . ')')
                ->execute('getSlaveRows');

        if (count($aRows) <= 0) {
            return false;
        }
        $result = array();
        foreach ($aConnections as $k1 => $v1) {
            foreach ($aRows as $k => $v) {
                if ($v1 == $v['user_id']) {
                    $result[$k1] = $v;
                    break;
                }
            }
        }

        return $result;
    }

    public function getMutualFriends($iUserId, $iUserId2, $iLimit = 30, $bNoCount = false) {
        $sExtra1 = $sExtra2 = '';

        if ($sPlugin = Phpfox_Plugin::get('userconnect.service_algorithm_getmutualfriends')) {
            eval($sPlugin);
        }

        $aRows = $this->database()->select(($bNoCount ? '' : 'SQL_CALC_FOUND_ROWS ') . Phpfox::getUserField())
                ->from(Phpfox::getT('friend'), 'f')
                ->innerJoin('(SELECT friend_user_id FROM ' . Phpfox::getT('friend') . ' WHERE is_page = 0 AND user_id = ' . $iUserId . $sExtra1 . ')', 'sf', 'sf.friend_user_id = f.friend_user_id')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = f.friend_user_id')
                ->where('f.is_page = 0 AND f.user_id = ' . $iUserId2 . $sExtra2)
                ->order('RAND()')
                ->group('f.friend_user_id')
                ->limit($iLimit)
                ->execute('getSlaveRows');

        if (!$bNoCount) {
            $iCnt = $this->database()->getField('SELECT FOUND_ROWS()');
        }

        return array($iCnt, $aRows);
    }

    public function getExtraPath($from_id, $to_id, $rand = 10, $level, $old_path, $start_i) {
        if (!isset($_SESSION['olduserpath'])) {
            $_SESSION['olduserpath'] = array();
        }
        $new_path = array();
        $start_i++;
        $length = count($old_path);
        $pre_end = $old_path[$length - 1];
        $before_user = $old_path[$start_i - 1];
        $pre_user = $old_path[$start_i + 1];
        list($iCnt, $mutualFriends) = $this->getMutualFriends($before_user, $pre_user, $rand);
        if (count($mutualFriends) == count($_SESSION['olduserpath'])) {
            $_SESSION['olduserpath'] = array();
        }
        if ($iCnt > 1) {
            foreach ($mutualFriends as $m => $f) {

                if ($f['user_id'] != $old_path[$start_i] && !in_array($f['user_id'], $_SESSION['olduserpath'])) {
                    if ($level > 1) {
                        $aConnection = $this->getConnectionPath($f['user_id'], $pre_end, 1, $level);
                    } else {
                        $_SESSION['olduserpath'][] = $f['user_id'];
                        $new_path[] = array($f['user_id']);
                        return $new_path;
                    }

                    if (count($aConnection) == $level) {
                        $_SESSION['olduserpath'][] = $f['user_id'];
                        $new_path[] = $aConnection;
                        return $new_path;
                    }
                }
            }
        }
        $start_i++;
        $level--;
        return $new_path;
    }

    public function clearCache($sType = "friend", $iItemId = 0, $user_id = 0, $level = 5) {
        if ($sType == "hiddenuser") {
            $cacheID = "userconnection_get_hidden_path_user";
            $this->_oCache->remove();
        }
        if ($sType == "friend" || $sType = "hiddenuser") {

            for ($i = 0; $i < $level; $i++) {
                $cacheID = "userconnection_get_friends_lv_" . $i . '_by_user_' . $user_id;
                $cacheIDCount = "userconnection_count_friends_lv_" . $i . '_by_user_' . $user_id;
                $cacheIDPath = "userconnection_get_full_path_lv_from_" . $i . '_to_' . $user_id . '_level_' . $level;

                $this->_oCache->remove($cacheID);
                $this->_oCache->remove($cacheIDCount);
                $this->_oCache->remove($cacheIDPath);
            }
            $this->cache()->remove();
            return true;
        }
        return false;
    }

}

?>
