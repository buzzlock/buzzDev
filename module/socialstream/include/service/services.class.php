<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Service_Services extends Phpfox_Service
{

	private $_isAdminCanViewAllFeeds = 0;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this -> _isAdminCanViewAllFeeds = FALSE;
	}
	

	public function checkActivityFeedBlock($sConnection)
	{
		return (int)$this -> database() -> select('count(*)') -> from(Phpfox::getT('block'), 'b') -> where('b.is_active = 0 AND b.module_id = "feed" AND b.component = "display" AND b.m_connection = "' . $sConnection . '"') -> execute('getField');
	}

	/**
     * Check if user can view the social stream feed
     */
	public function canView($iUserId = null, $iPrivacy = null)
	{
		if ($iUserId == null || $iPrivacy == null)
			return false;

		if (Phpfox::getUserId() == (int)$iUserId)
		{
			return true;
		}

		switch ($iPrivacy)
		{
			case 0 :
				return true;
			case 1 :
				//Friend
				return Phpfox::getService('friend') -> isFriend(Phpfox::getUserId(), $iUserId);
			case 2 :
				//Friend of friend
				return Phpfox::getService('friend') -> isFriend(Phpfox::getUserId(), $iUserId) || Phpfox::getService('friend') -> isFriendOfFriend($iUserId);
			case 3 :
				//Only me
				return Phpfox::getUserId() == $iUserId;
			default :
				return false;
		}
	}

	public function getFeedTypes_OnlySocialStream($iUserId = null)
	{
		$aProviders = Phpfox::getService('socialbridge') -> getAllProviderData(Phpfox::getUserId());

        if(!array_key_exists('facebook', $aProviders))
        {
            $aProviders['facebook'] = null;
        }
        if(!array_key_exists('twitter', $aProviders))
        {
            $aProviders['twitter'] = null;
        }

		$aFeedTypes = array("all" => Phpfox::getPhrase('socialstream.all_feeds'));

		if ($aProviders['facebook'] && $aProviders['facebook']['connected'])
		{
            $aFacebookSetting = $this->getSetting('facebook', $iUserId, $aProviders['facebook']['profile']['identity']);
            if(isset($aFacebookSetting['enable']) && $aFacebookSetting['enable'] == 1)
            {
			    $aFeedTypes['socialstream_facebook'] = Phpfox::getPhrase('socialstream.facebook');
            }
		}

		if ($aProviders['twitter'] && $aProviders['twitter']['connected'])
		{
            $aTwitterSetting = $this->getSetting('twitter', $iUserId, $aProviders['twitter']['profile']['identity']);
            if(isset($aTwitterSetting['enable']) && $aTwitterSetting['enable'] == 1)
            {
                $aFeedTypes['socialstream_twitter'] = Phpfox::getPhrase('socialstream.twitter');
            }
		}

		$aFeedTypes['network_only'] = Phpfox::getPhrase('socialstream.network_only');
		return $aFeedTypes;
	}

	/**
	 * @param string $sService
	 * @param int $iUserId
	 * @param string $sService
	 * @return array
	 */
	public function getSetting($sService, $iUserId, $sIdentity)
	{
		$sTable = Phpfox::getT('socialstream_setting');
		$sWhere = "user_id='{$iUserId}' AND service='{$sService}' AND identity='{$sIdentity}'";
		$aRow = $this -> database() -> select('*') -> from($sTable) -> where($sWhere) -> execute('getSlaveRow');

		if ($aRow)
		{
			return $aRow;
		}

		$this -> database() -> insert($sTable, array(
			'user_id' => $iUserId,
			'identity' => $sIdentity,
			'service' => $sService
		));

		$aRow = $this -> database() -> select('*') -> from($sTable) -> where($sWhere) -> execute('getSlaveRow');

		return $aRow;
	}

	/**
	 * @param array $aParams
	 * @param int $iSettingId OPtional
	 * @return void
	 *
	 */
	public function updateSetting($aParams, $iSettingId = NULL)
	{
		if ($iSettingId == NULL)
		{
			$iSettingId = $aParams['setting_id'];
		}

		if (isset($aParams['setting_id']))
		{
			unset($aParams['setting_id']);
		}

		$sTable = Phpfox::getT('socialstream_setting');
		$this -> database() -> update($sTable, $aParams, "setting_id={$iSettingId}");
	}

	/**
	 * @param int $iUserId
	 * @param string $sService
	 * @param int $iLimit
	 */
	public function getFeed($iUserId, $sService, $iLimit = NULL)
	{
		if (!Phpfox::isModule('socialbridge'))
		{
			return FALSE;
		}

		if ($iUserId == NULL)
		{
			return FALSE;
		}

		#Check ban status
		$aUser = Phpfox::getService('user') -> getUser($iUserId);
		$aBanned = Phpfox::getService('ban') -> isUserBanned($aUser);
		if (isset($aBanned['is_banned']) && $aBanned['is_banned'] == 1)
		{
			return FALSE;
		}

		#Check token & profile
        list($aToken, $aProfile) = Phpfox::getService('socialbridge') -> getTokenData($sService, $iUserId);
		if (!$aToken || !$aProfile)
		{
			return FALSE;
		}

		$sIdentity = $aProfile['identity'];
		$aSetting = $this -> getSetting($sService, $iUserId, $sIdentity);
		$iPrivacy = $aSetting['privacy'];
		$iLastFeed = $aSetting['lastfeed_timestamp'];

		Phpfox_Error::skip(TRUE);

		if ($iLimit == null)
		{
			$iLimit = (int)Phpfox::getParam('socialstream.maximum_feeds_per_time');
		}

		Phpfox_Error::skip(FALSE);

		if ($iLimit <= 0)
		{
			$iLimit = 5;
		}
		elseif ($iLimit > 200)
		{
			$iLimit = 200;
		}

		try
		{
			$aResults = array();

			switch ($sService)
			{
				case 'facebook' :
					$aResults = Phpfox::getService('socialbridge.provider.facebook') -> getFeeds($iLastFeed, $iLimit, $iUserId);
					if (count($aResults) && is_array($aResults))
					{
						$aResults = array_reverse($aResults);
					}
					break;
				case 'twitter' :
                    $iMaxGet = ($iLastFeed == 0) ? $iLimit : 200;
                    if($iLastFeed == 0)
                    {
                        $iLastFeed = null;
                    }
                    $aDatas = Phpfox::getService('socialbridge.provider.twitter') -> getFeeds($iLastFeed, $iMaxGet, 1, $sIdentity, $iUserId);
                    if (count($aDatas) && !isset($aDatas['error']))
					{
						$aResults = array_slice($aDatas, -$iLimit, $iLimit);
						$iLastFeed = $aResults[0]['id_str'];
					}
					break;
			}

			if ($aResults)
			{
				Phpfox::getService('socialstream.process') -> addFeed($iUserId, $sService, $aResults, $iPrivacy, $aToken, $aProfile, $sIdentity, $aSetting);
                
                $aSetting['lastfeed_timestamp'] = ($sService=='twitter') ? $iLastFeed : time(); //twitter: last feed id, facebook: last feed timestamp
				$aSetting['lastcheck_timestamp'] = time();
				$this -> updateSetting($aSetting);

				return TRUE;
			}
			return FALSE;
		}
		catch (Exception $ex)
		{
			$aResponse['error'] = $ex -> getMessage();
			$aResponse['apipublisher'] = $sService;
		}
		return $aResponse;
	}

	/**
	 * @TODO replace some function in agents class
	 * @param null $iUserId
	 * @return bool|int
	 */
    public function isLogged($iUserId = null)
    {
        if ($iUserId == null)
        {
            return false;
        }
        
        return (int)$this -> database() -> select('count(*)') -> from(Phpfox::getT('socialbridge_token'), 'sbt') -> join(Phpfox::getT('socialbridge_services'), 'sbs', 'sbt.service = sbs.name') -> where('sbt.user_id = ' . $iUserId . ' AND (sbs.name = "facebook" OR sbs.name = "twitter")') -> execute('getSlaveField');
    }

    public function getAllAgents()
    {
        return $this->database()->select('st.user_id AS user_id, st.service AS service_name')
            ->from(Phpfox::getT('socialbridge_token'), 'st')
            ->join(Phpfox::getT('socialstream_services'), 'ss', 'ss.name = st.service')
            ->execute('getRows');
    }

	/**
	 * @param null $iFacebookUserId
	 * @return string
	 */
    public function getFaceBookPicture($iIdentity = null)
    {
        if ($iIdentity == null || (int)$iIdentity <= 0)
        {
            return "";
        }
        
        $imgLink = "http://graph.facebook.com/%s/picture";
        $imgLink = sprintf($imgLink, $iIdentity);
        return $imgLink;
    }

    /**
     * @return array
     * @author AnNT
     */
    public function getProviders()
    {
        return $this->database()->select('service_id, name, title')->from(Phpfox::getT('socialstream_services'))->execute('getSlaveRows');
    }
    
    /**
     * @param int[optional] $iStartTime, int[optional] $iEndTime, int[optional] $iPage, int[optional] $iLimit
     * @return array
     * @author AnNT
     */
    public function getStatsByDate($iStartTime = null, $iEndTime = null, $iPage = 1, $iLimit = 10)
    {
        $aServices = $this->getProviders();
        
        $sCond = '';
        if($iStartTime > 0 && $iEndTime > 0)
        {
            $sCond = 'f.time_stamp >= '.$iStartTime.' AND f.time_stamp <= '.$iEndTime;
        }
        
        $sSelect = 'DATE(CONVERT_TZ(FROM_UNIXTIME(f.time_stamp),@@session.time_zone,"+00:00")) AS feeds_date';
        
        $aDates = $this->database()->select($sSelect)
            ->from(Phpfox::getT('socialstream_feeds'), 'f')
            ->where($sCond)
            ->group('feeds_date')
            ->execute('getSlaveRows');
        $iCnt = count($aDates);
        
        if($iCnt == 0 || $aServices == null)
        {
            return array(0, array());
        }
        
        foreach($aServices as $aService)
        {
            $sSelect .= ', SUM(IF(s.name="'.$aService['name'].'", 1, 0)) AS '.$aService['name'];
        }        
        
        $aRows = $this->database()->select($sSelect)
            ->from(Phpfox::getT('socialstream_feeds'), 'f')
            ->join(Phpfox::getT('socialstream_services'), 's', 's.service_id = f.service_id')
            ->where($sCond)
            ->group('feeds_date')
            ->order('feeds_date DESC')
            ->limit($iPage, $iLimit, $iCnt)
            ->execute('getSlaveRows');
            
        return array($iCnt, $aRows);
    }
    
    /**
     * @param string[optional] $sKeyword, string[optional] $sType, int[optional] $iPage, int[optional] $iLimit
     * @return array
     * @author AnNT
     */
    public function getStatsByUser($sKeyword = null, $sType = null, $iPage = 1, $iLimit = 10)
    {
        $aServices = $this->getProviders();
        
        $sCond = '';
        if(!empty($sKeyword) && !empty($sType))
        {
            switch($sType)
            {
                case 'en':
                    $sCond = 'u.email LIKE "%'.$sKeyword.'%" OR u.full_name LIKE "%'.$sKeyword.'%"';
                    break;
                case 'e':
                    $sCond = 'u.email LIKE "%'.$sKeyword.'%"';
                    break;
                case 'n':
                    $sCond = 'u.full_name LIKE "%'.$sKeyword.'%"';
            }
        }
        
        $sSelect = 'f.user_id';
        
        $aUsers = $this->database()->select($sSelect)
            ->from(Phpfox::getT('socialstream_feeds'), 'f')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = f.user_id')
            ->where($sCond)
            ->group('f.user_id')
            ->execute('getSlaveRows');
        $iCnt = count($aUsers);
        
        if($iCnt == 0 || $aServices == null)
        {
            return array(0, array());
        }

        foreach($aServices as $aService)
        {
            $sSelect .= ', SUM(IF(s.name="'.$aService['name'].'", 1, 0)) AS '.$aService['name'];
        }        
        
        $aRows = $this->database()->select($sSelect.', '.Phpfox::getUserField())
            ->from(Phpfox::getT('socialstream_feeds'), 'f')
            ->join(Phpfox::getT('socialstream_services'), 's', 's.service_id = f.service_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = f.user_id')
            ->where($sCond)
            ->group('f.user_id')
            ->order('f.user_id ASC')
            ->limit($iPage, $iLimit, $iCnt)
            ->execute('getSlaveRows');
            
        return array($iCnt, $aRows);
    }
    
    /**
     * Copy from parse.output lib
     */
    public function replaceUserTag($sStr)
	{
		$sStr = preg_replace('/\[x=(\d+)\].+?\[\/x\]/ise', "''.stripslashes(\$this->_parseUserTagged('$1')).''", $sStr);
		
		return $sStr;
	}
    
    /**
     * Parses users from tags by querying the DB and getting their full name.
     * Copy from parse.output lib, remove link to user profile
     */
	private function _parseUserTagged($iUser)
	{
		$aUser = $this->database()->select('up.user_value, u.full_name, user_name')
			->from(Phpfox::getT('user'), 'u')
			->leftjoin(Phpfox::getT('user_privacy'), 'up', 'up.user_id = u.user_id AND up.user_privacy = \'user.can_i_be_tagged\'')
			->where('u.user_id = ' . (int) $iUser)
			->execute('getSlaveRow');

		$sOut = '';
		if (isset($aUser['user_value']) && !empty($aUser['user_value']) && $aUser['user_value'] > 2)
		{
			$sOut = $aUser['full_name'];
		}
		else
		{
			if (isset($aUser['user_name']))
			{
				$sOut = $aUser['full_name'];
			}
		}
		
		return $sOut;
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
		if ($sPlugin = Phpfox_Plugin::get('socialstream.service_services__call'))
		{
			return eval($sPlugin);
		}

		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}

}
?>
