<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Service_Helper extends Phpfox_Service {
	/**
	 * Remove quotes from PHP variables.
	 *
	 * @param string $string PHP variable to work with.
	 * @return string Converted PHP variable.
	 */
	public function removeQuote($string)
	{
		if (($string{0} == "'" || $string{0} == '"') && $string{strlen($string)-1} == $string{0})
		{
			return substr($string, 1, -1);
		}
		else
		{
			return $string;
		}
	}

	
	function convertTimeToCountdownString($iEndTimestamp)
	{
		$sStr = '';
		$iRemainSeconds = $iEndTimestamp - PHPFOX_TIME; 

		$iHourSeconds = 60 * 60;
		$iDaySeconds = $iHourSeconds * 24;
		$iWeekSeconds = $iDaySeconds * 7;
		$iMonthSeconds = $iWeekSeconds * 30;

		if($iRemainSeconds > $iMonthSeconds)
		{
			$iRMonth = (int) ($iRemainSeconds / $iMonthSeconds);
			$sStr .= $iRMonth . Phpfox::getPhrase('fundraising.m') . ' ';
			$iRemainSeconds = $iRemainSeconds - $iRMonth * $iMonthSeconds;
		}

		if($iRemainSeconds > $iWeekSeconds)
		{
			$iRWeek = (int) ($iRemainSeconds / $iWeekSeconds);
			$sStr .= $iRWeek . Phpfox::getPhrase('fundraising.w') . ' ';
			$iRemainSeconds =  $iRemainSeconds  - $iRWeek * $iWeekSeconds;
		}

		if($iRemainSeconds > $iDaySeconds)
		{
			$iRDay = (int) ($iRemainSeconds / $iDaySeconds);
			$sStr .= $iRDay . Phpfox::getPhrase('fundraising.d') . ' ';
			$iRemainSeconds =  $iRemainSeconds  - $iRDay * $iDaySeconds;
		}

		if($iRemainSeconds > $iHourSeconds)
		{
			$iRHour = (int) ($iRemainSeconds / $iHourSeconds);
			$sStr .= $iRHour . Phpfox::getPhrase('fundraising.h') . ' ';
			$iRemainSeconds =  $iRemainSeconds  - $iRHour * $iHourSeconds;
		}



		$sStr .=  Phpfox::getPhrase('fundraising.left');

		
		return $sStr; 
	}
}

?>