<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<script language="javascript">
    var html_code_for_blog  = '<table width="290" border="0" style="border:1px solid #CCCCCC;background-color:#FFFFFF; border-collapse:collapse;" cellpadding="5px" > ' ;
        html_code_for_blog += '<tr> <td align="center" nowrap="nowrap">';
        html_code_for_blog += '<span style="color:#dd0197;"><strong>{$music_info.title}</strong></span><br/>';        
        html_code_for_blog += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="230" height="70" id="build/swf/simpleplayer" align="middle">';
        html_code_for_blog += '<param name="allowScriptAccess" value="sameDomain" />';
        html_code_for_blog += '<param name="allowFullScreen" value="false" />';
        html_code_for_blog += '<param name="wmode" value="transparent" />';
        html_code_for_blog += '<param name="movie" value="{$core_path}module/musicsharing/static/swf/simpleplayer.swf?idsong={$music_info.song_id}&{if $boolAlbum == true}idalbum={$music_info.album_id}{else}idplaylist={$idplaylist}{/if}&rootURL={$core_path}'+ '&rootModule={*
			*}{if !isset($aParentModule)}{*
				*}{url link="musicsharing"}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing'}{*
			*}{/if}{*
		*}' + '" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />    <embed src="{$core_path}module/musicsharing/static/swf/simpleplayer.swf?idsong={$music_info.song_id}&{if $boolAlbum == true}idalbum={$music_info.album_id}{else}idplaylist={$idplaylist}{/if}&&rootURL={$core_path}'+ '&rootModule={*
			*}{if !isset($aParentModule)}{*
				*}{url link="musicsharing"}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing'}{*
			*}{/if}{*
		*}' + '" quality="high" bgcolor="#ffffff" width="290" height="70" name="build/swf/simpleplayer" align="middle" wmode="transparent" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />';
        html_code_for_blog += '</object>';               
        html_code_for_blog += '</td>';
        html_code_for_blog += '</tr>';
        html_code_for_blog += '</table>' ;
</script>

 <div class="top_popup">
        <div class="cter_popup" id="cter_popup">            
            <div class="right_url">
                <p>
                <div id="url" class="fl" style="margin-left:3px"><strong style="vertical-align: middle;">Link URL</strong></div>
                <div id="url_inactive" class="fl" style="display:none"><a style="vertical-align: middle;" href="javascript:void(0)" onclick="music_get_url('url','{*
						*}{if !isset($aParentModule)}{*
							*}{url link='musicsharing.listen.music_'.$music_info.song_id}{*
						*}{else}{*
							*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{*
						*}{/if}{*
					*}')">{phrase var='musicsharing.link_url'}</a></div>
                    <div class="fl">&nbsp;&nbsp;|&nbsp;&nbsp;</div>
                    <div class="fl" id="html_code" style="display:none"><strong style="vertical-align: middle;">HTML Code</strong></div>
                    <div class="fl" id="html_code_inactive"><a style="vertical-align: middle;" href="javascript:void(0)" onclick='music_get_url("html_code", html_code_for_blog)'>{phrase var='musicsharing.html_code'}</a></div>
                    <div class="fl">&nbsp;&nbsp;|&nbsp;&nbsp;</div>
                    <div class="fl" id="bb_code" style="display:none;"><strong style="vertical-align: middle;">Forum Code</strong></div>
                   <div class="fl" id="bb_code_inactive"> <a style="vertical-align: middle;" href="javascript:void(0)" onclick='music_get_url("bb_code","[URL={*
						*}{if !isset($aParentModule)}{*
							*}{url link='musicsharing.listen.music_'.$music_info.song_id}{*
						*}{else}{*
							*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{*
						*}{/if}{*
					*}]{$music_info.title}[/URL]")'>{phrase var='musicsharing.forum_code'}</a></div>
                </p>
                
                <p><input type="text" onclick="url_select_text(this)"  readonly="readonly" id="result_url" name="result_url"  style="width:507px;height:20px;border:1px solid #d2d8db; margin:1px; margin-top: 10px;" value="{*
					*}{if !isset($aParentModule)}{*
						*}{url link='musicsharing.listen.music_'.$music_info.song_id}{*
					*}{else}{*
						*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{*
					*}{/if}{*
				*}"/></p>
                {if ($music_info.is_download != 0 || $user_id == $music_info.user_id ) && $settings.can_download_song eq 1} 
                <div id="song_item_{$music_info.song_id}" class="right_url2 tam_left"><img src="{$core_path}module/musicsharing/static/image/music/icon_download.png" width="22" height="18px" />&nbsp;&nbsp;&nbsp;
                <span id="download"><a style="margin-top:4px;position: absolute; " class="music_player_tracks_url" type="audio" rel="{$music_info.song_id}" href="javascript:void();" onclick="window.location.href='{$core_path}module/musicsharing/static/download.php?f={$core_path}file/musicsharing/{$music_info.url}&fc=\'{$music_info.title}.mp3\'&idsong={$music_info.song_id}';return false;">{phrase var='musicsharing.download'}</a>
                </div>
                {/if}
                 <div class="right_url2 tam_left"><img src="{$core_path}module/musicsharing/static/image/music/icon_guiquayahoo.png" width="22" height="18" align="absmiddle"/>&nbsp;&nbsp;&nbsp;<a style="margin-top:4px;position: absolute; " href='ymsgr:sendIM?m=%20{*
					*}{if !isset($aParentModule)}{*
						*}{url link='musicsharing.listen.music_'.$music_info.song_id}{*
					*}{else}{*
						*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{*
					*}{/if}{*
				*}' target="_blank">{phrase var='musicsharing.send_to_yahoo'}</a></div>
                {if $user_id > 0} 
               <div class="right_url2 tam_left"><img src="{$core_path}module/musicsharing/static/image/music/icon_themvaoplaysist.png" width="22" height="18" align="absmiddle"/>&nbsp;
                    <a style="margin-top:4px;position: absolute; " href="#?call=musicsharing.addplaylist&amp;height=100&amp;width=400&amp;idsong={$music_info.song_id}" class="inlinePopup" title="{phrase var='musicsharing.add_song_to_playlist'}">{phrase var='musicsharing.add_to_playlist'}</a>
                     </div>
            {/if}
            <div id="bookmark" class="right_url2 tam_left">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style "
                addthis:url="{*
					*}{if !isset($aParentModule)}{*
						*}{url link='musicsharing.listen.music_'.$music_info.song_id}{*
					*}{else}{*
						*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{*
					*}{/if}{*
				*}"
                >
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_2"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7be7602a83d379"></script>
            {if PHPFOX_IS_AJAX || PHPFOX_IS_AJAX_PAGE}
            {literal}
            <script>
                $Behavior.init = function()
                {
                     $.getScript('http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7be7602a83d379&domready=1', function() {
                        addthis.init();
                          addthis.toolbox(".addthis_toolbox");
                      });
                }
            </script>   
            
            {/literal}
            {/if}
            <!-- AddThis Button END -->

            </div>
        </div>
    </div>
    
 </div>  