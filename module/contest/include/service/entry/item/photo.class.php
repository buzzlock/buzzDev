<?php

defined('PHPFOX') or exit('NO DICE!');
require_once dirname(dirname(__file__)) . '/item/item_abstract.class.php';

class Contest_Service_Entry_Item_Photo extends Phpfox_service implements Contest_Service_Entry_Item_Item_Abstract{
	private $_aImageSize = array(
		30, 50, 200, 500
		);

	/**
	 * [$_sOriginalSuffix string of the original images
	 * @var string
	 */
	private $_sOriginalSuffix = '';

	public function __construct()
	{
		if(Phpfox::isModule('advancedphoto'))
		{
			$this->_sPhotoModuleName = 'advancedphoto';
		}
		else
		{
			$this->_sPhotoModuleName = 'video';

		}
		$this->_sTable = Phpfox::getT('photo');
	}

	public function getAddNewItemLink($iContestId) {
		$sAddParamName = Phpfox::getService('contest.constant')->getYnAddParamForNavigateBack();
		if($this->_sPhotoModuleName == 'advancedphoto')
		{
			$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto.add', array($sAddParamName => $iContestId));
		}
		else
		{
			$sLink = Phpfox::getLib('url')->makeUrl('photo.add', array($sAddParamName => $iContestId));
		}
		

		return $sLink;
	}

	/**
	 * in case we encounter a post form, we know it is a search request
	 * @param  integer $iLimit number of items per page
	 * @param  integer $iPage  page number
	 * @return lsit {'total' => int, 'aItems' => array of item}
	 */
	public function getItemsOfCurrentUser($iLimit = 5, $iPage = 0)
	{
		$sConds = 'user_id = ' . Phpfox::getUserId() . ' ';
		//in case we encounter a post form, we know it is a search request
		if($iSearchId = Phpfox::getLib('request')->get('search-id') )
		{
			$sKeyword = Phpfox::getService('contest.helper')->getSearchKeyword($iSearchId);
			$sConds .= $this->database()->search($sType ='like%' , $mField = array('title'), $sSearch = $sKeyword) ;
		}

		// by default we only get items of current user
		$iCnt = $this->database()->select('COUNT(*)')
			->from($this->_sTable)
			->where($sConds) 
			->execute('getSlaveField');	


		$aItems = $this->database()->select('*' )
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
		
		$aItem = $this->database()->select('* ' )
				->from($this->_sTable)
				->where('photo_id = ' . $iItemId)
				->execute('getSlaveRow');

		// change path to make it more general when generating images
		$aItem['destination'] = 'photo' . PHPFOX_DS . $aItem['destination'];

		return $aItem;
	}

	public function getTemplateViewPath()
	{
		return 'contest.entry.content.photo';
	}

	public function getDataToInsertIntoEntry($iItemId)
	{	
		$aItem = $this->getItemFromFox($iItemId);

		$sFullSourcePath = Phpfox::getParam('core.dir_pic') . $aItem['destination'];
		if (file_exists(sprintf($sFullSourcePath, '')))
        {
            $sSuffix = '';
        }
        else
        {
            $sSuffix = '_1024';
        }

		$sImagePath = Phpfox::getService('contest.entry.process')->copyImageToContest($sFullSourcePath, $sSuffix, $this->_aImageSize);

		// column name here must comply with column in db
		$aReturn = array(
            'image_path' => $sImagePath
        );

		return $aReturn;
	}

	public function getDataFromFoxAdaptedWithContestEntryData($iItemId)
	{
		$aItem = $this->getItemFromFox($iItemId);

		$aItem['image_path'] = $aItem['destination'];
		return $aItem;	
	}


}