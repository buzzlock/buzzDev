<?php

defined('PHPFOX') or exit('NO DICE!');

class userconnect_component_block_display extends Phpfox_Component {

    public function process() {

        $user_id = phpfox::getUserId();

        $maxLevel = Phpfox::getUserParam('userconnect.connection_levels');
        
        if (!is_numeric($maxLevel)) {
            $maxLevel = 1;
        }
        
        if ($maxLevel <= 0) {
            $maxLevel = 1;
        }
        else
        {
            if ($maxLevel > 5) {
                $maxLevel = 5;
            }
        }
        $aMenuList = array(
            Phpfox::getPhrase('userconnect.current_friends') => '#userconnect.view?type=current',
            Phpfox::getPhrase('userconnect.2nd_level_friends') => '#userconnect.view?type=leveltwo',
            Phpfox::getPhrase('userconnect.3rd_level_friends') => '#userconnect.view?type=levelthree',
            Phpfox::getPhrase('userconnect.4th_level_friends') => '#userconnect.view?type=levelfourth',
            Phpfox::getPhrase('userconnect.5th_level_friends') => '#userconnect.view?type=levelfive',
        );
        $aMenus = array();
        $count = 0;
        foreach ($aMenuList as $key => $item) {
            if ($count < $maxLevel) {
                $aMenus[$key] = $item;
            }
            $count++;
        }
        $iLevel = $this->getParam("level") == 0 ? 1 : $this->getParam("level");
        
        $this->template()->assign(array(
            'aMenu' => $aMenus,
            'sHeader' => Phpfox::getPhrase("userconnect.user_connections"),
            'level' => $iLevel,
                )
        );
        $bExtend = 1;

        $iLimit = Phpfox::getUserParam('userconnect.users_number_show');
        if ($iLimit <= 0)
            $iLimit = 6;
        $iPage = $this->request()->get("page");
        if (!$iPage)
            $iPage = 1;

        $keyword = phpfox::getLib('session')->get('uscnf_keysearch');
        $this->template()->assign(array('keyword' => $keyword));
        if ($keyword === false) {
            $keyword = "";
        }
        $result = Phpfox::getService("userconnect.algorithm")->getFriendsByLevel($user_id, $iLevel, $iPage, $iLimit, $keyword);
        if ($result == false) {
            $iCnt = 0;
            $aUsers = array();
        } else {
            $iCnt = $result[phpfox::getUserId()][$iLevel]['number'];
            $aUsers = $result[phpfox::getUserId()][$iLevel]['friends'];
        }

        $ViewMore = 1;
        if ($iLimit * $iPage >= $iCnt)
            $ViewMore = 0;
        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $iCnt));

        $this->template()
                ->setHeader('cache', array(
                    'pager.css' => 'style_css',
                    'country.js' => 'module_core',
                        )
                )
                ->assign(array(
                    'aUsers' => $aUsers,
                    'bExtend' => $bExtend,
                    'Page' => $iPage + 1,
                    'level' => $iLevel,
                    'ViewMore' => $ViewMore,
                    'total_friends' => $iCnt,
                        )
        );
        return 'block';
    }
}

?>