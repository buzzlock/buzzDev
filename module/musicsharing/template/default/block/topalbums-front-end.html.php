<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
{literal}
<script>
  tt_Init();
</script>
<style type="text/css">
    .pic_album .overlay
    {
         background: url("{/literal}{$core_path}{literal}/module/musicsharing/static/image/m_size.png") no-repeat scroll 0 0 transparent;
    }
</style>
{/literal}
<table cellpading="0" cellspacing="0" border="0" width="100%">
           <tr>
               <td>
                       <div class="box_ys2" id="song_list_frame">
                         <div class="top_right_box" >
                            <div class="top_left_box" ></div>
                           <div class="title_box" style="padding-top:7px; padding-left:2px">{phrase var='musicsharing.top'} {phrase var='musicsharing.albums'}</div>
                           </div>

                         <div class="t"><div class="l"><div class="r" style="padding:1px">
                            <div  style="margin-top: 12px;min-height: 200px;">

                                      {foreach from=$top_albums  item=aAlbum}

                                            {template file="musicsharing.block.album_info"}
                                       {/foreach}
				<div class="clear"></div>
                                {if count($top_albums) > 4}
                                    <div style="float:right">
                                        <a style="margin-right:10px;" href="{if !isset($aParentModule)}{url link="musicsharing.album"}{else}{$aParentModule.msf.album}{/if}">{phrase var='musicsharing.view_more'} &#187;</a>
                                    </div>
                                {/if}
                                <div style="clear: both;"></div>
                            </div>
                        </div></div></div>

                       <div class="b"><div class="l"><div class="r"><div class="bl"><div class="br" style="height:7px">
                    </div></div></div></div></div>
                    </div>
               </td>
           </tr>
   </table>