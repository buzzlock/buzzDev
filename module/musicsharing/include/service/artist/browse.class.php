<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Service_Artist_Browse extends Phpfox_Service {

    /**
     * Class constructor
     */
    public function __construct()
    {
        
    }

    public function query()
    {
        $aParentModule =  phpFox::getLib('session')->get('pages_msf');
        
        $this->database()->select('(
            SELECT COUNT(*) 
            FROM ' . Phpfox::getT('m2bmusic_album') . ' AS filter_album 
            WHERE filter_album.user_id = user.user_id
            AND (
	filter_album.user_id = ' . Phpfox::getUserId() . ' OR filter_album.privacy IN (0)  
	OR (
		filter_album.privacy = 4 
		AND ' . Phpfox::getUserId() . ' IN (
			SELECT ' . Phpfox::getT('friend_list_data') . '.friend_user_id 
			FROM  ' . Phpfox::getT('friend_list') . '
			INNER JOIN ' . Phpfox::getT('privacy') . ' ON ' . Phpfox::getT('privacy') . '.friend_list_id = ' . Phpfox::getT('friend_list') . '.list_id 
			INNER JOIN ' . Phpfox::getT('user') . ' ON ' . Phpfox::getT('user') . '.user_id = ' . Phpfox::getT('privacy') . '.user_id 
			INNER JOIN ' . Phpfox::getT('friend_list_data') . ' ON ' . Phpfox::getT('friend_list') . '.list_id = ' . Phpfox::getT('friend_list_data') . '.list_id 
			WHERE ' . Phpfox::getT('privacy') . '.module_id = "musicsharing_album" AND ' . Phpfox::getT('privacy') . '.item_id = filter_album.album_id
		)
	)  
	OR (
		filter_album.privacy = 3 
		AND filter_album.user_id = ' . Phpfox::getUserId() . '
	)  
	OR (
		filter_album.privacy IN (1) 
		AND filter_album.user_id IN (
			SELECT fr.user_id 
			FROM ' . Phpfox::getT('friend') . ' as fr 
			WHERE fr.friend_user_id = ' . Phpfox::getUserId() . '
		)
	) 
	OR (
		filter_album.privacy IN (2) 
		AND (
			filter_album.user_id IN (
				SELECT f.user_id 
				FROM ' . Phpfox::getT('friend') . ' AS f 
				INNER JOIN (
					SELECT ffxf.friend_user_id 
					FROM ' . Phpfox::getT('friend') . ' AS ffxf 
					WHERE ffxf.is_page = 0 
					AND ffxf.user_id = ' . Phpfox::getUserId() . '
				) AS sf ON sf.friend_user_id = f.friend_user_id 
				JOIN ' . Phpfox::getT('user') . ' AS u ON u.user_id = f.friend_user_id
			) 
			OR filter_album.user_id IN (
				SELECT fr.user_id 
				FROM ' . Phpfox::getT('friend') . ' AS fr 
				WHERE fr.friend_user_id = ' . Phpfox::getUserId() . '
			)
		)
	)
) ' . ($aParentModule ? 'AND (filter_album.module_id = "' . $aParentModule['module_id'] . '" AND filter_album.item_id = ' . $aParentModule['item_id'] . ')' : 'AND (filter_album.module_id IS NULL OR filter_album.module_id = "")') . ') AS total_album, ');
    }

    public function processRows(&$aRows)
    {
        
    }

    public function getQueryJoins($bIsCount = false, $bNoQueryFriend = false)
    {
        
    }

    public function __call($sMethod, $aArguments)
    {
        
    }

}

?>
