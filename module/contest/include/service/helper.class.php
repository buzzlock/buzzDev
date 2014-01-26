<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Service_Helper extends Phpfox_service
{

	/**
	 * to create left sub menu for a controller 
	 * <pre>
	 * Phpfox::getService('fundraising')->buildMenu();
	 * </pre>
	 * @by minhta
	 */
	public function buildMenu() {
		$aFilterMenu = array(
			Phpfox::getPhrase('contest.all_contests')=> '',
			Phpfox::getPhrase('contest.my_contests') => 'my',
		);

		if (!Phpfox::getParam('core.friends_only_community') && Phpfox::isModule('friend')) {
			$aFilterMenu[Phpfox::getPhrase('contest.friends_contests')] = 'friend';
		}

		if (Phpfox::getUserParam('contest.can_approve_contest')) {
			$iPendingTotal = Phpfox::getService('contest.contest')->getTotalPendings();
			
			if ($iPendingTotal) {
				$aFilterMenu[Phpfox::getPhrase('contest.pending_contests') . (Phpfox::getUserParam('contest.can_approve_contest') ? '<span class="pending">' . $iPendingTotal . '</span>' : 0)] = 'pending';
			}
		}

		$aFilterMenu[] = true;

		$aFilterMenu[Phpfox::getPhrase('contest.featured_contests')] = 'featured';
		$aFilterMenu[Phpfox::getPhrase('contest.premium_contests')] = 'premium';
		$aFilterMenu[Phpfox::getPhrase('contest.ending_soon_contests')] = 'ending_soon';
		$aFilterMenu[Phpfox::getPhrase('contest.closed_contests')] = 'closed';
		$aFilterMenu[Phpfox::getPhrase('contest.my_following_contests')] = 'my_following';
        $aFilterMenu[Phpfox::getPhrase('contest.my_favorite_contests')] = 'my_favorite';

		$aFilterMenu[] = true;

		$aFilterMenu[Phpfox::getPhrase('contest.my_entries')] = 'my_entries';
		$aFilterMenu[Phpfox::getPhrase('contest.pending_entries')] = 'pending_entries';

		Phpfox::getLib('template')->buildSectionMenu('contest', $aFilterMenu);
	}

	public function getPhrasesForValidator()
	{
		return array(
			'contest.this_field_is_required',
			'contest.please_enter_an_amount_greater_or_equal',
			'contest.please_enter_a_value_with_a_valid_extension',
			'contest.please_enter_a_valid_url'
			);
	}

	/**
	 * description
	 * @param return
	 * @return return
	 */
	public function copyImageFromFoxToContest ($sFullFoxImagePath, $sFullNewImagePath)
	{
		if(file_exists($sFullFoxImagePath))
		{
			 @copy($sFullFoxImagePath, $sFullNewImagePath);
			 return true;
		}
		else
		{
			return false;
		}

	}

	public function generateErrorHtmlFromArrayOfMessage($aMessages)
	{
		$sHtml = '<div> ';
		foreach ($aMessages as $sMessage) {
			$sHtml .= '<div class="error_message">' . $sMessage . '</div>';
		}

		$sHtml .= '</div>';

		return $sHtml;
	}

	public function getMoneyText($iAmount, $sCurrency = null)
	{	
		if(!$sCurrency)
		{
			$sCurrency = Phpfox::getService('core.currency')->getDefault();
		}
		
		$sSymbol = Phpfox::getService('core.currency')->getSymbol($sCurrency);
		// return  $sCurrency . ' ' . $iAmount . ' ' . $sSymbol;
		return   $iAmount . ' ' . $sCurrency;
	}

	public function setSearchKeyword($sKeyword)
	{
		unset($_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['search']);

		$iId = md5(uniqid());
		$_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['search'][$iId] = $sKeyword;
		return $iId;
	}

	public function getSearchKeyword($iSearchId)
	{
		return isset($_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['search'][$iSearchId]) ? $_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['search'][$iSearchId] : false;
	}
	
	public function getUserImage($iUserId)
	{
        $aRow = $this->database()->select('user_image, server_id')
                ->from(Phpfox::getT('user'))
                ->where('user_id = ' . $iUserId)
                ->execute('getSlaveRow');
        
        return $aRow;
	}

	public function getCurrency()
	{
		$sFoxCurrency = Phpfox::getService('contest.helper')->getPhpfoxDefaultCurrency();

		return $sFoxCurrency;
	}

	public function getAdminPaypalEmail()
	{
		return Phpfox::getParam('contest.admin_paypal_email');
	}

	public function getContestDefaultCurrency() {
		return 'USD';
	}

	public function getPhpfoxDefaultCurrency() {
		
		return Phpfox::getService('core.currency')->getDefault();
	}

	public function checkCurrencyInSupportedList($sCurrency, $sGateway = 'paypal')
	{
		$oGateway = Phpfox::getService('younetpaymentgateways')->load($sGateway);
		$aSupportedCurrencies = $oGateway->getSupportedCurrencies();

		if(in_array($sCurrency, $aSupportedCurrencies) )		
		{
			return true;
		}

		return false;
	}
	
    function convertTimeToCountdownString($iEndTimestamp, $bIsIncludeSecondAndMinute = false)
	{
		$sStr = '';
		$iRemainSeconds = $iEndTimestamp - PHPFOX_TIME; 
		
		$iMinuteSeconds = 60;
		$iHourSeconds = 60 * 60;
		$iDaySeconds = $iHourSeconds * 24;
		$iWeekSeconds = $iDaySeconds * 7;
		$iMonthSeconds = $iWeekSeconds * 30;
		
		if($iRemainSeconds > $iMonthSeconds)
		{
			$iRMonth = (int) ($iRemainSeconds / $iMonthSeconds);
			$sStr .= $iRMonth . Phpfox::getPhrase('contest.m') . ' ';
			$iRemainSeconds = $iRemainSeconds - $iRMonth * $iMonthSeconds;
		}

		if($iRemainSeconds > $iWeekSeconds)
		{
			$iRWeek = (int) ($iRemainSeconds / $iWeekSeconds);
			$sStr .= $iRWeek . Phpfox::getPhrase('contest.w') . ' ';
			$iRemainSeconds =  $iRemainSeconds  - $iRWeek * $iWeekSeconds;
		}

		if($iRemainSeconds > $iDaySeconds)
		{
			$iRDay = (int) ($iRemainSeconds / $iDaySeconds);
			$sStr .= $iRDay . Phpfox::getPhrase('contest.d') . ' ';
			$iRemainSeconds =  $iRemainSeconds  - $iRDay * $iDaySeconds;
		}

		if($iRemainSeconds > $iHourSeconds)
		{
			$iRHour = (int) ($iRemainSeconds / $iHourSeconds);
			$sStr .= $iRHour . Phpfox::getPhrase('contest.h') . ' ';
			$iRemainSeconds =  $iRemainSeconds  - $iRHour * $iHourSeconds;
		}

		if($bIsIncludeSecondAndMinute)
		{
			if($iRemainSeconds > $iMinuteSeconds)
			{
				$iRMinute = (int) ($iRemainSeconds / $iMinuteSeconds);
				$sStr .= $iRMinute . Phpfox::getPhrase('contest.m') . ' ';
				$iRemainSeconds =  $iRemainSeconds  - $iRMinute * $iMinuteSeconds;
			}

			$sStr .= $iRemainSeconds . Phpfox::getPhrase('contest.s') . ' ';
		}


		//$sStr .=  Phpfox::getPhrase('contest.left');

		
		return $sStr; 
	}

	public function setSessionBeforeAddItemFromSubmitForm($iContestId, $iType)
	{
		$iCurrentUserId = Phpfox::getUserId();
		$_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['submit_entry'][$iCurrentUserId]['contest_id'] = $iContestId;
		$_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['submit_entry'][$iCurrentUserId]['type_id'] = $iType;
	}

	public function getSessionAfterUserAddNewItem($iType)
	{
		$iCurrentUserId = Phpfox::getUserId();

		if(isset($_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['submit_entry'][$iCurrentUserId]))
		{
			if($_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['submit_entry'][$iCurrentUserId]['type_id'] == $iType)
			{
				return $_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['submit_entry'][$iCurrentUserId]['contest_id'];
			}
		}

		return false;
	}

	public function removeSessionAddNewItemOfUser()
	{
		$iCurrentUserId = Phpfox::getUserId();
		unset($_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['submit_entry'][$iCurrentUserId]);

		return true;
	}
	/**
	 * description
	 * @param return
	 * @return return
	 */
	public function getUserGroupSettingBySettingNameAndUserId ($sSettingName, $iUserId)
	{
		
	}

	public function convertToUserTimeZone($iTime)
	{
		$iTimeZoneOffsetInSecond = Phpfox::getLib('date')->getTimeZone() * 60 * 60;
		// on the interface we have convert into gmt, now we roll back to server time
		$iTime = $iTime + $iTimeZoneOffsetInSecond;

		return $iTime;
	}

	public function convertFromUserTimeZoneToServerTime($iTime)
	{
		$iTimeZoneOffsetInSecond = Phpfox::getLib('date')->getTimeZone() * 60 * 60;
		// on the interface we have convert into gmt, now we roll back to server time
		$iTime = $iTime - $iTimeZoneOffsetInSecond;

		return $iTime;
	}

	public function getMaxImageFileSize()
	{
		return (Phpfox::getUserParam('contest.max_upload_size_contest') === 0 ? null : Phpfox::getLib('phpfox.file')->filesize((Phpfox::getUserParam('contest.max_upload_size_contest') / 1024) * 1048576));
	}

	public function checkFeedExist($iItemId, $sTypeId)
	{
		$aRow = $this->database()->select('feed_id')
                ->from(Phpfox::getT('feed'))
                ->where(' item_id = ' . $iItemId . 
                		' AND type_id = \'' . $sTypeId . '\'')
                ->execute('getRow');
        if(isset($aRow['feed_id']))
        {
        	return $aRow['feed_id'];
        }
        else
        {
        	return false;
        }
	}
    
    public function timestampToCountdownString($iTimeStamp)
    {
        $result = '';
        
        $iLeft = $iTimeStamp - PHPFOX_TIME;
        
        if ($iLeft >= 60)
        {
            $sLeft = $this->secondsToString($iLeft);
            $result = $sLeft.' '.Phpfox::getPhrase('contest.left');
        }
        elseif ($iLeft > 0)
        {
            $result = '1'.Phpfox::getPhrase('contest.m').' '.Phpfox::getPhrase('contest.left');
        }
        
        return $result;
    }

    /**
     * Convert seconds to string
     * @param int $timeInSeconds
     * @return string
     */
    public function secondsToString($timeInSeconds)
    {
        static $phrases = null;

        $seeks = array(
            31536000,
            2592000,
            86400,
            3600,
            60
        );

        if (null == $phrases)
        {
            $phrases = array(
                array(
                    ' '.Phpfox::getPhrase('contest.year'),
                    ' '.Phpfox::getPhrase('contest.month'),
                    ' '.Phpfox::getPhrase('contest.day'),
                    Phpfox::getPhrase('contest.h'),
                    Phpfox::getPhrase('contest.m')
                ),
                array(
                    ' '.Phpfox::getPhrase('contest.years'),
                    ' '.Phpfox::getPhrase('contest.months'),
                    ' '.Phpfox::getPhrase('contest.days'),
                    Phpfox::getPhrase('contest.h'),
                    Phpfox::getPhrase('contest.m')
                )
            );
        }

        $result = array();

        $remain = $timeInSeconds;

        foreach ($seeks as $index => $seek)
        {
            $check = intval($remain / $seek);
            $remain = $remain % $seek;

            if ($check > 0)
            {
                $result[] = $check . $phrases[($check > 1) ? 1 : 0][$index];
            }

            if ($timeInSeconds < 86400)
            {
                if (count($result) > 1)
                {
                    break;
                }
            }
            else
            {
                if (count($result) > 0)
                {
                    break;
                }
            }
        }

        return implode(' ', $result);
    }
    
    public function getTimeLineStatus($iStart, $iEnd)
    {
        if ($iStart > PHPFOX_TIME)
        {
            return 'opening';
        }
        elseif ($iEnd < PHPFOX_TIME)
        {
            return 'end';
        }
        else
        {
            return 'on_going';
        }
    }
}