<?php

defined('PHPFOX') or exit('NO DICE!');
require_once dirname(dirname(__file__)) . '/item/item_abstract.class.php';

class Contest_Service_Entry_Item_Video extends Phpfox_service implements Contest_Service_Entry_Item_Item_Abstract{

	private $_aImageSize = array(
		30, 50, 120
		);

	/**
	 * [$_sOriginalSuffix string of the original images
	 * @var string
	 */
	private $_sOriginalSuffix = '_120';

	public function __construct()
	{
		if(Phpfox::isModule('videochannel'))
		{
			$this->_sVideoModuleName = 'videochannel';
			$this->_sTable = Phpfox::getT('channel_video');
			$this->_sEmbedTable = Phpfox::getT('channel_video_embed');
		}
		else
		{
			$this->_sVideoModuleName = 'video';
			$this->_sTable = Phpfox::getT('video');
			$this->_sEmbedTable = Phpfox::getT('video_embed');
		}

	}


	public function getAddNewItemLink($iContestId) {
		$sAddParamName = Phpfox::getService('contest.constant')->getYnAddParamForNavigateBack();

		if($this->_sVideoModuleName == 'videochannel')
		{
			$sLink = Phpfox::getLib('url')->makeUrl('videochannel.add', array($sAddParamName => $iContestId));
		}
		else
		{
			$sLink = Phpfox::getLib('url')->makeUrl('video.add', array($sAddParamName => $iContestId));
		}
		

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

		// by default we only get items of current user
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
		
		$aItem = $this->database()->select('v.*, ve.* ' )
				->from($this->_sTable, 'v')
				->leftJoin($this->_sEmbedTable, 've', 'v.video_id = ve.video_id')
				->where('v.video_id = ' . $iItemId)
				->execute('getSlaveRow');

		// change path to make it more general when generating images
		$aItem['image_path'] = 'video' . PHPFOX_DS . $aItem['image_path'];


		return $aItem;
	}

	public function getTemplateViewPath()
	{
		return 'contest.entry.content.video';
	}

	public function getDataToInsertIntoEntry($iItemId)
	{
		$aItem = $this->getItemFromFox($iItemId);
                
		$sFullSourcePath =Phpfox::getParam('core.dir_pic') . $aItem['image_path'];
                
                if($aItem['image_server_id']!=0){
                    $sOriginalSource = sprintf($sFullSourcePath, $this->_sOriginalSuffix);
                    $sOriginalSource = str_replace(PHPFOX_DIR, Phpfox::getParam('core.path'), $sOriginalSource);
                    $sSrc = Phpfox::getLib('cdn')->getUrl($sOriginalSource, $aItem['image_server_id']);
                    $p = PHPFOX_DIR_FILE . PHPFOX_DS . 'pic' . PHPFOX_DS . 'contest' . PHPFOX_DS;
                    if (!is_dir($p)) {
                        if (!@mkdir($p, 0777, 1)) {
                        }
                    }
                    $sImage = $sSrc;
                    $sImageLocation = Phpfox::getLib('file')->getBuiltDir(Phpfox::getParam('core.dir_pic')."contest/"). md5(PHPFOX_TIME.PHpfox::getUserId().'contest') . '%s.jpg';
                    $oImage = Phpfox::getLib('request')->send($sImage, array(), 'GET');
                    $sTempImage = 'contest_temporal_image_'.PHPFOX_TIME.PHpfox::getUserId();
                    Phpfox::getLib('file')->writeToCache($sTempImage, $oImage);
                    @copy(PHPFOX_DIR_CACHE . $sTempImage, sprintf($sImageLocation, $this->_sOriginalSuffix));
                    unlink(PHPFOX_DIR_CACHE . $sTempImage);
                    $tmp = sprintf($sImageLocation, $this->_sOriginalSuffix);
                    $tmp = str_replace("/", '\\', $tmp);
                    $sImagePath = Phpfox::getService('contest.entry.process')->copyImageToContest($sImageLocation, $this->_sOriginalSuffix, $this->_aImageSize);
                    unlink($tmp);
                }
                else
                {
                    $sImagePath = Phpfox::getService('contest.entry.process')->copyImageToContest($sFullSourcePath, $this->_sOriginalSuffix, $this->_aImageSize);
                }  
		

		//copy db
		// column name here must comply with column in db
		$aReturn = array(
			'embed_code' => $aItem['embed_code'],
			'video_url' => $aItem['video_url'],
			'image_path' => $sImagePath
			);

		return $aReturn;
		//copy file
		
	}

	public function getDataFromFoxAdaptedWithContestEntryData($iItemId)
	{
		$aItem = $this->getItemFromFox($iItemId);
		return $aItem;	
	}
}