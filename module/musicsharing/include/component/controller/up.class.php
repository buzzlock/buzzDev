<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class MusicSharing_Component_Controller_Up extends Phpfox_Component
{

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        // We only allow users the ability to upload images.
        if (!PhpFox::isUser())
        {
            exit;
        }
        
        if (isset($_FILES['Filedata']) && !isset($_FILES['musicfile']))
        { // enable_mass_uploader == true
            $_FILES['musicfile'] = array();
            $_FILES['musicfile']['error']['m'] = UPLOAD_ERR_OK;
            $_FILES['musicfile']['name']['m'] = $_FILES['Filedata']['name'];
            $_FILES['musicfile']['type']['m'] = $_FILES['Filedata']['type'];
            $_FILES['musicfile']['tmp_name']['m'] = $_FILES['Filedata']['tmp_name'];
            $_FILES['musicfile']['size']['m'] = $_FILES['Filedata']['size'];
        }
        // If no images were uploaded lets get out of here.
        if (!isset($_FILES['musicfile']))
        {
            exit;
        }

        // Make sure the user group is actually allowed to upload an image
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        if ($this->request()->get('album'))
        {
            $album_id = $this->request()->get('album');
        }
        else
        {
            $album_id = 0;
        }

        $album_info = phpFox::getService('musicsharing.music')->getAlbumInfo($album_id);
        $rest_number_song = $settings['max_songs'] - $album_info['num_track'];

        $space_music = phpFox::getService('musicsharing.music')->getUsedSpace(phpFox::getUserId());

        $limit_space = $settings['max_storage_size'] - $space_music / 1024;

        if ($limit_space <= 0)
            $limit_space = 0;
        if ($limit_space < $settings['max_file_size_upload'])
            $settings['max_file_size_upload'] = $limit_space;
        $settings['max_file_size_upload_mb'] = round($settings['max_file_size_upload'] / 1024, 2);
        if ($rest_number_song < 0 || $settings['max_file_size_upload'] <= 0)
            $rest_number_song = 0;
        //endcheck
        $oFile = phpFox::getLib('file');
        $aVals = $this->request()->get('val');
        if (!is_array($aVals))
        {
            $aVals = array();
        }
        $aImages = array();
        $aFeed = array();
        $iFileSizes = 0;
        $iCnt = 0;

        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_frame_start')) ? eval($sPlugin) : false);
        $aMusics = array();

        foreach ($_FILES['musicfile']['error'] as $iKey => $sError)
        {

            if ($sError == UPLOAD_ERR_OK)
            {

                if ($aMusics = $oFile->load('Filedata', array(
                    'mp3',
                    'MP3',
                        ), ($rest_number_song === 0 ? null : ($settings['max_file_size_upload_mb']))
                        )
                )
                {

                    if ($settings['max_songs'] <= $album_info['num_track'])
                    {
                        Phpfox_Error::set('Upload fail.');
                    }
                    else
                    {
                        $file = $_FILES['Filedata'];
                        $currentDate = date("Y-m-d H:i:s");
                        $full_songname = $file['name'];
                        $arrayName = explode(".", $full_songname);
                        $ext = $arrayName[sizeof($arrayName) - 1];
                        $lengExt = strlen($ext);
                        $name_song = substr($full_songname, 0, strlen($full_songname) - ($lengExt + 1));
                        $filesize = $file['size'];
                        $p = PHPFOX_DIR_FILE . 'musicsharing' . PHPFOX_DS;
                        if (!is_dir($p))
                        {
                            if (!@mkdir($p, 0777, 1))
                            {
                                
                            }
                        }
                        
                        $target_path = phpFox::getLib('file')->upload('Filedata', $p, $file['name']);
                        $song = array();
                        $song['title'] = $name_song;
                        $song['title_url'] = $name_song;
                        $song['album_id'] = $album_id;
                        $song['filesize'] = $filesize;
                        $song['url'] = sprintf($target_path, '');
                        $song['ext'] = $ext;
                        $song['cat_id'] = 0;
                        $song['singer_id'] = 0;
                        $furl = $p . $song['url'];
                        $aMeta = $oFile->getMeta($furl);
                        $id = 0;
                        if (count($aMeta))
                        {
                            if (isset($aMeta['audio']) && isset($aMeta['audio']['dataformat']) && $aMeta['audio']['dataformat'] == "mp3")
                            {
                                $id = phpFox::getService('musicsharing.music')->uploadSong($song);
                            }
                            else
                            {
                                $oFile->unlink($furl);
                                phpfox_error::set('- ' . $song['title'] . ' - ' . phpFox::getPhrase('musicsharing.invalid_file_type_we_only_accept_mp3_file'));
                            }
                        }
                        else
                        {
                            $oFile->unlink($furl);
                            phpfox_error::set('- ' . $song['title'] . ' - ' . phpFox::getPhrase('musicsharing.invalid_file_type_we_only_accept_mp3_file'));
                        }
                        
                        if (Phpfox_Error::isPassed())
                        {
                            $rname_song = str_replace("'", "\'", $name_song);
                            $html = '<a class="first" href="' . phpFox::getLib('url')->makeUrl('musicsharing.listen', array('music' => $id)) . '">' . $rname_song . ' </a>';

                            echo ';if(window.parent.document.getElementById(\'uploaded_number\').value ==0){window.parent.document.getElementById(\'uploaded_song_msf\').innerHTML="";window.parent.document.getElementById(\'uploaded_number\').value = 1 }window.parent.document.getElementById(\'uploaded_song_msf\').innerHTML += \'<li style="background-color:#F1F1F1">' . $html . '</li>\';';
                        }
                    }
                    (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_up_process_song')) ? eval($sPlugin) : false);
                }
                else
                {
                    
                }
            }
        }

        $iFeedId = 0;
        // Make sure we were able to upload some images
        if (count($aMusics) && Phpfox_Error::isPassed())
        {
            
        }
        else
        {
            // Output JavaScript    
            //echo '<script type="text/javascript">';
            echo 'window.parent.document.getElementById(\'js_upload_error_message\').innerHTML = \'<div class="error_message">' . implode('', Phpfox_Error::get()) . '</div>\';';
            // echo '</script>';
        }

        exit;
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_frame_clean')) ? eval($sPlugin) : false);
    }

}

?>