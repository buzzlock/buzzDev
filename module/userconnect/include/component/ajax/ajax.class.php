<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('PHPFOX') or exit('NO DICE!');

class userconnect_Component_Ajax_Ajax extends Phpfox_Ajax {

    private $_keyword = "";
    private $_currentview = "";

    public function search() {
        $aSearch = $this->get('search');

        $this->_keyword = $this->get('keysearch');
        $this->_keyword = isset($aSearch['search']) ? $aSearch['search'] : "";
        $this->_keyword = phpfox::getLib('database')->escape($this->_keyword);
        $this->_keyword = str_replace("_", "\_", $this->_keyword);
        phpfox::getLib('session')->set('uscnf_keysearch', $this->_keyword);
        $this->_currentview = $this->get('level');

        Phpfox::getBlock('userconnect.display', array(
            'js_userconnect_content' => 'current',
            'level' => $this->_currentview,
        ));
        $sContent = $this->getContent(false);
        $this->html('#js_userconnect_content', $sContent);
    }

    public function findPath() {
        $level = $this->get('level');
        $from_id = $this->get('from_id');
        $to_id = $this->get('to_id');
        $astr = $this->get('astr');
        $old_path = explode(',', $astr);
        unset($old_path[0]);
        $start_i = $this->get('start_i');
        $aConnections = phpfox::getService('userconnect.algorithm')->getExtraPath($from_id, $to_id, 10, $level - $start_i, $old_path, $start_i);
        $this->xjs($aConnections, 0, $old_path, $start_i, $level - $start_i);
        return "";
    }

    private function xjs($aConnections = array(), $rand = 0, $old_path, $start_id, $level) {
        if ($aConnections === false || count($aConnections) <= 0) {
            $aConnections = array_slice($old_path, $start_id, $level);
        } else {
            $aConnections = $aConnections[$rand];
        }
        $aConnections = phpfox::getService('userconnect.algorithm')->getInfoPath($aConnections, null);
        $js = "";

        foreach ($aConnections as $k => $v) {
            Phpfox::getLib('template')->assign(array(
                'view_path' => $aConnections[$k],
                'user_id' => phpfox::getUserId(),
                'level_start_id' => $start_id,
                'is_view_js' => true,
                'from_id' => $old_path[count($old_path) - 1],
                'level' => count($old_path) - 1,
                'core_path' => phpfox::getParam('core.path'),
            ))->getTemplate('userconnect.block.miniuser');
            $html = str_replace(array("\r\n", "\n", "\r"), "", $this->getContent(true));
            $js .= "replaceUser(" . $start_id . ",'" . $html . "'," . $v['user_id'] . ");";
            $start_id++;
        }
        echo $js;
    }

    public function viewConnectionPath() {
        Phpfox::getBlock('userconnect.connectionpath', array(
            'level' => $this->get('level'),
            'from_id' => $this->get('from_id'),
            'from_1' => $this->get('from_1'),
        ));
        $this->call('<script>$Behavior.initMenuViewPath();</script>');
    }

    public function view() {
        phpfox::getLib('session')->remove('uscnf_keysearch');
        $type = $this->get('type');
        $level = 1;
        switch ($type) {
            case 'current':
                Phpfox::getBlock('userconnect.display', array(
                    'js_userconnect_content' => 'current',
                    'level' => 1,
                ));
                $level = 1;
                $sContent = $this->getContent(false);
                $this->html('#js_userconnect_content', $sContent);
                break;
            case 'leveltwo':
                Phpfox::getBlock('userconnect.display', array(
                    'js_userconnect_content' => 'current',
                    'level' => 2,
                ));
                $level = 2;
                $sContent = $this->getContent(false);
                $this->html('#js_userconnect_content', $sContent);
                break;
            case 'levelthree':
                Phpfox::getBlock('userconnect.display', array(
                    'js_userconnect_content' => 'current',
                    'level' => 3,
                ));
                $level = 3;
                $sContent = $this->getContent(false);
                $this->html('#js_userconnect_content', $sContent);
                break;
            case 'levelfourth':
                Phpfox::getBlock('userconnect.display', array(
                    'js_userconnect_content' => 'current',
                    'level' => 4,
                ));
                $level = 4;
                $sContent = $this->getContent(false);
                $this->html('#js_userconnect_content', $sContent);
                break;
            case 'levelfive':
                Phpfox::getBlock('userconnect.display', array(
                    'js_userconnect_content' => 'current',
                    'level' => 5,
                ));
                $level = 5;
                $sContent = $this->getContent(false);
                $this->html('#js_userconnect_content', $sContent);
                break;
        }
        $this->call('setLevel("' . $level . '");');
    }

    public function viewMoreLevel() {
        $iPage = $this->get("page");
        $iLimit = Phpfox::getUserParam('userconnect.users_number_show');
        if ($iLimit <= 0)
            $iLimit = 6;
        $user_id = phpfox::getUserId();

        if (!$iPage)
            $iPage = 1;

        $iLevel = $this->get("level");

        if (!$iLevel)
            $iLevel = 1;

        $keyword = phpfox::getLib('session')->get('uscnf_keysearch');
        if ($keyword === false) {
            $keyword = "";
        } else {
            if ($iPage == 1)
                $iPage = 2;
        }
        $result = Phpfox::getService("userconnect.algorithm")->getFriendsByLevel($user_id, $iLevel, $iPage, $iLimit, $keyword);
        if ($result == false) {
            $iCnt = 0;
            $aUsers = array();
        } else {
            $iCnt = $result[phpfox::getUserId()][$iLevel]['number'];
            $aUsers = $result[phpfox::getUserId()][$iLevel]['friends'];
        }

        $ViewMore = 0;
        if ($iLimit * $iPage < $iCnt)
            $ViewMore = 1;

        $bExtend = 1;
        Phpfox::getLib('template')->assign(array(
            'aUsers' => $aUsers,
            'Page' => $iPage + 1,
            'level' => $iLevel,
            'bExtend' => $bExtend,
            'ViewMore' => $ViewMore,
        ))->getTemplate('userconnect.block.entrynew');
        $sContent = $this->getContent(false);
        $this->append('#js_userconnect_viewmore_show', $sContent); //after

        $sBlkViewMore = '#js_userconnect_viewmore';
        $url = phpfox::getLib('url')->makeUrl('userconnect.view', array('page' => $iPage, 'level' => $iLevel));

        if ($iLimit * $iPage < $iCnt) {
            $ViewMore = "<a href=\"{$url}\" onclick='$(this).html($.ajaxProcess(\"Loading...\"));$.ajaxCall(\"userconnect.viewMoreLevel\",\"page=" . ($iPage + 1) . "&level=" . $iLevel . "\");return false;' class=\"pager_view_more no_ajax_link\" >View More</a>";
            $this->html($sBlkViewMore, $ViewMore);
        } else {
            $ViewMore = '';
            $this->remove($sBlkViewMore);
        }
    }

    public function updateconnectionsettings() {
        $value = $this->get('value');

        phpfox::getService('userconnect')->saveMyConnectSettings(phpfox::getUserId(), "showconnectionpath", $value);
        phpfox::getService('userconnect.algorithm')->clearCache("hiddenuser");
    }

    public function loadSettings() {

        Phpfox::getBlock('userconnect.settings', array('user_group_id' => $this->get('user_group_id')));
        $this->html('#div_settings', $this->getContent(false));
        $this->html('#loading', '');
    }

    public function addRequest() {
        Phpfox::isUser(true);
        $iHidden = 0;
        $iUser_id = $this->get('user_id');
        $iKey = $this->get('key');
        $iFlag = $this->get('flag');
        if (Phpfox::getService('friend.request')->isRequested(PHPFOX::getUserId(), $iUser_id)) {
            return;
        } else {
            if (Phpfox::getService('friend.request.process')->add(Phpfox::getUserId(), $iUser_id, 0, '')) {
                //Show in hide field here...
                $iUser = 0;
                foreach ($_SESSION['friends_request'] as $iUser_Temp => $iValue) {
                    if ($iValue == 0) {
                        $_SESSION['friends_request'][$iUser_Temp] = 1;
                        $iUser = $iUser_Temp;
                        $iHidden = 1;
                        break;
                    }
                }
                if ($iFlag == 0) {

                    $this->call("$('#js_item_youknow_$iUser').removeClass('frpr_k').addClass('frpr');");
                }
                $_SESSION['you_know_check']++;
                $this->html('#link_send_request_' . $iKey, PHPFOX::getPhrase('userconnect.friend_request_sent'));
                $sSelector = "$('#js_item_youknow_" . $iUser_id . "')";
                if ($_SESSION['you_know_check'] == count($_SESSION['friends_request'])) {

                    $this->call($sSelector . ".fadeOut(1500).queue(function() { $sSelector.remove();$('#key_hidden_$iUser').val($iFlag);$('#js_item_youknow_$iUser').appendTo('#show_user_you_know_$iFlag');$('#js_item_youknow_$iUser').fadeIn(1500);$('#js_block_border_userconnect_mayyouknow').delay(1500).hide();});");
                } else {

                    $this->call($sSelector . ".fadeOut(1500).queue(function() { $sSelector.remove();$('#key_hidden_$iUser').val($iFlag);$('#js_item_youknow_$iUser').appendTo('#show_user_you_know_$iFlag');$('#js_item_youknow_$iUser').fadeIn(1500);});");
                }
            }
        }
    }

}

?>
