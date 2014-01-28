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
  </style>
{/literal}

<div class="colAlbum  fleft">
        <div class="pic_album">
            <span class=""></span>
            <a class="def" href="{if !isset($aParentModule)}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{/if}">
				<span class="img">
					 {if $aAlbum.album_image != ""}
						<img width="115" border="0" height="108" src="{$core_path}module/musicsharing/static/image/space.gif" style="background: url('{$core_path}file/pic/musicsharing/{$aAlbum.album_image}') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">
					{else}
						 <img width="115" border="0" height="108" src="{$core_path}module/musicsharing/static/image/space.gif" style="background: url('{$core_path}module/musicsharing/static/image/music/nophoto_album_thumb.png') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">
					{/if}
				</span>
				<span class="overlay"></span>
			</a>
        </div>
        <div class="txtAlbum" >
            <h2><a class="gray12" href="{if !isset($aParentModule)}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{/if}" title="{$aAlbum.title}">{$aAlbum.title|clean|shorten:12:"...":false}</a></h2>
            <div class="author_play_track">
                <span><strong><a style="border: none;" href="{url link=$aAlbum.user_name}" title="{$aAlbum.full_name}">{$aAlbum.full_name|clean|shorten:5}</a></strong></span> |
                <span>{phrase var='musicsharing.released'}: {$aAlbum.year}</span> <br/>
                <span> <a href="{if !isset($aParentModule)}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{/if}">{$aAlbum.play_count} {phrase var='musicsharing.plays'}</a> </span> |
                <span> <a href="{if !isset($aParentModule)}{url link='musicsharing.song.album_'.$aAlbum.album_id.'.title_'.$aAlbum.title}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.song.album_'.$aAlbum.album_id.'.title_'.$aAlbum.title}{/if}">{$aAlbum.num_track} {phrase var='musicsharing.tracks'}</a>  </span>
             </div>
        </div>
    </div>