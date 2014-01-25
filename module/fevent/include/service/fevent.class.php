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
class Fevent_Service_Fevent extends Phpfox_Service {

    private $_aCallback = false;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->_sTable = Phpfox::getT('fevent');
    }

    public function callback($aCallback) {
        $this->_aCallback = $aCallback;

        return $this;
    }

    public function getEvent($sEvent, $bUseId = false, $bNoCache = false) {
        static $aEvent = null;

        if ($aEvent !== null && $bNoCache === false) {
            return $aEvent;
        }

        $bUseId = true;

        if (Phpfox::isUser()) {
            $this->database()->select('ei.invite_id, ei.rsvp_id, ')->leftJoin(Phpfox::getT('fevent_invite'), 'ei', 'ei.event_id = e.event_id AND ei.invited_user_id = ' . Phpfox::getUserId());
        }

        if (Phpfox::isModule('friend')) {
            $this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = e.user_id AND f.friend_user_id = " . Phpfox::getUserId());
        } else {
            $this->database()->select('0 as is_friend, ');
        }

        $aEvent = $this->database()->select('e.*, ' . (Phpfox::getParam('core.allow_html') ? 'et.description_parsed' : 'et.description') . ' AS description, ' . Phpfox::getUserField())
                ->from($this->_sTable, 'e')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->join(Phpfox::getT('fevent_text'), 'et', 'et.event_id = e.event_id')
                ->where('e.event_id = ' . (int) $sEvent)
                ->execute('getRow');

        if (!isset($aEvent['event_id'])) {
            return false;
        }

        if (!Phpfox::isUser()) {
            $aEvent['invite_id'] = 0;
            $aEvent['rsvp_id'] = 0;
        }

        if ($aEvent['view_id'] == '1') {
            if ($aEvent['user_id'] == Phpfox::getUserId() || Phpfox::getUserParam('fevent.can_approve_events') || Phpfox::getUserParam('fevent.can_view_pirvate_events')) {
                
            } else {
                return false;
            }
        }

        $aEvent['event_date'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aEvent['start_time']);
        if ($aEvent['start_time'] < $aEvent['end_time']) {
			if($aEvent['isrepeat']==-1)
            {
				$aEvent['event_date'] .= ' - ';
				if (date('dmy', $aEvent['start_time']) === date('dmy', $aEvent['end_time'])) {
					$aEvent['event_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time_short'), $aEvent['end_time']);
				} else {
					$aEvent['event_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aEvent['end_time']);
				}
			}
        }

        // Get custom values
        $aCustom = $this->database()->select('cv.value, cf.phrase_var_name')
                ->from(Phpfox::getT('fevent_custom_value'), 'cv')
                ->innerJoin(Phpfox::getT('fevent_custom_field'), 'cf', 'cf.field_id = cv.field_id')
                ->where('cv.event_id = ' . $aEvent['event_id'] . ' AND cf.is_active = 1')
                ->execute('getSlaveRows');
        if (isset($aCustom[0])) {
            foreach ($aCustom as $iKey => $aField) {
                $sValue = $aField['value'];
                if (preg_match("/^\[.*?\]$/", $sValue)) {
                    $aValues = explode(",", trim($sValue, '[]'));
                    $sValue = "";
                    foreach ($aValues as $sVal) {
                        $sVal = trim($sVal, '"');
                        $sValue .= "<li>$sVal</li>";
                    }
                    $sValue = '<ul>' . $sValue . '</ul>';
                }
                $aField['value'] = $sValue;
                $aCustom[$iKey] = $aField;
            }
            $aEvent["custom"] = $aCustom;
        } else {
            $aEvent["custom"] = array();
        }

        if (isset($aEvent['gmap']) && !empty($aEvent['gmap'])) {
            $aEvent['gmap'] = unserialize($aEvent['gmap']);
        }

        $aEvent['categories'] = Phpfox::getService('fevent.category')->getCategoriesById($aEvent['event_id']);

        if (!empty($aEvent['address'])) {
            $aEvent['map_location'] = $aEvent['address'];
            if (!empty($aEvent['city'])) {
                $aEvent['map_location'] .= ',' . $aEvent['city'];
            }
            if (!empty($aEvent['postal_code'])) {
                $aEvent['map_location'] .= ',' . $aEvent['postal_code'];
            }
            if (!empty($aEvent['country_child_id'])) {
                $aEvent['map_location'] .= ',' . Phpfox::getService('core.country')->getChild($aEvent['country_child_id']);
            }
            if (!empty($aEvent['country_iso'])) {
                $aEvent['map_location'] .= ',' . Phpfox::getService('core.country')->getCountry($aEvent['country_iso']);
            }

            $aEvent['map_location'] = urlencode($aEvent['map_location']);
        }

        return $aEvent;
    }

    public function getEventCoordinates() {
        return $this->database()->select('event_id, lat, lng')
                        ->from($this->_sTable)
                        ->execute('getRows');
    }

    public function getEventsByIds($aIds) {
        $sIds = join(',', $aIds);
        $aRows = $this->database()->select('event_id, lat, lng, title, start_time, start_gmt_offset, location, address, city')
                ->from($this->_sTable)
                ->where("event_id IN ($sIds)")
                ->execute('getRows');
        foreach ($aRows as $iKey => $aEvent) {
            $aEvent['start_time'] = Phpfox::getLib('date')->convertFromGmt($aEvent['start_time'], $aEvent['start_gmt_offset']);
            $aEvent['start_time'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_browse_time_stamp'), $aEvent['start_time']);
            $aEvent['link'] = Phpfox::getLib('url')->permalink('fevent', $aEvent['event_id'], $aEvent['title']);
            $aRows[$iKey] = $aEvent;
        }
        return $aRows;
    }

    public function getTimeLeft($iId) {
        $aEvent = $this->getEvent($iId, true);

        return ($aEvent['mass_email'] + (Phpfox::getUserParam('fevent.total_mass_emails_per_hour') * 60));
    }

    public function canSendEmails($iId, $bNoCache = false) {
        if (Phpfox::getUserParam('fevent.total_mass_emails_per_hour') === 0) {
            return true;
        }

        $aEvent = $this->getEvent($iId, true, $bNoCache);

        return (($aEvent['mass_email'] + (Phpfox::getUserParam('fevent.total_mass_emails_per_hour') * 60) > PHPFOX_TIME) ? false : true);
    }

    public function getForEdit($iId, $bForce = false) {
        $aEvent = $this->database()->select('e.*, et.description')
                ->from($this->_sTable, 'e')
                ->join(Phpfox::getT('fevent_text'), 'et', 'et.event_id = e.event_id')
                ->where('e.event_id = ' . (int) $iId)
                ->execute('getRow');

        if (empty($aEvent)) {
            return false;
        }
        if ((($aEvent['user_id'] == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event')) || $bForce === true) {
            $aEvent['start_time'] = Phpfox::getLib('date')->convertFromGmt($aEvent['start_time'], $aEvent['start_gmt_offset']);
            $aEvent['end_time'] = Phpfox::getLib('date')->convertFromGmt($aEvent['end_time'], $aEvent['end_gmt_offset']);

            $aEvent['start_month'] = date('n', $aEvent['start_time']);
            $aEvent['start_day'] = date('j', $aEvent['start_time']);
            $aEvent['start_year'] = date('Y', $aEvent['start_time']);
            $aEvent['start_hour'] = date('H', $aEvent['start_time']);
            $aEvent['start_minute'] = date('i', $aEvent['start_time']);

            $aEvent['end_month'] = date('n', $aEvent['end_time']);
            $aEvent['end_day'] = date('j', $aEvent['end_time']);
            $aEvent['end_year'] = date('Y', $aEvent['end_time']);
            $aEvent['end_hour'] = date('H', $aEvent['end_time']);
            $aEvent['end_minute'] = date('i', $aEvent['end_time']);

            $aEvent['categories'] = Phpfox::getService('fevent.category')->getCategoryIds($aEvent['event_id']);

            return $aEvent;
        }

        return false;
    }

    public function getInvites($iEvent, $iRsvp, $iPage = 0, $iPageSize = 8) {
        $aInvites = array();
        $iCnt = $this->database()->select('COUNT(*)')
                ->from(Phpfox::getT('fevent_invite'))
                ->where('event_id = ' . (int) $iEvent . ' AND rsvp_id = ' . (int) $iRsvp)
                ->execute('getSlaveField');

        if ($iCnt) {
            $aInvites = $this->database()->select('ei.*, ' . Phpfox::getUserField())
                    ->from(Phpfox::getT('fevent_invite'), 'ei')
                    ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = ei.invited_user_id')
                    ->where('ei.event_id = ' . (int) $iEvent . ' AND ei.rsvp_id = ' . (int) $iRsvp)
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->order('ei.invite_id DESC')
                    ->execute('getSlaveRows');
        }

        return array($iCnt, $aInvites);
    }

    public function getInviteForUser($iLimit = 6) {
        $aRows = $this->database()->select('e.*')
                ->from(Phpfox::getT('fevent_invite'), 'ei')
                ->join(Phpfox::getT('fevent'), 'e', 'e.event_id = ei.event_id')
                ->where('ei.rsvp_id = 0 AND ei.invited_user_id = ' . Phpfox::getUserId())
                ->limit($iLimit)
                ->execute('getRows');

        foreach ($aRows as $iKey => $aRow) {
            $aRows[$iKey]['start_time_phrase'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_browse_time_stamp'), $aRow['start_time']);
            $aRows[$iKey]['start_time_phrase_stamp'] = Phpfox::getTime('g:sa', $aRow['start_time']);
        }

        return $aRows;
    }

    public function getForProfileBlock($iUserId, $iLimit = 5) {
        $iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));

        $aEvents = $this->database()->select('m.*')
                ->from($this->_sTable, 'm')
                ->join(Phpfox::getT('fevent_invite'), 'ei', 'ei.event_id = m.event_id AND ei.rsvp_id = 1 AND ei.invited_user_id = ' . (int) $iUserId)
                ->where('m.view_id = 0 AND m.start_time >= \'' . $iTimeDisplay . '\'')
                ->limit($iLimit)
                ->order('m.start_time ASC')
                ->execute('getSlaveRows');

        foreach ($aEvents as $iKey => $aEvent) {
            $aEvents[$iKey]['url'] = Phpfox::getLib('url')->permalink('fevent', $aEvent['event_id'], $aEvent['title']);
            $aEvents[$iKey]['start_time_stamp'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_view_time_stamp_profile'), $aEvent['start_time']);
            $aEvents[$iKey]['location_clean'] = Phpfox::getLib('parse.output')->split(Phpfox::getLib('parse.output')->clean($aEvent['location']), 10);
        }

        return $aEvents;
    }

    public function getForParentBlock($sModule, $iItemId, $iLimit = 5) {
        $iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));

        $aEvents = $this->database()->select('m.event_id, m.title, m.tag_line, m.image_path, m.server_id, m.start_time, m.location, m.country_iso, m.city, m.module_id, m.item_id')
                ->from($this->_sTable, 'm')
                ->where('m.view_id = 0 AND m.module_id = \'' . $this->database()->escape($sModule) . '\' AND m.item_id = ' . (int) $iItemId . ' AND m.start_time >= \'' . $iTimeDisplay . '\'')
                ->limit($iLimit)
                ->order('m.start_time ASC')
                ->execute('getSlaveRows');

        foreach ($aEvents as $iKey => $aEvent) {
            $aEvents[$iKey]['url'] = Phpfox::getLib('url')->makeUrl('fevent', array('redirect' => $aEvent['event_id']));
            $aEvents[$iKey]['start_time_stamp'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_view_time_stamp_profile'), $aEvent['start_time']);
            $aEvents[$iKey]['location_clean'] = Phpfox::getLib('parse.output')->split(Phpfox::getLib('parse.output')->clean($aEvent['location']), 10);
        }

        return $aEvents;
    }

    public function getPendingTotal() {
        $iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));

        return $this->database()->select('COUNT(*)')
                        ->from($this->_sTable)
                        ->where('view_id = 1 AND start_time >= \'' . $iTimeDisplay . '\'')
                        ->execute('getSlaveField');
    }

    public function getRandomSponsored() {
        $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $sCacheId = $this->cache()->set('event_sponsored_' . $iToday);
        if (!($aEvents = $this->cache()->get($sCacheId))) {
            $aEvents = $this->database()->select('s.*, s.country_iso AS sponsor_country_iso, e.*')
                    ->from($this->_sTable, 'e')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                    ->join(Phpfox::getT('ad_sponsor'), 's', 's.item_id = e.event_id')
                    ->where('e.view_id = 0 AND e.privacy = 0 AND e.is_sponsor = 1 AND s.module_id = "fevent" AND e.start_time >= \'' . $iToday . '\'')
                    ->execute('getRows');

            foreach ($aEvents as $iKey => $aEvent) {
                $aEvents[$iKey]['categories'] = Phpfox::getService('fevent.category')->getCategoriesById($aEvent['event_id']);
                $aEvents[$iKey]['event_date'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aEvent['start_time']) . ' - ';
                if (date('dmy', $aEvent['start_time']) === date('dmy', $aEvent['end_time'])) {
                    $aEvents[$iKey]['event_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time_short'), $aEvent['end_time']);
                } else {
                    $aEvents[$iKey]['event_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aEvent['end_time']);
                }
            }

            $this->cache()->save($sCacheId, $aEvents);
        }
        $aEvents = Phpfox::getService('ad')->filterSponsor($aEvents);

        if ($aEvents === true || (is_array($aEvents) && !count($aEvents))) {
            return false;
        }


        // Randomize to get a event
        return $aEvents[rand(0, (count($aEvents) - 1))];
    }

    public function isAlreadyInvited($iItemId, $aFriends) {
        if ((int) $iItemId === 0) {
            return false;
        }

        if (is_array($aFriends)) {
            if (!count($aFriends)) {
                return false;
            }

            $sIds = '';
            foreach ($aFriends as $aFriend) {
                if (!isset($aFriend['user_id'])) {
                    continue;
                }

                $sIds[] = $aFriend['user_id'];
            }

            $aInvites = $this->database()->select('invite_id, rsvp_id, invited_user_id')
                    ->from(Phpfox::getT('fevent_invite'))
                    ->where('event_id = ' . (int) $iItemId . ' AND invited_user_id IN(' . implode(', ', $sIds) . ')')
                    ->execute('getSlaveRows');

            $aCache = array();
            foreach ($aInvites as $aInvite) {
                $aCache[$aInvite['invited_user_id']] = ($aInvite['rsvp_id'] > 0 ? Phpfox::getPhrase('fevent.responded') : Phpfox::getPhrase('fevent.invited'));
            }

            if (count($aCache)) {
                return $aCache;
            }
        }

        return false;
    }

    public function getSiteStatsForAdmins() {
        $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        return array(
            'phrase' => Phpfox::getPhrase('fevent.events'),
            'value' => $this->database()->select('COUNT(*)')
                    ->from(Phpfox::getT('fevent'))
                    ->where('view_id = 0 AND time_stamp >= ' . $iToday)
                    ->execute('getSlaveField')
        );
    }

    public function getUpcoming($bIsPage = false, $bIsProfile = false) {
        static $aUpcoming = null;
        static $iTotal = null;
        
        if ($aUpcoming !== null) {
            return array($iTotal, $aUpcoming);
        }
        
        $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        
        $aUpcoming = array();
        $repeatday = "( v.isrepeat>-1 and v.timerepeat>" . ($iToday) . ")";
        $repeattime = "(v.isrepeat>-1 and (v.timerepeat=0 or " . $repeatday . "))";
        
        $aRows = $this->database()->select('v.*, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('fevent'), 'v')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
                ->where('v.view_id = 0 AND (' . $repeattime . ' or v.start_time >= \'' . $iToday . '\')')
                ->order('v.start_time ASC')                
                ->execute('getSlaveRows');
       	
        // Check privacy
        $aRows = $this->checkPrivacy($aRows, $bIsPage, $bIsProfile);
        $iTotal = 0;
        if (is_array($aRows) && count($aRows)) {
            $iTotal = count($aRows);
            //shuffle($aRows);
            $iIndex = 0;
            foreach ($aRows as $iKey => $aRow) {
                if ($iIndex === 7) {
                    break;
                }
                $iIndex++;
                $aUpcoming[] = $aRow;
            }
        }
        
        return array($iTotal, $aUpcoming);
    }

    public function getPast($bIsPage = false, $bIsProfile = false) {
        static $aPast = null;
        static $iTotal = null;

        if ($aPast !== null) {
            return array($iTotal, $aPast);
        }

        $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $aPast = array();

        $repeatday = "( v.isrepeat>-1 and v.timerepeat<=" . ($iToday) . ")";
        $repeattime = "(v.isrepeat>-1 and (v.timerepeat=0 or " . $repeatday . "))";

        $aRows = $this->database()->select('v.*, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('fevent'), 'v')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
                ->where('v.view_id = 0 AND (' . $repeattime . ' or (v.isrepeat=-1  and v.start_time < \'' . $iToday . '\'))')
                ->order('v.start_time DESC')
                ->execute('getSlaveRows');

        // Check privacy
        $aRows = $this->checkPrivacy($aRows, $bIsPage, $bIsProfile);

        $iTotal = 0;
        if (is_array($aRows) && count($aRows)) {
            $iTotal = count($aRows);
            //shuffle($aRows);
            $iIndex = 0;
            foreach ($aRows as $iKey => $aRow) {
                if ($iIndex === 7) {
                    break;
                }
                $iIndex++;
                $aPast[] = $aRow;
            }
        }

        return array($iTotal, $aPast);
    }

    public function getJsEvents($bIsPage = false, $bIsProfile = false)
    {
        $aRows = $this->database()->select('*')
                ->from($this->_sTable)
                ->where('view_id = 0')
                ->execute('getRows');

        $aRows = $this->checkPrivacy($aRows, $bIsPage, $bIsProfile);
        return $aRows;
    }

    public function getFeatured($bIsPage = false, $bIsProfile) {
        static $aFeatured = null;
        static $iTotal = null;

        if ($aFeatured !== null) {
            return array($iTotal, $aFeatured);
        }

        $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        
        $aFeatured = array();
        $repeatday = "( v.isrepeat>-1 and v.timerepeat>" . ($iToday) . ")";
        $repeattime = "(v.isrepeat>-1 and (v.timerepeat=0 or " . $repeatday . "))";
        
        $aRows = $this->database()->select('v.*, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('fevent'), 'v')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
                ->where('v.view_id = 0 AND v.is_featured = 1 AND (' . $repeattime . ' or v.start_time >= \'' . $iToday . '\')')
                ->order('v.start_time ASC')                
                ->execute('getSlaveRows');
       	
        // Check privacy
        $aRows = $this->checkPrivacy($aRows, $bIsPage, $bIsProfile);

        $iTotal = 0;
        if (is_array($aRows) && count($aRows)) {
            $iTotal = count($aRows);
            shuffle($aRows);
            $iIndex = 0;
            foreach ($aRows as $iKey => $aRow) 
            {                
                $aRow['convert_start_time'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aRow['start_time']);
        		if($aRow['isrepeat']==-1)
                    $aRow['convert_end_time'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $aRow['end_time']);
        		else
        		{
                    $content_repeat="";
                    $until="";
                    if($aRow['isrepeat']==0)
                    {
                        $content_repeat=Phpfox::getPhrase('fevent.daily');
                    }
                    else if($aRow['isrepeat']==1)
                    {
                        $content_repeat=Phpfox::getPhrase('fevent.weekly');
                    }
                    else if($aRow['isrepeat']==2)
                    {
                        $content_repeat=Phpfox::getPhrase('fevent.monthly');
                    }
                    if($content_repeat!="")
                    {
                        if($aRow['timerepeat']!=0)
            			{
                            $sDefault = null;
                            $until = Phpfox::getTime("M j, Y", $aRow['timerepeat']);
                            $content_repeat .= ", " . Phpfox::getPhrase('fevent.until') . " " . $until;
            			}
                    }		
                    $aRow['convert_end_time'] = $content_repeat;			
        		}
                                        
                if ($iIndex === 7) {
                    break;
                }
                $iIndex++;
                $aFeatured[] = $aRow;
            }
        }
        
        return array($iTotal, $aFeatured);
    }

    public function getForRssFeed() {
        $iTimeDisplay = Phpfox::getLib('phpfox.date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
        $aConditions = array();
        $aConditions[] = "e.view_id = 0 AND e.module_id = 'fevent' AND e.item_id = 0";
        $aConditions[] = "AND e.start_time >= '" . $iTimeDisplay . "'";

        $aRows = $this->database()->select('e.*, et.description_parsed AS description, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('fevent'), 'e')
                ->join(Phpfox::getT('fevent_text'), 'et', 'et.event_id = e.event_id')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->where($aConditions)
                ->order('e.time_stamp DESC')
                ->execute('getSlaveRows');

        foreach ($aRows as $iKey => $aRow) {
            $aRows[$iKey]['link'] = Phpfox::permalink('fevent', $aRow['event_id'], $aRow['title']);
            $aRows[$iKey]['creator'] = $aRow['full_name'];
        }

        return $aRows;
    }

    public function getImages($iId, $iLimit = null) {
        return $this->database()->select('image_id, image_path, server_id')
                        ->from(Phpfox::getT('fevent_image'))
                        ->where('event_id = ' . (int) $iId)
                        ->order('ordering ASC')
                        ->limit($iLimit)
                        ->execute('getSlaveRows');
    }

    public function getCustomFields($iParentId = 0) {
        $aFields = $this->database()->select('cf.*, fec.name AS category_name')
                ->from(Phpfox::getT('fevent_custom_field'), 'cf')
                ->leftJoin(Phpfox::getT('fevent_category'), 'fec', 'fec.category_id = cf.category_id')
                ->where("fec.parent_id = $iParentId")
                ->order('cf.ordering ASC')
                ->execute('getRows');

        $aCustomFields = array();
        foreach ($aFields as $aField) {
            $aCustomFields[$aField['category_id']][] = $aField;
        }

        $aCategories = $this->database()->select('fec.*')
                ->from(Phpfox::getT('fevent_category'), 'fec')
                ->where("fec.parent_id = $iParentId")
                ->order('fec.ordering ASC')
                ->execute('getRows');

        foreach ($aCategories as $iKey => $aCategory) {
            if (isset($aCustomFields[$aCategory['category_id']])) {
                $aCategories[$iKey]['child'] = $aCustomFields[$aCategory['category_id']];
            }
            $aSubs = $this->getCustomFields($aCategory['category_id']);
            if (isset($aSubs[0])) {
                $aCategories[$iKey]['subs'] = $aSubs;
            }
        }

        if (isset($aCustomFields[0])) {
            $aCategories['PHPFOX_EMPTY_GROUP']['child'] = $aCustomFields[0];
        }

        return $aCategories;
    }

    public function execute($iPageId = 0)
    {
        $aActualConditions = (array)Phpfox::getLib('search')->getConditions();
        $this->_aConditions = array();
        $sWhen = Phpfox::getLib('request')->get('when');

        $this->_sView = Phpfox::getLib('request')->get('view');
        foreach ($aActualConditions as $iKey => $sCond)
        {
            switch ($this->_sView)
            {
                case 'friend':
                    $sCond = str_replace('%PRIVACY%', '0,1,2', $sCond);
                    break;
                case 'my':
                    $sCond = str_replace('%PRIVACY%', '0,1,2,3,4', $sCond);
                    break;
                default:
                    $sCond = str_replace('%PRIVACY%', '0', $sCond);
                    break;
            }
            
            if ($sWhen == "upcoming")
            {
                $position = strpos($sCond, "AND m.start_time");
                if ($position !== false)
                {
                    $sCond = ' AND ((m.isrepeat > -1 and (m.timerepeat = 0 or m.timerepeat > ' . PHPFOX_TIME . ')) or m.start_time > \'' . PHPFOX_TIME . '\')';
                }
            }
            
            if ($sWhen == 'this-month')
            {
                $position = strpos($sCond, "AND m.start_time");
                if ($position !== false)
                {
                    $iStartMonth = Phpfox::getLib('date')->mktime(0, 0, 0, date('m'), 1, date('Y'));
                    $iEndMonth = Phpfox::getLib('date')->mktime(23, 59, 59, date('m'), date('t'), date('Y'));
                    $gmt_offset = Phpfox::getTimeZone()*3600;
                    
                    $no_repeat = "(m.isrepeat =-1 AND m.start_time+$gmt_offset <= $iEndMonth AND m.start_time+$gmt_offset >= $iStartMonth)";
                    $daily     = "(m.isrepeat = 0 AND m.start_time+$gmt_offset <= $iEndMonth AND m.timerepeat+$gmt_offset >= $iStartMonth)";
                    $weekly    = "(m.isrepeat = 1 AND m.start_time+$gmt_offset <= $iEndMonth AND m.start_time+FLOOR((m.timerepeat-m.start_time)/604800)*604800+$gmt_offset >= $iStartMonth)";
                    $monthly   = "(m.isrepeat = 2 AND m.start_time+$gmt_offset <= $iEndMonth AND m.timerepeat+$gmt_offset >= $iStartMonth AND (
                        (m.timerepeat+$gmt_offset >= $iEndMonth AND DAY(CONVERT_TZ(FROM_UNIXTIME(m.start_time+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) <= DAY(CONVERT_TZ(FROM_UNIXTIME($iEndMonth-$gmt_offset+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00'))) OR 
                        (m.timerepeat+$gmt_offset < $iEndMonth AND DAY(CONVERT_TZ(FROM_UNIXTIME(m.start_time+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) <= DAY(CONVERT_TZ(FROM_UNIXTIME(m.timerepeat+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')))
                    ))";
                    
                    $sCond = " AND (" . $daily . " OR " . $weekly . " OR " . $monthly . " OR " . $no_repeat . ")";
                }
            }
            
            if ($sWhen == 'this-week')
            {
                $position = strpos($sCond, "AND m.start_time");
                if ($position !== false)
                {
                    $iStartWeek = (date('w') == 0) ? strtotime('monday last week') : strtotime('monday this week');
                    $iEndWeek = $iStartWeek + 604800 - 1;
                    $gmt_offset = Phpfox::getTimeZone()*3600;
                    
                    $no_repeat = "(m.isrepeat =-1 AND m.start_time+$gmt_offset <= $iEndWeek AND m.start_time+$gmt_offset >= $iStartWeek)";
                    $daily     = "(m.isrepeat = 0 AND m.start_time+$gmt_offset <= $iEndWeek AND m.timerepeat+$gmt_offset >= $iStartWeek)";
                    $weekly    = "(m.isrepeat = 1 AND m.start_time+$gmt_offset <= $iEndWeek AND m.start_time+FLOOR((m.timerepeat-m.start_time)/604800)*604800+$gmt_offset >= $iStartWeek)";
                    $monthly   = "(m.isrepeat = 2 AND m.start_time+$gmt_offset <= $iEndWeek AND m.timerepeat+$gmt_offset >= $iStartWeek AND DAY(CONVERT_TZ(FROM_UNIXTIME(m.start_time+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) >= DAY(CONVERT_TZ(FROM_UNIXTIME($iStartWeek-$gmt_offset+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) AND (
                        (m.timerepeat+$gmt_offset >= $iEndWeek AND DAY(CONVERT_TZ(FROM_UNIXTIME(m.start_time+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) <= DAY(CONVERT_TZ(FROM_UNIXTIME($iEndWeek-$gmt_offset+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00'))) OR 
                        (m.timerepeat+$gmt_offset < $iEndWeek AND DAY(CONVERT_TZ(FROM_UNIXTIME(m.start_time+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) <= DAY(CONVERT_TZ(FROM_UNIXTIME(m.timerepeat+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')))
                    ))";
                    
                    $sCond = " AND (" . $daily . " OR " . $weekly . " OR " . $monthly . " OR " . $no_repeat . ")";
                }
            }
            
            if ($sWhen == 'today')
            {
                $position = strpos($sCond, "AND (m.start_time");
                if ($position !== false)
                {
                    $iStartDay = strtotime(date('Y-m-d 00:00:00'));
                    $iEndDay = strtotime(date('Y-m-d 23:59:59'));
                    $gmt_offset = Phpfox::getTimeZone()*3600;
                    
                    $no_repeat = "(m.isrepeat =-1 AND m.start_time+$gmt_offset <= $iEndDay AND m.start_time+$gmt_offset >= $iStartDay)";
                    $daily     = "(m.isrepeat = 0 AND m.start_time+$gmt_offset <= $iEndDay AND m.timerepeat+$gmt_offset >= $iStartDay)";
                    $weekly    = "(m.isrepeat = 1 AND m.start_time+$gmt_offset <= $iEndDay AND m.timerepeat+$gmt_offset >= $iStartDay AND DAYOFWEEK(CONVERT_TZ(FROM_UNIXTIME(m.start_time+$gmt_offset),@@session.time_zone,'+00:00')) = DAYOFWEEK(CONVERT_TZ(FROM_UNIXTIME($iStartDay),@@session.time_zone,'+00:00')))";
                    $monthly   = "(m.isrepeat = 2 AND m.start_time+$gmt_offset <= $iEndDay AND m.timerepeat+$gmt_offset >= $iStartDay AND DAY(CONVERT_TZ(FROM_UNIXTIME(m.start_time+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) = DAY(CONVERT_TZ(FROM_UNIXTIME($iStartDay-$gmt_offset+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')))";
                    
                    $sCond = " AND (" . $daily . " OR " . $weekly . " OR " . $monthly . " OR " . $no_repeat . ")";
                }
            }
            
            $this->_aConditions[] = $sCond;
        }

        if ($sWhen == 'past')
        {
            $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $repeatday = "( m.isrepeat>-1 and m.timerepeat<=" . ($iToday) . ")";
            $repeattime = "(m.isrepeat>-1 and (m.timerepeat=0 or " . $repeatday . "))";

            $this->_aConditions[] = ' and (' . $repeattime . ' or (m.isrepeat=-1  and m.start_time < \'' . $iToday . '\'))';
        }
        
        $sLocation = urldecode(Phpfox::getLib('request')->get('location'));
        if (strlen(trim($sLocation))>0)
        {
            $sLocation = Phpfox::getLib('parse.input')->prepare($sLocation);
            
            $this->_aConditions[] = " AND (m.location LIKE '%$sLocation%')";
        }
        
        $sCity = urldecode(Phpfox::getLib('request')->get('city'));
        if (strlen(trim($sCity))>0)
        {
            $sCity = Phpfox::getLib('parse.input')->prepare($sCity);

            $this->_aConditions[] = " AND (m.city LIKE '%$sCity%')";
        }

        $sZipCode = urldecode(Phpfox::getLib('request')->get('zipcode'));
        if (strlen(trim($sZipCode))>0)
        {
            $sZipCode = Phpfox::getLib('parse.input')->prepare($sZipCode);

            $this->_aConditions[] = " AND (m.postal_code LIKE '%$sZipCode%')";
        }
       
        $srangevaluefrom = urldecode(Phpfox::getLib('request')->get('rangevaluefrom'));
        if (strlen(trim($srangevaluefrom))>0)
        {
            $rangevaluefrom = Phpfox::getLib('parse.input')->prepare($srangevaluefrom);
            preg_match("/[0-9]*/", $rangevaluefrom,$kq);
           
            if($kq==null || strlen(trim($kq[0]))<strlen(trim($rangevaluefrom)))
            {
                $this->_aConditions[] = " AND (1=0)";
            }
            else
            {
                $rangevaluefrom = (int) $rangevaluefrom;
                $rangetype = Phpfox::getLib('request')->get('rangetype');
                if ($rangetype == 1)
                {
                    $rangevaluefrom = $rangevaluefrom * 1000;
                }
                elseif($rangetype == 0)
                {
                    $rangevaluefrom = $rangevaluefrom * 1609;
                }
                $this->_aConditions[] = " AND (m.range_value_real >= '$rangevaluefrom')";
            }
        }
        
        $srangevalueto = urldecode(Phpfox::getLib('request')->get('rangevalueto'));
        if (strlen(trim($srangevalueto))>0)
        {
            $rangevalueto = Phpfox::getLib('parse.input')->prepare($srangevalueto);
            preg_match("/[0-9]*/", $srangevalueto,$kq);
            if($kq==null || strlen(trim($kq[0]))<strlen(trim($srangevalueto)))
            {
                $this->_aConditions[] = " AND (1=0)";
            }
            else
            {
                $rangevalueto = (int) $rangevalueto;
                $rangetype = Phpfox::getLib('request')->get('rangetype');
                if ($rangetype == 1)
                {
                    $rangevalueto = $rangevalueto * 1000;
                }
                elseif($rangetype==0)
                {
                    $rangevalueto = $rangevalueto * 1609;
                }
                $this->_aConditions[] = " AND (m.range_value_real <= '$rangevalueto')";
            }
        }

        if ($sCountry = Phpfox::getLib('request')->get('country'))
        {
            $sCountry = Phpfox::getLib('parse.input')->prepare($sCountry);

            $this->_aConditions[] = " AND (m.country_iso LIKE '%$sCountry%')";
        }

        if ($icountry_child_id = Phpfox::getLib('request')->get('childid'))
        {
            if ($icountry_child_id > 0)
            {
                $this->_aConditions[] = " AND (m.country_child_id = " . $icountry_child_id . ")";
            }
        }

        if (Phpfox::getLib('request')->get('date'))
        {
            $sDate = Phpfox::getLib('request')->get('date');
            preg_match('/(\d+)\-(\d+)\-(\d+)/', $sDate, $aMatches);
            if (!empty($aMatches[3]))
            {
                $iStartDay = Phpfox::getLib('date')->mktime(0, 0, 0, intval($aMatches[2]), intval($aMatches[3]), intval($aMatches[1]));
                $iEndDay = Phpfox::getLib('date')->mktime(23, 59, 0, intval($aMatches[2]), intval($aMatches[3]), intval($aMatches[1]));
                $gmt_offset = Phpfox::getTimeZone()*3600;
               
                $no_repeat = "(m.isrepeat =-1 AND m.start_time+$gmt_offset <= $iEndDay AND m.start_time+$gmt_offset >= $iStartDay)";
                $daily     = "(m.isrepeat = 0 AND m.start_time+$gmt_offset <= $iEndDay AND m.timerepeat+$gmt_offset >= $iStartDay)";
                $weekly    = "(m.isrepeat = 1 AND m.start_time+$gmt_offset <= $iEndDay AND m.timerepeat+$gmt_offset >= $iStartDay AND DAYOFWEEK(CONVERT_TZ(FROM_UNIXTIME(m.start_time+$gmt_offset),@@session.time_zone,'+00:00')) = DAYOFWEEK(CONVERT_TZ(FROM_UNIXTIME($iStartDay),@@session.time_zone,'+00:00')))";
                $monthly   = "(m.isrepeat = 2 AND m.start_time+$gmt_offset <= $iEndDay AND m.timerepeat+$gmt_offset >= $iStartDay AND DAY(CONVERT_TZ(FROM_UNIXTIME(m.start_time+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')) = DAY(CONVERT_TZ(FROM_UNIXTIME($iStartDay-$gmt_offset+(m.start_gmt_offset*3600)),@@session.time_zone,'+00:00')))";
                
                $this->_aConditions[] = " AND (" . $daily . " OR " . $weekly . " OR " . $monthly . " OR " . $no_repeat . ")";
            }
        }
        
        Phpfox::getService('fevent.browse')->getQueryJoins(true);

        $this->_iCnt = $this->database()->select('COUNT(m.event_id) ')
            ->from($this->_sTable, 'm')
            ->where($this->_aConditions)
            ->execute('getSlaveField');

        $this->_aRows = array();
        if ($this->_iCnt)
        {
            Phpfox::getService('fevent.browse')->getQueryJoins();
            $this->database()->from($this->_sTable, 'm')->where($this->_aConditions);
            Phpfox::getService('fevent.browse')->query();
            
            if($sWhen == 'past')
            {
                $order = 'm.start_time DESC';
            }
            else
            {
                $order = Phpfox::getLib('search')->getSort();
            }
            
            $this->_aRows = $this->database()->select('m.*,u.*')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = m.user_id')
                ->order($order)
                ->limit(Phpfox::getLib('search')->getPage(), Phpfox::getLib('search')->getDisplay(), $this->_iCnt)
                ->execute('getSlaveRows');
        }

        Phpfox::getService('fevent.browse')->processRows($this->_aRows);
        return $this->_aRows;
    }

    public function getCount() {
        return $this->_iCnt;
    }

    public function checkPrivacy($aRows, $bIsPage = false, $bIsProfile = false)
    {
        if (!is_array($aRows))
        {
            return $aRows;
        }
        
        $iUserId = Phpfox::getUserId();
        
        $sView = $this->request()->get('view');
        
        $aOutput = array();
        foreach ($aRows as $iKey => $aRow)
        {
            $bIsPage = $bIsPage ? $bIsPage : 0;
            
            if ($bIsProfile !== false && $aRow['user_id'] != $bIsProfile)
            {
                continue;
            }
            
            if ($bIsProfile === false && $aRow['item_id'] != $bIsPage)
            {
                continue;
            }
            
            $iRsvp = $this->database()->select('rsvp_id')->from(Phpfox::getT('fevent_invite'))->where('event_id = '.(int)$aRow['event_id'].' AND invited_user_id = '.(int)$iUserId)->execute('getField');
            if(!in_array($iRsvp, array('0', '1', '2', '3')))
            {
                $iRsvp = '-1';
            }
            
            switch($sView)
            {
                case 'my':
                    if($iUserId == $aRow['user_id'])
                    {
                        $aOutput[] = $aRow;
                    }
                    break;
                case 'friend':
                    if(in_array($aRow['privacy'], array('0', '1', '2')) && Phpfox::getService('friend')->isFriend($aRow['user_id'], $iUserId))
                    {
                        $aOutput[] = $aRow;
                    }
                    break;
                case 'featured':
                    if($aRow['is_featured'] && $aRow['privacy'] == '0')
                    {
                        $aOutput[] = $aRow;
                    }
                    break;
                case 'attending':
                    if($iRsvp == '1')
                    {
                        $aOutput[] = $aRow;
                    }
                    break;
                case 'may-attend':
                    if($iRsvp == '2')
                    {
                        $aOutput[] = $aRow;
                    }
                    break;
                case 'not-attending':
                    if($iRsvp == '3')
                    {
                        $aOutput[] = $aRow;
                    }
                    break;
                case 'invites':
                    if($iRsvp == '0')
                    {
                        $aOutput[] = $aRow;
                    }
                    break;
                default:
                    if($aRow['privacy'] == '0')
                    {
                        $aOutput[] = $aRow;
                    }
            }
        }

        return $aOutput;
    }

    public function updateSetting($name, $default_value) {
        $aRows = phpfox::getLib("database")->select('*')->from(phpfox::getT('fevent_setting'))
                ->where('name="' . $name . '"')
                ->execute('getSlaveRows');
        $oFilter = Phpfox::getLib('parse.input');

        if (count($aRows) == 0) {
            $aInserts = array();
            $aInserts['name'] = $name;
            $aInserts['default_value'] = $oFilter->clean($default_value);
            phpfox::getLib("database")->insert(phpfox::getT('fevent_setting'), $aInserts);
        } else {
            $aUpdates = array();
            $aUpdates['default_value'] = $oFilter->clean($default_value);
            phpfox::getLib("database")->update(phpfox::getT('fevent_setting'), $aUpdates, 'name="' . $name . '"');
        }
    }

    public function getSetting($name) {
        $aRow = phpfox::getLib("database")->select('*')->from(phpfox::getT('fevent_setting'))
                ->where('name="' . $name . '"')
                ->execute('getSlaveRow');
        return $aRow;
    }

    public function getAllEventPhpfox() {
        $aRows = phpfox::getLib("database")->select('*')->from(phpfox::getT('event'))
                ->execute('getSlaveRows');
        return $aRows;
    }

    public function getAllCategorydataPhpfox($event_id) {
        $aRow = phpfox::getLib("database")->select('*')
                ->from(phpfox::getT('event_category_data'))
                ->where('event_id=' . $event_id)
                ->execute('getSlaveRow');
        return $aRow;
    }

    public function getAllFeedEventPhpfox($event_id) {
        $aRows = phpfox::getLib("database")->select('*')
                ->from(phpfox::getT('event_feed'))
                ->where('parent_user_id=' . $event_id)
                ->execute('getSlaveRows');
        return $aRows;
    }

    public function getFeedCommentPhpfox($item_id) {
        $aRow = phpfox::getLib("database")->select('*')
                ->from(phpfox::getT('event_feed_comment'))
                ->where('feed_comment_id=' . $item_id)
                ->execute('getSlaveRow');
        return $aRow;
    }

    public function getEventTextPhpfox($event_id) {
        $aRow = phpfox::getLib("database")->select('*')
                ->from(phpfox::getT('event_text'))
                ->where('event_id=' . $event_id)
                ->execute('getSlaveRow');
        return $aRow;
    }

    public function getInviteEventPhpfox($event_id) {
        $aRows = phpfox::getLib("database")->select('*')
                ->from(phpfox::getT('event_invite'))
                ->where('event_id=' . $event_id)
                ->execute('getSlaveRows');
        return $aRows;
    }

    public function getIdEventLast() {
        $aRows = phpfox::getLib("database")->select('*')->from(phpfox::getT('fevent'))
                ->limit(1)
                ->order('event_id desc')
                ->execute('getSlaveRows');
        if (count($aRows) == 0)
            return 0;
        else
            return $aRows[0]['event_id'] + 10;
    }

    public function buildRRule($aEvent)
    {
        $rRule = "";
        $gmt_offset = $aEvent['start_gmt_offset']*3600;
        $start = $aEvent['start_time'] + $gmt_offset;
        $end = $aEvent['timerepeat'] + $gmt_offset;
        $isrepeat = $aEvent['isrepeat'];
        
        switch($isrepeat)
        {
            case 0:
                $rRule = "\nRRULE:FREQ=DAILY;COUNT=".(floor(($end - $start)/86400) + 1);
                break;
            case 1:
                $by_day = strtoupper(substr(date('D', $start), 0, 2));
                $rRule = "\nRRULE:FREQ=WEEKLY;COUNT=".(floor(($end - $start)/604800) + 1).";BYDAY=".$by_day;
                break;
            case 2:
                $hour = date('H', $start);
                $minute = date('i', $start);
                $day = date('d', $start);
                $month = date('m', $start);
                $year = date('Y', $start);
                
                $cnt = 0;
                while ($start <= $end)
                {
                    $cnt++;
                    do
                    {
                        $month++;
                        if($month == 13)
                        {
                            $month = 1;
                            $year++;
                        }
                        $start = mktime($hour, $minute, 0, $month, $day, $year);
                    }
                    while(date('d', $start) != $day);
                }
                
                $rRule = "\nRRULE:FREQ=MONTHLY;COUNT=".$cnt.";BYMONTHDAY=".$day;
        }
        
        return $rRule;
    }
    
    public function getJdpickerPhrases()
    {
        $sPhrases = "";
        $aVarNames = array(
            'fevent.january',
            'fevent.february',
            'fevent.march',
            'fevent.april',
            'fevent.may',
            'fevent.june',
            'fevent.july',
            'fevent.august',
            'fevent.september',
            'fevent.october',
            'fevent.november',
            'fevent.december',
            'fevent.weekday_sunday',
            'fevent.weekday_monday',
            'fevent.weekday_tuesday',
            'fevent.weekday_wednesday',
            'fevent.weekday_thursday',
            'fevent.weekday_friday',
            'fevent.weekday_saturday');
        
        foreach ($aVarNames as $sVarName)
        {
            $sPhrases .= "\noTranslations['$sVarName'] = '" . str_replace("'", "\\'", Phpfox::getPhrase($sVarName)) . "';";
        }
        
        return $sPhrases;
    }

    /**
     * If a call is made to an unknown method attempt to connect
     * it to a specific plug-in with the same name thus allowing 
     * plug-in developers the ability to extend classes.
     *
     * @param string $sMethod is the name of the method
     * @param array $aArguments is the array of arguments of being passed
     */
    public function __call($sMethod, $aArguments) {
        /**
         * Check if such a plug-in exists and if it does call it.
         */
        if ($sPlugin = Phpfox_Plugin::get('fevent.service_event__call')) {
            return eval($sPlugin);
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

}

?>