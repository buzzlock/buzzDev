<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class musicsharing_Component_Controller_UpLoad extends Phpfox_Component
{
    public function __construct($aParams)
    {
        parent::__construct($aParams);
    }

    public function process()
    {
        PhpFox::isUser(true);
        $aParentModule = $this->getParam('aParentModule');
        $album_id = $this->request()->getInt('album');
        if ($aParentModule)
        {
            $url = $this->url()->makeUrl($aParentModule['module_id'] . '.' . $aParentModule['item_id'] . '.musicsharing.upload', array('album' => $album_id));
            phpFox::getLib('session')->set('pages_msf', $aParentModule);

            if (!phpFox::getService('pages')->hasPerm($aParentModule['item_id'], 'musicsharing.share_music'))
            {
                $this->url()->send("subscribe");
            }
        }
        else
        {
            $url = $this->url()->makeUrl('musicsharing.upload', array('album' => $album_id));

            phpFox::getLib('session')->remove('pages_msf');
        }

        if (isset($aParentModule['use_timeline']) && $aParentModule['use_timeline'])
        {
            $this->template()->assign(array('iUploadNumber' => 1));
        }
        
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);

        $settings = Phpfox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        
        $album_info = Phpfox::getService('musicsharing.music')->getAlbumInfo($album_id);

        if ((isset($album_info["user_id"]) && $album_info["user_id"] != phpFox::getUserId()) || count($album_info) <= 2)
        {
            $album_id = 0;
            $album_info = array();
            $album_info['num_track'] = 0;
            $album_info['title'] = '';
        }

        $rest_number_song = $settings['max_songs'] - $album_info['num_track'];

        $space_music = Phpfox::getService('musicsharing.music')->getUsedSpace(phpFox::getUserId());

        $limit_space = $settings['max_storage_size'] - $space_music / 1024;

        if ($limit_space <= 0)
            $limit_space = 0;

        if ($limit_space < $settings['max_file_size_upload'])
            $settings['max_file_size_upload'] = $limit_space;

        $settings['max_file_size_upload_mb'] = round($settings['max_file_size_upload'] / 1024, 2);

        if ($rest_number_song < 0 || $settings['max_file_size_upload'] <= 0)
            $rest_number_song = 0;

        $iMaxFileSize = $settings['max_file_size_upload_mb'] * 1048576;

        if (PHPFOX_IS_AJAX_PAGE || PHPFOX_IS_AJAX)
        {
            echo '<script type="text/javascript">var music_redict_url = "' . $url . '";</script>';
        }
        $this->template()->assign(
                array(
                    'settings' => $settings,
                    'album_info' => $album_info,
                    'rest_number_song' => $rest_number_song,
                    'limit_space' => $limit_space,
                    'total_space_used' => round($space_music / 1024 / 1024, 2),
                )
        );

        $this->template()
                ->setPhrase(array(
                    'musicsharing.you_can_upload_a_mp3_file',
                    'core.name',
                    'core.status',
                    'core.in_queue',
                    'core.upload_failed_your_file_size_is_larger_then_our_limit_file_size',
                    'core.more_queued_than_allowed'
                        )
                )
                ->setHeader(array(
                    'massuploader/swfupload.js' => 'static_script',
                    'massuploader/upload.js' => 'static_script',
                    '<script type="text/javascript">
                    var music_redict_url = "' . $url . '";
                    $oSWF_settings =
                    {
                        object_holder: function()
                        {
                            return \'swf_msf_upload_button_holder\';
                        },

                        div_holder: function()
                        {
                            return \'swf_msf_upload_button\';
                        },

                        get_settings: function()
                        {
                            swfu.setUploadURL("' . $this->url()->makeUrl('musicsharing.up') . '");
                            swfu.setFileTypes("*.mp3","MP3 Music Files");
                            swfu.setFileSizeLimit("' . $iMaxFileSize . ' B");
                            swfu.setFileUploadLimit(' . $rest_number_song . ');
                            swfu.setFileQueueLimit(' . $rest_number_song . ');
                            swfu.customSettings.flash_user_id = ' . phpFox::getUserId() . ';
                            swfu.customSettings.sHash = "' . phpFox::getService('core')->getHashForUpload() . '";
                            swfu.customSettings.sAjaxCall = "musicsharing.uploadProcess";
                            swfu.customSettings.sAjaxCallAction = function(){
                                tb_show(\'\', \'\', null, \'' . Phpfox::getPhrase('musicsharing.please_hold_while_your_files_are_being_processed') . '\');
                            };

                            swfu.atFileQueue = function()
                            {
                                $(\'#js_msf_form :input\').each(function(iKey, oObject)
                                {
                                    swfu.addPostParam($(oObject).attr(\'name\'), $(oObject).val());
                                });
                            }
                        }
                    }
                </script>',
                        )
        );

        $this->template()->assign(array(
            'album_id' => $album_id,
            'core_path' => phpFox::getParam('core.path')
        ));
        //load all album of current user...

        $where = sprintf(" %s.user_id = %d", phpFox::getT("m2bmusic_album"), phpFox::getUserId());
        $aAlbums = Phpfox::getService('musicsharing.music')->getAlbums(0, null, null, null, $where);

        $this->setParam('album_id', $album_id);

        $this->template()
                ->setTitle('Upload Music')
                ->setBreadcrumb(phpFox::getPhrase('musicsharing.music_sharing'), null)
                ->setHeader('cache', array(
                    'progress.js' => 'static_script',
                    'm2bmusic_class.js' => 'module_musicsharing',
                    'musicsharing_style.css' => 'module_musicsharing',
                    'suppress_menu.css' => 'module_musicsharing',
                        )
                )
                ->assign(
                        array(
                            'iMaxFileSize' => $iMaxFileSize,
                            'aAlbums' => $aAlbums,
                        )
        );

        //modified section (v 300b1)
        //build filter menu
        Phpfox::getService('musicsharing.music')->getSectionMenu($aParentModule);
        $catitle = $this->template()->getBreadCrumb();
        if (!$aParentModule)
        {
            $satitle = isset($catitle[1]) ? (isset($catitle[1][0]) ? $catitle[1][0] : (isset($catitle[0][0]) ? $catitle[0][0] : "")) : "";
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.upload_song'), null, true);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

}
