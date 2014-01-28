<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>
      <li id="song_{$iSong.song_id}" class="seMusicRow">
        <table cellpadding='0' cellspacing='0' class="seMusicRowInnerTable" style="border:none">
            <tr id="song_{$iSong.song_id}" class='classified_row {if $iSong.index % 2 == 0 } classified_even{else} classified_odd{/if}'>
                <td class="seMusicDeleteCheckbox">
                  <input type='checkbox' class="mysong_checkbox" name='delete_song[]' id="song_{$iSong.song_id}_checkbox" value='{$iSong.song_id}' onclick="checkDisableStatus();" />
                </td>
                
                <td class="seMusicRowButton">
                  <a target="_blank" href="{if !isset($aParentModule)}{url link='musicsharing.listen.music_'.$iSong.song_id}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.listen.music_".$iSong.song_id}{/if}"><img width="17" height="17" alt="" src="{$core_path}module/musicsharing/static/image/music/audio_small.gif"  border="0" /></a>

                </td>
                
                <td class='seMusicRowTitle music_title' id="seMusicTitle_{$iSong.song_id}">
                  <span class="seMusicID" style="display:none;">{$iSong.song_id}</span>
                  <span class="seMusicTitle">{$iSong.title}</span>
                  <span class="seMusicTitleEditor" style="display:none;"><input type="text" class="text" style="width: 250px;"/></span>
                </td>
                <td style="width:100px">
                    {if $iSong.singer_title != ""}{$iSong.singer_title}{else} {if $iSong.other_singer != ""}{$iSong.other_singer}{else} {phrase var='musicsharing.not_updated'}{/if}{/if}
                </td>
                <td style="width:100px">
                {if $iSong.cat_title != ""}{$iSong.cat_title}{else} {phrase var='musicsharing.not_updated'} {/if}
                    
                </td>
                <td class="seMusicRowFilesize" align='center' style="width:100px">
                  {if $iSong.filesize == 0}{phrase var='musicsharing.n_a'}{else}{$iSong.filesize}{phrase var='musicsharing.mb'}{/if}
                </td>
              
                <td align="center"  nowrap="nowrap" style="width:100px">
                        &nbsp;<a href="#?call=musicsharing.editsong&amp;height=350&amp;width=650&amp;idsong={$iSong.song_id}&amp;page={$page}&amp;album={$album_id}" class="inlinePopup" title="Edit song information">{phrase var='musicsharing.edit'}</a> |
                        <a href="javascript:void(0);"  onclick="if (confirm('{phrase var='musicsharing.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} delete_song('{$iSong.song_id}','song_{$iSong.song_id}'){literal}}{/literal} return false">{phrase var='musicsharing.delete'}</a>&nbsp;
                    
                </td>
                 <td align="left"  nowrap="nowrap" style="width:10px">    
                 {if $index_song == 0 and $cur_page == 1}
                        <a href="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$album_id.'/page_'.$cur_page}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.albumsongs.album_".$album_id.'/page_'.$cur_page}{/if}"><img src="{$core_path}module/musicsharing/static/image/arrow-up.png"/></a><br/>
                        <a href="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$album_id.'/ordersongdown_'.$iSong.song_id.'/page_'.$cur_page}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.albumsongs.album_".$album_id.'/ordersongdown_'.$iSong.song_id.'/page_'.$cur_page}{/if}"><img src="{$core_path}module/musicsharing/static/image/arrow-down.png"/></a>
                  {else}
                        {if $cur_page eq $max_page and $index_song eq count($list_info)-1 }
                            <a href="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$album_id.'/ordersongup_'.$iSong.song_id.'/page_'.$cur_page}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.albumsongs.album_".$album_id.'/ordersongup_'.$iSong.song_id.'/page_'.$cur_page}{/if}"><img src="{$core_path}module/musicsharing/static/image/arrow-up.png"/></a><br/>
                          <a href="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$album_id.'/page_'.$cur_page}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.albumsongs.album_".$album_id.'/page_'.$cur_page}{/if}"><img src="{$core_path}module/musicsharing/static/image/arrow-down.png"/></a>
                        {else}
                          <a href="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$album_id.'/ordersongup_'.$iSong.song_id.'/page_'.$cur_page}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.albumsongs.album_".$album_id.'/ordersongup_'.$iSong.song_id.'/page_'.$cur_page}{/if}"><img src="{$core_path}module/musicsharing/static/image/arrow-up.png"/></a><br/>
                          <a href="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$album_id.'/ordersongdown_'.$iSong.song_id.'/page_'.$cur_page}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.albumsongs.album_".$album_id.'/ordersongdown_'.$iSong.song_id.'/page_'.$cur_page}{/if}"><img src="{$core_path}module/musicsharing/static/image/arrow-down.png"/></a>
                        {/if}  
                  {/if}                                                                          
                </td>
        </tr>
        </table>
      </li>