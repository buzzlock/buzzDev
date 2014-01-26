<?php

defined('PHPFOX') or exit('NO DICE!');
require_once dirname(dirname(__file__)).'/item/item_abstract.class.php';

class Contest_Service_Entry_Item_Music extends Phpfox_service implements Contest_Service_Entry_Item_Item_Abstract
{
    private $_aImageSize = array(30, 50, 120);

    /**
     * [$_sOriginalSuffix string of the original images
     * @var string
     */
    private $_sOriginalSuffix = '_120';

    public function __construct()
    {
        if (Phpfox::isModule('musicsharing'))
        {
            $this->_sModuleName = 'musicsharing';
            $this->_sTable = Phpfox::getT('m2bmusic_album_song');
            $this->_sTableAlbum = Phpfox::getT('m2bmusic_album');
        }
        else
        {
            $this->_sModuleName = 'music';
            $this->_sTable = Phpfox::getT('music_song');
            $this->_sTableAlbum = Phpfox::getT('music_album');
        }
    }

    public function getAddNewItemLink($iContestId)
    {
        $sAddParamName = Phpfox::getService('contest.constant')->getYnAddParamForNavigateBack();

        if ($this->_sModuleName == 'musicsharing')
        {
            $sLink = Phpfox::getLib('url')->makeUrl('musicsharing.upload', array($sAddParamName => $iContestId));
        }
        else
        {
            $sLink = Phpfox::getLib('url')->makeUrl('music.upload', array($sAddParamName => $iContestId));
        }

        return $sLink;
    }

    public function getItemsOfCurrentUser($iLimit = 5, $iPage = 0)
    {
        if ($this->_sModuleName == 'musicsharing')
        {
            $sConds = 'a.user_id = '.Phpfox::getUserId().' ';
            $sOrder = 's.song_id DESC';
        }
        else
        {
            $sConds = 's.user_id = '.Phpfox::getUserId().' ';
            $sOrder = 's.time_stamp DESC';
        }
        
        //in case we encounter a post form, we know it is a search request
        if ($iSearchId = Phpfox::getLib('request')->get('search-id'))
        {
            $sKeyword = Phpfox::getService('contest.helper')->getSearchKeyword($iSearchId);
            $sConds .= $this->database()->search($sType = 'like%', $mField = array('s.title'), $sSearch = $sKeyword);
        }

        // by default we only get items of current user
        $iCnt = $this->database()->select('COUNT(*)')
        ->from($this->_sTable, 's')
        ->leftJoin($this->_sTableAlbum, 'a', 'a.album_id = s.album_id')
        ->where($sConds)
        ->execute('getSlaveField');

        if ($iCnt > 0)
        {
            $aItems = $this->database()->select('s.*, s.server_id as song_server_id, a.user_id')
            ->from($this->_sTable, 's')
            ->leftJoin($this->_sTableAlbum, 'a', 'a.album_id = s.album_id')
            ->where($sConds)
            ->limit($iPage, $iLimit, $iCnt)
            ->order($sOrder)
            ->execute('getSlaveRows');
        }
        else
        {
            $aItems = array();
        }

        return array($iCnt, $aItems);
    }

    public function getItemFromFox($iItemId)
    {
        if (!$iItemId)
        {
            return false;
        }
        
        $aItem = $this->database()->select('s.*, s.server_id as song_server_id, a.user_id')
        ->from($this->_sTable, 's')
        ->leftJoin($this->_sTableAlbum, 'a', 'a.album_id = s.album_id')
        ->where('s.song_id = '.$iItemId)
        ->execute('getSlaveRow');

        if (!empty($aItem))
        {
            if ($this->_sModuleName == 'musicsharing')
            {
                $aItem['song_path'] = 'musicsharing'.PHPFOX_DS.$aItem['url'];
            }
            else
            {
                $aItem['song_path'] = 'music'.PHPFOX_DS.sprintf($aItem['song_path'], '');
            }
        }
        
        return $aItem;
    }

    public function getTemplateViewPath()
    {
        return 'contest.entry.content.music';
    }

    public function getDataToInsertIntoEntry($iItemId)
    {
        $aItem = $this->getItemFromFox($iItemId);

        $aReturn = array(
            'song_path' => $aItem['song_path'],
            'song_server_id' => $aItem['song_server_id']
        );

        return $aReturn;
    }

    public function getDataFromFoxAdaptedWithContestEntryData($iItemId)
    {
        $aItem = $this->getItemFromFox($iItemId);
        return $aItem;
    }
	
    public function getSongPath($sSong, $iServerId = null)
	{
		if (Phpfox::getParam('core.allow_cdn') && !empty($iServerId))
		{
			$sSong = str_replace('musicsharing'.PHPFOX_DS, '', $sSong);
            $sSong = str_replace('music'.PHPFOX_DS, '', $sSong);
            
            $sTempSong = Phpfox::getLib('cdn')->getUrl($sSong, $iServerId);
			if (!empty($sTempSong))
			{
				$sSong = $sTempSong;
			}
		}
        else
        {
            $sSong = Phpfox::getParam('core.path').'file'.PHPFOX_DS.$sSong;
        }
		
		return $sSong;
	}
}
