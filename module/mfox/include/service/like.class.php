<?php
/**
 * @package mfox
 * @version 3.01
 */
defined('PHPFOX') or exit('NO DICE!');
/**
 * @author ductc@younetco.com
 * @package mfox
 * @subpackage mfox.service
 * @version 3.01
 * @since May 27, 2013
 * @link Mfox Api v1.0
 */
class Mfox_Service_Like extends Phpfox_Service
{
    /**
     * Change the type if needed.
     * @param string $sType
     * @return string
     */
    public function changeType($sType)
    {
        switch ($sType) {
            case 'feed_mini':
                break;

            default:
                break;
        }
        
        return $sType;
    }

    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see like/add
     * 
     * @global string $token
     * @param array $aData
     * @return array
     */
    public function postAction($aData)
    {
        return $this->add($aData);
    }

    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see like/add
     * 
     * @global string $token
     * @param array $aData
     * @return array
     */
    public function add($aData)
    {
        /**
         * @var string
         */
        $sType = isset($aData['sType']) ? $aData['sType'] : '';
        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var string Using in page.
         */
        $sModule = isset($aData['sModule']) ? $aData['sModule'] : '';
        /**
         * @var int Using in page.
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        if (empty($sType) || $iItemId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }
        $sType = $this->changeType($sType);
        switch ($sType) {
            case 'photo':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnPhoto($iItemId);
                break;
            
            case 'photo_album':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnAlbum($iItemId);
                break;
            
            case 'music_song':
                $aError = Phpfox::getService('mfox.song')->checkPrivacyOnSong($iItemId);
                break;
            
            case 'music_album':
                $aError = Phpfox::getService('mfox.album')->checkPrivacyOnMusicAlbum($iItemId);
                break;
            
            case 'video':
                $aError = Phpfox::getService('mfox.video')->checkPrivacyOnVideo($iItemId, $sModule, $iItem);
                break;
            
            case 'user_status':
                $aError = Phpfox::getService('mfox.feed')->checkPrivacyOnUserStatusFeed($iItemId, $sType, $sModule, $iItem);
                break;
            default:
                
                break;
        }
        if (isset($aError))
        {
            return $aError;
        }
        if (Phpfox::isModule('like'))
        {
            if (Phpfox::getService('like.process')->add($sType, $iItemId))
            {
                return array('result' => 1);
            }
        }
        return array(
            'error_code' => 1,
            'error_message' => Phpfox_Error::get()
        );
    }
    
    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see like/delete
     * 
     * @see Like_Service_Process
     * @param array $aData
     * @return array
     */
    public function deleteAction($aData)
    {
        return $this->delete($aData);
    }

    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see like/delete
     * 
     * @see Like_Service_Process
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        /**
         * @var string
         */
        $sType = isset($aData['sType']) ? $aData['sType'] : '';
        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var string Using in page.
         */
        $sModule = isset($aData['sModule']) ? $aData['sModule'] : '';
        /**
         * @var int Using in page.
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        if (empty($sType) || $iItemId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }
        /**
         * @var string
         */
        $sType = $this->changeType($sType);
        switch ($sType) {
            case 'photo':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnPhoto($iItemId);
                break;
            
            case 'photo_album':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnAlbum($iItemId);
                break;
            
            case 'music_song':
                $aError = Phpfox::getService('mfox.song')->checkPrivacyOnSong($iItemId);
                break;
            
            case 'music_album':
                $aError = Phpfox::getService('mfox.album')->checkPrivacyOnMusicAlbum($iItemId);
                break;
            
            case 'video':
                $aError = Phpfox::getService('mfox.video')->checkPrivacyOnVideo($iItemId, $sModule, $iItem);
                break;
            case 'user_status':
                $aError = Phpfox::getService('mfox.feed')->checkPrivacyOnUserStatusFeed($iItemId, $sType, $sModule, $iItem);
                break;
            default:
                
                break;
        }
        if (isset($aError))
        {
            return $aError;
        }
        if (Phpfox::isModule('like'))
        {
            if (Phpfox::getService('like.process')->delete($sType, $iItemId))
            {
                return array('result' => 1);
            }
        }
        return array(
            'error_code' => 1,
            'error_message' => Phpfox_Error::get()
        );
    }
    
    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * + lastLikeIdViewed: int, required.
     * + amountOfLike: int, required.
     * 
     * Output data:
	 * + iLikeId: int
	 * + iUserId: int
	 * + sFullName: string
	 * + sImage: string
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see like/listalllikes
     * 
     * @param array $aData
     * @return array
     */
    public function getAction($aData)
    {
        return $this->listalllikes($aData);
    }

    /**
     * Input data:
     * + iId: int, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * 
     * @param array $aData
     * @param int $iId
     * @return array
     */
    function deleteByIdAction($aData, $iId)
    {
        /**
         * @var array
         */
        $aLike = $this->database()
                ->select('l.type_id AS sType, l.item_id AS iItemId')
                ->from(Phpfox::getT('like'), 'l')
                ->where('l.like_id = ' . (int) $iId)
                ->execute('getRow');
        if (!$aLike)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Unlike with error! "
            );
        }
        return $this->delete($aLike);
    }

    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * + lastLikeIdViewed: int, optional.
     * + amountOfLike: int, optional.
     * 
     * Output data:
	 * + iLikeId: int
	 * + iUserId: int
	 * + sFullName: string
	 * + sImage: string
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see like/listalllikes
     * 
     * @param array $aData
     * @return array
     */
    public function listalllikes($aData)
    {
        /**
         * @var string
         */
        $sType = isset($aData['sType']) ? $aData['sType'] : '';
        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var string Using in page.
         */
        $sModule = isset($aData['sModule']) ? $aData['sModule'] : '';
        /**
         * @var int Using in page.
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        /**
         * @var int
         */
        $lastLikeIdViewed = isset($aData['lastLikeIdViewed']) ? (int) $aData['lastLikeIdViewed'] : 0;
        /**
         * @var int
         */
        $amountOfLike = isset($aData['amountOfLike']) ? (int) $aData['amountOfLike'] : 20;
        if (empty($sType) || $iItemId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }
        $sType = $this->changeType($sType);
        switch ($sType) {
            case 'photo':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnPhoto($iItemId);
                break;
            
            case 'photo_album':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnAlbum($iItemId);
                break;
            
            case 'music_song':
                $aError = Phpfox::getService('mfox.song')->checkPrivacyOnSong($iItemId);
                break;
            
            case 'music_album':
                $aError = Phpfox::getService('mfox.album')->checkPrivacyOnMusicAlbum($iItemId);
                break;
            
            case 'video':
                $aError = Phpfox::getService('mfox.video')->checkPrivacyOnVideo($iItemId, $sModule, $iItem);
                break;
            
            case 'user_status':
                $aError = Phpfox::getService('mfox.feed')->checkPrivacyOnUserStatusFeed($iItemId, $sType, $sModule, $iItem);
                break;
            
            default:
                
                break;
        }
        if (isset($aError))
        {
            return $aError;
        }
        /**
         * @var array
         */
        $aLikes = $this->database()
                ->select('l.like_id, l.user_id, u.full_name, u.user_image, u.server_id AS user_server_id')
                ->from(Phpfox::getT('like'), 'l')
                ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = l.user_id')
                ->where('l.type_id = \'' . $this->database()->escape($sType) . '\' AND l.item_id = ' . (int) $iItemId . ($lastLikeIdViewed > 0 ? ' AND l.like_id > ' . $lastLikeIdViewed : ''))
                ->order('l.like_id DESC')
                ->limit((int)$amountOfLike)
                ->execute('getRows');
        /**
         * @var array
         */
        $aResult = array();
        foreach($aLikes as $aLike)
        {
            $aResult[] = array(
                'iLikeId' => $aLike['like_id'],
                'iUserId' => $aLike['user_id'],
                'sFullName' => $aLike['full_name'],
                'sImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aLike['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aLike['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                )
            );
        }
        return $aResult;
    }

}
