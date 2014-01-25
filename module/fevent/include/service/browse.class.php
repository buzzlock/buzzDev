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
 * @package         YouNet_Event
 */
class Fevent_Service_Browse extends Phpfox_Service
{
    private $_aListings = array();

    private $_sCategory = null;

    private $_iAttending = null;

    private $_aCallback = false;

    private $_bFull = false;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = Phpfox::getT('fevent');
    }

    public function category($sCategory)
    {
        $this->_sCategory = $sCategory;

        return $this;
    }

    public function attending($iAttending)
    {
        $this->_iAttending = $iAttending;

        return $this;
    }

    public function callback($aCallback)
    {
        $this->_aCallback = $aCallback;

        return $this;
    }

    public function full($bFull)
    {
        $this->_bFull = $bFull;

        return $this;
    }

    public function query()
    {
        $this->database()->select('m.server_id as event_server_id, ');
        
        if ($this->_iAttending !== null)
        {
            $this->database()->group('m.event_id');
        }

        if (Phpfox::isUser() && Phpfox::isModule('like'))
        {
            $this->database()->select('lik.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'lik', 'lik.type_id = \'fevent\' AND lik.item_id = m.event_id AND lik.user_id = ' . Phpfox::getUserId());
        }
    }

    public function processRows(&$aRows)
    {
        $aNewRows = $aRows;
        $aRows = array();
        foreach ($aNewRows as $aEvent)
        {
            $content_repeat = "";
            $until = "";
            if ($aEvent['isrepeat'] == 0)
            {
                $content_repeat = Phpfox::getPhrase('fevent.daily');
            }
            elseif ($aEvent['isrepeat'] == 1)
            {
                $content_repeat = Phpfox::getPhrase('fevent.weekly');
            }
            elseif ($aEvent['isrepeat'] == 2)
            {
                $content_repeat = Phpfox::getPhrase('fevent.monthly');
            }
            if ($content_repeat != "")
            {
                if ($aEvent['timerepeat'] != 0)
                {
                    $sDefault = null;
                    $until = Phpfox::getTime("M j, Y", $aEvent['timerepeat']);
                    $content_repeat .= ", " . Phpfox::getPhrase('fevent.until') . " " . $until;
                }
            }
            $aEvent['content_repeat'] = $content_repeat;
            //$aEvent['start_time'] = Phpfox::getLib('date')->convertFromGmt($aEvent['start_time'], $aEvent['start_gmt_offset']);
            $iDate = Phpfox::getTime('dmy', $aEvent['start_time']);
            //$aEvent['end_time'] = Phpfox::getLib('date')->convertFromGmt($aEvent['end_time'], $aEvent['start_gmt_offset']);
            $iDateEnd = Phpfox::getTime('dmy', $aEvent['end_time']);

            if ($iDate == Phpfox::getTime('dmy', PHPFOX_TIME))
            {
                $iDate = Phpfox::getPhrase('fevent.today');
            }
            elseif ($iDate == Phpfox::getTime('dmy', (PHPFOX_TIME + 86400)))
            {
                $iDate = Phpfox::getPhrase('fevent.tomorrow');
            }
            else
            {
                $iDate = Phpfox::getTime(Phpfox::getParam('fevent.fevent_browse_time_stamp'), $aEvent['start_time']);
            }

            $aEvent['start_time_phrase'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_browse_time_stamp'), $aEvent['start_time']);
            $aEvent['start_time_phrase_stamp'] = Phpfox::getTime('g:sa', $aEvent['start_time']);

            $aEvent['aFeed'] = array(
                'feed_display' => 'mini',
                'comment_type_id' => 'fevent',
                'privacy' => $aEvent['privacy'],
                'comment_privacy' => $aEvent['privacy_comment'],
                'like_type_id' => 'fevent',
                'feed_is_liked' => (isset($aEvent['is_liked']) ? $aEvent['is_liked'] : false),
                'feed_is_friend' => (isset($aEvent['is_friend']) ? $aEvent['is_friend'] : false),
                'item_id' => $aEvent['event_id'],
                'user_id' => $aEvent['user_id'],
                'total_comment' => $aEvent['total_comment'],
                'feed_total_like' => $aEvent['total_like'],
                'total_like' => $aEvent['total_like'],
                'feed_link' => Phpfox::getLib('url')->permalink('fevent', $aEvent['event_id'], $aEvent['title']),
                'feed_title' => $aEvent['title'],
                'type_id' => 'fevent');

            $aEvent['event_date'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aEvent['start_time']);
            if ($aEvent['start_time'] < $aEvent['end_time'])
            {
                if ($aEvent['isrepeat'] == -1)
                {
                    $aEvent['event_date'] .= ' - ';
                    if (date('dmy', $aEvent['start_time']) === date('dmy', $aEvent['end_time']))
                    {
                        $aEvent['event_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time_short'), $aEvent['end_time']);
                    }
                    else
                    {
                        $aEvent['event_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aEvent['end_time']);
                    }
                }
            }

            // add count down
            $seconds_taken = $aEvent['start_time'] - PHPFOX_TIME;
            if ($seconds_taken > 0)
            {
                $aEvent['time_left'] = $this->seconds2string($seconds_taken);
            }
            else
            {
                $aEvent['time_left'] = '';
            }

            $aEvent['url'] = Phpfox::getLib('url')->permalink('fevent', $aEvent['event_id'], $aEvent['title']);

            $aRows[$iDate][] = $aEvent;
        }
    }

    /**
     * Convert seconds to string
     * @param int $timeInSeconds
     * @return string
     */
    public function seconds2string($timeInSeconds)
    {
        static $phrases = null;

        $seeks = array(
            31536000,
            2592000,
            86400,
            3600,
            60);

        if (null == $phrases)
        {
            $phrases = array(array(
                    Phpfox::getPhrase('fevent.year'),
                    Phpfox::getPhrase('fevent.month'),
                    Phpfox::getPhrase('fevent.day'),
                    Phpfox::getPhrase('fevent.hour'),
                    Phpfox::getPhrase('fevent.minute')), array(
                    Phpfox::getPhrase('fevent.years'),
                    Phpfox::getPhrase('fevent.months'),
                    Phpfox::getPhrase('fevent.days'),
                    Phpfox::getPhrase('fevent.hours'),
                    Phpfox::getPhrase('fevent.minutes')));
        }

        $result = array();

        $remain = $timeInSeconds;

        foreach ($seeks as $index => $seek)
        {
            $check = intval($remain / $seek);
            $remain = $remain % $seek;

            if ($check > 0)
            {
                $result[] = $check . ' ' . $phrases[($check > 1) ? 1 : 0][$index];
            }
            else
            {
                continue;
            }

            if (count($result) > 1)
            {
                break;
            }
        }

        return implode(' ', $result);
    }

    public function getQueryJoins($bIsCount = false, $bNoQueryFriend = false)
    {
        $this->database()->innerJoin(Phpfox::getT('fevent_text'), 'ft', 'ft.event_id = m.event_id');
        if (Phpfox::isModule('friend') && Phpfox::getService('friend')->queryJoin($bNoQueryFriend))
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = m.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }

        if ($this->_sCategory !== null)
        {
            $this->database()->innerJoin(Phpfox::getT('fevent_category_data'), 'mcd', 'mcd.event_id = m.event_id');

            if (!$bIsCount)
            {
                $this->database()->group('m.event_id');
            }
        }

        if ($this->_iAttending !== null)
        {
            $this->database()->innerJoin(Phpfox::getT('fevent_invite'), 'ei', 'ei.event_id = m.event_id AND ei.rsvp_id = ' . (int)$this->_iAttending . ' AND ei.invited_user_id = ' . Phpfox::getUserId());

            if (!$bIsCount)
            {
                $this->database()->select('ei.rsvp_id, ');
                $this->database()->group('m.event_id');
            }
        }
        else
        {
            if (Phpfox::isUser())
            {
                $this->database()->leftJoin(Phpfox::getT('fevent_invite'), 'ei', 'ei.event_id = m.event_id AND ei.invited_user_id = ' . Phpfox::getUserId());

                if (!$bIsCount)
                {
                    $this->database()->select('ei.rsvp_id, ');
                    $this->database()->group('m.event_id');
                }
            }
        }
    }

    /**
     * If a call is made to an unknown method attempt to connect
     * it to a specific plug-in with the same name thus allowing 
     * plug-in developers the ability to extend classes.
     *
     * @param string $sMethod is the name of the method
     * @param array $aArguments is the array of arguments of being passed
     */
    public function __call($sMethod, $aArguments)
    {
        /**
         * Check if such a plug-in exists and if it does call it.
         */
        if ($sPlugin = Phpfox_Plugin::get('fevent.service_browse__call'))
        {
            return eval($sPlugin);
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __class__ . '::' . $sMethod . '()', E_USER_ERROR);
    }
}

?>