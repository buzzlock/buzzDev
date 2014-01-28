<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>

{literal}
<style type="text/css">
  .bold_album
  {
    font-weight: bold;
  }
  div.album_tooltip
  {
      width:300px;
  }
  .table_left
  {
      float:left;
  }
  .table_right
  {
      margin-left:128px;
}
  .gray12{
      font-size: 12px;
      font-weight: bold;
  }

{/literal}
</style>

<div style="float:left">

    <div class="table_left pic_album">
            <span class=""></span>
            <a class="def" href="{url link='musicstore.listen.album_'.$aAlbum.album_id}" onmouseout="UnTip()" onclick="UnTip()" onmouseover="javascript:Tip('<div class=\'album_tooltip\'><h4><strong>{$name}:</strong> {$aAlbum.title_replace}</h4><div><span class=\'bold_album\'>{$description}:</span> {$aAlbum.description}</div><div class=\'bold_album\'>{$listofsongs}:</div><div></div><div><ul>{if count($aAlbum.list_song)>0}{foreach from=$aAlbum.list_song item=list_song}<li>- {$list_song.title}</li>{/foreach}{if count($aAlbum.list_song)>10 }<li>- ...</li>{/if}{else}No songs in this album{/if}</ul></div>')">
                    <span class="img">
                         {if $aAlbum.album_image != ""}
                            <img width="115" border="0" height="108" src="{$core_path}module/musicstore/static/image/space.gif" style="background: url('{$core_path}file/pic/musicstore/{$aAlbum.album_image}') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">
                        {else}
                             <img width="115" border="0" height="108" src="{$core_path}module/musicstore/static/image/space.gif" style="background: url('{$core_path}module/musicstore/static/image/music/nophoto_album_thumb.png') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">

                        {/if}

                    </span>
                    <span class="overlay"></span>
                </a>
    </div>
<div class="txtAlbum" style="height:120px;">
            <h2><a class="gray12" href="{url link='musicstore.listen.album_'.$aAlbum.album_id}" title="{$aAlbum.title}">{$aAlbum.title|clean|shorten:100:"...":false}</a></h2>
            <div class="author_play_track">
                 <span> Play: <a href="{url link='musicstore.listen.album_'.$aAlbum.album_id}">{$aAlbum.play_count}</a> </span><br/>
                <span> Song: <a href="{url link='musicstore.song.album_'.$aAlbum.album_id.'.title_'.$aAlbum.title}">{$aAlbum.num_track}</a>  </span><br/><br/>
               
             </div>
      
        </div>
              
    <div class="clear"></div>

</div>      


