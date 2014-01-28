<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style type="text/css">
#right{
    width: 220px;
}
</style>
{/literal}
<ul class="action">
{foreach from=$relatedAlbums item=ralbum}
{if $ralbum.num_track > 0}
       <li> <a class="first" href="{if !isset($aParentModule)}{url link='musicsharing.listen.album_'.$ralbum.album_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.album_'.$ralbum.album_id}{/if}">{$ralbum.title} </a> </li>
  {/if}
{/foreach}

</ul>