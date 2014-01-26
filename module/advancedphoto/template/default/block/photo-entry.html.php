<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: photo-entry.html.php 4532 2012-07-19 10:03:18Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

<ul id="ynadvphoto_album_photo_gallery"  class="viewimage">
{foreach from=$aPhotos item=aPhoto name=photos}
	{if !phpfox::isMobile()}
		<li class="ynadvphoto_album_photo_drag_item_holder" ynadvphoto_album_photo_drag_item_id="{$aPhoto.photo_id}">
			<div id="ynadvphoto_album_photo_draggable_item">
				{template file='advancedphoto.block.photo-entry-each'}
			</div> 
		</li> 
	{else}
		{template file='advancedphoto.block.mobile-detail'}
	{/if}
{/foreach}
</ul>

<div class="clear"></div>
{if (!isset($bIsUseTimelineInterface) || !$bIsUseTimelineInterface) && (!isset($bIsInPhotosOfMyAlbum) || !$bIsInPhotosOfMyAlbum)}
<div class="t_right">
	{pager}
</div>
{/if}

{if isset($bIsInPhotosOfMyAlbum) && $bIsInPhotosOfMyAlbum}
<script type="text/javascript">
	$Behavior.ynadvancedphotoInitDragDropPhotos = function () {l}
		$('#ynadvphoto_album_photo_gallery').dragsort({l} dragSelector: ".ynadvphoto_album_photo_drag_selector",  dragBetween: true, dragEnd: saveAlbumPhotoOrder, placeHolderTemplate: "<li class='ynadvphoto_album_photo_placeHolder'><div></div></li>" {r});
	{r};
	function saveAlbumPhotoOrder(){l}
		ynphoto.saveAlbumPhotoOrder();
	{r}
</script>
{/if}
