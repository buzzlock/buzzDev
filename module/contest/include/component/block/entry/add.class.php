<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Entry_Add extends Phpfox_component{

	private function _checkIsSearchingAndForward()
	{	
		$oUrl = Phpfox::getLib('url');

		if(isset($_POST['search']) && isset($_POST['search']['search']))
		{
			$iId = Phpfox::getService('contest.helper')->setSearchKeyword($_POST['search']['search']);
			$oUrl->setParam('search-id', $iId);
			$oUrl->forward($oUrl->getFullUrl());
		}
	}

	public function process ()
	{

		$this->_checkIsSearchingAndForward();

		$iContestId  = $this->request()->getInt('req2');
		if(!Phpfox::getService('contest.permission')->canSubmitEntry($iContestId, Phpfox::getUserId()))
		{
			return false;
		}

		$iItemId = $this->request()->get('itemid');
		$sChosenItemTitle = '';
		if ($iItemId) {
			//remove session stored
			Phpfox::getService('contest.helper')->removeSessionAddNewItemOfUser();
			$aContest = Phpfox::getService('contest.contest')->getContestById($iContestId);
			$aChosenItem = Phpfox::getService('contest.entry')->getItemDataFromFox($aContest['type'], $iItemId);
			$sChosenItemTitle = $aChosenItem['title'];
		}


		$aAddEntryTemplateData = Phpfox::getService('contest.entry')->getDataOfAddEntryTemplate($iContestId);
        
		$aAddEntryTemplateData['iChosenItemId'] = $iItemId ? $iItemId : 0;

		$aAddEntryTemplateData['sChosenItemTitle'] = $sChosenItemTitle;

		$sActionUrl = Phpfox::getLib('url')->permaLink('contest', $iContestId, $sTitle = '', $bRedirect = false, $sMessage = null, $aExtra = array('action' => 'add'));

		if(isset($_POST['search']))
		{

		}
		$aYnContestItemSearchTool = array(
			'search' => array(
				'action' => $sActionUrl,
				'default_value' => Phpfox::getPhrase('contest.search_items'),
				'name' => 'search'
				)
			);

		if($iSearchId = Phpfox::getLib('request')->get('search-id') )
		{
			$sKeyword = Phpfox::getService('contest.helper')->getSearchKeyword($iSearchId);
			$aYnContestItemSearchTool['search']['actual_value'] = $sKeyword;
		}
                
		$this->template()->assign(array(
			'aAddEntryTemplateData' => $aAddEntryTemplateData,
			'aYnContestItemSearchTool' => $aYnContestItemSearchTool
				// 'sHeader' => Phpfox::getPhrase('contest.categories')
			)
		);			

		Phpfox::getLib('pager')->set(array('page' => $aAddEntryTemplateData['iPage'], 'size' => $aAddEntryTemplateData['iPageSize'], 'count' => $aAddEntryTemplateData['iTotalItems']));

	}
}