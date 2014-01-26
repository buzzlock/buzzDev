<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: albums.html.php 3533 2011-11-21 14:07:21Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if isset($bIsUserProfile) && $bIsUserProfile}
	<div class="menu">
	<ul id="advancedphoto_tab">
		<li>
			<a href="{url link=$aUser.user_name}advancedphoto"><span>{phrase var='advancedphoto.photos'}</span></a> 
		</li>
		<li class="active">
			<a href="{url link=$aUser.user_name}advancedphoto/albums"><span>{phrase var='advancedphoto.albums'}</span></a>	
		</li>
	</ul>
	<div class="clear"></div>
	<br/>
</div>	
{/if}

{if isset($bIsInMyAlbum) && $bIsInMyAlbum && isset($bIsInMyAlbumEditMode) && !$bIsInMyAlbumEditMode && !phpfox::isMobile()}
<div class="" style="margin-left: 371px; margin-top: -14px; padding-bottom: 10px;">
	<a href="{url link='current' mode='edit'}" > <span><strong>{phrase var='advancedphoto.edit_album_order'} </strong> </span> </a>
</div>
{/if}
	
{if $bIsInHomePageAlbums}
<h1 class="newest-album">{phrase var='advancedphoto.newest_albums'}</h1>
{/if}
{if count($aAlbums)}
<ul id="ynadvphoto_gallery">
	{foreach from=$aAlbums item=aAlbum name=albums}
		{if !phpfox::isMobile()}
		<li class="ynadvphoto_drag_item_holder" ynadvphoto_drag_item_id="{$aAlbum.album_id}">
			<div id="ynadvphoto_draggable_item">
				{template file='advancedphoto.block.album-entry'}
			</div>
		</li>
		{else}
			{template file='advancedphoto.block.mobile-album-detail'}	
		{/if}
	{/foreach}
</ul>
<div class="clear"></div>
{pager}
{else}
<div class="extra_info">
	{phrase var='advancedphoto.no_albums_found_here'}
</div>
{/if}

{if isset($bIsInMyAlbum) && $bIsInMyAlbum  && isset($bIsInMyAlbumEditMode) && $bIsInMyAlbumEditMode }
<script type="text/javascript">
	$Behavior.ynadvphotoInitializeDragdropForAlbums = function () {l}
		$('#ynadvphoto_gallery').dragsort({l} dragSelector: ".ynadvphoto_drag_selector", dragEnd: saveAlbumOrder, dragBetween: true, placeHolderTemplate: "<li class='ynadvphoto_placeHolder'><div></div></li>" {r});
	{r};
	function saveAlbumOrder()
	{l}
		ynphoto.saveAlbumOrder();
	{r}
</script>
{/if}
