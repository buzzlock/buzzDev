<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_controller_createdata extends Phpfox_Component {

    public function process() {
        $number_friends = 10;
        $this->randomFriends($number_friends);
    }

    function make_seed($number_users) {
        list($usec, $sec) = explode(' ', microtime());
        return (float) $sec + ((float) $usec * $number_users);
    }

    private function randomFriends($number_friends = 1) {
        $this->createUserFriends(phpfox::getUserId(), $number_friends);
    }

    private function createUserFriends($user_id = 1, $number_friends = 1) {
        for ($i = $user_id + 1; $i < $user_id + $number_friends; $i++) {
            $this->createMemberShip($user_id, $i);
        }
    }

    private function createMemberShip($friend_id = null, $owner_id = null) {
        if ($friend_id == null || $owner_id == null) {
            return false;
        }
        if ($friend_id == $owner_id) {
            return false;
        }
        phpfox::getLib('database')->insert(Phpfox::getT('friend'), array(
            'list_id' => 0,
            'user_id' => $friend_id,
            'friend_user_id' => $owner_id,
            'time_stamp' => PHPFOX_TIME
                )
        );

        phpfox::getLib('database')->insert(Phpfox::getT('friend'), array(
            'list_id' => 0,
            'user_id' => $owner_id,
            'friend_user_id' => $friend_id,
            'time_stamp' => PHPFOX_TIME
                )
        );
    }

}

?>
