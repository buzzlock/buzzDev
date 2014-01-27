<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Service_Fundraising extends Phpfox_Service {

	public function __construct() {
		$this->_sTable = Phpfox::getT('fundraising_campaign');
		$this->_aErrorStatus = array(
			'invalid_permission' => array(
				'code' => 1,
				'phrase' => '' . Phpfox::getPhrase('fundraising.invalid_permission_or_closed_campaign')
			),
		);
			
	}

	private $_aBadgeStatus = array(
		'donate_button' => 1,
		'donors' => 2,
		'both' => 3,
		'none' => 4
	);

	public function convertToShortTypeAmount($iAmount)
	{
		if($iAmount >= 1000 && $iAmount < 1000000 )
		{
			$iAmount = round($iAmount / 1000, 1) . 'k';
		}
		else if ($iAmount >= 1000000)
		{
			$iAmount = round($iAmount / 1000000, 1) . 'M';
		}

		return $iAmount;
		
	}
	
	/**
	 * 1: $2.00 
		2: 2.00$ 
		3: 2.00 USD 
		4: $2.00 USD 
	 * @param type $iAmount
	 * @param type $sCurrency
	 * @return type
	 */
	public function getCurrencyText($iAmount, $sCurrency)
	{
//		$sText = ($iAmount) ? Phpfox::getService('core.currency')->getCurrency($iAmount, $sCurrency) : Phpfox::getPhrase('fundraising.unlimited_upper');
		$iFormatType = Phpfox::getParam('fundraising.currency_display_type');
		if(!$iAmount)
		{
			return Phpfox::getPhrase('fundraising.unlimited_upper');
		}

		if(Phpfox::getParam('fundraising.is_use_shorten_form'))
		{
			$iAmount = $this->convertToShortTypeAmount($iAmount);
		}
                  
		switch($iFormatType)
		{
			case 1:
				$sText =  Phpfox::getService('core.currency')->getSymbol($sCurrency) . $iAmount ;
				break;
			case 2: 
				$sText = $iAmount . Phpfox::getService('core.currency')->getSymbol($sCurrency);
				break;
			case 3: 
                                $sText = Phpfox::getService('core.currency')->getCurrency(number_format($iAmount, 2,  '.', ','), $sCurrency);
				//$sText = $iAmount . ' ' . $sCurrency;
				break;
			case 4: 
				$sText = Phpfox::getService('core.currency')->getSymbol($sCurrency) . $iAmount . ' ' . $sCurrency;
				break;
			default:
				$sText = $iAmount . Phpfox::getService('core.currency')->getSymbol($sCurrency);
				break;
		}
		
		return $sText;
	}

	public function getAllErrorStatus()
	{
		return $this->_aErrorStatus;
	}
	
	/**
	 * 
	 * @return error status code and phrase if having or false
	 */
	public function getErrorStatusNumber($sName)
	{
		if(isset($this->_aErrorStatus[$sName]))
		{
			return $this->_aErrorStatus[$sName]['code'];
		}

		return false;
	}
	

	/**
	 * get callback to add feed in page 
	 * 
	 * @param int $iCampaignId
	 */
	public function getFundraisingAddCallback($iCampaignId)
	{
		Phpfox::getService('pages')->setIsInPage();
		
		return array(
			'module' => 'pages',
			'item_id' => $iCampaignId,
			'table_prefix' => 'pages_'
		);
	}

	public function getAllBadgeStatus()
	{
		return $this->_aBadgeStatus;
	}
	
	public function getBadgeStatusNumber($sName)
	{
		if(isset($this->_aBadgeStatus[$sName]))
		{
			return $this->_aBadgeStatus[$sName];
		}

		return false;
	}

	public function getFrameUrl($iCampaignId, $iStatus = 3)
	{
		if(!$iCampaignId)
		{
			return false;
		}
		$sCorePath = Phpfox::getParam('core.path');
		$sFrameUrl = $sCorePath . 'module/fundraising/static/campaign-badge.php?id=' . $iCampaignId . '&status=' . $iStatus;

		return $sFrameUrl;
	}

	public function getBadgeCode($sFrameUrl)
	{
		return "<iframe src=\"{$sFrameUrl}\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:300px; height:600px;\" allowTransparency=\"true\">;</iframe>";
	}
	/**
	 * check in the list of friends what friend user has invited then make the short list
	 * @TODO: none 
	 * <pre>
	 * Phpfox::getService('fundraising')->isAlreadyInvited($iCampaignId, $aFriendList);
	 * </pre>
	 * @by minhta
	 * @param int $iCampaignId 
	 * @param int $aFriends list of user's friend 
	 * @return short list of uninvited friend, false if there's no one
	 */
	public function isAlreadyInvited($iCampaignId, $aFriends) {
		if ((int) $iCampaignId === 0) {
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

			$aInvites = $this->database()->select('invited_id, donor_id, invited_user_id')
					->from(Phpfox::getT('fundraising_invited'))
					->where('campaign_id = ' . (int) $iCampaignId . ' AND invited_user_id IN(' . implode(', ', $sIds) . ')')
					->execute('getSlaveRows');

			$aCache = array();
			foreach ($aInvites as $aInvite) {
				$aCache[$aInvite['invited_user_id']] = ($aInvite['donor_id'] > 0 ? Phpfox::getPhrase('fundraising.signed') : Phpfox::getPhrase('fundraising.invited'));
			}

			if (count($aCache)) {
				return $aCache;
			}
		}

		return false;
	}

	/**
	 * parse text for showing on form based on the campaign
	 * it will replace some predefined symbol by the corresponding text
	 * @TODO: none 
	 * <pre>
	 * Phpfox::getService('fundraising')->parseVar($textm, $campaign);
	 * </pre>
	 * @by minhta
	 * @param string $sToBeParsedText the text to be parsed 
	 * @param array $aCampaign the corresponding campaign
	 * @return
	 */
	public function parseVar($sToBeParsedText, $aCampaign) {
		$aReplace = array('[title]', '[campaign_url]', '[financial_goal]', '[short_description]', '[description]',
			'[start_time]', '[end_time]', '[total_amount]', '[full_name]'
		);

		$oDate = Phpfox::getLib('date');
		$sUser = Phpfox::getService('user')->getUser(Phpfox::getUserId());
		$aLink = Phpfox::getLib('url')->permalink('fundraising', $aCampaign['campaign_id'], $aCampaign['title']);
		$sLink = '<a href="' . $aLink . '" title = "' . $aCampaign['title'] . '" target="_blank">' . $aLink . '</a>';

		$aVar = array($aCampaign['title'], $sLink, $aCampaign['financial_goal'], $aCampaign['short_description'], $aCampaign['description'],
			$oDate->convertTime($aCampaign['start_time']), $oDate->convertTime($aCampaign['end_time']), $aCampaign['total_amount'], $sUser['full_name']
		);
		$sToBeParsedText = str_replace($aReplace, $aVar, $sToBeParsedText);
		return $sToBeParsedText;
	}

	/**
	 * get statistic for module petition, it doesn't include statistic in a page
	 * @TODO: complete later 
	 * <pre>
	 * </pre>
	 * @by minhta
	 * @return array
	 */
	public function getStats() {
		$aStats = array();
		$oCustomCache = Phpfox::getService('fundraising.cache');
		$sKey = 'site_stats' ;
		$sType = 'site_stats';
		
		if (!($aStats = $oCustomCache->get($sKey, $sType)))
		{

			$aStats['ongoing'] = (int) $this->database()->select('COUNT(*)')
											->from($this->_sTable)
											->where('is_approved = 1 AND status = ' . Phpfox::getService('fundraising.campaign')->getStatusCode('ongoing') . ' AND module_id = "fundraising"')
											->execute('getSlaveField');
			$aStats['reached'] = (int) $this->database()->select('COUNT(*)')
											->from($this->_sTable)
											->where('is_approved = 1 AND status = ' . Phpfox::getService('fundraising.campaign')->getStatusCode('reached') . ' AND module_id = "fundraising"')
											->execute('getSlaveField');
			$aStats['closed'] = (int) $this->database()->select('COUNT(*)')
											->from($this->_sTable)
											->where('is_approved = 1 AND status = ' . Phpfox::getService('fundraising.campaign')->getStatusCode('closed') . ' AND module_id = "fundraising"')
											->execute('getSlaveField');
			$aStats['expired'] = (int) $this->database()->select('COUNT(*)')
											->from($this->_sTable)
											->where('is_approved = 1 AND status = ' . Phpfox::getService('fundraising.campaign')->getStatusCode('expired') . ' AND module_id = "fundraising"')
											->execute('getSlaveField');

			$aStats['ongoing'] = number_format($aStats['ongoing'], 0, '.', ',');
			$aStats['reached'] = number_format($aStats['reached'], 0, '.', ',');
			$aStats['closed'] = number_format($aStats['closed'], 0, '.', ',');
			$aStats['expired'] = number_format($aStats['expired'], 0, '.', ',');

			$oCustomCache->set($sKey, $aStats);
		}

		return $aStats;
	}

	/**
	 * to create left sub menu for a controller
	 * @TODO: replace petition, complete all menu later 
	 * <pre>
	 * Phpfox::getService('fundraising')->buildMenu();
	 * </pre>
	 * @by minhta
	 */
	public function buildMenu() {
		$aFilterMenu = array(
			Phpfox::getPhrase('fundraising.all_fundraisings') => '',
			Phpfox::getPhrase('fundraising.my_fundraisings') => 'my',
		);

		if (!Phpfox::getParam('core.friends_only_community') && Phpfox::isModule('friend')) {
			$aFilterMenu[Phpfox::getPhrase('fundraising.friends_fundraisings')] = 'friend';
		}

		if (Phpfox::getUserParam('fundraising.can_approve_campaigns')) {
			$iPendingTotal = Phpfox::getService('fundraising.campaign')->getTotalPendings();

			if ($iPendingTotal) {
				$aFilterMenu[Phpfox::getPhrase('fundraising.pending_fundraisings') . (Phpfox::getUserParam('fundraising.can_approve_campaigns') ? '<span class="pending">' . $iPendingTotal . '</span>' : 0)] = 'pending';
			}
		}

		$aFilterMenu[] = true;

		if (Phpfox::isUser()) {
			$aFilterMenu[Phpfox::getPhrase('fundraising.my_donated_campaigns')] = 'idonated';
		}
		$aFilterMenu[Phpfox::getPhrase('fundraising.featured_campaigns')] = 'featured';
		$aFilterMenu[Phpfox::getPhrase('fundraising.reached_campaigns')] = 'reached';
		$aFilterMenu[Phpfox::getPhrase('fundraising.expired_campaigns')] = 'expired';
		$aFilterMenu[Phpfox::getPhrase('fundraising.closed_campaigns')] = 'closed';
		Phpfox::getLib('template')->buildSectionMenu('fundraising', $aFilterMenu);
	}

	// datlv ---------------------------

	/**
	 * get donor list for detail block
	 * @by datlv
	 * @TODO: complete after can donate
	 * <pre>
	 * Phpfox::getSetvice('fundraising')->getDonations($iId);
	 * </pre>
	 * @param $iId
	 * @param $iLimit
	 * @return array()
	 */
	/*public function getDonations($iId, $iLimit = null) {
		 $aDonations = array();
		  $iTotal = 0;

		  $aRows =  $this->database()->select('fd.*, ' . Phpfox::getUserField())
		  ->from(Phpfox::getT('fundraising_donor'),'fd')
		  ->join(Phpfox::getT('fundraising_campaign'), 'fc', 'fc.petition_id= fd.petition_id')
		  ->join(Phpfox::getT('user'), 'u', 'u.user_id = fd.user_id')
		  ->where('fd.campaign_id = ' . (int) $iId)
		  ->order('fd.time_stamp DESC')
		  ->execute('getSlaveRows');

		  if (is_array($aRows) && count($aRows))
		  {
		  $iTotal = count($aRows);
		  foreach ($aRows as $iKey => $aRow)
		  {
		  if ($iLimit != null && $iKey === $iLimit)
		  {
		  break;
		  }
		  $aDonations[] = $aRow;
		  }
		  }
		$aDonations = array();

		for ($i = 1; $i < 5; $i++) {
			$aDonations[] = array(
				'campaign_id' => '1',
				'user_id' => ($i == 4) ? Null : ($i % 2) ? '1' : '2',
				'is_guest' => ($i == 4) ? '1' : '0',
				'is_anonymous' => ($i == 3) ? '1' : '0',
				'message' => 'test',
				'amount' => $i * 100,
				'time_stamp' => strtotime(date('d-m-Y h:i:s')),
				'full_name' => ($i == 4) ? 'guest' : ($i == 3) ? 'anonymous donor' : 'fullname',
				'email_address' => 'testacc@gmail.com',
				'user_image' => NULL,
				'user_name' => 'test',
				'is_support' => '0',
				'total_comeback' => '0',
				'total_share' => '0',
			);
		}

		$aDonations[] = array(
			'campaign_id' => '1',
			'user_id' => '2',
			'is_guest' => '0',
			'is_anonymous' => '0',
			'message' => NULL,
			'amount' => 0,
			'is_support' => '1',
			'total_comeback' => '5',
			'total_share' => '1',
			'time_stamp' => strtotime(date('d-m-Y h:i:s')),
			'full_name' => 'test',
			'email_address' => 'testacc@gmail.com',
			'user_image' => NULL,
			'user_name' => 'profile-2',
		);

		$iTotal = $i + 1;

		return array($iTotal, $aDonations);
	}*/

	/**
	 * get news list for detail block
	 * @by datlv
	 * <pre>
	 * Phpfox::getService('fundraising')->getNews($iId);
	 * </pre>
	 * @param $iId
	 * @param $iLimit
	 * @return array()
	 */
	public function getNews($iId, $iLimit = null) {
		$aNews = array();
		$iTotal = 0;

		$aRows = $this->database()->select('fn.*')
				->from(Phpfox::getT('fundraising_news'), 'fn')
				->where('fn.campaign_id = ' . (int) $iId)
				->order('fn.time_stamp DESC')
				->execute('getSlaveRows');

		if (is_array($aRows) && count($aRows)) {
			$iTotal = count($aRows);
			foreach ($aRows as $iKey => $aRow) {
				if ($iLimit != null && $iKey === $iLimit) {
					break;
				}
				$aNews[] = $aRow;
			}
		}

		return array($iTotal, $aNews);
	}

	/**
	 * we only support paypal
	 * @by datlv
	 * @return array(currency)
	 */
	public function getCurrentCurrencies($sGateway = 'paypal', $sDefaultCurrency = '') {
		
		$aFoxCurrencies = Phpfox::getService('core.currency')->getForBrowse();
		$oGateway = Phpfox::getService('younetpaymentgateways')->load($sGateway);
		$aSupportedCurrencies = $oGateway->getSupportedCurrencies();

		$sDefaultCurrency = $sDefaultCurrency ? $sDefaultCurrency : Phpfox::getService('core.currency')->getDefault();
		$aDefaultCurrency = array();
		$aResults = array();
		foreach($aFoxCurrencies as $aCurrency)
		{
			if(in_array($aCurrency['currency_id'], $aSupportedCurrencies) )		
			{
				if($aCurrency['currency_id'] == $sDefaultCurrency)
				{
					$aDefaultCurrency = $aCurrency;
				}
				else
				{
					$aResults[] = $aCurrency;
				}
			}
		}

		array_unshift($aResults, $aDefaultCurrency);

		return $aResults;
		//}
	}

	/**
	 * get all image of this campaign
	 * @by datlv
	 * @TODO : complete later
	 * <pre>
	 * Phpfox::getService('fundraising')->getImages($iId)
	 * </pre>
	 * @param $iId
	 * @return array() images of campaign
	 */
	public function getImages($iId) {
		$aRows = $this->database()->select('fi.*')
				->from(Phpfox::getT('fundraising_image'), 'fi')
				->where('fi.campaign_id = ' . (int) $iId)
				->order('fi.ordering DESC')
				->execute('getSlaveRows');

		return $aRows;
	}

	// --------------------------datlv

	public function __call($sMethod, $aArguments) {
		if ($sPlugin = Phpfox_Plugin::get('fundraising.service_fundraising__call')) {
			return eval($sPlugin);
		}

		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}

}
?>

