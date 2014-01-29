<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class userconnect_service_userconnect extends phpfox_service {

    private $_array_user_connect;

    public function getArrayUserConnect() {

        $oCache = Phpfox::getLib("cache");

        $sCacheId = $oCache->set('getArrayUserConnect');

        if (!($aData = $oCache->get($sCacheId))) {
            $atemp = array();
            $aRows = $this->database()->select('*')
                    ->from(phpfox::getT('friend'), 'friend')
                    ->execute('getSlaveRows');

            foreach ($aRows as $aRow) {
                $array = array();
                $array['friend'] = $aRow['friend_user_id'];
                $atemp[$aRow['user_id']]['friend'][] = $array;
                $atemp[$aRow['user_id']]['status'] = 0;
                $atemp[$aRow['user_id']]['user_id'] = $aRow['user_id'];
            }
            $aData = $atemp;
            $oCache->save($sCacheId, $aData);
        }

        return $aData;
    }

    public function getListUserConnect() {
        $atemp = array();
        $aRows = $this->database()->select('*')
                ->from(phpfox::getT('friend'), 'friend')
                ->join(phpfox::getT('user'), 'user', 'user.user_id=friend.friend_user_id')
                ->execute('getSlaveRows');

        foreach ($aRows as $aRow) {
            $array = array();
            $array['friend'] = $aRow['friend_user_id'];
            $atemp[$aRow['user_id']]['friend'][] = $array;
            $atemp[$aRow['user_id']]['status'] = 0;
            $atemp[$aRow['user_id']]['user_id'] = $aRow['user_id'];
        }

        return $atemp;
    }

    public function getUserConenct($user_id, $arrayUser) {
        if (isset($arrayUser[$user_id]) == null)
            return null;
        return count($arrayUser[$user_id]) > 0 ? $arrayUser[$user_id] : null;
    }

    public function getLevelFriend($level, $user_id) {
        $this->_array_user_connect = array();
        $aUserConnect = $this->getArrayUserConnect();
        $array_temp = array();
        $array_temp[] = $user_id;
        $this->TryFriend($aUserConnect, $array_temp, $level, $user_id, $user_id);

        return $this->_array_user_connect;
    }

    public function TryFriend($aUserConnect, $array_temp, $level, $currentuser, $user_id) {
        if ($level == 0) {
            $this->saveUserConnect($array_temp);
            return;
        }
        $ayourfriend = phpfox::getService("userconnect")->getUserConenct($currentuser, $aUserConnect);

        foreach ($ayourfriend as $yourfriend) {
            if (in_array($yourfriend['friend_user_id'], $array_temp) == false) {
                $array_temp[] = $yourfriend['friend_user_id'];
                $this->TryFriend($aUserConnect, $array_temp, $level - 1, $yourfriend['friend_user_id'], $user_id);
                $array_temp = array();
                $array_temp[] = $user_id;
            } else {
                $this->saveUserConnect($array_temp);
            }
        }
        if (count($ayourfriend) == 0) {
            $this->saveUserConnect($array_temp);
        }
    }

    public function saveUserConnect($array_temp) {
        if ($this->isexistUserConnect($this->_array_user_connect, $array_temp))
            $this->_array_user_connect[] = $array_temp;
    }

    public function isexistUserConnect($array_temp) {
        if (in_array($array_temp, $this->_array_user_connect) == false)
            return true;
        return false;
    }

    public function SearchAllForLevel($iLevel, $user_id, $iLimit, $iPage, $iMore = false) {

        if ($iLevel > 5) {
            $iLevel = 5;
        }
        $array_temp = array();
        $cacheFileName = 'user_' . Phpfox::getUserId() . '_connection_cached_lv' . $iLevel;
        $iCacheID = phpfox::getLib('cache')->set($cacheFileName);
        if ($iMore && $this->_aCachedUsers = phpfox::getLib('cache')->get($iCacheID)) {
            $aRows = $this->_aCachedUsers;
        } else {
            $friend_ids = $this->getAllFriendIdsAtLevel($user_id, $iLevel);
            if (count($friend_ids)) {
                $friends_in = ' (' . implode(',', $friend_ids) . ') ';
                //$aRows = $this->database()->select('distinct user.*,uf.user_id as is_featured, uf.ordering as featured_order')
                $aRows = $this->database()->select('distinct user.user_id,user.user_name,user.full_name,user.user_image,uf.user_id as is_featured, uf.ordering as featured_order')
                        ->from(phpfox::getT('user'), 'user')
                        ->leftjoin(Phpfox::getT('user_featured'), 'uf', 'uf.user_id = user.user_id')
                        ->where('user.user_id in ' . $friends_in . ' ')
                        ->order('user.user_id')
                        ->execute('getSlaveRows');
                if (count($aRows)) {
                    //cache users found
                    phpfox::getLib('cache')->save($iCacheID, $aRows);
                }
            } else {
                $aRows = array();
            }
        }
        $aUsers = array();
        $iCnt = count($aRows);
        if ($iCnt) {
            $beginCnt = (int) (($iPage - 1) * $iLimit);
            $endCnt = (int) ($iPage * $iLimit);
            $endCnt = ($endCnt < $iCnt) ? $endCnt : $iCnt;
            for ($i = $beginCnt; $i < $endCnt; $i++) {
                $aUsers[] = $aRows[$i];
            }
        }
        return array($aUsers, $iCnt);
    }

    public function getAllFriendIdsAtLevel($user_id, $iLevel) {
        $aRows = $this->database()->select('user_id, friend_user_id as friend_id')
                ->from(Phpfox::getT('friend'))
                ->where(' is_page = 0')
                ->order('user_id')
                ->execute('getRows');
        //create an array with key = user_id and value = list of friends having format (id1, id2, id3)

        $count = 0;
        $friends_list = array();
        $temp_list = array();
        foreach ($aRows as $row) {
            if ($count == 0) {
                $count = $row['user_id'];
                $temp_list[] = $row['friend_id'];
            } else if ($count == $row['user_id']) {
                $temp_list[] = $row['friend_id'];
            } else { //$count < $row['user_id'] because the $row['user_id'] is ASC
                $friends_list[$count] = $temp_list;

                $count = $row['user_id'];
                $temp_list = array();
                $temp_list[] = $row['friend_id']; //new temp_list
            }
        }
        //for the last list

        $friends_list[$count] = $temp_list;

        $user_connected_members = array();
        $friend_connected_memmbers = array();
        $searched_memmbers = array();

        if (isset($friends_list[$user_id])) {
            //level 1 friends
            $friend_connected_memmbers = $friends_list[$user_id];
            $searched_memmbers = array_merge(array($user_id), $friend_connected_memmbers);

            for ($i = 1; $i < $iLevel; $i++) {
                $current_users = $friend_connected_memmbers;
                $friend_connected_memmbers = array();
                foreach ($current_users as $user) {
                    if (isset($friends_list[$user])) {
                        foreach ($friends_list[$user] as $insert_users) {
                            //if the members were not added or search before
                            if (!in_array($insert_users, $searched_memmbers)) {
                                $friend_connected_memmbers[] = $insert_users;
                                $searched_memmbers[] = $insert_users;  //not search in next time
                            }
                        }
                    }
                }
                //if no friends fould at that level, break the search;
                if (!count($friend_connected_memmbers))
                    break;
            }
            return $friend_connected_memmbers;
        }
    }

    public function getAllPeopleYouKnow($user_id, $iLimit) {
        $iLevel = 4; // fix value of searching people
        $aRows = $this->database()->select('user_id, friend_user_id as friend_id')
                ->from(Phpfox::getT('friend'))
                ->where(' is_page = 0 ')
                ->order('user_id, RAND()')
                ->execute('getRows');
        //create an array with key = user_id and value = list of friends having format (id1, id2, id3)
        $count = 0;
        $friends_list = array();
        $temp_list = array();
        foreach ($aRows as $row) {
            if ($count == 0) {
                $count = $row['user_id'];
                $temp_list[] = $row['friend_id'];
            } else if ($count == $row['user_id']) {
                $temp_list[] = $row['friend_id'];
            } else { //$count < $row['user_id'] because the $row['user_id'] is ASC
                $friends_list[$count] = $temp_list;

                $count = $row['user_id'];
                $temp_list = array();
                $temp_list[] = $row['friend_id']; //new temp_list
            }
        }
        //for the last list

        $friends_list[$count] = $temp_list;

        $user_connected_members = array();
        $friend_connected_memmbers = array();
        $searched_memmbers = array();

        if (isset($friends_list[$user_id])) {
            //level 1 friends
            $friend_connected_memmbers = $friends_list[$user_id];
            $searched_memmbers = array_merge(array($user_id), $friend_connected_memmbers);
            $you_and_friends = $searched_memmbers;
            for ($i = 1; $i < $iLevel; $i++) {
                $current_users = $friend_connected_memmbers;
                $friend_connected_memmbers = array();
                foreach ($current_users as $user) {
                    if (isset($friends_list[$user])) {
                        foreach ($friends_list[$user] as $insert_users) {
                            //if the members were not added or search before
                            if (!in_array($insert_users, $searched_memmbers)) {
                                $friend_connected_memmbers[] = $insert_users;
                                $searched_memmbers[] = $insert_users;  //not search in next time
                            }
                        }
                    }
                }
                //if no friends fould at that level, break the search;
                if (!count($friend_connected_memmbers))
                    break;
            }
            $people_you_now = array_diff($searched_memmbers, $you_and_friends);
            if (count($people_you_now) > $iLimit) {
                $rand_keys = array_rand($people_you_now, $iLimit);
                $temp_array = array();
                if (count($rand_keys) > 1) {
                    foreach ($rand_keys as $key) {
                        $temp_array[] = $people_you_now[$key];
                    }
                } else {
                    $temp_array[] = $people_you_now[$rand_keys];
                }

                $people_you_now = $temp_array;
            } else {
                $temp_array = array();
                foreach ($people_you_now as $person) {
                    $temp_array[] = $person;
                }
                $people_you_now = $temp_array;
            }
            return $people_you_now;
        }
    }

    public function getUser($user_id) {
        $aRow = $this->database()->select('*')
                ->from(phpfox::getT('user'), 'user')
                ->where("user.user_id=" . $user_id)
                ->execute('getSlaveRow');
        return $aRow;
    }

    public function MinLine($iLevel, $user_id, $friend_id) {
        if ($iLevel < 2) {
            $iLevel = 2;
        }
        if ($iLevel > 5) {
            $iLevel = 5;
        }

        $connection_path[0] = $user_id;
        $found = false;

        $hideUserIds = array();
        $aRows = $this->database()->select('user_id')
                ->from(Phpfox::getT('userconnection_settings'))
                ->where('name = \'showconnectionpath\' AND value = 0')
                ->execute('getRows');
        foreach ($aRows as $row) {
            $hideUserIds[] = $row['user_id'];
        }

        // if current user was hiden from connection path 
        if (in_array($friend_id, $hideUserIds)) {
            return array('status' => 3);
        }
        //print_r($hideUserIds);
        $levels_track[0] = array($user_id);
        //current friends
        $lookupIds = '(' . $user_id . ') ';
        $maxLevel = $iLevel;
        $searched_members = array();
        for ($i = 1; $i <= $maxLevel; $i++) {
            $friends = $this->database()->select('user_id,friend_user_id')
                    ->from(Phpfox::getT('friend'))
                    ->where('user_id in ' . $lookupIds . ' AND is_page = 0')
                    ->order('user_id')
                    ->execute('getRows');

            if (count($friends)) {
                $current_level_friends = array();
                $levels_track[$i] = $friends;
                foreach ($friends as $friend) {
                    $current_level_friends[] = $friend['friend_user_id'];
                    if ($friend['friend_user_id'] == $friend_id) {
                        $found = true;
                        $connection_path[$i] = $friend['friend_user_id'];
                        break;
                    }
                }

                $lookupIds = ' (' . implode(',', $current_level_friends) . ') ';
            } else {
                return array('status' => 0);
            }
            if ($found)
                break;
        }
        if ($found) {     //found the connection path but only the 0 and the last item

            if (count($connection_path)) {
                $iLevel = 1;
                foreach ($connection_path as $key => $path) {
                    $iLevel = $key;
                }
            }
            $maxLevel = $iLevel;
            $path = array();
            $path[] = $connection_path[$iLevel];

            while ($iLevel > 1) {    //find missing connection paths
                $friend_id = $path[($maxLevel - $iLevel)];
                if (isset($levels_track[$iLevel])) {
                    $users = $levels_track[$iLevel];
                    $isFound = false;
                    foreach ($users as $user) {
                        if (!in_array($user['user_id'], $hideUserIds)) {
                            if ($user['friend_user_id'] == $friend_id) {
                                $path[] = $user['user_id'];
                                $isFound = true;
                                break;
                            }
                        }
                    }
                    if (!$isFound) {
                        return array('status' => 2);
                    }
                }

                $iLevel--;
            }

            $path[] = $connection_path[0];
        } else {
            return array('status' => 0);
        }
        return array('status' => 1, 'path' => $path);
    }

    public function saveMyConnectSettings($user_id, $name, $value) {

        $iCount = (int) $this->database()->select('count(*)')
                        ->from(phpfox::getT('userconnection_settings'), 'setting')
                        ->where('user_id=' . $user_id . ' and user_group_id=0 and name="' . $name . '"')
                        ->execute('getSlaveField');

        if ($iCount == 0) {
            $aInserts = array();
            $aInserts['user_id'] = $user_id;
            $aInserts['name'] = $name;
            $aInserts['value'] = $value;
            $aInserts['user_group_id'] = 0;
            $this->database()->insert(phpfox::getT('userconnection_settings'), $aInserts);
        } else {
            $this->database()->update(phpfox::getT('userconnection_settings'), array(
                'value' => $value,
                    ), 'user_id=' . $user_id . ' and user_group_id=0  and name="' . $name . '"');
        }
    }

    public function getMyConnectSettings($user_id) {
        $aRows = $this->database()->select('*')
                ->from(phpfox::getT('userconnection_settings'), 'setting')
                ->where('user_id=' . $user_id)
                ->execute('getSlaveRows');

        $settings = array();
        foreach ($aRows as $aRow) {
            $settings[$aRow['name']] = $aRow['value'];
        }
        return $settings;
    }

    public function getSettings($user_group_id = 1) {
        $settings = array();
        $prefix = Phpfox::getParam(array('db', 'prefix'));
        $aRows = $this->database()->select("*")
                ->from($prefix . "userconnection_settings")
                ->where("user_group_id=" . $user_group_id)
                ->execute('getSlaveRows');
        foreach ($aRows as $aRow) {
            $settings[$aRow['name']] = $aRow['value'];
        }
        return $settings;
    }

    public function setSettings($params = array(), $user_group_id) {
        $prefix = Phpfox::getParam(array('db', 'prefix'));
        $this->database()->delete($prefix . "userconnection_settings", "user_group_id=" . $user_group_id);
        foreach ($params as $key => $value) {
            if ($key != "select_group_member")
                $this->database()->insert($prefix . "userconnection_settings", array('user_group_id' => $user_group_id,
                    'user_id' => 0,
                    'name' => $key,
                    'value' => $value));
        }
    }

    public function getUserGroupId($user_id) {
        $prefix = Phpfox::getParam(array('db', 'prefix'));
        $aRow = $this->database()->select("*")
                ->from($prefix . "user")
                ->where("user_id=" . $user_id)
                ->execute('getSlaveRow');
        if (count($aRow) > 0)
            $user_group_id = @$aRow["user_group_id"];
        else
            $user_group_id = 0;
        return $user_group_id;
    }

    public function createCacheTree() {
        $oCache = Phpfox::getLib("cache");
        $sCacheId = $oCache->set('getArrayUserConnect');
        $atemp = array();
        $aData = $oCache->get($sCacheId);
        $oCache->remove('getArrayUserConnect');
        $oCache = Phpfox::getLib("cache");

        $sCacheId = $oCache->set('getArrayUserConnect');

        $aRows = $this->database()->select('*')
                ->from(phpfox::getT('friend'), 'friend')
                ->execute('getSlaveRows');

        foreach ($aRows as $aRow) {
            $array = array();
            $array['friend'] = $aRow['friend_user_id'];
            $atemp[$aRow['user_id']]['friend'][] = $array;
            $atemp[$aRow['user_id']]['status'] = 0;
            $atemp[$aRow['user_id']]['user_id'] = $aRow['user_id'];
        }

        $aData = $atemp;
        $oCache->save($sCacheId, $aData);
        return $aData;
    }

    public function updateMenuProfile($is_active) {
        $this->database()->update(phpfox::getT('menu'), array('is_active' => $is_active), 'm_connection="' . 'profile' . '" and module_id="' . "userconnect" . '"');
        Phpfox::getLib("cache")->remove('menu', 'substr');
    }

    public function updateBlockProfile($is_active) {
        Phpfox::isUser(true);
        Phpfox::getUserParam('admincp.has_admin_access', true);
        $this->database()->update(phpfox::getT('block'), array('is_active' => $is_active), 'component="' . "connectionpath_sidebar" . '" and module_id="' . "userconnect" . '"');
        $this->cache()->remove('block', 'substr');
    }

}

?>
