<?php
defined('PHPFOX') or exit('NO DICE!');
?>

			 <div class="table">
                 {if $count_playlist > 0}
                  <div class="table_left" style="font-weight: bold;">{phrase var="musicsharing.playlists"}</div>
                      <div class="table_right" style="margin-bottom: 10px;"> 
                      <select name="playlist" id="playlist" style="min-width: 200px;" >
                     {foreach from=$aPlaylists  item=iPlaylist}
                        <option value="{$iPlaylist.playlist_id}">{$iPlaylist.title}</option>
                     {/foreach}   
                    </select> 
                        </div>
              </div>
                <div class="table_clear">
                 <input type="submit" name="submit" value=" {phrase var='musicsharing.add_to_playlist'}" class="button" onclick="$.ajaxCall('musicsharing.addtoplaylist', 'idSong={$iSong}&amp;idPlaylist=' + $('#playlist').val());  tb_remove();" />
                 </div>
                 {else}
				 <?php
					// var_dump($this->_aVars["aParentModule"]);
				 ?>
                  {phrase var='musicsharing.please_insert_new_playlist'} ! <a title="{phrase var='musicsharing.add_new_playlist'}" href="{*
						*}{if !isset($aParentModule)}{*
							*}{url link='musicsharing.createplaylist'}{*
						*}{else}{*
							*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.createplaylist'}{*
						*}{/if}{*
					*}"> {phrase var='musicsharing.click_here_to_create_new'} </a>
                 {/if}
 
