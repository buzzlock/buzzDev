<?php
class Advancedphoto_Component_Block_Weekmonthtoday extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iNumberOfPhotos = Phpfox::getParam('advancedphoto.number_of_photos_displayed_on_top_blocks_on_homepage');
		list($iTotalTodayTops, $aTodayTops) = Phpfox::getService('advancedphoto')->getTodayTops($iNumberOfPhotos);
		list($iTotalThisMonthTops, $aThisMonthTops) = Phpfox::getService('advancedphoto')->getThisMonthTops($iNumberOfPhotos);
		list($iTotalThisWeekTops, $aThisWeekTops) = Phpfox::getService('advancedphoto')->getThisWeekTops($iNumberOfPhotos);

		$sViewAllTodayLink = Phpfox::getService('advancedphoto.helper')->getLinkViewAll('today');
		$sViewAllThisWeekLink = Phpfox::getService('advancedphoto.helper')->getLinkViewAll('this-week');
		$sViewAllThisMonthLink = Phpfox::getService('advancedphoto.helper')->getLinkViewAll('this-month');
		$this->template()->assign(array(
					'sHeader' => "",
					'corepath' => phpfox::getParam('core.path'),
					'aTodayTops' => $aTodayTops,
					'aThisMonthTops' => $aThisMonthTops,
					'aThisWeekTops' => $aThisWeekTops,
					'sViewAllTodayLink' => $sViewAllTodayLink,
					'sViewAllThisWeekLink' => $sViewAllThisWeekLink,
					'sViewAllThisMonthLink' => $sViewAllThisMonthLink,
				)
			);	
		return 'block';
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_weekmonthtoday_clean')) ? eval($sPlugin) : false);
	}
}
?>