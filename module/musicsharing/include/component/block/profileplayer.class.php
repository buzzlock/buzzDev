<?php

defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Block_Profileplayer extends Phpfox_Component
{
    private function getSongPathForCDN($sSong, $iServerId = null)
	{
		if (preg_match("/\{file\/musicsharing\/(.*)\.mp3\}/i", $sSong, $aMatches))
		{
			return Phpfox::getParam('core.path') . str_replace(array('{', '}'), '', $aMatches[0]);
		}
        
		$sSong = phpFox::getParam('core.path') . 'file/musicsharing/' . sprintf($sSong, '');
        
		if (Phpfox::getParam('core.allow_cdn') && !empty($iServerId))
		{
			$sTempSong = Phpfox::getLib('cdn')->getUrl($sSong, $iServerId);
			if (!empty($sTempSong))
			{
				$sSong = $sTempSong;
			}
		}
		
		return $sSong;
	}
    
    public function process()
    {
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        $this->template()->assign(array('settings' => $settings));

        $this->template()->assign(array(
            'core_path' => phpFox::getParam('core.path')
        ));
        

        if (isset($settings['can_post_on_profile']) && $settings['can_post_on_profile'] == 0)
        {
            $this->template()->assign(array(
                'suppress' => "suppress",
                'idplaylist' => -1,
            ));
            return 'block';
        }

        $aUser = (PHPFOX_IS_AJAX ? phpFox::getService('user')->get(phpFox::getUserId(), true) : $this->getParam('aUser'));
        $user_id = $aUser['user_id'];
        $playlist_id = phpFox::getService('musicsharing.music')->getPlaylisDefault($user_id);
        if ($playlist_id <= 0)
        {
            $this->template()->assign(array(
                'idplaylist' => $playlist_id,
            ));
            return;
        }
        $music_info = null;
        $info = phpFox::getService('musicsharing.music')->getPlaylistInfo($playlist_id);
        $amusic = phpFox::getService('musicsharing.music')->getPlaylistSongs($playlist_id, "", 1);
        
        $check = phpFox::getService('musicsharing.music')->check('musicsharing_playlist', $info['playlist_id'], $info['user_id'], $info['privacy'], $info['is_friend'], true);
        
        if (!$check)
        {
            $playlist_id = -1;
            $music_info = array();
            $this->template()->assign(array(
                'idplaylist' => $playlist_id,
                    )
            );
            return "block";
        }

        $music_info = isset($amusic[0]) ? $amusic[0] : 0;
        if (count($music_info) > 0)
        {
            $info = phpFox::getService('musicsharing.music')->getPlaylistInfo($playlist_id);
            $this->setParam('artistId', $info['user_id']);
            $this->setParam('aFeed', array(
                'comment_type_id' => 'musicsharing_playlist',
                'privacy' => 0,
                'comment_privacy' => $info['privacy_comment'],
                'like_type_id' => 'musicsharing_playlist',
                'feed_is_liked' => $info['is_liked'],
                'feed_is_friend' => $info['is_friend'],
                'item_id' => $playlist_id,
                'user_id' => $info['user_id'],
                'total_comment' => $info['total_comment'],
                'total_like' => $info['total_like'],
                'feed_link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $playlist_id)),
                'feed_title' => $info['title'],
                'feed_display' => 'view',
                'feed_total_like' => $info['total_like'],
                'report_module' => 'musicsharing_playlist',
                'report_phrase' => Phpfox::getPhrase('musicsharing.report_this_playlist'),
                'time_stamp' => strtotime($info['creation_date']),
            ));
            //??
            $_SESSION['musicsharing_listen'] = array('item_id' => $music_info['album_id'], 'type' => 'musicsharing_playlist');
            $info['album_image'] = $info['playlist_image'];

            $bHasSong = false;
            $arFirstSong = array();
            // For HMTL5 player.
            $arSongs = phpFox::getService('musicsharing.music')->getSongsInPlaylist($playlist_id, phpFox::getUserId());
            if (count($arSongs) > 0)
            {
                foreach ($arSongs as $i => $arSong)
                {
                    $arSongs[$i]['ordering'] = $i + 1;
                    $arSongs[$i]['url'] = $this->getSongPathForCDN($arSongs[$i]['url'], $arSongs[$i]['server_id']);
                }
                $bHasSong = true;
                $arFirstSong = $arSongs[0];
            }
            
            $this->template()->assign(array(
                'album_info' => $info,
                'urldata' => phpFox::getLib('url')->makeUrl('musicsharing.data.name_getplaylist.idplaylist_' . $playlist_id),
                'music_info' => $music_info,
                'user_id' => phpFox::getUserId(),
                'idplaylist' => $playlist_id,
                'time' => PHPFOX_TIME,
                'listofsongs' => phpFox::getPhrase('musicsharing.list_of_songs'),
                'description' => phpFox::getPhrase('musicsharing.description'),
                'name' => phpFox::getPhrase('musicsharing.name'),
                'bHasSong' => $bHasSong,
                'arSongs' => $arSongs,
                'arFirstSong' => $arFirstSong
                    )
            );
        }
        
        
        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_profileplayer_clean')) ? eval($sPlugin) : false);
    }

}

?>