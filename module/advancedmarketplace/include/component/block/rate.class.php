<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_Rate extends Phpfox_Component
{
	public function process()
	{
		$bCanRate = Phpfox::getUserParam('advancedmarketplace.can_post_a_review');
		if(!$bCanRate)
		{
			echo Phpfox::getPhrase('advancedmarketplace.you_can_not_post_a_review');
			exit();
		}
		$iId = $this->getParam('iId');
		$aListing = phpfox::getService('advancedmarketplace')->getListing($iId);
		if(empty($aListing))
		{
			return false;
		}

		if(phpfox::getUserId() == $aListing['user_id'])
		{
			echo Phpfox::getPhrase('advancedmarketplace.you_can_reviewed_your_own_listing');
			exit();
		}
		$aReview = phpfox::getService('advancedmarketplace')->getExistingReview($iId, phpfox::getUserId());
		
		if(!empty($aReview))
		{
			// return Phpfox_Error::set('You have reviewed this listing before');
			echo Phpfox::getPhrase('advancedmarketplace.you_have_reviewed_this_listing_before');
			echo "<script language='javascript' type='text/javascript'>$('.js_box').width(260);</script>";
			exit;
		}
		$core_url = phpfox::getParam('core.path');
		$aRatingCallback = array(
			'type' => 'rating',
			'default_rating' => 0,
			'item_id' => $this->getParam("iId"),
			'stars' => array(
				'2' => 2,/* Phpfox::getPhrase('video.poor'), */
				'4' => 4,/* Phpfox::getPhrase('video.nothing_special'), */
				'6' => 6,/* Phpfox::getPhrase('video.worth_watching'), */
				'8' => 8,/* Phpfox::getPhrase('video.pretty_cool'), */
				'10' => 10,/* Phpfox::getPhrase('video.awesome') */
			)
		); 
	
		$aStars = array();
		foreach ($aRatingCallback['stars'] as $iKey => $mStar)
		{
			if (is_numeric($mStar))
			{
				$aStars[$mStar] = $mStar;
			}
			else 
			{
				$aStars[$iKey] = $mStar;
			}
		}		
		
		$aRatingCallback['stars'] = $aStars;
		
		$this->template()
			->setPhrase(array(
				"advancedmarketplace.rating",
			))
			->assign(array(
				'aRatingCallback' => $aRatingCallback,
				'core_url' => $core_url,
				'item_id' => $this->getParam("iId"),
				'page' => $this->getParam("page", "0"),
				'bCanRate' => $bCanRate
			));
	}
}
?>
