<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 *
 *
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Wall
 */

class Wall_Service_Process extends Phpfox_Service
{
    public function __construct(){}

    public function setFeedVisibility($bVisible, $aVals)
    {
        foreach($aVals as $sKey => $sValue)
        {
            $aVals[$sKey] = mysql_real_escape_string($sValue);
        }
        $sCond = "view_id = '".$aVals['view_id']."'
        AND owner_id = '".$aVals['owner_id']."'
        AND feed_id = '".$aVals['feed_id']."'
        AND viewer_id = '".$aVals['viewer_id']."'";
        if($bVisible)
        {
            $this->database()->delete(Phpfox::getT('wall_hidden'), $sCond);
        }
        elseif(!$this->database()->select('1')->from(Phpfox::getT('wall_hidden'))->where($sCond)->execute('getField'))
        {
            $this->database()->insert(Phpfox::getT('wall_hidden'), $aVals);
        }
    }

    public function fillVisibility($aFeeds, $iViewerId, $sView, $iOwnerId)
    {
        $aHiddenFeedIds = $this->database()->select('feed_id')
        ->from(Phpfox::getT('wall_hidden'))
        ->where("viewer_id = '$iViewerId' AND view_id = '$sView' AND owner_id = '$iOwnerId'")
        ->execute("getRows");
        foreach($aFeeds as $iKey => $aFeed)
        {
            // Also truncate html
            if(!empty($aFeeds[$iKey]['feed_status']))
            {
                //$aFeeds[$iKey]['feed_status'] = $this->truncate($aFeeds[$iKey]['feed_status'], 200);
            }
            if(in_array(array('feed_id' => $aFeed['feed_id']), $aHiddenFeedIds))
            {
                $aFeeds[$iKey]["visible"] = false;
                continue;
            }
            $aFeeds[$iKey]["visible"] = true;
            if(isset($aFeeds[$iKey]["more_feed_rows"]))
            {
                foreach($aFeeds[$iKey]["more_feed_rows"] as $iKey2 => $aFeed2)
                {
                    // Also truncate html
                    if(!empty($aFeeds[$iKey]["more_feed_rows"][$iKey2]['feed_status']))
                    {
                        //$aFeeds[$iKey]["more_feed_rows"][$iKey2]['feed_status'] = $this->truncate($aFeeds[$iKey]["more_feed_rows"][$iKey2]['feed_status'], 200);
                    }
                    if(in_array(array('feed_id' => $aFeed2['feed_id']), $aHiddenFeedIds))
                    {
                        $aFeeds[$iKey]["more_feed_rows"][$iKey2]["visible"] = false;
                        continue;
                    }
                    $aFeeds[$iKey]["more_feed_rows"][$iKey2]["visible"] = true;
                }
            }
        }
        return $aFeeds;
    }

    public function compile($html, $sTagging)
    {
        //$html = htmlentities($html, ENT_QUOTES, 'UTF-8');
        $html = Phpfox::getLib('parse.input')->prepare($html);
        $html = preg_replace("/<br.*?>/i", "[br]", $html);
        $html = preg_replace("/<img(.*?)>/i", "[img$1]", $html);
        $html = str_replace(array('<', '>'), array('&lt;', '&gt;'), $html);
        $html = preg_replace("/\[img(.*?)\]/i", "<img$1>", $html);
        $html = preg_replace("/\[br\]/i", "<br/>", $html);
        $aTagging = json_decode($sTagging, true);
		if(is_null($aTagging))
		{
			$aTagging = array();
		}
        foreach($aTagging as $aTag)
        {
            //$sName = Phpfox::getLib('parse.input')->prepare($aTag["name"]);
            //$sName = htmlentities($aTag["name"], ENT_QUOTES, 'UTF-8');
            $sName = str_replace(array('<', '>'), array('&lt;', '&gt;'), $aTag["name"]);
            $sName = Phpfox::getLib('parse.input')->prepare($sName);
            $sUserName = $aTag["userName"];
            $iPos = strpos($html, $sName);
            if($iPos !== false)
            {
                $html = substr($html, 0, $iPos) . '<a href="' . Phpfox::getLib('url')->makeUrl($sUserName) . '">' . $sName . '</a>' . substr($html, $iPos + strlen($sName));
            }
        }
        return $html;
    }

    public function displayLinks($aFeeds)
    {
        foreach($aFeeds as $iKey => $aFeed)
        {
            if(isset($aFeeds[$iKey]["feed_status"]))
            {
                // Freeze the tags
                $aFeed["feed_status"] = preg_replace("/(src|href)=\"http/i", "$1=\"h--p", $aFeed["feed_status"]);
                // Convert links to anchors
                $aFeed["feed_status"] = preg_replace("/(http[s]*:\/\/[a-zA-Z0-9\-\._]{2,255}\.[a-zA-Z]{2,5}[a-zA-Z0-9\.\-\_#\?&=\|\/\(\)]*)/i", "<a href=\"$1\" target=\"_blank\">$1</a>", $aFeed["feed_status"]);
                // Freeze the www
                //$aFeed["feed_status"] = preg_replace("/(http[s]*):\/\/www\./i", "$1://___.", $aFeed["feed_status"]);
                $aFeed["feed_status"] = preg_replace("/:\/\/www\./i", "://___.", $aFeed["feed_status"]);
                // Convert links to anchors (non http)
                $aFeed["feed_status"] = preg_replace("/(www\.[a-zA-Z0-9\-\._]{2,255}\.[a-zA-Z]{2,5}[a-zA-Z0-9\.\-\_#\?&=\|\/\(\)]*)/i", "<a href=\"http://$1\" target=\"_blank\">$1</a>", $aFeed["feed_status"]);
                // Unfreeze the www
                $aFeed["feed_status"] = preg_replace("/:\/\/___\./i", "://www.", $aFeed["feed_status"]);
                // Unfreeze the tags
                $aFeed["feed_status"] = preg_replace("/(src|href)=\"h--p/i", "$1=\"http", $aFeed["feed_status"]);

                //Display emoticon images
                if(Phpfox::isModule('emoticon'))
                {
                    $aFeed["feed_status"] = Phpfox::getService('emoticon')->parse($aFeed["feed_status"]);
                }

                $aFeeds[$iKey]["feed_status"] = $aFeed["feed_status"];
            }
        }
        return $aFeeds;
    }

    private $_bIsCallback = false;

    public function addComment($aVals)
    {
        if (empty($aVals['privacy_comment']))
        {
            $aVals['privacy_comment'] = 0;
        }

        if (empty($aVals['privacy']))
        {
            $aVals['privacy'] = 0;
        }

        if (empty($aVals['parent_user_id']))
        {
            $aVals['parent_user_id'] = 0;
        }

        //$sStatus = $this->preParse()->prepare($aVals['user_status']);
        $sStatus = Phpfox::getService('wall.process')->compile($aVals['user_status'], $aVals['tagging']);
        $_SESSION['ADDCOMMENT_USERSTATUS'] = $aVals['user_status'];
        $iStatusId = $this->database()->insert(Phpfox::getT(($this->_bIsCallback ? $this->_aCallback['table_prefix'] : '') . 'feed_comment'), array(
                'user_id' => (int) Phpfox::getUserId(),
                'parent_user_id' => (int) $aVals['parent_user_id'],
                'privacy' => $aVals['privacy'],
                'privacy_comment' => $aVals['privacy_comment'],
                'content' => $sStatus,
                'time_stamp' => PHPFOX_TIME
            )
        );

        if($iStatusId && !empty($aVals['tagging']))
        {
            $aTagging = json_decode($aVals['tagging'], true);
            $notified = array();
            foreach($aTagging as $iUserId => $aInfo)
            {
                if(in_array($iUserId, $notified))
                    continue;
                // Send notification
                Phpfox::getService('notification.process')->add('wall_comment', $iStatusId, $iUserId);
                $notified[] = $iUserId;
            }
        }

        if ($this->_bIsCallback)
        {
            $sLink = $this->_aCallback['link'] . 'comment-id_' . $iStatusId . '/';

            if (!empty($this->_aCallback['notification']))
            {
                Phpfox::getLib('mail')->to($this->_aCallback['email_user_id'])
                    ->subject($this->_aCallback['subject'])
                    ->message(sprintf($this->_aCallback['message'], $sLink))
                    ->send();

                Phpfox::getService('notification.process')->add($this->_aCallback['notification'], $iStatusId, $this->_aCallback['email_user_id']);
            }

            return Phpfox::getService('feed.process')->add($this->_aCallback['feed_id'], $iStatusId, $aVals['privacy'], $aVals['privacy_comment'], (int) $aVals['parent_user_id']);
        }

        $aUser = $this->database()->select('user_name')
            ->from(Phpfox::getT('user'))
            ->where('user_id = ' . (int) $aVals['parent_user_id'])
            ->execute('getRow');

        $sLink = Phpfox::getLib('url')->makeUrl($aUser['user_name'], array('comment-id' => $iStatusId));

        Phpfox::getLib('mail')->to($aVals['parent_user_id'])
            ->subject(Phpfox::getUserBy('full_name') . ' wrote a comment on your wall.')
            ->message("" . Phpfox::getUserBy('full_name') . " wrote a comment on your <a href=\"" . $sLink . "\">wall</a>.\nTo see the comment thread, follow the link below:\n<a href=\"" . $sLink . "\">" . $sLink . "</a>")
            ->send();

        Phpfox::getService('notification.process')->add('feed_comment_profile', $iStatusId, $aVals['parent_user_id']);
        if (isset($aVals['feed_type']))
        {
            return Phpfox::getService('feed.process')->add($aVals['feed_type'], $iStatusId, $aVals['privacy'], $aVals['privacy_comment'], (int) $aVals['parent_user_id']);
        }
        return Phpfox::getService('feed.process')->add('feed_comment', $iStatusId, $aVals['privacy'], $aVals['privacy_comment'], (int) $aVals['parent_user_id']);
    }
}