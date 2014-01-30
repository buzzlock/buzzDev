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
 * @since June 5, 2013
 * @link Mfox Api v2.0
 */
class Mfox_Service_Photo extends Phpfox_Service {

    /**
     * Input data:
	 * + iCurrentPhotoId: int, required.
	 * + iLimitPhoto: int, optional.
	 * + iAlbumId: int, optional.
     * 
     * Output data:
     * + iPhotoId: int.
	 * + sTitle: string.
	 * + bCanPostComment: bool.
	 * + sPhotoUrl: string.
	 * + fRating: float.
	 * + iTotalVote: int.
	 * + iTotalBattle: int.
	 * + iAlbumId: int.
	 * + sAlbumName: string.
	 * + iTotalLike: int.
	 * + iTotalDislike: int.
	 * + bIsFeatured: bool.
	 * + bIsCover: bool.
	 * + iTotalView: int.
	 * + iTotalComment: int.
	 * + iTotalDownload: int.
	 * + iAllowDownload: int.
	 * + iIsSponsor: int.
	 * + iOrdering: int.
	 * + bIsProfilePhoto: bool.
	 * + sFileName: string.
	 * + sFileSize: string.
	 * + sMimeType: string.
	 * + sExtension: string.
	 * + sDescription: string.
	 * + iWidth: int.
	 * + iHeight: int.
	 * + sAlbumUrl: string.
	 * + sAlbumTitle: string.
	 * + iAlbumProfileId: int.
	 * + bIsViewed: bool.
	 * + aCategories: array.
	 * + bCategoryList: bool.
	 * + sOriginalDestination: string.
	 * + bIsFriend: bool.
	 * + iUserId: int.
	 * + iProfilePageId: int.
	 * + iUserServerId: int.
	 * + sUserName: string.
	 * + sFullName: string.
	 * + iGender: int.
	 * + sUserImage: string.
	 * + bIsInvisible: bool.
	 * + iUserGroupId: int.
	 * + iLanguageId: int.
	 * + iViewId: int.
	 * + iTypeId: int.
	 * + sModuleId: string.
	 * + iGroupId: int.
	 * + iParentUserId: int.
	 * + iServerId: int.
	 * + iMature: int.
	 * + iAllowComment: int.
	 * + iAllowRate: int.
	 * + bIsLiked: bool.
	 * + iPrivacy: int.
	 * + iPrivacyComment: int.
	 * + sTimeStamp: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/getfullalbumslide
     * 
     * @param array $aData
     * @return array
     */
    public function getfullalbumslide($aData)
    {
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0;
        // Get the current album we are trying to view
        $aAlbum = Phpfox::getService('photo.album')->getForView($aData['iAlbumId']);
        // Make sure this is a valid album
        if (!isset($aAlbum['album_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('photo.invalid_photo_album')
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
		{
            return array(
                'error_message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'),
                'error_code' => 1
            );
		}
        
        $aPreviousData = $aData;
        $aPreviousData['sAction'] = 'previous';
        $aPreviousPhotos = $this->albumslide($aPreviousData);

        $aNextData = $aData;
        $aNextData['sAction'] = 'next-with-current';
        $aNextPhotos = $this->albumslide($aNextData);
        
        foreach($aNextPhotos as $aPhoto)
        {
            $aPreviousPhotos[] = $aPhoto;
        }
        
        return $aPreviousPhotos;
    }
    
    /**
     * Input data:
	 * + iCurrentPhotoId: int, required.
	 * + iLimitPhoto: int, optional. Default 20.
	 * + iAlbumId: int, optional. (default = 0)
	 * + sAction: string, optional. Ex: "next" or "previous".
     * 
     * Output data:
     * + iPhotoId: int.
	 * + sTitle: string.
	 * + bCanPostComment: bool.
	 * + sPhotoUrl: string.
	 * + fRating: float.
	 * + iTotalVote: int.
	 * + iTotalBattle: int.
	 * + iAlbumId: int.
	 * + sAlbumName: string.
	 * + iTotalLike: int.
	 * + iTotalDislike: int.
	 * + bIsFeatured: bool.
	 * + bIsCover: bool.
	 * + iTotalView: int.
	 * + iTotalComment: int.
	 * + iTotalDownload: int.
	 * + iAllowDownload: int.
	 * + iIsSponsor: int.
	 * + iOrdering: int.
	 * + bIsProfilePhoto: bool.
	 * + sFileName: string.
	 * + sFileSize: string.
	 * + sMimeType: string.
	 * + sExtension: string.
	 * + sDescription: string.
	 * + iWidth: int.
	 * + iHeight: int.
	 * + sAlbumUrl: string.
	 * + sAlbumTitle: string.
	 * + iAlbumProfileId: int.
	 * + bIsViewed: bool.
	 * + aCategories: array.
	 * + bCategoryList: bool.
	 * + sOriginalDestination: string.
	 * + bIsFriend: bool.
	 * + iUserId: int.
	 * + iProfilePageId: int.
	 * + iUserServerId: int.
	 * + sUserName: string.
	 * + sFullName: string.
	 * + iGender: int.
	 * + sUserImage: string.
	 * + bIsInvisible: bool.
	 * + iUserGroupId: int.
	 * + iLanguageId: int.
	 * + iViewId: int.
	 * + iTypeId: int.
	 * + sModuleId: string.
	 * + iGroupId: int.
	 * + iParentUserId: int.
	 * + iServerId: int.
	 * + iMature: int.
	 * + iAllowComment: int.
	 * + iAllowRate: int.
	 * + bIsLiked: bool.
	 * + iPrivacy: int.
	 * + iPrivacyComment: int.
	 * + sTimeStamp: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/albumslide
     * 
     * @param array $aData
     * @return array
     */
    public function albumslide($aData)
    {
        if (!isset($aData['iCurrentPhotoId']) || $aData['iCurrentPhotoId'] < 1)
        {
            return array(
                'error_message' => ' Current photo id is not valid! ',
                'error_code' => 1
            );
        }
        
        if (!isset($aData['iAlbumId']) || $aData['iAlbumId'] < 1)
        {
            return array(
                'error_message' => ' Album id is not valid! ',
                'error_code' => 1
            );
        }
        /**
         * @var int
         */
        $iAlbumId = (int) $aData['iAlbumId'];
        // Get the current album we are trying to view
        $aAlbum = Phpfox::getService('photo.album')->getForView($aData['iAlbumId']);
        
        // Make sure this is a valid album
        if (!isset($aAlbum['album_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('photo.invalid_photo_album')
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
		{
            return array(
                'error_message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'),
                'error_code' => 1
            );
		}
        /**
         * @var array
         */
        $aCurrentPhoto = Phpfox::getService('photo')->getPhoto($aData['iCurrentPhotoId'], Phpfox::getUserId());

        if (!isset($aCurrentPhoto['photo_id']))
        {
            return array(
                'error_message' => ' Current photo is not valid! ',
                'error_code' => 1
            );
        }
        
        if (!isset($aData['iLimitPhoto']))
        {
            $aData['iLimitPhoto'] = 10;
        }
        
        $oDb = $this->database();
        $oDb->select('l.like_id as is_liked, u.user_id, u.profile_page_id, u.server_id AS user_server_id, u.user_name, u.full_name, u.gender, u.user_image, u.is_invisible, u.user_group_id, u.language_id, p.*, pa.name AS album_url, pi.*');
        $oDb->from(Phpfox::getT('photo'), 'p');
        
        $oDb->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = "photo" AND l.item_id = p.photo_id AND l.user_id = ' . Phpfox::getUserId());
        $oDb->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id');
        $oDb->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id');
        $oDb->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id');
        
        /**
         * @var array
         */
        $aConditions = array();
        $aConditions[] = 'p.album_id = ' . (int) $iAlbumId;
        
        // Set the current photo id condition.
        if (isset($aData['sAction']) && $aData['sAction'] == 'previous')
        {
            $aConditions[] = 'p.photo_id > ' . (int) $aData['iCurrentPhotoId'];
        }
        elseif(isset($aData['sAction']) && $aData['sAction'] == 'next-with-current')
        {
            $aConditions[] = 'p.photo_id <= ' . (int) $aData['iCurrentPhotoId'];
        }
        else // Next
        {
            $aConditions[] = 'p.photo_id < ' . (int) $aData['iCurrentPhotoId'];
        }
        
        $oDb->where(implode(' AND ', $aConditions));
        
        if (isset($aData['sAction']) && $aData['sAction'] == 'previous')
        {
            $oDb->order('p.photo_id ASC');
            $oDb->limit((int)$aData['iLimitPhoto']);
        }
        else // Next.
        {
            $oDb->order('p.photo_id DESC');
            $oDb->limit((int)$aData['iLimitPhoto'] + 1);
        }
        /**
         * @var array
         */
        $aPhotos = $oDb->execute('getRows');
        
        if (isset($aData['sAction']) && $aData['sAction'] == 'previous')
        {
            $aPhotos = array_reverse($aPhotos);
        }
        else // Next.
        {
            // Do nothing.
        }
        
        $aResult = array();

        foreach ($aPhotos as $aPhoto)
        {
            $aFeed = array(				
				'comment_type_id' => 'photo',
				'privacy' => $aPhoto['privacy'],
				'comment_privacy' => $aPhoto['privacy_comment'],
				'like_type_id' => 'photo',
				'feed_is_liked' => $aPhoto['is_liked'],
				'feed_is_friend' => $aPhoto['is_friend'],
				'item_id' => $aPhoto['photo_id'],
				'user_id' => $aPhoto['user_id'],
				'total_comment' => $aPhoto['total_comment'],
				'total_like' => $aPhoto['total_like'],
				'feed_link' => Phpfox::getLib('url')->permalink('photo', $aPhoto['photo_id'], $aPhoto['title']),
				'feed_title' => $aPhoto['title'],
				'feed_display' => 'view',
				'feed_total_like' => $aPhoto['total_like'],
				'report_module' => 'photo',
				'report_phrase' => Phpfox::getPhrase('photo.report_this_photo'));
            
            $aResult[] = array(
                'iPhotoId' => $aPhoto['photo_id'],
                'sTitle' => $aPhoto['title'],
                'bCanPostComment' => Phpfox::getService('mfox.comment')->checkCanPostComment($aFeed),
                'sPhotoUrl' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aPhoto['server_id'],
                    'path' => 'photo.url_photo',
                    'file' => $aPhoto['destination'],
                    'suffix' => '_1024',
                    'return_url' => true
                        )
                ),
                'fRating' => $aPhoto['total_rating'],
                'iTotalVote' => $aPhoto['total_vote'],
                'iTotalBattle' => $aPhoto['total_battle'],
                'iAlbumId' => $aPhoto['album_id'],
                'sAlbumName' => $aPhoto['album_title'],
                'iTotalLike' => $aPhoto['total_like'],
                'iTotalDislike' => $aPhoto['total_dislike'],
                'bIsFeatured' => $aPhoto['is_featured'],
                'bIsCover' => $aPhoto['is_cover'],
                'iTotalView' => $aPhoto['total_view'],
                'iTotalComment' => $aPhoto['total_comment'],
                'iTotalDownload' => $aPhoto['total_download'],
                'iAllowDownload' => $aPhoto['allow_download'],
                'iIsSponsor' => $aPhoto['is_sponsor'],
                'iOrdering' => $aPhoto['ordering'],
                'bIsProfilePhoto' => $aPhoto['is_profile_photo'],
                'sFileName' => $aPhoto['file_name'],
                'sFileSize' => $aPhoto['file_size'],
                'sMimeType' => $aPhoto['mime_type'],
                'sExtension' => $aPhoto['extension'],
                'sDescription' => $aPhoto['description'],
                'iWidth' => $aPhoto['width'],
                'iHeight' => $aPhoto['height'],
                'sAlbumUrl' => $aPhoto['album_url'],
                'sAlbumTitle' => $aPhoto['album_title'],
                'iAlbumProfileId' => $aPhoto['album_profile_id'],
                'bIsViewed' => $aPhoto['is_viewed'],
                'aCategories' => $aPhoto['categories'],
                'bCategoryList' => $aPhoto['category_list'],
                'sOriginalDestination' => $aPhoto['original_destination'],
                'bIsFriend' => (bool) $aPhoto['is_friend'],
                'iUserId' => $aPhoto['user_id'],
                'iProfilePageId' => $aPhoto['profile_page_id'],
                'iUserServerId' => $aPhoto['user_server_id'],
                'sUserName' => $aPhoto['user_name'],
                'sFullName' => $aPhoto['full_name'],
                'iGender' => $aPhoto['gender'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aPhoto['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aPhoto['user_image'],
                    'suffix' => '_20_square',
                    'return_url' => true
                        )
                ),
                'bIsInvisible' => $aPhoto['is_invisible'],
                'iUserGroupId' => $aPhoto['user_group_id'],
                'iLanguageId' => (int) $aPhoto['language_id'],
                'iViewId' => $aPhoto['view_id'],
                'iTypeId' => $aPhoto['type_id'],
                'sModuleId' => $aPhoto['module_id'],
                'iGroupId' => (int) $aPhoto['group_id'],
                'iParentUserId' => $aPhoto['parent_user_id'],
                'iServerId' => $aPhoto['server_id'],
                'iMature' => $aPhoto['mature'],
                'iAllowComment' => $aPhoto['allow_comment'],
                'iAllowRate' => $aPhoto['allow_rate'],
                'bIsLiked' => isset($aPhoto['is_liked']) ? $aPhoto['is_liked'] : 0,
                'iPrivacy' => $aPhoto['privacy'],
                'iPrivacyComment' => $aPhoto['privacy_comment'],
                'sTimeStamp' => date('l, F j, o', (int) $aPhoto['time_stamp']) . ' at ' . date('h:i a', (int) $aPhoto['time_stamp'])
            );
        }

        return $aResult;
    }
    
    /**
     * Input data:
	 * + iLimitPhoto: int, optional.
	 * + iAlbumId: int, optional.
     * + iCurrentPhotoId: int, required.
     * + sView: string, optional.
     * + iCategoryId: int, optional.
     * + bIsProfileUser: bool, optional. In profile.
     * + sModuleId: string, optional. Ex: "pages".
     * + iUserId: int, optional. In profile.
     * 
     * Output data:
     * + iPhotoId: int.
	 * + sTitle: string.
	 * + bCanPostComment: bool.
	 * + sPhotoUrl: string.
	 * + fRating: float.
	 * + iTotalVote: int.
	 * + iTotalBattle: int.
	 * + iAlbumId: int.
	 * + sAlbumName: string.
	 * + iTotalLike: int.
	 * + iTotalDislike: int.
	 * + bIsFeatured: bool.
	 * + bIsCover: bool.
	 * + iTotalView: int.
	 * + iTotalComment: int.
	 * + iTotalDownload: int.
	 * + iAllowDownload: int.
	 * + iIsSponsor: int.
	 * + iOrdering: int.
	 * + bIsProfilePhoto: bool.
	 * + sFileName: string.
	 * + sFileSize: string.
	 * + sMimeType: string.
	 * + sExtension: string.
	 * + sDescription: string.
	 * + iWidth: int.
	 * + iHeight: int.
	 * + sAlbumUrl: string.
	 * + sAlbumTitle: string.
	 * + iAlbumProfileId: int.
	 * + bIsViewed: bool.
	 * + aCategories: array.
	 * + bCategoryList: bool.
	 * + sOriginalDestination: string.
	 * + bIsFriend: bool.
	 * + iUserId: int.
	 * + iProfilePageId: int.
	 * + iUserServerId: int.
	 * + sUserName: string.
	 * + sFullName: string.
	 * + iGender: int.
	 * + sUserImage: string.
	 * + bIsInvisible: bool.
	 * + iUserGroupId: int.
	 * + iLanguageId: int.
	 * + iViewId: int.
	 * + iTypeId: int.
	 * + sModuleId: string.
	 * + iGroupId: int.
	 * + iParentUserId: int.
	 * + iServerId: int.
	 * + iMature: int.
	 * + iAllowComment: int.
	 * + iAllowRate: int.
	 * + bIsLiked: bool.
	 * + iPrivacy: int.
	 * + iPrivacyComment: int.
	 * + sTimeStamp: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/albumslide
     * 
     * @param array $aData
     * @return array
     */
    public function getfullphotoslide($aData)
    {
        $aPreviousData = $aData;
        $aPreviousData['sAction'] = 'previous';
        $aPreviousPhotos = $this->photoslide($aPreviousData);

        $aNextData = $aData;
        $aNextData['sAction'] = 'next-with-current';
        $aNextPhotos = $this->photoslide($aNextData);
        
        foreach($aNextPhotos as $aPhoto)
        {
            $aPreviousPhotos[] = $aPhoto;
        }
        
        return $aPreviousPhotos;
    }
    
    /**
     * Input data:
     * + iCurrentPhotoId: int, required.
     * + iLimitPhoto: int, optional.
     * + sView: string, optional.
     * + iCategoryId: int, optional.
     * + bIsProfileUser: bool, optional. In profile.
     * + sModuleId: string, optional. In page: "pages".
     * + iUserId: int, optional. In profile.
     * + sAction: string, optional. Ex: "next" or "previous".
     * 
     * Output data:
     * + iPhotoId: int.
	 * + sTitle: string.
	 * + bCanPostComment: bool.
	 * + sPhotoUrl: string.
	 * + fRating: float.
	 * + iTotalVote: int.
	 * + iTotalBattle: int.
	 * + iAlbumId: int.
	 * + sAlbumName: string.
	 * + iTotalLike: int.
	 * + iTotalDislike: int.
	 * + bIsFeatured: bool.
	 * + bIsCover: bool.
	 * + iTotalView: int.
	 * + iTotalComment: int.
	 * + iTotalDownload: int.
	 * + iAllowDownload: int.
	 * + iIsSponsor: int.
	 * + iOrdering: int.
	 * + bIsProfilePhoto: bool.
	 * + sFileName: string.
	 * + sFileSize: string.
	 * + sMimeType: string.
	 * + sExtension: string.
	 * + sDescription: string.
	 * + iWidth: int.
	 * + iHeight: int.
	 * + sAlbumUrl: string.
	 * + sAlbumTitle: string.
	 * + iAlbumProfileId: int.
	 * + bIsViewed: bool.
	 * + aCategories: array.
	 * + bCategoryList: bool.
	 * + sOriginalDestination: string.
	 * + bIsFriend: bool.
	 * + iUserId: int.
	 * + iProfilePageId: int.
	 * + iUserServerId: int.
	 * + sUserName: string.
	 * + sFullName: string.
	 * + iGender: int.
	 * + sUserImage: string.
	 * + bIsInvisible: bool.
	 * + iUserGroupId: int.
	 * + iLanguageId: int.
	 * + iViewId: int.
	 * + iTypeId: int.
	 * + sModuleId: string.
	 * + iGroupId: int.
	 * + iParentUserId: int.
	 * + iServerId: int.
	 * + iMature: int.
	 * + iAllowComment: int.
	 * + iAllowRate: int.
	 * + bIsLiked: bool.
	 * + iPrivacy: int.
	 * + iPrivacyComment: int.
	 * + sTimeStamp: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/photoslide
     * 
     * @param array $aData
     * @return array
     */
    public function photoslide($aData)
    {
        if (!isset($aData['iCurrentPhotoId']) || $aData['iCurrentPhotoId'] < 1)
        {
            return array(
                'error_message' => ' Current photo id is not valid! ',
                'error_code' => 1
            );
        }
        
        if (!isset($aData['iLimitPhoto']))
        {
            $aData['iLimitPhoto'] = 10;
        }
        /**
         * @var array
         */
        $aCurrentPhoto = Phpfox::getService('photo')->getPhoto($aData['iCurrentPhotoId'], Phpfox::getUserId());
        if (!isset($aCurrentPhoto['photo_id']))
        {
            return array(
                'error_message' => ' Current photo is not valid! ',
                'error_code' => 1
            );
        }
        
        $oDb = $this->database();

        $oDb->select('pa.name AS album_name, pa.profile_id AS album_profile_id, ppc.name as category_name, ppc.category_id, l.like_id as is_liked, adisliked.action_id as is_disliked, photo.*, u.user_id, u.profile_page_id, u.server_id AS user_server_id, u.user_name, u.full_name, u.gender, u.user_image, u.is_invisible, u.user_group_id, u.language_id, pi.width, pi.height');
        $oDb->from(Phpfox::getT('photo'), 'photo');
        
        if (isset($aData['sView']) && $aData['sView'] == 'friend')
        {
            // friend
            $oDb->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = photo.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }
        
        if (isset($aData['iCategoryId']) && $aData['iCategoryId'] > 0)
        {
            // category
            $oDb->innerJoin(Phpfox::getT('photo_category_data'), 'pcd', 'pcd.photo_id = photo.photo_id');
        }
        $oDb->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = photo.photo_id');
        $oDb->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = photo.album_id');
        $oDb->leftJoin(Phpfox::getT('photo_category_data'), 'ppcd', 'ppcd.photo_id = photo.photo_id');
        $oDb->leftJoin(Phpfox::getT('photo_category'), 'ppc', 'ppc.category_id = ppcd.category_id');
        $oDb->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = "photo" AND l.item_id = photo.photo_id AND l.user_id = ' . Phpfox::getUserId());
        $oDb->leftJoin(Phpfox::getT('action'), 'adisliked', 'adisliked.action_type_id = 2 AND adisliked.item_id = photo.photo_id AND adisliked.user_id = ' . Phpfox::getUserId());
        $oDb->join(Phpfox::getT('user'), 'u', 'u.user_id = photo.user_id');

        /**
         * @var array
         */
        $aConditions = array();
        if (isset($aData['sView']) && $aData['sView'] == 'my')
        {
            $aConditions[] = 'photo.user_id = ' . Phpfox::getUserId();
        }
        elseif (isset($aData['sView']) && $aData['sView'] == 'friend')
        {
            $aConditions[] = 'photo.view_id = 0';
            $aConditions[] = 'photo.group_id = 0';
            $aConditions[] = 'photo.type_id = 0';
            $aConditions[] = 'photo.privacy IN(0,1,2)';
        }
        else
        {
            // Not profile user.
            if (!isset($aData['bIsProfileUser']) || $aData['bIsProfileUser'] != 'true')
            {
                // In page.
                if (isset($aData['sModuleId']) && $aData['sModuleId'] != '' && isset($aData['iItemId']) && $aData['iItemId'] > 0)
                {
                    $aConditions[] = 'photo.view_id = 0';
                    $aConditions[] = 'photo.module_id = \'' . Phpfox::getLib('database')->escape($aData['sModuleId']) . '\'';
                    $aConditions[] = 'photo.group_id = ' . (int) $aData['iItemId'];
                    $aConditions[] = 'photo.privacy IN(0)';
                }
                else  // All - Public and not in page.
                {
                    $aConditions[] = 'photo.view_id = 0';
                    $aConditions[] = 'photo.group_id = 0';
                    $aConditions[] = 'photo.type_id = 0';
                    $aConditions[] = 'photo.privacy IN(0)';
                }
            }
        }
        // Profile user.
        if (isset($aData['bIsProfileUser']) && $aData['bIsProfileUser'] == 'true')
        {
            if (!isset($aData['iUserId']) || $aData['iUserId'] < 1)
            {
                return array(
                    'error_code' => 1,
                    'error_message' => "User id is not valid!"
                );
            }
            elseif ($aData['iUserId'] == Phpfox::getUserId()) // My profile.
            {
                $aConditions[] = 'photo.view_id IN(0,2)';
                $aConditions[] = 'photo.group_id = 0';
                $aConditions[] = 'photo.type_id = 0';
                $aConditions[] = 'photo.privacy IN(0,1,2,3,4)';
                $aConditions[] = 'photo.user_id = ' . Phpfox::getUserId();
            }
            elseif ($aData['iUserId'] != Phpfox::getUserId()) // User profile.
            {
                $aConditions[] = 'photo.view_id = 0';
                $aConditions[] = 'photo.group_id = 0';
                $aConditions[] = 'photo.type_id = 0';
                $aConditions[] = 'photo.privacy IN(0,1,2,3,4)';
                $aConditions[] = 'photo.user_id = ' . (int) $aData['iUserId'];
            }
        }
        // Category filter.
        if (isset($aData['iCategoryId']) && $aData['iCategoryId'] > 0)
        {
            $aConditions[] = 'pcd.category_id = ' . (int) $aData['iCategoryId'];
        }
        // Set the current photo id condition.
        if (isset($aData['sAction']) && $aData['sAction'] == 'previous')
        {
            $aConditions[] = 'photo.photo_id > ' . (int) $aData['iCurrentPhotoId'];
        }
        elseif(isset($aData['sAction']) && $aData['sAction'] == 'next-with-current')
        {
            $aConditions[] = 'photo.photo_id <= ' . (int) $aData['iCurrentPhotoId'];
        }
        else // Next
        {
            $aConditions[] = 'photo.photo_id < ' . (int) $aData['iCurrentPhotoId'];
        }
        // Set conditions.
        $oDb->where(implode(' AND ', $aConditions));
        $oDb->group('photo.photo_id');
        // Check action to get limit.
        if (isset($aData['sAction']) && $aData['sAction'] == 'previous')
        {
            $oDb->order('photo.photo_id ASC');
            $oDb->limit((int)$aData['iLimitPhoto']);
        }
        else // Next.
        {
            $oDb->order('photo.photo_id DESC');
            $oDb->limit((int)$aData['iLimitPhoto'] + 1);
        }
        /**
         * @var array
         */
        $aPhotos = $oDb->execute('getRows');
        
        if (isset($aData['sAction']) && $aData['sAction'] == 'previous')
        {
            $aPhotos = array_reverse($aPhotos);
        }
        else // Next.
        {
            // Do nothing.
        }
        /**
         * @var array
         */
        $aResult = array();
        foreach ($aPhotos as $aPhoto)
        {
            $aFeed = array(				
				'comment_type_id' => 'photo',
				'privacy' => $aPhoto['privacy'],
				'comment_privacy' => $aPhoto['privacy_comment'],
				'like_type_id' => 'photo',
				'feed_is_liked' => $aPhoto['is_liked'],
				'feed_is_friend' => $aPhoto['is_friend'],
				'item_id' => $aPhoto['photo_id'],
				'user_id' => $aPhoto['user_id'],
				'total_comment' => $aPhoto['total_comment'],
				'total_like' => $aPhoto['total_like'],
				'feed_link' => Phpfox::getLib('url')->permalink('photo', $aPhoto['photo_id'], $aPhoto['title']),
				'feed_title' => $aPhoto['title'],
				'feed_display' => 'view',
				'feed_total_like' => $aPhoto['total_like'],
				'report_module' => 'photo',
				'report_phrase' => Phpfox::getPhrase('photo.report_this_photo'));
        
            $aResult[] = array(
                'iPhotoId' => $aPhoto['photo_id'],
                'sTitle' => $aPhoto['title'],
                'bCanPostComment' => Phpfox::getService('mfox.comment')->checkCanPostComment($aFeed),
                'sPhotoUrl' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aPhoto['server_id'],
                    'path' => 'photo.url_photo',
                    'file' => $aPhoto['destination'],
                    'suffix' => '_1024',
                    'return_url' => true
                        )
                ),
                'fRating' => $aPhoto['total_rating'],
                'iTotalVote' => $aPhoto['total_vote'],
                'iTotalBattle' => $aPhoto['total_battle'],
                'iAlbumId' => $aPhoto['album_id'],
                'sAlbumName' => $aPhoto['album_title'],
                'iTotalLike' => $aPhoto['total_like'],
                'iTotalDislike' => $aPhoto['total_dislike'],
                'bIsFeatured' => $aPhoto['is_featured'],
                'bIsCover' => $aPhoto['is_cover'],
                'iTotalView' => $aPhoto['total_view'],
                'iTotalComment' => $aPhoto['total_comment'],
                'iTotalDownload' => $aPhoto['total_download'],
                'iAllowDownload' => $aPhoto['allow_download'],
                'iIsSponsor' => $aPhoto['is_sponsor'],
                'iOrdering' => $aPhoto['ordering'],
                'bIsProfilePhoto' => $aPhoto['is_profile_photo'],
                'sFileName' => $aPhoto['file_name'],
                'sFileSize' => $aPhoto['file_size'],
                'sMimeType' => $aPhoto['mime_type'],
                'sExtension' => $aPhoto['extension'],
                'sDescription' => $aPhoto['description'],
                'iWidth' => $aPhoto['width'],
                'iHeight' => $aPhoto['height'],
                'sAlbumUrl' => $aPhoto['album_url'],
                'sAlbumTitle' => $aPhoto['album_title'],
                'iAlbumProfileId' => $aPhoto['album_profile_id'],
                'bIsViewed' => $aPhoto['is_viewed'],
                'aCategories' => $aPhoto['categories'],
                'bCategoryList' => $aPhoto['category_list'],
                'sOriginalDestination' => $aPhoto['original_destination'],
                'bIsFriend' => (bool) $aPhoto['is_friend'],
                'iUserId' => $aPhoto['user_id'],
                'iProfilePageId' => $aPhoto['profile_page_id'],
                'iUserServerId' => $aPhoto['user_server_id'],
                'sUserName' => $aPhoto['user_name'],
                'sFullName' => $aPhoto['full_name'],
                'iGender' => $aPhoto['gender'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aPhoto['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aPhoto['user_image'],
                    'suffix' => MAX_SIZE_OF_USER_IMAGE,
                    'return_url' => true
                        )
                ),
                'bIsInvisible' => $aPhoto['is_invisible'],
                'iUserGroupId' => $aPhoto['user_group_id'],
                'iLanguageId' => (int) $aPhoto['language_id'],
                'iViewId' => $aPhoto['view_id'],
                'iTypeId' => $aPhoto['type_id'],
                'sModuleId' => $aPhoto['module_id'],
                'iGroupId' => (int) $aPhoto['group_id'],
                'iParentUserId' => $aPhoto['parent_user_id'],
                'iServerId' => $aPhoto['server_id'],
                'iMature' => $aPhoto['mature'],
                'iAllowComment' => $aPhoto['allow_comment'],
                'iAllowRate' => $aPhoto['allow_rate'],
                'bIsLiked' => isset($aPhoto['is_liked']) ? $aPhoto['is_liked'] : 0,
                'iPrivacy' => $aPhoto['privacy'],
                'iPrivacyComment' => $aPhoto['privacy_comment'],
                'sTimeStamp' => date('l, F j, o', (int) $aPhoto['time_stamp']) . ' at ' . date('h:i a', (int) $aPhoto['time_stamp'])
            );
        }

        return $aResult;
    }

    /**
     * Input data:
     * + iAlbumId: int, required.
     * + bIsUserProfile: bool, required.
     * 
     * Output data:
     * + bIsFriend: bool.
     * + bIsLiked: bool.
     * + iAlbumId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + iUserId: int.
     * + sName: string.
     * + iTotalPhoto: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + sDescription: string.
     * + sFullName: string.
     * + sUserImage: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/albumview
     * 
     * @see Phpfox_Image_Helper
     * @param array $aData
     * @return array
     */
    public function albumview($aData)
    {
        if (!Phpfox::getUserParam('photo.can_view_photo_albums'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view this photo albums!"
            );
        }

        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view photos!"
            );
        }

        if (!isset($aData['iAlbumId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view this photo album!"
            );
        }

        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('comment_photo_album', $aData['iAlbumId'], Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('photo_album_like', $aData['iAlbumId'], Phpfox::getUserId());
        }

        /**
         * @var bool
         */
        $bIsProfilePictureAlbum = false;
        if (isset($aData['bIsUserProfile']) && $aData['bIsUserProfile'] == "true")
        {
            $bIsProfilePictureAlbum = true;
            $aAlbum = Phpfox::getService('photo.album')->getForProfileView($aData['iAlbumId']);
            $aAlbum['name'] = Phpfox::getPhrase('photo.profile_pictures');
        }
        else
        {
            // Get the current album we are trying to view
            $aAlbum = Phpfox::getService('photo.album')->getForView($aData['iAlbumId']);
            if ($aAlbum['profile_id'] > 0)
            {
                $bIsProfilePictureAlbum = true;
                $aAlbum['name'] = Phpfox::getPhrase('photo.profile_pictures');
            }
        }
        
        // Make sure this is a valid album
        if (!isset($aAlbum['album_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('photo.invalid_photo_album')
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
		{
            return array(
                'error_message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'),
                'error_code' => 1
            );
		}
        return array(
            'bIsFriend' => $aAlbum['is_friend'],
            'bIsLiked' => $aAlbum['is_liked'],
            'iAlbumId' => $aAlbum['album_id'],
            'iViewId' => $aAlbum['view_id'],
            'iPrivacy' => $aAlbum['privacy'],
            'iPrivacyComment' => $aAlbum['privacy_comment'],
            'iUserId' => $aAlbum['user_id'],
            'sName' => $aAlbum['name'],
            'iTotalPhoto' => $aAlbum['total_photo'],
            'iTotalComment' => $aAlbum['total_comment'],
            'iTotalLike' => $aAlbum['total_like'],
            'sDescription' => $aAlbum['description'],
            'sFullName' => $aAlbum['full_name'],
            'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                'user' => $aAlbum,
                'suffix' => '_50_square',
                'max_height' => 50,
                'max_width' => 50,
                'return_url' => true
            ))
        );
    }

    /**
     * Input data:
     * + iAlbumId: int, required.
     * + bIsUserProfile: bool, optional. (In profile)
     * + iProfileId: int, optional. (In profile)
     * + iAmountOfPhoto: int, optional.
     * + iLastPhotoIdViewed: int, optional.
     * + sType: string, optional.
     * 
     * Output data:
     * + iPhotoId: int.
     * + sTitle: string.
     * + sPhotoUrl: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/listalbumphoto
     * 
     * @param array $aData
     * @return array
     */
    public function listalbumphoto($aData)
    {
        if (!Phpfox::getUserParam('photo.can_view_photo_albums'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view this photo albums!"
            );
        }

        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view photos!"
            );
        }

        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('comment_photo_album', $aData['iAlbumId'], Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('photo_album_like', $aData['iAlbumId'], Phpfox::getUserId());
        }
        /**
         * @var int
         */
        $iPageSize = isset($aData['iAmountOfPhoto']) ? (int) $aData['iAmountOfPhoto'] : 10;
        /**
         * @var int
         */
        $iLastPhotoIdViewed = isset($aData['iLastPhotoIdViewed']) ? (int) $aData['iLastPhotoIdViewed'] : 0;
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? $aData['iAlbumId'] : 0;
        if ($iAlbumId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => "Album id is not valid!"
            );
        }
        
        /**
         * @var bool
         */
        $bIsProfilePictureAlbum = false;
        if (isset($aData['bIsUserProfile']) && $aData['bIsUserProfile'] == 'true')
        {
            $iProfileId = isset($aData['iProfileId']) ? (int) $aData['iProfileId'] : 0;
            if ($iProfileId < 1)
            {
                return array(
                    'error_code' => 1,
                    'error_message' => "Profile id is not valid!"
                );
            }
            
            $bIsProfilePictureAlbum = true;
            $aAlbum = Phpfox::getService('photo.album')->getForProfileView((int) $iProfileId);
            $aAlbum['name'] = Phpfox::getPhrase('photo.profile_pictures');
        }
        else
        {
            // Get the current album we are trying to view
            $aAlbum = Phpfox::getService('photo.album')->getForView((int) $aData['iAlbumId']);
            if ($aAlbum['profile_id'] > 0)
            {
                $bIsProfilePictureAlbum = true;
                $aAlbum['name'] = Phpfox::getPhrase('photo.profile_pictures');
            }
        }
        
        // Make sure this is a valid album
        if (!isset($aAlbum['album_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('photo.invalid_photo_album')
            );
        }
        
        /**
         * @var array
         */
        $aCallback = null;
        if (!empty($aAlbum['module_id']))
        {
            $aCallback = Phpfox::callback($aAlbum['module_id'] . '.getPhotoDetails', $aAlbum);
        }
        
        if (Phpfox::isModule('privacy'))
        {
            $bResult = Phpfox::getService('privacy')->check('photo_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true);

            if (!$bResult)
            {
                return array(
                    'error_code' => 1,
                    'error_message' => "You don't have permission to view this photo albums!"
                );
            }
        }

        // Setup the page data
        $iPage = 0;

        if (isset($aData['sType']) && $aData['sType'] == 'new')
        {
            $sCond = $iLastPhotoIdViewed > 0 ? ' AND p.photo_id > ' . $iLastPhotoIdViewed : '';
        }
        else
        {
            $sCond = $iLastPhotoIdViewed > 0 ? ' AND p.photo_id < ' . $iLastPhotoIdViewed : '';
        }

        // Create the SQL condition array
        $aConditions = array();
        $aConditions[] = 'p.album_id = ' . $iAlbumId . ' ' . $sCond;
        
        // Get the photos based on the conditions
        list($iCnt, $aPhotos) = Phpfox::getService('photo')->get($aConditions, 'p.photo_id DESC', $iPage, $iPageSize);
        
        $aResult = array();

        foreach ($aPhotos as $aPhoto)
        {
            $aResult[] = array(
                'iPhotoId' => $aPhoto['photo_id'],
                'sTitle' => $aPhoto['title'],
                'sPhotoUrl' => Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aPhoto['server_id'],
                        'path' => 'photo.url_photo',
                        'file' => $aPhoto['destination'],
                        'suffix' => MAX_SIZE_OF_USER_IMAGE_PHOTO,
                        'return_url' => true,
                    )
                )
            );
        }

        return $aResult;
    }

    /**
     * Input data:
     * + iPhotoId: int, required.
     * + sFeedback: string, optional.
     * + iReport: int, optional.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/report
     * 
     * @param array $aData
     * @return array
     */
    public function report($aData)
    {
        if (!isset($aData['iPhotoId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getForEdit($aData['iPhotoId']);
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid or has been deleted! "
            );
        }
        $oReport = Phpfox::getService('report');
        /**
         * @var array
         */
        $aVals = array(
            'type' => 'photo',
            'id' => $aData['iPhotoId']
        );

        if (isset($aData['sFeedback']) && !Phpfox::getLib('parse.format')->isEmpty($aData['sFeedback']))
        {
            $aVals['feedback'] = $aData['sFeedback'];
        }
        else
        {
            $aVals['feedback'] = '';

            $aReasons = $oReport->getOptions($aVals['type']);
            $aReasonId = array();
            foreach ($aReasons as $aReason)
            {
                $aReasonId[$aReason['report_id']] = $aReason['report_id'];
            }

            if (!isset($aData['iReport']) || !isset($aReasonId[$aData['iReport']]))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => " Reason is not valid! "
                );
            }
        }
        $aVals['report'] = isset($aData['iReport']) ? $aData['iReport'] : '';
        /**
         * @var bool
         */
        $bCanReport = $oReport->canReport($aVals['type'], $aVals['id']);
        if ($bCanReport)
        {
            if ($bResult = Phpfox::getService('report.data.process')->add($aVals['report'], $aVals['type'], $aVals['id'], $aVals['feedback']))
            {
                return array(
                    'result' => $bResult,
                    'message' => "Report successfully!"
                );
            }
            else
            {
                return array(
                    'error_code' => 1,
                    'error_message' => Phpfox_Error::get()
                );
            }
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('report.you_have_already_reported_this_item')
            );
        }
    }
    
    /**
     * Input data:
     * + iPhotoId: int.
     * 
     * Output data:
     * + result: int.
     * + message: string.
     * + sProfileImage: string.
     * + error_code: int.
     * + error_message: string.
     * + system_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/setprofile
     * 
     * @param array $aData
     * @return array
     */
    public function setprofile($aData)
    {
        if (!isset($aData['iPhotoId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getForEdit($aData['iPhotoId']);
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid or has been deleted! "
            );
        }
        /**
         * @var int
         */
        $iUserId = Phpfox::getService('mfox.auth')->hasAccess('photo', 'photo_id', $aData['iPhotoId'], 'photo.can_edit_own_photo', 'photo.can_edit_other_photo');
        if (!$iUserId)
        {
            return array(
                'error_code' => 1,
                'error_message' => 'You do not have permission to set profile this photo!'
            );
        }
        /**
         * @var bool
         */
        $bResult = Phpfox::getService('photo.process')->makeProfilePicture($aData['iPhotoId']);
        if ($bResult)
        {
            return array(
                'result' => $bResult,
                'message' => Phpfox::getPhrase('photo.profile_photo_successfully_updated'),
                'sProfileImage' => Phpfox::getLib('image.helper')->display(array(
                        'server_id' => Phpfox::getUserBy('server_id'),
                        'title' => Phpfox::getUserBy('full_name'),
                        'path' => 'core.url_user',
                        'file' => Phpfox::getUserBy('user_image'),
                        'suffix' => '_50_square',
                        'return_url' => true,
                    )
                )
            );
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => 'You do not have permission to set profile this photo!',
                'system_message' => Phpfox_Error::get()
            );
        }
    }

    /**
     * Input data:
     * + iPhotoId: int, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: bool.
     * + message: string.
     * + sCoverImage: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/setcover
     * 
     * @param array $aData
     * @return array
     */
    public function setcover($aData)
    {
        if (!isset($aData['iPhotoId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getForEdit($aData['iPhotoId']);
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid or has been deleted! "
            );
        }
        /**
         * @var int
         */
        $iUserId = Phpfox::getService('mfox.auth')->hasAccess('photo', 'photo_id', $aData['iPhotoId'], 'photo.can_edit_own_photo', 'photo.can_edit_other_photo');
        if (!$iUserId)
        {
            return array(
                'error_code' => 1,
                'error_message' => 'You do not have permission to set cover this photo!'
            );
        }
        /**
         * var bool
         */
        $bResult = Phpfox::getService('user.process')->updateCoverPhoto($aData['iPhotoId']);
        if ($bResult)
        {
            return array(
                'result' => $bResult,
                'message' => "Set cover photo successfully!",
                'sCoverImage' => Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aPhoto['server_id'],
                        'path' => 'photo.url_photo',
                        'file' => $aPhoto['destination'],
                        'suffix' => '_500',
                        'return_url' => true,
                    )
                )
            );
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox_Error::get()
            );
        }
    }

    /**
     * Input data:
     * + iPhotoId: int, required.
     * 
     * Output data:
     * + result: int
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/delete
     * 
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        if (!isset($aData['iPhotoId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */        
        $aPhoto = Phpfox::getService('photo')->getForEdit($aData['iPhotoId']);
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid or has been deleted! "
            );
        }

        if ($this->deletePhoto($aData['iPhotoId']))
        {
            return array('result' => true, 'message' => ' Photo successfully deleted! ');
        }
        
        return array(
            'error_code' => 1,
            'error_message' => 'Can not delete this photo! Maybe you don\'t have permission to delete it.'
        );
    }
    /**
     * Used to delete a photo.
     *
     * @param int $iId ID of the photo we want to delete.
     *
     * @return boolean We return true since if nothing fails we were able to delete the image.
     */
    public function deletePhoto($iId, $bPass = false)
    {
		// Get the image ID and full path to the image.
		$aPhoto = $this->database()->select('user_id, module_id, group_id, is_sponsor, album_id, photo_id, destination')
			->from(Phpfox::getT('photo'))
			->where('photo_id = ' . (int) $iId)
			->execute('getRow');
	
		if (!isset($aPhoto['user_id']))
		{
		    return false;
		}
		
		if ($aPhoto['module_id'] == 'pages' && Phpfox::getService('pages')->isAdmin($aPhoto['group_id']))
		{		
			$bPass = true;
		}
	
		if ($bPass === false && !Phpfox::getService('mfox.auth')->hasAccess('photo', 'photo_id', $iId, 'photo.can_delete_own_photo', 'photo.can_delete_other_photos', $aPhoto['user_id']))
		{
		    return false;
		}
	
		// Create the total file size var for all the images
		$iFileSizes = 0;
		// Make sure the original image exists
		if (!empty($aPhoto['destination']) && file_exists(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '')))
		{
		    // Add to the file size var
		    $iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], ''));
	
		    // Remove the image
		    Phpfox::getLib('file')->unlink(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], ''));
		}
	
		// Loop thru all the other smaller images
		foreach(Phpfox::getParam('photo.photo_pic_sizes') as $iSize)
		{
		    // Make sure the image exists
		    if (!empty($aPhoto['destination']) && file_exists(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize)))
		    {
				// Add to the file size var
				$iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize));
		
				// Remove the image
				Phpfox::getLib('file')->unlink(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize));
		    }
		}
        
		// Delete this entry from the database
		$this->database()->delete(Phpfox::getT('photo'), 'photo_id = ' . $aPhoto['photo_id']);
		$this->database()->delete(Phpfox::getT('photo_info'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the ratings for this photo
		$this->database()->delete(Phpfox::getT('photo_rating'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the photo tags
		$this->database()->delete(Phpfox::getT('photo_tag'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the category_data
		$this->database()->delete(Phpfox::getT('photo_category_data'), 'photo_id = ' . $aPhoto['photo_id']);
		// delete the battles
		$this->database()->delete(Phpfox::getT('photo_battle'), 'photo_1 = ' . $aPhoto['photo_id'] . ' OR photo_2 = ' . $aPhoto['photo_id']);
	
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('photo', $iId) : null);
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_photo', $iId) : null);
		(Phpfox::isModule('tag') ? Phpfox::getService('tag.process')->deleteForItem($aPhoto['user_id'], $iId, 'photo') : null);
	
		// Update user space usage
		if ($iFileSizes > 0)
		{
		    Phpfox::getService('user.space')->update($aPhoto['user_id'], 'photo', $iFileSizes, '-');
		}
	
		// Update user activity
		Phpfox::getService('user.activity')->update($aPhoto['user_id'], 'photo', '-');
	
		if ($aPhoto['album_id'] > 0)
		{
		    Phpfox::getService('photo.album.process')->updateCounter($aPhoto['album_id'], 'total_photo', true);
		}

		if ($aPhoto['is_sponsor'] == 1)
		{
			$this->cache()->remove('photo_sponsored');
		}
		return true;
    }
    
    /**
     * Input data: 
     * + iPhotoId: int, required.
     * + sTitle: string, required.
     * + sDescription: string, optional.
     * + sCategory: string, optional.
     * + sTagList: string, optional.
     * + iMature: int, optional.
     * + bAllowRate: bool, optional.
     * + bAllowDownload: bool, optional.
     * + bSetAlbumCover: bool, optional.
     * + iMoveTo: int, optional.
     * + iPrivacy: int, optional.
     * + iPrivacyComment: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/edit
     * 
     * @param array $aData
     * @return array
     */
    public function edit($aData)
    {
        if (!isset($aData['iPhotoId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getForEdit($aData['iPhotoId']);
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid! "
            );
        }
        /**
         * @var int
         */
        $iUserId = Phpfox::getService('mfox.auth')->hasAccess('photo', 'photo_id', $aData['iPhotoId'], 'photo.can_edit_own_photo', 'photo.can_edit_other_photo');
        if ($iUserId)
        {
            if (!isset($aData['sTitle']) || Phpfox::getLib('parse.format')->isEmpty($aData['sTitle']))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => " Title is not valid! "
                );
            }
            /**
             * @var array
             */
            $aVals = array(
                'title' => $aData['sTitle'],
                'description' => isset($aData['sDescription']) ? $aData['sDescription'] : '',
                'tag_list' => isset($aData['sTagList']) ? $aData['sTagList'] : $aPhoto['tag_list'],
                'mature' => isset($aData['iMature']) ? (int) $aData['iMature'] : $aPhoto['mature'],
                'allow_rate' => isset($aData['bAllowRate']) ? (bool) $aData['bAllowRate'] : $aPhoto['allow_rate'],
                'allow_download' => isset($aData['bAllowDownload']) ? (bool) $aData['bAllowDownload'] : $aPhoto['allow_download'],
                'set_album_cover' => ((isset($aData['bSetAlbumCover']) && $aData['bSetAlbumCover'] === 'true') ? $aData['iPhotoId'] : null),
                'album_id' => isset($aData['iAlbumId']) ? $aData['iAlbumId'] : $aPhoto['album_id']
            );
            
            if ($aVals['mature'] > 2 || $aVals['mature'] < 0)
            {
                $aVals['mature'] = 0;
            }
            if (isset($aData['sCategory']))
            {
                $aTemp = explode(',', $aData['sCategory']);
                $aCategories = array();
                foreach ($aTemp as $iCategory)
                {
                    if (is_numeric($iCategory))
                    {
                        $aCategories[] = $iCategory;
                    }
                }
                $aVals['category_id'] = $aCategories;
            }
            else
            {
                $aCategories = array();
                foreach ($aPhoto['categories'] as $aCategory)
                {
                    $aCategories[] = $aCategory['category_id'];
                }
                $aVals['category_id'] = $aCategories;
            }
            
            // Fix bug duplicate cover album photo.
            $iMoveTo = isset($aData['iMoveTo']) && $aData['iMoveTo'] > 0 ? (int) $aData['iMoveTo'] : '';
            if ($iMoveTo == $aPhoto['album_id'])
            {
                $aVals['move_to'] = '';
            }
            else
            {
                Phpfox::getLib('database')->update(Phpfox::getT('photo'), array('is_cover' => '0'), 'photo_id = ' . (int) $aPhoto['photo_id']);
                
                $aVals['move_to'] = isset($aData['iMoveTo']) ? (int) $aData['iMoveTo'] : '';
            }
            
            $aVals['privacy'] = isset($aData['iPrivacy']) ? $aData['iPrivacy'] : $aPhoto['privacy'];
            $aVals['privacy_comment'] = isset($aData['iPrivacyComment']) ? $aData['iPrivacyComment'] : $aPhoto['privacy_comment'];
            
            if ($this->updatePhoto($iUserId, $aData['iPhotoId'], $aVals))
            {
                return array('result' => true, 'message' => ' Photo successfully edited! ');
            }

            return array(
                'error_code' => 1,
                'error_message' => Phpfox_Error::get()
            );
        }

        return array(
            'error_code' => 1,
            'error_message' => 'You do not have permission to edit this photo!'
        );
    }
    
    /**
     * Updating a new photo. We piggy back on the add() method so we don't have to do the same code twice.
     *
     * @param int $iUserId User ID of the user that the photo belongs to.
     * @param array $aVals Array of the post data being passed to insert.
     * @param boolean $bAllowTitleUrl Set to true to allow the editing of the SEO url.
     *
     * @return int ID of the newly added photo or the ID of the current photo we are editing.
     */
    public function updatePhoto($iUserId, $iId, $aVals, $bAllowTitleUrl = false)
    {
		$aVals['photo_id'] = $iId;
	
		return $this->addPhoto($iUserId, $aVals, true, $bAllowTitleUrl);
    }
    
    /**
     * Adding a new photo.
     *
     * @param int $iUserId User ID of the user that the photo belongs to.
     * @param array $aVals Array of the post data being passed to insert.
     * @param boolean $bIsUpdate True if we plan to update the entry or false to insert a new entry in the database.
     * @param boolean $bAllowTitleUrl Set to true to allow the editing of the SEO url.
     *
     * @return int ID of the newly added photo or the ID of the current photo we are editing.
     */
    public function addPhoto($iUserId, $aVals, $bIsUpdate = false, $bAllowTitleUrl = false)
    {
		$oParseInput = Phpfox::getLib('parse.input');
	
		// Create the fields to insert.
		$aFields = array();

		// Make sure we are updating the album ID
		(!empty($aVals['album_id']) ? $aFields['album_id'] = 'int' : null);
	
		// Is this an update?
		if ($bIsUpdate)
		{
		    // Make sure we only update the fields that the user is allowed to
		    (Phpfox::getUserParam('photo.can_add_mature_images') ? $aFields['mature'] = 'int' : null);
		    (Phpfox::getUserParam('photo.can_control_comments_on_photos') ? $aFields['allow_comment'] = 'int' : null);
		    ((Phpfox::getUserParam('photo.can_add_to_rating_module') && Phpfox::getParam('photo.can_rate_on_photos')) ? $aFields['allow_rate'] = 'int' : null);
		    (!empty($aVals['destination']) ? $aFields[] = 'destination' : null);
		    $aFields['allow_download'] = 'int';
		    $aFields['server_id'] = 'int';
	
		    // Check if we really need to update the title
		    if (!empty($aVals['title']))
		    {
				$aFields[] = 'title';
		
				// Clean the title for any sneaky attacks
				$aVals['title'] = $oParseInput->clean($aVals['title'], 255);
				
				if (Phpfox::getParam('photo.rename_uploaded_photo_names'))
				{
				    $aFields[] = 'destination';
                    /**
                     * @var array
                     */
				    $aPhoto = $this->database()->select('destination')
					    ->from(Phpfox::getT('photo'))
					    ->where('photo_id = ' . $aVals['photo_id'])
					    ->execute('getRow');
                    /**
                     * @var string
                     */
				    $sNewName = preg_replace("/^(.*?)-(.*?)%(.*?)$/", "$1-" . str_replace('%', '', $aVals['title']) . "%$3", $aPhoto['destination']);
		
				    $aVals['destination'] = $sNewName;
		
				    Phpfox::getLib('file')->rename(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], ''), Phpfox::getParam('photo.dir_photo') . sprintf($sNewName, ''));
		
				    // Create thumbnails with different sizes depending on the global param.
				    foreach(Phpfox::getParam('photo.photo_pic_sizes') as $iSize)
				    {
						Phpfox::getLib('file')->rename(Phpfox::getParam('photo.dir_photo') . sprintf($aPhoto['destination'], '_' . $iSize), Phpfox::getParam('photo.dir_photo') . sprintf($sNewName, '_' . $iSize));
				    }
				}				
		    }
		    /**
             * @var int
             */
		    $iAlbumId = (int) (empty($aVals['move_to']) ? (isset($aVals['album_id']) ? $aVals['album_id'] : 0) : $aVals['move_to']);
		    if (!empty($aVals['set_album_cover']))
		    {
		    	$aFields['is_cover'] = 'int';	
		    	$aVals['is_cover'] = '1';		
		    	
		    	$this->database()->update(Phpfox::getT('photo'), array('is_cover' => '0'), 'album_id = ' . (int) $iAlbumId);    
		    }
		    
		    if (!empty($aVals['move_to']))
		    {
		    	$aFields['album_id'] = 'int';
		    	$iOldAlbumId = $aVals['album_id'];
		    	$aVals['album_id'] = (int) $aVals['move_to'];
		    }
		    
		    if (isset($aVals['privacy']))
		    {
		    	$aFields['privacy'] = 'int';	
		    	$aFields['privacy_comment'] = 'int';	
		    }
		    
		    // Update the data into the database.
		    $this->database()->process($aFields, $aVals)->update(Phpfox::getT('photo'), 'photo_id = ' . (int) $aVals['photo_id']);
	
		    // Check if we need to update the description of the photo
		    if (!empty($aVals['description']))
		    {
				$aFieldsInfo = array(
					'description'
				);
		
				// Clean the data before we add it into the database
				$aVals['description'] = $oParseInput->clean($aVals['description']);
		    }    
	
		    (!empty($aVals['width']) ? $aFieldsInfo[] = 'width' : 0);
		    (!empty($aVals['height']) ? $aFieldsInfo[] = 'height' : 0);
	
		    // Check if we have anything to add into the photo_info table
		    if (isset($aFieldsInfo))
		    {
				$this->database()->process($aFieldsInfo, $aVals)->update(Phpfox::getT('photo_info'), 'photo_id = ' . (int) $aVals['photo_id']);
		    }
	
		    // Add tags for the photo
		    if (Phpfox::isModule('tag') && isset($aVals['tag_list']) && !empty($aVals['tag_list']) && Phpfox::getUserParam('photo.can_add_tags_on_photos'))
		    {
				Phpfox::getService('tag.process')->update('photo', $aVals['photo_id'], $iUserId, $aVals['tag_list']);
		    }
	
		    // Make sure if we plan to add categories for this image that there is something to add
		    if (isset($aVals['category_id']) && count($aVals['category_id']))
		    {
				// Loop thru all the categories
		    	$this->database()->delete(Phpfox::getT('photo_category_data'), 'photo_id = ' . (int) $aVals['photo_id']);
				foreach ($aVals['category_id'] as $iCategory)
				{
				    // Add each of the categories					
				    Phpfox::getService('photo.category.process')->updateForItem($aVals['photo_id'], $iCategory);
				}
		    }
            /**
             * @var int
             */
		    $iId = $aVals['photo_id'];
		    
		    if (Phpfox::isModule('privacy') && isset($aVals['privacy']))
		    {
				if ($aVals['privacy'] == '4')
				{
					Phpfox::getService('privacy.process')->update('photo', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
				}
				else 
				{
					Phpfox::getService('privacy.process')->delete('photo', $iId);
				}	
		    }
			
			if (!isset($aVals['privacy']))
			{
				$aVals['privacy'] = 0;
			}
			
			if (!isset($aVals['privacy_comment']))
			{
				$aVals['privacy_comment'] = 0;
			}
			
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->update('photo', $iId, $aVals['privacy'], $aVals['privacy_comment'], 0, $iUserId) : null);

			if (!empty($aVals['move_to']))
			{
				Phpfox::getService('photo.album.process')->updateCounter($iOldAlbumId, 'total_photo');
				Phpfox::getService('photo.album.process')->updateCounter($aVals['move_to'], 'total_photo');
			}						
		}
		else
		{
		    if (!empty($aVals['callback_module']))
		    {
		    	$aVals['module_id'] = $aVals['callback_module'];
		    }
			
			// Define all the fields we need to enter into the database
		    $aFields['user_id'] = 'int';
		    $aFields['parent_user_id'] = 'int';
		    $aFields['type_id'] = 'int';
		    $aFields['time_stamp'] = 'int';
		    $aFields['server_id'] = 'int';
		    $aFields['view_id'] = 'int';
		    $aFields['group_id'] = 'int';
		    $aFields[] = 'module_id';
		    $aFields[] = 'title';
		    
		    if (isset($aVals['privacy']))
		    {
		    	$aFields['privacy'] = 'int';	
		    	$aFields['privacy_comment'] = 'int';	
		    }		    
	
		    // Define all the fields we need to enter into the photo_info table
		    $aFieldsInfo = array(
			    'photo_id' => 'int',
			    'file_name',
			    'mime_type',
			    'extension',
			    'file_size' => 'int',
			    'description'
		    );
	
		    // Clean and prepare the title and SEO title
		    $aVals['title'] = $oParseInput->clean(rtrim(preg_replace("/^(.*?)\.(jpg|jpeg|gif|png)$/i", "$1", $aVals['name'])), 255);
	
		    // Add the user_id
		    $aVals['user_id'] = $iUserId;
	
		    // Add the original server ID for LB.
		    $aVals['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
	
		    // Add the time stamp.
		    $aVals['time_stamp'] = PHPFOX_TIME;
	
		    $aVals['view_id'] = (Phpfox::getUserParam('photo.photo_must_be_approved') ? '1' : '0');
	
		    // Insert the data into the database.
		    $iId = $this->database()->process($aFields, $aVals)->insert(Phpfox::getT('photo'));
	
		    // Prepare the data to enter into the photo_info table
		    $aInfo = array(
			    'photo_id' => $iId,
			    'file_name' => Phpfox::getLib('parse.input')->clean($aVals['name'], 100),
			    'extension' => strtolower($aVals['ext']),
			    'file_size' => $aVals['size'],
			    'mime_type' => $aVals['type'],
			    'description' => (empty($aVals['description']) ? null : $this->preParse()->prepare($aVals['description']))
		    );
	
		    // Insert the data into the photo_info table
		    $this->database()->process($aFieldsInfo, $aInfo)->insert(Phpfox::getT('photo_info'));
	
		    if (!Phpfox::getUserParam('photo.photo_must_be_approved'))
		    {
				// Update user activity
				Phpfox::getService('user.activity')->update($iUserId, 'photo');
		    }
			
		    // Make sure if we plan to add categories for this image that there is something to add
		    if (isset($aVals['category_id']) && count($aVals['category_id']))
		    {
				// Loop thru all the categories
				foreach ($aVals['category_id'] as $iCategory)
				{
				    // Add each of the categories
				    Phpfox::getService('photo.category.process')->updateForItem($iId, $iCategory);
				}
		    }			
		    
			if (isset($aVals['privacy']))
			{
				if ($aVals['privacy'] == '4')
				{
					Phpfox::getService('privacy.process')->add('photo', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));			
				}			    
			}
		}
	    
		// Return the photo ID#
		return $iId;
    }

    /**
     * Input data:
     * + iAlbumId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/albumdelete
     * 
     * @param array $aData
     * @return array
     */
    public function albumdelete($aData)
    {
        if (!isset($aData['iAlbumId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }

        if ($this->deleteAlbum($aData['iAlbumId']))
        {
            return array('result' => 1, 'message' => Phpfox::getPhrase('photo.photo_album_successfully_deleted'));
        }

        return array(
            'error_code' => 1,
            'error_message' => Phpfox_Error::get()
        );
    }
    /**
     * Delete album.
     * @param int $iAlbumId
     * @return boolean
     */
    public function deleteAlbum($iAlbumId)
	{
        /**
         * @var array
         */
		$aAlbum = $this->database()->select('album_id, user_id')
			->from(Phpfox::getT('photo_album'))
			->where('album_id = ' . (int) $iAlbumId)
			->execute('getRow');
			
		if (!isset($aAlbum['album_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('photo.not_a_valid_photo_album_to_delete'));
		}
			
		if (!Phpfox::getService('mfox.auth')->hasAccess('photo_album', 'album_id', $iAlbumId, 'photo.can_delete_own_photo_album', 'photo.can_delete_other_photo_albums', $aAlbum['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('photo.you_do_not_have_sufficient_permission_to_delete_this_photo_album'));
		}			
		
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('photo_album', $aAlbum['album_id']) : null);
		/**
         * @var array
         */
		$aPhotos = $this->database()->select('photo_id')
			->from(Phpfox::getT('photo'))
			->where('album_id = ' . $aAlbum['album_id'])
			->execute('getRows');
			
		foreach ($aPhotos as $aPhoto)
		{
			Phpfox::getService('photo.process')->delete($aPhoto['photo_id']);
		}
		
		$this->database()->delete(Phpfox::getT('photo_album'), 'album_id = ' . $aAlbum['album_id']);
		$this->database()->delete(Phpfox::getT('photo_album_info'), 'album_id = ' . $aAlbum['album_id']);
		
		return true;
	}
    
    /**
     * Input data:
     * + iAlbumId: int, required.
     * + sName: string, required.
     * + sDescription: string, optional.
     * + iPrivacy: int, optional.
     * + iPrivacyComment: optional.
     * + sPrivacyList: string, optional. Ex: "1,3,4,5".
     * 
     * Output data:
     * + result: int.
     * + message: string.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/albumedit
     * 
     * @param array $aData
     * @return array
     */
    public function albumedit($aData)
    {
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0;
        /**
         * @var string
         */
        $sName = isset($aData['sName']) ? $aData['sName'] : '';
        if ($iAlbumId < 1 || Phpfox::getLib('parse.format')->isEmpty($sName))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aAlbum = Phpfox::getService('photo.album')->getForEdit($iAlbumId);

        if (!isset($aAlbum['album_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('photo.photo_album_not_found')
            );
        }
        /**
         * @var array
         */
        $aVals = array(
            'name' => $sName,
            'description' => isset($aData['sDescription']) ? $aData['sDescription'] : '',
            'privacy' => isset($aData['iPrivacy']) ? $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? $aData['iPrivacyComment'] : 0,
            'privacy_list' => isset($aData['sPrivacyList']) ? explode(',', $aData['sPrivacyList']) : null
        );

        if (Phpfox::getService('photo.album.process')->update($aAlbum['album_id'], $aVals))
        {
            return array('result' => true, 'message' => 'Photo album successfully edited!');
        }

        return array(
            'error_code' => 1,
            'error_message' => Phpfox_Error::get()
        );
    }

    /**
     * Input data:
     * + iAmountOfPhoto: int, optional.
     * + iLastPhotoIdViewed: int, optional.
     * + bIsUserProfile: bool, optional. In profile.
     * + iUserId: int, optional. In profile.
     * + sView: string, optional.
     * + iCategory: int, optional.
     * + sType: string, optional.
     * + sModuleId: string, optional.
     * + iItemId: int, optional.
     * + sTag: string, optional.
     * 
     * Output data:
	 * + iPhotoId: int.
	 * + sTitle: string.
	 * + sPhotoUrl: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/friend
     * 
     * @param array $aData
     * @return array
     */
    public function friend($aData)
    {
        $aData['sView'] = 'friend';
        return $this->getPhoto($aData);
    }

    /**
     * Input data:
     * + iAmountOfPhoto: int, optional.
     * + iLastPhotoIdViewed: int, optional.
     * + bIsUserProfile: bool, optional.
     * + iUserId: int, optional.
     * + sView: string, optional.
     * + iCategory: int, optional.
     * + sType: string, optional.
     * + sModuleId: string, optional.
     * + iItemId: int, optional.
     * + sTag: string, optional.
     * 
     * Output data:
	 * + iPhotoId: int.
	 * + sTitle: string.
	 * + sPhotoUrl: string.
     * 
     * @param array $aData
     * @return array
     */
    public function getMyLatestPhoto($iUserId)
    {
        $aData = array(
            'iAmountOfPhoto' => 1,
            'bIsUserProfile' => 'true',
            'iUserId' => $iUserId
        );
        return $this->getPhoto($aData);
    }
    
    /**
     * Input data:
     * + iAmountOfPhoto: int, optional.
     * + iLastPhotoIdViewed: int, optional.
     * + bIsUserProfile: bool, optional.
     * + iUserId: int, optional.
     * + sView: string, optional.
     * + iCategory: int, optional.
     * + sType: string, optional.
     * + sModuleId: string, optional.
     * + iItemId: int, optional.
     * + sTag: string, optional.
     * 
     * Output data:
	 * + iPhotoId: int.
	 * + sTitle: string.
	 * + sPhotoUrl: string.
     * 
     * @param array $aData
     * @return array
     */
    private function getPhoto($aData)
    {
        if (!isset($aData['iAmountOfPhoto']))
        {
            $aData['iAmountOfPhoto'] = 5;
        }

        if (!isset($aData['iLastPhotoIdViewed']))
        {
            $aData['iLastPhotoIdViewed'] = 0;
        }
        /**
         * @var bool
         */
        $bNoAccess = false;

        if (isset($aData['bIsUserProfile']) && $aData['bIsUserProfile'] == 'true')
        {
            $bIsUserProfile = true;

            if (!isset($aData['iUserId']))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => " User is not valid! "
                );
            }

            /**
             * @var array
             */
            $aUser = Phpfox::getService('user')->get($aData['iUserId'], true);
            if (!$aUser)
            {
                return array(
                    'error_message' => ' User is not valid! ',
                    'error_code' => 1
                );
            }

            if (!Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'photo.display_on_profile'))
            {
                $bNoAccess = true;
            }
        }
        else
        {
            $bIsUserProfile = false;
        }

        if (!isset($aData['sView']))
        {
            $aData['sView'] = '';
        }

        if (!isset($aData['iCategory']))
        {
            $iPhotoCategory = $aData['iCategory'];
        }
        else
        {
            $iPhotoCategory = '';
        }

        $sCond = ' TRUE ';

        if (isset($aData['sType']) && $aData['sType'] == 'new')
        {
            $sCond .= ($aData['iLastPhotoIdViewed'] > 0 ? ' AND photo.photo_id > ' . (int) $aData['iLastPhotoIdViewed'] . ' ' : '');
        }
        else // Load more.
        {
            $sCond .= ($aData['iLastPhotoIdViewed'] > 0 ? ' AND photo.photo_id < ' . (int) $aData['iLastPhotoIdViewed'] . ' ' : '');
        }

        switch ($aData['sView']) {
            case 'pending':
                $sCond .= ' AND photo.view_id = 1 ';
                break;
            case 'my':
                $sCond .= ' AND photo.user_id = ' . Phpfox::getUserId() . ' ';
                break;

            default:
                if ($bIsUserProfile)
                {
                    $sCond .= ' AND photo.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND photo.group_id = 0 AND photo.type_id = 0 AND photo.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND photo.user_id = ' . (int) $aUser['user_id'];
                }
                else
                {
                    if (isset($aData['sModuleId']) && isset($aData['iItemId']) && !empty($aData['iItemId']))
                    {
                        $sCond .= ' AND photo.view_id = 0 AND photo.module_id = \'' . Phpfox::getLib('database')->escape($aData['sModuleId']) . '\' AND photo.group_id = ' . (int) $aData['iItemId'] . ' AND photo.privacy IN(%PRIVACY%) ';
                    }
                    else
                    {
                        $sCond .= ' AND photo.view_id = 0 AND photo.group_id = 0 AND photo.type_id = 0 AND photo.privacy IN(%PRIVACY%) ';
                    }
                }

                if ($aData['sView'] == 'friend')
                {
                    $sCond .= ' AND photo.user_id != ' . Phpfox::getUserId();
                }
                break;
        }

        /**
         * @var int
         */
        $iCategory = null;

        if (isset($aData['iCategory']))
        {
            $iCategory = $aData['iCategory'];
            $sCond .= ' AND pcd.category_id = ' . (int) $aData['iCategory'] . ' ';
        }

        if (isset($aData['sTag']))
        {
            if (($aTag = Phpfox::getService('tag')->getTagInfo('photo', $aData['sTag'])))
            {
                $sCond .= ' AND tag.tag_text = \'' . Phpfox::getLib('database')->escape($aTag['tag_text']) . '\'';
            }
        }

        if ($aData['sView'] == 'featured')
        {
            $sCond .= ' AND photo.is_featured = 1 ';
        }

        if (!Phpfox::getParam('photo.display_profile_photo_within_gallery'))
        {
            $sCond .= ' AND photo.is_profile_photo = 0 ';
        }

        if ($aData['sView'] != 'friend')
        {
            $this->database()
                    ->select('pa.name AS album_name, pa.profile_id AS album_profile_id, ppc.name as category_name, ppc.category_id, ')
                    ->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = photo.album_id')
                    ->leftJoin(Phpfox::getT('photo_category_data'), 'ppcd', 'ppcd.photo_id = photo.photo_id')
                    ->leftJoin(Phpfox::getT('photo_category'), 'ppc', 'ppc.category_id = ppcd.category_id')
                    ->group('photo.photo_id');

            if (Phpfox::isModule('like'))
            {
                $this->database()
                        ->select('l.like_id as is_liked, adisliked.action_id as is_disliked, ')
                        ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = "photo" AND l.item_id = photo.photo_id AND l.user_id = ' . Phpfox::getUserId() . '')
                        ->leftJoin(Phpfox::getT('action'), 'adisliked', 'adisliked.action_type_id = 2 AND adisliked.item_id = photo.photo_id AND adisliked.user_id = ' . Phpfox::getUserId());
            }
        }
        /**
         * @var bool
         */
        $bIsCount = false;
        if ($aData['sView'] != 'friend')
        {
            $bNoQueryFriend = false;
        }
        else
        {
            $bNoQueryFriend = true;
        }
        
        if (Phpfox::isModule('friend') && $bNoQueryFriend)
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = photo.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }

        if (isset($aData['sTag']))
        {
            $this->database()->innerJoin(Phpfox::getT('tag'), 'tag', 'tag.item_id = photo.photo_id AND tag.category_id = \'photo\'');
        }

        if ($iCategory !== null || (isset($iPhotoCategory) && $iPhotoCategory != ''))
        {
            $this->database()->innerJoin(Phpfox::getT('photo_category_data'), 'pcd', 'pcd.photo_id = photo.photo_id');
            if (!$bIsCount)
            {
                $this->database()->group('photo.photo_id');
            }
        }

        // Set privacy
        switch ($aData['sView']) {
            case 'friend':
                $sCond = str_replace('%PRIVACY%', '0,1,2', $sCond);
                break;
            case 'my':
                $sCond = str_replace('%PRIVACY%', '0,1,2,3,4', $sCond);
                break;
            default:
                $sCond = str_replace('%PRIVACY%', '0', $sCond);
                break;
        }

        if ($bNoAccess == false)
        {
            /**
             * @var array
             */
            $aPhotos = $this->database()
                    ->select('photo.*')
                    ->from(Phpfox::getT('photo'), 'photo')
                    ->where($sCond)
                    ->order('photo.photo_id DESC')
                    ->limit((int) $aData['iAmountOfPhoto'])
                    ->execute('getRows');
            
            $aResult = array();

            foreach ($aPhotos as $aPhoto)
            {
                $aResult[] = array(
                    'iPhotoId' => $aPhoto['photo_id'],
                    'sTitle' => $aPhoto['title'],
                    'sPhotoUrl' => Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aPhoto['server_id'],
                        'path' => 'photo.url_photo',
                        'file' => $aPhoto['destination'],
                        'suffix' => MAX_SIZE_OF_USER_IMAGE_PHOTO,
                        'return_url' => true
                            )
                    )
                );
            }

            return $aResult;
        }
        else
        {
            return array();
        }
    }

    /**
     * Input data:
     * + iAmountOfPhoto: int, optional.
     * + iLastPhotoIdViewed: int, optional.
     * + bIsUserProfile: bool, optional. In profile.
     * + iUserId: int, optional. Not use.
     * + sView: string, optional.
     * + iCategory: int, optional.
     * + sType: string, optional.
     * + sModuleId: string, optional.
     * + iItemId: int, optional.
     * + sTag: string, optional.
     * 
     * Output data:
	 * + iPhotoId: int.
	 * + sTitle: string.
	 * + sPhotoUrl: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/my
     * 
     * @param array $aData
     * @return array
     */
    public function my($aData)
    {
        $aData['iUserId'] = Phpfox::getUserId();
        $aData['sView'] = 'my';

        return $this->getPhoto($aData);
    }

    /**
     * Input data:
     * + privacy: int, optional.
     * + privacy_comment: int, optional.
     * + album_id: int, optional.
     * + sCallbackModule: string, optional.
     * + iCallbackItemId: int, optional.
     * + iParentUserId: int, optional.
     * + iGroupId: int, optional.
     * + sStatusInfo: string, optional.
     * + sUserStatus: string, optional.
     * + sVideoTitle: string, optional.
     * + bTwitterConnection: bool, optional.
     * + bFacebookConnection: bool, optional.
     * + sAction: string, optional.
     * + page_id: int, optional.
     * + image[]: upload file images.
     * 
     * Output data:
	 * + result: int.
	 * + message: string.
	 * + error_code: int.
	 * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/upload
     * 
     * @param array $aData
     * @return array
     */
    public function upload($aData)
    {
        // If no images were uploaded lets get out of here.
        if (!isset($_FILES['image']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Upload image is not valid! "
            );
        }

        // Make sure the user group is actually allowed to upload an image
        if (!Phpfox::getUserParam('photo.can_upload_photos'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You can not upload photo!"
            );
        }

        if (($iFlood = Phpfox::getUserParam('photo.flood_control_photos')) !== 0)
        {
            $aFlood = array(
                'action' => 'last_post', // The SPAM action
                'params' => array(
                    'field' => 'time_stamp', // The time stamp field
                    'table' => Phpfox::getT('photo'), // Database table we plan to check
                    'condition' => 'user_id = ' . Phpfox::getUserId(), // Database WHERE query
                    'time_stamp' => $iFlood * 60 // Seconds);	
                )
            );

            // actually check if flooding
            if (Phpfox::getLib('spam')->check($aFlood))
            {
                Phpfox_Error::set(Phpfox::getPhrase('photo.uploading_photos_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());
            }

            if (!Phpfox_Error::isPassed())
            {
                return array(
                    'error_code' => 1,
                    'error_message' => Phpfox_Error::get()
                );
            }
        }

        $oFile = Phpfox::getLib('file');
        $oImage = Phpfox::getLib('image');

        $aVals = array();
        if (isset($aData['privacy']))
        {
            $aVals['privacy'] = $aData['privacy'];
        }
        if (isset($aData['privacy_comment']))
        {
            $aVals['privacy_comment'] = $aData['privacy_comment'];
        }
        if (isset($aData['album_id']))
        {
            $aVals['album_id'] = $aData['album_id'];
        }
        else
        {
            $aVals['album_id'] = '';
        }

        if (isset($aData['sCallbackModule']) && !empty($aData['sCallbackModule']))
        {
            $aVals['callback_module'] = $aData['sCallbackModule'];
        }
        else
        {
            $aVals['callback_module'] = '';
        }
        if (isset($aData['iCallbackItemId']) && !empty($aData['iCallbackItemId']))
        {
            $aVals['callback_item_id'] = $aData['iCallbackItemId'];
        }
        else
        {
            $aVals['callback_item_id'] = '';
        }

        $aVals['parent_user_id'] = isset($aData['iParentUserId']) ? (int) $aData['iParentUserId'] : 0;
        $aVals['group_id'] = isset($aData['iGroupId']) ? (int) $aData['iGroupId'] : 0;
        $aVals['status_info'] = isset($aData['sStatusInfo']) ? $aData['sStatusInfo'] : '';
        $aVals['user_status'] = isset($aData['sUserStatus']) ? $aData['sUserStatus'] : 'Write something...';
        $aVals['iframe'] = 1;
        $aVals['method'] = 'simple';
        $aVals['video_inline'] = 1;
        $aVals['video_title'] = isset($aData['sVideoTitle']) ? $aData['sVideoTitle'] : '';
        $bTwitterConnection = isset($aData['bTwitterConnection']) ? $aData['bTwitterConnection'] : 0;
        $aVals['connection']['twitter'] = $bTwitterConnection;
        $aVals['twitter_connection'] = $bTwitterConnection;
        $bFacebookConnection = isset($aData['bFacebookConnection']) ? $aData['bFacebookConnection'] : 0;
        $aVals['connection']['facebook'] = $bFacebookConnection;
        $aVals['facebook_connection'] = $bFacebookConnection;

        if (isset($aData['sAction']) && !empty($aData['sAction']))
        {
            $aVals['action'] = $aData['sAction'];
        }
        else
        {
            $aVals['action'] = '';
        }
        if (isset($aData['iPageId']))
        {
            $aVals['page_id'] = $aData['iPageId'];
        }
        else
        {
            $aVals['page_id'] = 0;
        }

        if (!is_array($aVals))
        {
            $aVals = array();
        }

        $bIsInline = false;
        if (isset($aVals['action']) && $aVals['action'] == 'upload_photo_via_share')
        {
            $bIsInline = true;
        }

        $oServicePhotoProcess = Phpfox::getService('photo.process');
        $aImages = array();
        $iFileSizes = 0;
        $iCnt = 0;

        if (!empty($aVals['album_id']))
        {
            $aAlbum = Phpfox::getService('photo.album')->getAlbum(Phpfox::getUserId(), $aVals['album_id'], true);
        }

        if (isset($aData['sStatusInfo']) && !empty($aData['sStatusInfo']))
        {
            $aVals['description'] = $aData['sStatusInfo'];
        }

        foreach ($_FILES['image']['error'] as $iKey => $sError)
        {
            if ($sError == UPLOAD_ERR_OK)
            {
                //$iLimitUpload = (Phpfox::getUserParam('photo.photo_max_upload_size') === 0 ? null : (Phpfox::getUserParam('photo.photo_max_upload_size') / 1024));
                $iLimitUpload = null;

                if ($aImage = $oFile->load('image[' . $iKey . ']', array('jpg', 'gif', 'png'), $iLimitUpload))
                {
                    if (isset($aVals['action']) && $aVals['action'] == 'upload_photo_via_share')
                    {
                        $aVals['description'] = (isset($aVals['is_cover_photo']) ? null : $aVals['status_info']);
                        $aVals['type_id'] = (isset($aVals['is_cover_photo']) ? '2' : '1');
                    }

                    if ($iId = $oServicePhotoProcess->add(Phpfox::getUserId(), array_merge($aVals, $aImage)))
                    {
                        $iCnt++;
                        $aPhoto = Phpfox::getService('photo')->getForProcess($iId);

                        // Move the uploaded image and return the full path to that image.
                        $sFileName = $oFile->upload('image[' . $iKey . ']', Phpfox::getParam('photo.dir_photo'), (Phpfox::getParam('photo.rename_uploaded_photo_names') ? Phpfox::getUserBy('user_name') . '-' . $aPhoto['title'] : $iId), (Phpfox::getParam('photo.rename_uploaded_photo_names') ? array() : true));

                        // Get the original image file size.
                        $iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''));

                        // Get the current image width/height
                        $aSize = getimagesize(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''));

                        // Update the image with the full path to where it is located.
                        $oServicePhotoProcess->update(Phpfox::getUserId(), $iId, array(
                            'destination' => $sFileName,
                            'width' => $aSize[0],
                            'height' => $aSize[1],
                            'server_id' => 0,
                            'allow_rate' => (empty($aVals['album_id']) ? '1' : '0')
                                )
                        );

                        // Assign vars for the template.
                        $aImages[] = array(
                            'photo_id' => $iId,
                            'server_id' => 0,
                            'destination' => $sFileName,
                            'name' => $aImage['name'],
                            'ext' => $aImage['ext'],
                            'size' => $aImage['size'],
                            'width' => $aSize[0],
                            'height' => $aSize[1],
                            'completed' => 'false'
                        );
                    }
                }
            }
            else
            {
                Phpfox_Error::set('Upload file error : ' . $sError);
            }
        }

        $iFeedId = 0;

        // Make sure we were able to upload some images
        if (count($aImages))
        {
            if (defined('PHPFOX_IS_HOSTED_SCRIPT'))
            {
                unlink(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''));
            }

            $aCallback = (!empty($aVals['callback_module']) ? (Phpfox::hasCallback($aVals['callback_module'], 'addPhoto') ? Phpfox::callback($aVals['callback_module'] . '.addPhoto', $aVals['callback_item_id']) : null) : null);

            $sAction = (isset($aVals['action']) ? $aVals['action'] : 'view_photo');

            // Have we posted an album for these set of photos?
            if (isset($aVals['album_id']) && !empty($aVals['album_id']))
            {
                $aAlbum = Phpfox::getService('photo.album')->getAlbum(Phpfox::getUserId(), $aVals['album_id'], true);

                // Set the album privacy
                Phpfox::getService('photo.album.process')->setPrivacy($aVals['album_id']);

                // Check if we already have an album cover
                if (!Phpfox::getService('photo.album.process')->hasCover($aVals['album_id']))
                {
                    // Set the album cover
                    Phpfox::getService('photo.album.process')->setCover($aVals['album_id'], $iId);
                }

                // Update the album photo count
                if (!Phpfox::getUserParam('photo.photo_must_be_approved'))
                {
                    Phpfox::getService('photo.album.process')->updateCounter($aVals['album_id'], 'total_photo', false, count($aImages));
                }

                $sAction = 'view_album';
            }

            // Update the user space usage
            Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'photo', $iFileSizes);

            if (isset($aVals['page_id']) && $aVals['page_id'] > 0)
            {
                if (Phpfox::getService('pages.process')->setCoverPhoto($aVals['page_id'], $iId, true))
                {
                    $aVals['is_cover_photo'] = 1;
                }
                else
                {
                    return array(
                        'error_code' => 1,
                        'error_message' => Phpfox_Error::get()
                    );
                }
            }

            $sExtra = '';
            if (!empty($aVals['start_year']) && !empty($aVals['start_month']) && !empty($aVals['start_day']))
            {
                $sExtra .= '&start_year= ' . $aVals['start_year'] . '&start_month= ' . $aVals['start_month'] . '&start_day= ' . $aVals['start_day'] . '';
            }

            $oImage = Phpfox::getLib('image');
            $iFileSizes = 0;
            $iGroupId = 0;
            $bProcess = false;
            $sCallbackModule = null;
            $iCallbackItemId = null;
            if ($aCallback !== null)
            {
                $sCallbackModule = $aCallback['module'];
                $iCallbackItemId = $aCallback['item_id'];
            }
            $iParentUserId = (isset($aVals['parent_user_id']) ? (int) $aVals['parent_user_id'] : 0);
            $bIsCoverPhoto = (isset($aVals['is_cover_photo']) ? 1 : 0);
            $iPageId = ((isset($aVals['page_id']) && $aVals['page_id'] > 0) ? $aVals['page_id'] : null);

            foreach ($aImages as $iKey => $aImage)
            {
                if ($aImage['completed'] == 'false')
                {
                    $aPhoto = Phpfox::getService('photo')->getForProcess($aImage['photo_id']);

                    if (isset($aPhoto['photo_id']))
                    {
                        if (Phpfox::getParam('core.allow_cdn'))
                        {
                            Phpfox::getLib('cdn')->setServerId($aPhoto['server_id']);
                        }

                        if ($aPhoto['group_id'] > 0)
                        {
                            $iGroupId = $aPhoto['group_id'];
                        }

                        $sFileName = $aPhoto['destination'];

                        foreach (Phpfox::getParam('photo.photo_pic_sizes') as $iSize)
                        {
                            // Create the thumbnail
                            if ($oImage->createThumbnail(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''), Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize, true, ((Phpfox::getParam('photo.enabled_watermark_on_photos') && Phpfox::getParam('core.watermark_option') != 'none') ? (Phpfox::getParam('core.watermark_option') == 'image' ? 'force_skip' : true) : false)) === false)
                            {
                                continue;
                            }

                            if (Phpfox::getParam('photo.enabled_watermark_on_photos'))
                            {
                                $oImage->addMark(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, '_' . $iSize));
                            }

                            // Add the new file size to the total file size variable
                            $iFileSizes += filesize(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, '_' . $iSize));

                            if (defined('PHPFOX_IS_HOSTED_SCRIPT'))
                            {
                                unlink(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, '_' . $iSize));
                            }
                        }

                        // Get is_page variable.
                        $bIsPage = ((isset($aVals['page_id']) && !empty($aVals['page_id'])) ? 1 : 0);

                        if (Phpfox::getParam('photo.delete_original_after_resize') && $bIsPage != 1)
                        {
                            Phpfox::getLib('file')->unlink(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''));
                        }
                        else if (Phpfox::getParam('photo.enabled_watermark_on_photos'))
                        {
                            $oImage->addMark(Phpfox::getParam('photo.dir_photo') . sprintf($sFileName, ''));
                        }

                        $aImages[$iKey]['completed'] = 'true';

                        break;
                    }
                }
            }

            // Update the user space usage
            Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'photo', $iFileSizes);

            $iNotCompleted = 0;
            foreach ($aImages as $iKey => $aImage)
            {
                if ($aImage['completed'] == 'false')
                {
                    $iNotCompleted++;
                }
            }

            if ($iNotCompleted === 0)
            {
                $aCallback = ($sCallbackModule ? (Phpfox::hasCallback($sCallbackModule, 'addPhoto') ? Phpfox::callback($sCallbackModule . '.addPhoto', $iCallbackItemId) : null) : null);

                $iFeedId = 0;

                if (!Phpfox::getUserParam('photo.photo_must_be_approved') && !$bIsCoverPhoto)
                {
                    (Phpfox::isModule('feed') ? $iFeedId = Phpfox::getService('feed.process')->callback($aCallback)->add('photo', $aPhoto['photo_id'], $aPhoto['privacy'], $aPhoto['privacy_comment'], (int) $iParentUserId) : null);

                    if (count($aImages) && !$sCallbackModule)
                    {
                        $aExtraPhotos = array();

                        foreach ($aImages as $aImage)
                        {
                            if ($aImage['photo_id'] == $aPhoto['photo_id'])
                            {
                                continue;
                            }

                            Phpfox::getLib('database')->insert(Phpfox::getT('photo_feed'), array(
                                'feed_id' => $iFeedId,
                                'photo_id' => $aImage['photo_id']
                                    )
                            );
                        }
                    }
                }

                // this next if is the one you will have to bypass if they come from sharing a photo in the activity feed.
                if ($sAction == 'upload_photo_via_share')
                {
                    if ($bIsCoverPhoto)
                    {
                        Phpfox::getService('user.process')->updateCoverPhoto($aImage['photo_id']);
                    }
                    else
                    {
                        $aFeeds = Phpfox::getService('feed')->get(Phpfox::getUserId(), $iFeedId);

                        if (!isset($aFeeds[0]))
                        {
                            Phpfox::addMessage(Phpfox::getPhrase('feed.this_item_has_successfully_been_submitted'));
                        }
                    }

                    Phpfox::addMessage(Phpfox::getPhrase('photo.photo_successfully_uploaded'));
                }
                else
                {
                    // Only display the photo block if the user plans to upload more pictures
                    if ($sAction == 'view_photo')
                    {
                        Phpfox::addMessage((count($aImages) == 1 ? Phpfox::getPhrase('photo.photo_successfully_uploaded') : Phpfox::getPhrase('photo.photos_successfully_uploaded')));
                    }
                    elseif ($sAction == 'view_album' && isset($aImages[0]['album']))
                    {
                        Phpfox::addMessage((count($aImages) == 1 ? Phpfox::getPhrase('photo.photo_successfully_uploaded') : Phpfox::getPhrase('photo.photos_successfully_uploaded')));
                    }
                    else
                    {
                        Phpfox::addMessage((count($aImages) == 1 ? Phpfox::getPhrase('photo.photo_successfully_uploaded') : Phpfox::getPhrase('photo.photos_successfully_uploaded')));
                    }
                }

                return array(
                    'result' => true,
                    'message' => Phpfox::getMessage()
                );
            }
            else
            {
                return array(
                    'error_code' => 1,
                    'error_message' => 'One file per time only!'
                );
            }

            return array(
                'error_code' => 0,
                'message' => Phpfox::getMessage()
            );
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox_Error::get()
            );
        }
    }
    
    /**
     * Input data:
     * + iPhotoId: int, required.
     * + iCategoryId: int, optional.
     * + iUserId: int, optional. (To view photo of someone - Include yourself)
     * + iAlbumId: int, optional.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + iPhotoId: int.
	 * + sTitle: string.
	 * + sPhotoUrl: string.
	 * + fRating: float.
	 * + iTotalVote: int.
	 * + iTotalBattle: int.
	 * + iAlbumId: int.
	 * + sAlbumName: string.
	 * + iTotalLike: int.
	 * + iTotalDislike: int.
	 * + bIsFeatured: bool.
	 * + bIsCover: bool.
	 * + iTotalView: int.
	 * + iTotalComment: int.
	 * + iTotalDownload: int.
	 * + iAllowDownload: int.
	 * + iIsSponsor: int.
	 * + iOrdering: int.
	 * + bIsProfilePhoto: bool.
	 * + sFileName: string.
	 * + sFileSize: string.
	 * + sMimeType: string.
	 * + sExtension: string.
	 * + sDescription: string.
	 * + iWidth: int.
	 * + iHeight: int.
	 * + sAlbumUrl: string.
	 * + sAlbumTitle: string.
	 * + iAlbumProfileId: int.
	 * + bIsViewed: bool.
	 * + aCategories: array.
	 * + bCategoryList: bool.
	 * + sOriginalDestination: string.
	 * + iNextPhotoId: int.
	 * + iPreviousPhotoId: int.
	 * + bIsFriend: bool.
	 * + iUserId: int.
	 * + iProfilePageId: int.
	 * + iUserServerId: int.
	 * + sUserName: string.
	 * + sFullName: string.
	 * + iGender: int.
	 * + sUserImage: string.
	 * + bIsInvisible: bool.
	 * + bCanPostComment: bool.
	 * + iUserGroupId: int.
	 * + iLanguageId: int.
	 * + iViewId: int.
	 * + iTypeId: int.
	 * + iModuleId: int.
	 * + iGroupId: int.
	 * + iParentUserId: int.
	 * + iServerId: int.
	 * + iMature: int.
	 * + iAllowComment: int.
	 * + iAllowRate: int.
	 * + bIsLiked: bool.
	 * + iPrivacyComment: int.
	 * + sTimeStamp: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/view
     * 
     * @param array $aData
     * @return string
     */
    public function view($aData)
    {
        if (!isset($aData['iPhotoId']))
        {
            return array(
                'error_message' => ' Parameter is not valid! ',
                'error_code' => 1
            );
        }
        
        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_message' => 'You can not view photos!',
                'error_code' => 1
            );
        }
        
        $aCallback = null;
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getPhoto($aData['iPhotoId'], Phpfox::getUserId());
        /**
         * @var array
         */
        $aFeed = array(				
				'comment_type_id' => 'photo',
				'privacy' => $aPhoto['privacy'],
				'comment_privacy' => $aPhoto['privacy_comment'],
				'like_type_id' => 'photo',
				'feed_is_liked' => $aPhoto['is_liked'],
				'feed_is_friend' => $aPhoto['is_friend'],
				'item_id' => $aPhoto['photo_id'],
				'user_id' => $aPhoto['user_id'],
				'total_comment' => $aPhoto['total_comment'],
				'total_like' => $aPhoto['total_like'],
				'feed_link' => Phpfox::getLib('url')->permalink('photo', $aPhoto['photo_id'], $aPhoto['title']),
				'feed_title' => $aPhoto['title'],
				'feed_display' => 'view',
				'feed_total_like' => $aPhoto['total_like'],
				'report_module' => 'photo',
				'report_phrase' => Phpfox::getPhrase('photo.report_this_photo'));
        
        $aPhoto['bCanPostComment'] = Phpfox::getService('mfox.comment')->checkCanPostComment($aFeed);
        
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_message' => ' Photo is not valid! ',
                'error_code' => 1
            );
        }

        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo', $aPhoto['photo_id'], $aPhoto['user_id'], $aPhoto['privacy'], $aPhoto['is_friend'], true))
		{
            return array(
                'error_message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'),
                'error_code' => 1
            );
		}
		/**
         * @var int
         */
        $iCategory = (isset($aData['iCategoryId']) && $aData['iCategoryId'] > 0) ? $aData['iCategoryId'] : null;
        /**
         * @var int
         */
        $iUserId = isset($aData['iUserId']) ? $aData['iUserId'] : 0;

        if (!empty($aPhoto['module_id']) && $aPhoto['module_id'] != 'photo')
        {
            $aCallback = Phpfox::callback($aPhoto['module_id'] . '.getPhotoDetails', $aPhoto);

            if ($aPhoto['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'photo.view_browse_photos'))
            {
                return array(
                    'error_message' => ' Unable to view this item due to privacy settings. ',
                    'error_code' => 1
                );
            }
        }
        /**
         * @var array
         */
        $aPhotoStream = Phpfox::getService('photo')->getPhotoStream($aPhoto['photo_id'], (isset($aData['iAlbumId']) ? $aData['iAlbumId'] : '0'), $aCallback, $iUserId, $iCategory, $aPhoto['user_id']);

        if ($aPhoto)
        {
            return array(
                'iPhotoId' => $aPhoto['photo_id'],
                'sTitle' => $aPhoto['title'],
                'sPhotoUrl' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aPhoto['server_id'],
                    'path' => 'photo.url_photo',
                    'file' => $aPhoto['destination'],
                    'suffix' => '_1024',
                    'return_url' => true
                        )
                ),
                'fRating' => $aPhoto['total_rating'],
                'iTotalVote' => $aPhoto['total_vote'],
                'iTotalBattle' => $aPhoto['total_battle'],
                'iAlbumId' => $aPhoto['album_id'],
                'sAlbumName' => $aPhoto['album_title'],
                'iTotalLike' => $aPhoto['total_like'],
                'iTotalDislike' => $aPhoto['total_dislike'],
                'bIsFeatured' => $aPhoto['is_featured'],
                'bIsCover' => $aPhoto['is_cover'],
                'iTotalView' => $aPhoto['total_view'],
                'iTotalComment' => $aPhoto['total_comment'],
                'iTotalDownload' => $aPhoto['total_download'],
                'iAllowDownload' => $aPhoto['allow_download'],
                'iIsSponsor' => $aPhoto['is_sponsor'],
                'iOrdering' => $aPhoto['ordering'],
                'bIsProfilePhoto' => $aPhoto['is_profile_photo'],
                'sFileName' => $aPhoto['file_name'],
                'sFileSize' => $aPhoto['file_size'],
                'sMimeType' => $aPhoto['mime_type'],
                'sExtension' => $aPhoto['extension'],
                'sDescription' => $aPhoto['description'],
                'iWidth' => $aPhoto['width'],
                'iHeight' => $aPhoto['height'],
                'sAlbumUrl' => $aPhoto['album_url'],
                'sAlbumTitle' => $aPhoto['album_title'],
                'iAlbumProfileId' => $aPhoto['album_profile_id'],
                'bIsViewed' => $aPhoto['is_viewed'],
                'aCategories' => $aPhoto['categories'],
                'bCategoryList' => $aPhoto['category_list'],
                'sOriginalDestination' => $aPhoto['original_destination'],
                'iNextPhotoId' => isset($aPhotoStream['next']['photo_id']) ? $aPhotoStream['next']['photo_id'] : 0,
                'iPreviousPhotoId' => isset($aPhotoStream['previous']['photo_id']) ? $aPhotoStream['previous']['photo_id'] : 0,
                'bIsFriend' => (bool) $aPhoto['is_friend'],
                'iUserId' => $aPhoto['user_id'],
                'iProfilePageId' => $aPhoto['profile_page_id'],
                'iUserServerId' => $aPhoto['user_server_id'],
                'sUserName' => $aPhoto['user_name'],
                'sFullName' => $aPhoto['full_name'],
                'iGender' => $aPhoto['gender'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aPhoto['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aPhoto['user_image'],
                    'suffix' => '_20_square',
                    'return_url' => true
                        )
                ),
                'bIsInvisible' => $aPhoto['is_invisible'],
                'bCanPostComment' => $aPhoto['bCanPostComment'],
                'iUserGroupId' => $aPhoto['user_group_id'],
                'iLanguageId' => (int) $aPhoto['language_id'],
                'iViewId' => $aPhoto['view_id'],
                'iTypeId' => $aPhoto['type_id'],
                'iModuleId' => (int) $aPhoto['module_id'],
                'iGroupId' => (int) $aPhoto['group_id'],
                'iParentUserId' => $aPhoto['parent_user_id'],
                'iServerId' => $aPhoto['server_id'],
                'iMature' => $aPhoto['mature'],
                'iAllowComment' => $aPhoto['allow_comment'],
                'iAllowRate' => $aPhoto['allow_rate'],
                'bIsLiked' => isset($aPhoto['is_liked']) ? $aPhoto['is_liked'] : 0,
                'iPrivacy' => $aPhoto['privacy'],
                'iPrivacyComment' => $aPhoto['privacy_comment'],
                'sTimeStamp' => date('l, F j, o', (int) $aPhoto['time_stamp']) . ' at ' . date('h:i a', (int) $aPhoto['time_stamp'])
            );
        }
        else
        {
            return array();
        }
    }
    
    /**
     * Input data:
     * + iLastTime: int, optional.
     * + sAction: string, optional.
     * + iAmountOfAlbum: int, optional.
     * + bIsUserProfile: bool, optional.
     * + iUserId: int, optional.
     * + sModuleId: string, optional.
     * + iItemId: int, optional.
     * + view: string, optional.
     * 
     * Output data:
     * + iAlbumId: int.
     * + sAlbumImageURL: string.
     * + sName: string.
     * + iTotalPhoto: int.
     * + iTimeStamp: int.
     * + iTimeStampUpdate: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + iUserId: int.
     * + iGroupId: int.
     * + iViewId: int.
     * 
     * @param array $aData
     * @return array
     */
    private function getAlbum($aData)
    {
        /**
         * @var int
         */
        $iLastTime = isset($aData['iLastTime']) ? (int) $aData['iLastTime'] : 0;
        /**
         * @var string
         */
        $sAction = isset($aData['sAction']) ? $aData['sAction'] : 'more';
        /**
         * @var int
         */
        $iAmountOfAlbum = isset($aData['iAmountOfAlbum']) ? (int) $aData['iAmountOfAlbum'] : 0;
        /**
         * @var bool
         */
        $bIsUserProfile = false;
        if (isset($aData['bIsUserProfile']) && $aData['bIsUserProfile'] == 'true')
        {
            $bIsUserProfile = true;
            if (!isset($aData['iUserId']))
            {
                return array(
                    'error_message' => ' Parameter is not valid! ',
                    'error_code' => 1
                );
            }
            /**
             * @var array
             */
            $aUser = Phpfox::getService('user')->get($aData['iUserId'], true);
            if (!$aUser)
            {
                return array(
                    'error_message' => ' User is not valid! ',
                    'error_code' => 1
                );
            }
        }
        /**
         * @var string
         */
        $sCond = ' TRUE ';
        if ($iLastTime > 0)
        {
            if ($sAction == 'new')
            {
                $sCond .= ' AND pa.time_stamp > ' . $iLastTime . ' ';
            }
            else
            {
                $sCond .= ' AND pa.time_stamp < ' . $iLastTime . ' ';
            }
        }
        if ($bIsUserProfile)
        {
            $sCond .= ' AND pa.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND pa.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND pa.user_id = ' . (int) $aUser['user_id'];
        }
        else
        {
            if ($aData['view'] == 'my')
            {
                $sCond .= ' AND pa.user_id = ' . Phpfox::getUserId() . ' AND pa.profile_id = 0';
            }
            else
            {
                $sCond .= ' AND pa.view_id = 0 AND pa.privacy IN(%PRIVACY%) AND pa.total_photo > 0 AND pa.profile_id = 0';
            }
        }
        // In page:
        if (isset($aData['sModuleId']) && isset($aData['iItemId']) && !empty($aData['iItemId']))
        {
            $sCond .= ' AND pa.module_id = \'' . $aData['sModuleId'] . '\' AND pa.group_id = ' . (int) $aData['iItemId'];
        }
        $this->database()
                ->select('p.destination, p.server_id, ')
                ->leftJoin(Phpfox::getT('photo'), 'p', 'p.album_id = pa.album_id AND pa.view_id = 0 AND p.is_cover = 1');
        /**
         * @var bool
         */
        $bNoQueryFriend = $aData['view'] != 'friend';
        if (Phpfox::isModule('friend') && Phpfox::getService('friend')->queryJoin($bNoQueryFriend))
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = pa.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }
        switch ($aData['view']) {
            case 'friend':
                $sCond = str_replace('%PRIVACY%', '0,1,2', $sCond);
                break;
            case 'my':
                $sCond = str_replace('%PRIVACY%', '0,1,2,3,4', $sCond);
                break;
            default:
                $sCond = str_replace('%PRIVACY%', '0', $sCond);
                break;
        }
        $this->database()
                ->select('pa.*')
                ->from(Phpfox::getT('photo_album'), 'pa')
                ->where($sCond);
        if ($iAmountOfAlbum > 0)
        {
            $this->database()->limit($iAmountOfAlbum);
        }
        /**
         * @var array
         */
        $aAlbumPhotos = $this->database()                
                ->order('pa.time_stamp DESC')
                ->execute('getRows');
        /**
         * @var array
         */
        $aResult = array();
        foreach ($aAlbumPhotos as $aAlbum)
        {
            $aResult[] = array(
                'iAlbumId' => $aAlbum['album_id'],
                'sAlbumImageURL' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aAlbum['server_id'],
                    'path' => 'photo.url_photo',
                    'file' => $aAlbum['destination'],
                    'suffix' => MAX_SIZE_OF_USER_IMAGE_PHOTO,
                    'return_url' => true
                        )
                ),
                'sName' => $aAlbum['name'],
                'iTotalPhoto' => $aAlbum['total_photo'],
                'iTimeStamp' => $aAlbum['time_stamp'],
                'iTimeStampUpdate' => $aAlbum['time_stamp_update'],
                'iTotalComment' => $aAlbum['total_comment'],
                'iTotalLike' => $aAlbum['total_like'],
                'iTotalDislike' => $aAlbum['total_dislike'],
                'iPrivacy' => $aAlbum['privacy'],
                'iPrivacyComment' => $aAlbum['privacy_comment'],
                'iUserId' => $aAlbum['user_id'],
                'iGroupId' => $aAlbum['group_id'],
                'iViewId' => $aAlbum['view_id']
            );
        }
        return $aResult;
    }

    /**
     * Input data:
     * + iLastTime: int, optional.
     * + sAction: string, optional. Ex: "more" or "new".
     * + iAmountOfAlbum: int, optional.
     * + bIsUserProfile: bool, optional.
     * + iUserId: int, optional. Not use.
     * + sModuleId: string, optional.
     * + iItemId: int, optional.
     * + view: string, optional. Not use.
     * 
     * Output data:
     * + iAlbumId: int.
     * + sAlbumImageURL: string.
     * + sName: string.
     * + iTotalPhoto: int.
     * + iTimeStamp: int.
     * + iTimeStampUpdate: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + iUserId: int.
     * + iGroupId: int.
     * + iViewId: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/myalbum
     * 
     * @param array $aData
     * @return array
     */
    public function myalbum($aData)
    {
        $aData['view'] = 'my';
        $aData['iUserId'] = Phpfox::getUserId();

        return $this->getAlbum($aData);
    }

    /**
     * Input data:
     * + iLastTime: int, optional.
     * + sAction: string, optional. Ex: "more" or "new".
     * + iAmountOfAlbum: int, optional.
     * + bIsUserProfile: bool, optional. Not use.
     * + iUserId: int, optional.
     * + sModuleId: string, optional.
     * + iItemId: int, optional.
     * + view: string, optional.
     * 
     * Output data:
     * + iAlbumId: int.
     * + sAlbumImageURL: string.
     * + sName: string.
     * + iTotalPhoto: int.
     * + iTimeStamp: int.
     * + iTimeStampUpdate: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + iUserId: int.
     * + iGroupId: int.
     * + iViewId: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/profilealbum
     * 
     * @param array $aData
     * @return array
     */
    public function profilealbum($aData)
    {
        $aData['bIsUserProfile'] = 'true';
        
        return $this->getAlbum($aData);
    }

    /**
     * Input data:
     * + sName: string, required.
     * + sDescription: string, optional.
     * + iPrivacy: int, optional.
     * + iPrivacyComment: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see photo/albumcreate
     * 
     * @param array $aData
     * @return boolean|array
     */
    public function albumcreate($aData)
    {
        if (!isset($aData['sName']) || Phpfox::getLib('parse.format')->isEmpty($aData['sName']))
        {
            return array(
                'error_message' => 'Name is not valid!',
                'error_code' => 1
            );
        }
        if (!isset($aData['sDescription']))
        {
            $aData['sDescription'] = '';
        }
        if (!isset($aData['iPrivacy']))
        {
            $aData['iPrivacy'] = 0;
        }
        if (!isset($aData['iPrivacyComment']))
        {
            $aData['iPrivacyComment'] = 0;
        }

        // Get the total number of albums this user has
        $iTotalAlbums = Phpfox::getService('photo.album')->getAlbumCount(Phpfox::getUserId());

        // Check if they are allowed to create new albums
        $bAllowedAlbums = (Phpfox::getUserParam('photo.max_number_of_albums') == 'null' ? : (!Phpfox::getUserParam('photo.max_number_of_albums') ? false : (Phpfox::getUserParam('photo.max_number_of_albums') <= $iTotalAlbums ? false : true)));

        // Are they allowed to create new albums?
        if (!$bAllowedAlbums)
        {
            // They have reached their limit
            return array(
                'error_message' => Phpfox::getPhrase('photo.you_have_reached_your_limit_you_are_currently_unable_to_create_new_photo_albums'),
                'error_code' => 1
            );
        }

        // Assigned the post vals
        $aVals = array(
            'name' => $aData['sName'],
            'description' => $aData['sDescription'],
            'privacy' => $aData['iPrivacy'],
            'privacy_comment' => $aData['iPrivacyComment']
        );

        // Add the photo album
        if ($iId = Phpfox::getService('photo.album.process')->add($aVals))
        {
            return array('result' => $iId);
        }

        return array(
            'error_message' => Phpfox_Error::get(),
            'error_code' => 1
        );
    }

    /**
     * Use for notification.
     * @param array $aNotification
     * @return array
     */
    public function doPhotoGetCommentNotification($aNotification)
    {
        /**
         * @var array
         */
        $aPhoto = $this->database()->select('p.photo_id, p.title, u.user_id, u.gender, u.user_name, u.full_name')
                ->from(Phpfox::getT('photo'), 'p')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
                ->where('p.photo_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aPhoto['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_commented_on_gender_photo_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aPhoto['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aPhoto['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        elseif ($aPhoto['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_commented_on_your_photo_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aPhoto['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_commented_on_span_class_drop_data_user_full_name_s_span_photo_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aPhoto['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aPhoto['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }

        return array(
            'link' => array('iPhotoId' => $aPhoto['photo_id'], 'sTitle' => $aPhoto['title']),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
            'sModule' => 'photo',
            'sMethod' => 'getCommentNotification'
        );
    }
    /**
     * Use for notification.
     * @param array $aNotification
     * @return array
     */
    public function doPhotoAlbumGetNotificationAlbum_Like($aNotification)
    {
        /**
         * @var array
         */
        $aAlbum = $this->database()->select('b.album_id, b.name, b.user_id, u.gender, u.full_name')
                ->from(Phpfox::getT('photo_album'), 'b')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
                ->where('b.album_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aAlbum['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_liked_gender_own_photo_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aAlbum['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aAlbum['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        elseif ($aAlbum['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_liked_your_photo_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aAlbum['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_liked_span_class_drop_data_user_full_name_s_span_photo_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aAlbum['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aAlbum['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }

        return array(
            'link' => array(
                'iAlbumId' => $aAlbum['album_id'],
                'sAlbumTitle' => $aAlbum['name']
            ),
            'message' => strip_tags($sPhrase),
            'sModule' => '',
            'sMethod' => '',
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
    /**
     * Use for notification.
     * @param array $aNotification
     * @return array
     */
    public function doPhotoAlbumGetCommentNotificationAlbum($aNotification)
    {
        /**
         * @var array
         */
        $aAlbum = $this->database()->select('b.album_id, b.name, b.user_id, u.gender, u.full_name')
                ->from(Phpfox::getT('photo_album'), 'b')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
                ->where('b.album_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aAlbum['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_commented_on_gender_photo_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aAlbum['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aAlbum['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        elseif ($aAlbum['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_commented_on_your_photo_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aAlbum['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_commented_on_span_class_drop_data_user_full_name_s_span_photo_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aAlbum['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aAlbum['name'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }

        return array(
            'link' => array(
                'iAlbumId' => $aAlbum['album_id'],
                'sAlbumTitle' => $aAlbum['name']
            ),
            'message' => strip_tags($sPhrase),
            'sModule' => 'photo',
            'sMethod' => 'getCommentNotificationAlbum',
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
    /**
     * Use for notification.
     * @param array $aNotification
     * @return boolean
     */
    public function doPhotoGetNotificationLike($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()
                ->select('b.photo_id, b.title, b.user_id, u.gender, u.full_name')
                ->from(Phpfox::getT('photo'), 'b')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
                ->where('b.photo_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        if (!isset($aNotification['user_id']) || !isset($aRow['user_id']))
        {
            return false;
        }
        $aRow['title'] = Phpfox::getLib('parse.output')->split($aRow['title'], 20);
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_liked_gender_own_photo_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_liked_your_photo_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('photo.user_name_liked_span_class_drop_data_user_full_name_s_span_photo_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }

        return array(
            'link' => array('iPhotoId' => $aRow['photo_id'], 'sTitle' => $aRow['title']),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
    
    /**
     * Input data:
     * + iPhotoId: int, required.
     * 
     * Output data:
     * + sImage: string.
     * + iTimestamp: int.
	 * + sTimeConverted: string.
	 * + iCommentId: int.
	 * + iUserId: int.
	 * + sFullName: string.
	 * + sContent: string.
	 * + iTotalLike: int.
	 * + bIsLiked: bool.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see photo/list_all_comments
     * 
     * @param array $aData
     * @return array
     */
    public function list_all_comments($aData)
    {
        if (!isset($aData['iPhotoId']))
        {
            return array(
                'error_message' => ' Parameter is not valid! ',
                'error_code' => 1
            );
        }
        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_message' => 'You can not view photos!',
                'error_code' => 1
            );
        }
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getPhoto($aData['iPhotoId'], Phpfox::getUserId());
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid or has been deleted! "
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo', $aPhoto['photo_id'], $aPhoto['user_id'], $aPhoto['privacy'], $aPhoto['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
        return Phpfox::getService('mfox.comment')->listallcomments(array('sType' => 'photo', 'iItemId' => $aPhoto['photo_id']));
    }
    
    /**
     * Check privacy comment.
     * @param int $iPhotoId
     * @return null|array Error message array.
     */
    public function checkPrivacyCommentOnPhoto($iPhotoId)
    {        
        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_message' => 'You can not view photos!',
                'error_code' => 1
            );
        }
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getPhoto($iPhotoId, Phpfox::getUserId());
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid or has been deleted! "
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo', $aPhoto['photo_id'], $aPhoto['user_id'], $aPhoto['privacy'], $aPhoto['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
        // Check can post comment or not.
        if (!Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aPhoto))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to post comment on this item!');
        }
        return null;
    }
    
    /**
     * Check privacy on photo.
     * @param int $iPhotoId
     * @return null|array
     */
    public function checkPrivacyOnPhoto($iPhotoId)
    {        
        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_message' => 'You can not view photos!',
                'error_code' => 1
            );
        }
        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getPhoto($iPhotoId, Phpfox::getUserId());
        if (!isset($aPhoto['photo_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Photo is not valid or has been deleted! "
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo', $aPhoto['photo_id'], $aPhoto['user_id'], $aPhoto['privacy'], $aPhoto['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
        return null;
    }
    /**
     * Using to check privacy comment on album.
     * @param int $iAlbumId
     * @param bool $bIsUserProfile
     * @return null|array
     */
    public function checkPrivacyCommentOnAlbum($iAlbumId, $bIsUserProfile = false)
    {
        if (!Phpfox::getUserParam('photo.can_view_photo_albums'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view this photo albums!"
            );
        }
        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view photos!"
            );
        }
        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('comment_photo_album', $iAlbumId, Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('photo_album_like', $iAlbumId, Phpfox::getUserId());
        }
        /**
         * @var bool
         */
        if ($bIsUserProfile)
        {
            $aAlbum = Phpfox::getService('photo.album')->getForProfileView($iAlbumId);
        }
        else
        {
            // Get the current album we are trying to view
            $aAlbum = Phpfox::getService('photo.album')->getForView($iAlbumId);
        }
        // Make sure this is a valid album
        if (!isset($aAlbum['album_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('photo.invalid_photo_album')
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
		{
            return array(
                'error_message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'),
                'error_code' => 1
            );
		}
        // Check can post comment or not.
        if (!Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aAlbum))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to post comment on this item!');
        }
        return null;
    }
    /**
     * Check privacy on album.
     * @param int $iAlbumId
     * @param bool $bIsUserProfile
     * @return null|array
     */
    public function checkPrivacyOnAlbum($iAlbumId, $bIsUserProfile = false)
    {
        if (!Phpfox::getUserParam('photo.can_view_photo_albums'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view this photo albums!"
            );
        }
        if (!Phpfox::getUserParam('photo.can_view_photos'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You don't have permission to view photos!"
            );
        }
        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('comment_photo_album', $iAlbumId, Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('photo_album_like', $iAlbumId, Phpfox::getUserId());
        }
        /**
         * @var bool
         */
        if ($bIsUserProfile)
        {
            $aAlbum = Phpfox::getService('photo.album')->getForProfileView($iAlbumId);
        }
        else
        {
            // Get the current album we are trying to view
            $aAlbum = Phpfox::getService('photo.album')->getForView($iAlbumId);
        }
        // Make sure this is a valid album
        if (!isset($aAlbum['album_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('photo.invalid_photo_album')
            );
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('photo_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
		{
            return array(
                'error_message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'),
                'error_code' => 1
            );
		}
        return null;
    }
    /**
     * Push Cloud Message for photo.
     * @param int $iPhotoId
     */
    public function doPushCloudMessagePhoto($aData)
    {
        /**
         * @var int
         */
        $iPhotoId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;

        /**
         * @var array
         */
        $aPhoto = Phpfox::getService('photo')->getPhoto($iPhotoId, Phpfox::getUserId());
        
        if (isset($aPhoto['user_id']) && $aPhoto['user_id'] != Phpfox::getUserId())
        {
            /**
             * @var int
             */
            $iPushId = Phpfox::getService('mfox.push')->savePush($aData, $aPhoto['user_id']);
            // Push cloud message.
            Phpfox::getService('mfox.cloudmessage')->send(array('message' => 'notification', 'iPushId' => $iPushId), $aPhoto['user_id']);
        }
    }
    /**
     * Push Cloud Message for photo album.
     * @param array $aData
     */
    public function doPushCloudMessagePhotoAlbum($aData)
    {
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var bool
         */
        $bIsUserProfile = isset($aData['bIsUserProfile']) ? (bool) $aData['bIsUserProfile'] : false;
        if ($bIsUserProfile)
        {
            $aAlbum = Phpfox::getService('photo.album')->getForProfileView($iAlbumId);
        }
        else
        {
            // Get the current album we are trying to view
            $aAlbum = Phpfox::getService('photo.album')->getForView($iAlbumId);
        }
        
        if (isset($aAlbum['user_id']) && $aAlbum['user_id'] != Phpfox::getUserId())
        {
            /**
             * @var int
             */
            $iPushId = Phpfox::getService('mfox.push')->savePush($aData, $aAlbum['user_id']);
            // Push cloud message.
            Phpfox::getService('mfox.cloudmessage')->send(array('message' => 'notification', 'iPushId' => $iPushId), $aAlbum['user_id']);
        }
    }
}
