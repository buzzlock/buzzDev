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
  .line_album{
    border-top: 1px solid #DFDFDF;
    }
{/literal}
</style>
<div>
    <div class="border_top"></div>
<div class="table">
    <div class="table_left pic_album">
            <span class=""></span>
            <a class="def" title="{$aAlbum.title|clean} - {$aAlbum.user_name}" alt="{$aAlbum.title|clean} - {$aAlbum.user_name}" href="{*
				*}{if !isset($aParentModule)}{*
					*}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{*
				*}{else}{*
					*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{*
				*}{/if}{*
                *}">

				<span class="img">
                    {if $aAlbum.album_image != ""}
						<img width="115" border="0" height="108" src="{$core_path}module/musicsharing/static/image/space.gif" style="background: url('{img server_id=$aAlbum.server_id path='musicsharing.url_image' suffix='_115' file=$aAlbum.album_image max_width='115' max_height='115' return_url=true}') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">
                    {else}
						 <img width="115" border="0" height="108" src="{$core_path}module/musicsharing/static/image/space.gif" style="background: url('{$core_path}module/musicsharing/static/image/music/nophoto_album_thumb.png') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">
					{/if}

				</span>
				<span class="overlay"></span>
			</a>
    </div>
    <div class="table_right">
         <div class="txtAlbum" style="min-height:120px;">
            <h2>{*[trace-privacy: {$aAlbum.privacy}]*}
				<a class="gray12" href="{*
						*}{if !isset($aParentModule)}{*
							*}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{*
						*}{else}{*
							*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{*
						*}{/if}{*
				*}" title="{$aAlbum.title} - {$aAlbum.user_name}">{$aAlbum.title|clean|shorten:100:"...":false}</a></h2>
            <div class="author_play_track">
				<div class="top-descript">
					<span>{phrase var='musicsharing.play_s'}:</span> <span class="link-style">{$aAlbum.play_count}</span><br/>
					<span>{phrase var='musicsharing.released'}: {$aAlbum.year} {phrase var='musicsharing.by'} <a href="{url link=$aAlbum.user_name}">{$aAlbum.full_name}</a></a></span> <br/>
					<span>{phrase var='musicsharing.songs'}:</span> <span class="link-style">{$aAlbum.num_track}</span><br/><br/>
					<?php
						//@jh: hot fix
						if(strpos($this->_aVars['aAlbum']['description'], "<br />") === false){ldelim}
							$this->_aVars['aAlbum']['description'] = str_replace("\n", "<br />", $this->_aVars['aAlbum']['description']);
						{rdelim}
					?>
					<span>{$aAlbum.description|shorten:220:"...":false} </span>
				</div>
				<a class="play-playlist" href="{*
					*}{if !isset($aParentModule)}{*
						*}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{*
					*}{else}{*
						*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{*
					*}{/if}{*
				*}" title="{$aAlbum.title} - {$aAlbum.user_name}">
					<span class="listen_playlist">{phrase var='musicsharing.listen_album'}</span>
				</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>