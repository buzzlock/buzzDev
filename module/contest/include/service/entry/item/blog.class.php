<?php

defined('PHPFOX') or exit('NO DICE!');
require_once dirname(dirname(__file__)) . '/item/item_abstract.class.php';

class Contest_Service_Entry_Item_Blog extends Phpfox_service implements Contest_Service_Entry_Item_Item_Abstract{

	public function __construct()
	{
		$this->_sTable = Phpfox::getT('blog');
	}
	public function getAddNewItemLink($iContestId) {
		$sAddParamName = Phpfox::getService('contest.constant')->getYnAddParamForNavigateBack();
		$sLink = Phpfox::getLib('url')->makeUrl('blog.add', array($sAddParamName => $iContestId));

		return $sLink;
	}

	public function getItemsOfCurrentUser($iLimit = 5, $iPage = 0)
	{
		$sConds = 'user_id = ' . Phpfox::getUserId() . ' ';
		//in case we encounter a post form, we know it is a search request
		if($iSearchId = Phpfox::getLib('request')->get('search-id') )
		{
			$sKeyword = Phpfox::getService('contest.helper')->getSearchKeyword($iSearchId);
			$sConds .= $this->database()->search($sType ='like%' , $mField = array('title'), $sSearch = $sKeyword) ;
		}

		$iCnt = $this->database()->select('COUNT(*)')
			->from($this->_sTable)
			->where($sConds)
			->execute('getSlaveField');	

			
		$aItems = $this->database()->select('* ' )
				->from($this->_sTable)
				->where($sConds)
				->limit($iPage, $iLimit, $iCnt)
				->order('time_stamp DESC')
				->execute('getSlaveRows');
		return array($iCnt, $aItems);
	}

	public function getItemFromFox($iItemId) 
	{
		if(!$iItemId)
		{
			return false;
		}
		$aItem = $this->database()->select('b.*, bt.* ' )
				->from($this->_sTable, 'b')
				->leftJoin(Phpfox::getT('blog_text'), 'bt', 'b.blog_id = bt.blog_id')
				->where('bt.blog_id = ' . $iItemId)
				->execute('getSlaveRow');

		return $aItem;
	}

	public function getTemplateViewPath()
	{
		return 'contest.entry.content.blog';
	}

	public function getDataToInsertIntoEntry($iItemId)
	{
		$aItem = $this->getItemFromFox($iItemId);

		//copy db
		// column name here must comply with column in db
		$aReturn = array(
			'blog_content' => $aItem['text'],
			'blog_content_parsed' => $aItem['text_parsed'],
			'total_attachment' => $aItem['total_attachment']
			);

		return $aReturn;
		//copy file
		
	}

	public function getDataFromFoxAdaptedWithContestEntryData($iItemId)
	{
		$aItem = $this->getItemFromFox($iItemId);
		$aItem['blog_content'] = Phpfox::getParam('core.allow_html') ? $aItem['text_parsed'] : $aItem['text'];
		$aItem['blog_content_parsed'] = Phpfox::getParam('core.allow_html') ? $aItem['text_parsed'] : $aItem['text'];
		return $aItem;	
	}

}