<?php
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style type="text/css">
#tb_msf_s tr td
{
    vertical-align: middle;
}
</style>
{/literal}
<table id="tb_msf_s" cellpading="0" cellspacing="0" border="0" width="100%">
           <tr>
               <td>
                       <div class="box_ys2" id="song_list_frame">
                         <div class="top_right_box" >
                            <div class="top_left_box" ></div>
                           <div class="title_box" style="padding-top:7px; padding-left:2px">{phrase var='musicstore.top_songs'}</div>
                           </div>
                          
                         <div class="t"><div class="l"><div class="r" style="padding:1px">
                         {if count($top_songs)>0}   
                            <div>
                                <table cellpadding="0" cellspacing="0" width="100%">
                                <tr style="background:#2C2C2C none repeat scroll 0 0;">
                                    <td height="25px" width="40%" style="font-weight:bold;color:#FFF;padding:2px 2px 2px 7px;">{phrase var='musicstore.song'}</td>
                                    <td style="font-weight:bold;color:#FFF;padding:2px;text-align:center">{if $user_id > 0}{phrase var='musicstore.play_add_download'}{else}{phrase var='musicstore.play_download'}{/if}</td>
                                    <td style="font-weight:bold;color:#FFF;padding:2px;text-align:center">{phrase var='musicstore.file_type'}</td>
                                    <td style="font-weight:bold;color:#FFF;padding:2px;text-align:center">{phrase var='musicstore.artist'}</td>
                                </tr>
                                {foreach from=$top_songs  item=iSong}
                                {template file="musicstore.block.song_info}
                                {/foreach}
                             <tr><td></td><td></td><td></td><td style="float: right; padding-right: 15px; text-align: right"><br/><a href="{url link = 'musicstore.song'}">{phrase var='musicstore.view_more'} &#187;</a></td></tr>
                            </table>
                            </div>
                            {else}
                                 <div align="center" style="margin: 20px;">{phrase var='musicstore.there_is_no_song_yet'}</div>         
                            {/if}
                        </div></div></div>

                       <div class="b"><div class="l"><div class="r"><div class="bl"><div class="br" style="height:7px">
                    </div></div></div></div></div>
                    </div>
               </td>
           </tr>
   </table>