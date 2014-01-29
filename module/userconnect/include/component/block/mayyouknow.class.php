<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_block_mayyouknow extends Phpfox_Component {

    public function process() {
    	
		
        unset($_SESSION['friends_request']);
        unset($_SESSION['you_know_check']);
        $user_id = $this->getParam('user_id');
        $this->template()->assign(array('user_id' => Phpfox::getUserId(),
            'profile_id' => $user_id));
        if ($user_id != Phpfox::getUserId()) {
            return 'block';
        }
        $this->template()->assign(array("sHeader" => Phpfox::getPhrase('userconnect.people_you_may_know')));

        $iLimit = Phpfox::getUserParam('userconnect.may_you_know_numbers');
        if ($iLimit <= 0) {
            return false;
        }
        $oCache = PHPFOX::GetLib("cache");
        $sCacheId = $oCache->set('userconnect_' . $user_id . "_" . ($iLimit + 150));

        if (!($aFriends = $oCache->get($sCacheId))) {
            $aFriends = phpfox::getService("userconnect")->getAllPeopleYouKnow($user_id, $iLimit + 150);
        }

        //---------------------------------------check have or not friend request...-------------------
        $aFriendYouKnow = array();
        $aUser_Owner_Id = PHPFOX::getUserId();
        $iFlag = 0;


        for ($iI = 0; $iI < count($aFriends); $iI++) {

            if (!Phpfox::getService('friend.request')->isRequested($aUser_Owner_Id, $aFriends[$iI]) && !Phpfox::getService('friend.request')->isRequested($aFriends[$iI], $aUser_Owner_Id)) {
                $aFriendYouKnow[$iFlag] = $aFriends[$iI];
                $iFlag++;
            }
        }

        if (empty($aFriendYouKnow)) {
            return false;
        }

        //---------------------------------------End check--------------------------------------

        $aUser_YouKnow = array();
        $iCnt = count($aFriendYouKnow);
        $kq = 0;
        $user_id_YouKnow = -1;
        for ($i = 0; $i < $iCnt; $i++) {
            $kq_rows = phpfox::getService("userconnect")->getUser($aFriendYouKnow[$i]);

            if ($i == 0)
                $user_id_YouKnow = $aFriendYouKnow[$i];
            if (count($kq_rows) > 0 && $kq_rows['user_id'] != $user_id) {
                $kq++;
                $aUser_YouKnow[] = $kq_rows;
                if ($kq <= $iLimit) {
                    $_SESSION['friends_request'][$kq_rows['user_id']] = 1;
                } else {
                    $_SESSION['friends_request'][$kq_rows['user_id']] = 0;
                }
            }
            if ($kq >= $iLimit + 20) //by HT : get 5 users more than limit                
                break;
        }



        if (count($aUser_YouKnow) <= 0) {
            return false;
        }
        $_SESSION['you_know_check'] = 0;

        $this->template()->assign(array(
            'aUser_YouKnow' => $aUser_YouKnow,
            'user_id_YouKnow' => $user_id_YouKnow,
            'iLimit' => $iLimit
        ));
        return 'block';
    }

}

?>