<?php

defined('PHPFOX') or exit('NO DICE!');

class JobPosting_Service_Helper extends Phpfox_service {
	/**
	 * to create left sub menu for a controller
	 * <pre>
	 * Phpfox::getService('fundraising')->buildMenu();
	 * </pre>
	 * @by vudp
	 */
	public function buildMenu() {
		$aFilterMenu = array(Phpfox::getPhrase('jobposting.all_jobs') => '', Phpfox::getPhrase('jobposting.all_companies') => 'jobposting.company', Phpfox::getPhrase('jobposting.my_favorite_jobs') => 'favorite', Phpfox::getPhrase('jobposting.my_following_jobs') => 'following', Phpfox::getPhrase('jobposting.my_favorite_companies') => 'jobposting.company.view_favoritecompany', Phpfox::getPhrase('jobposting.my_following_companies') => 'jobposting.company.view_followingcompany', Phpfox::getPhrase('jobposting.my_applied_jobs') => 'appliedjob', Phpfox::getPhrase('jobposting.my_company') => 'jobposting.company.view_mycompany', );
		if (Phpfox::getUserParam('jobposting.can_approve_job')) {
			$iPendingTotal = Phpfox::getService('jobposting.job') -> getPendingTotal();

			if ($iPendingTotal) {
				$aFilterMenu[Phpfox::getPhrase('jobposting.pending_jobs') . (Phpfox::getUserParam('jobposting.can_approve_job') ? '<span class="pending">' . $iPendingTotal . '</span>' : 0)] = 'pending_jobs';
			}
		}

		if (Phpfox::getUserParam('jobposting.can_approve_company')) {
			$iPendingTotal = Phpfox::getService('jobposting.company') -> getPendingTotal();

			if ($iPendingTotal) {
				$aFilterMenu[Phpfox::getPhrase('jobposting.pending_company') . (Phpfox::getUserParam('jobposting.can_approve_company') ? '<span class="pending">' . $iPendingTotal . '</span>' : 0)] = 'jobposting.company.view_pending_companies';
			}
		}

		Phpfox::getLib('template') -> buildSectionMenu('jobposting', $aFilterMenu);
	}

	//display
	public function convertToUserTimeZone($iTime) {
		$iTimeZoneOffsetInSecond = Phpfox::getLib('date') -> getTimeZone() * 60 * 60;
		// on the interface we have convert into gmt, now we roll back to server time
		$iTime = $iTime + $iTimeZoneOffsetInSecond;

		return $iTime;
	}

	//save to database
	public function convertFromUserTimeZone($iTime) {
		$iTimeZoneOffsetInSecond = Phpfox::getLib('date') -> getTimeZone() * 60 * 60;
		// on the interface we have convert into gmt, now we roll back to server time
		$iTime = $iTime - $iTimeZoneOffsetInSecond;

		return $iTime;
	}
	
	public function getCurrency(){
		return PHpfox::getService('core.currency')->getDefault();
	}

	public function getTextParseCurrency($sPrice){
		$sCurrency = $this->getCurrency();
		$sPriceWithCurrency = Phpfox::getService('core.currency')->getSymbol(($sCurrency ? $sCurrency : $this->getDefault())) . $sPrice;	
		
		return $sPriceWithCurrency;
		
	}

	public function getTextJsCurrency($sPrice){
		$sCurrency = $this->getCurrency();
		$sPriceWithCurrency = $sPrice . ' ' . $sCurrency;
		
		return $sPriceWithCurrency;
	}

}
?>