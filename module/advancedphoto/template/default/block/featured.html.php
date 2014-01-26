<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: featured.html.php 3214 2011-09-30 12:05:14Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !phpfox::isMobile()}
<!-- Slide Show -->
<div id="galleria">
	{foreach from=$aFeatureds item=aPhoto name=aFeatured}
		<div href="{$aPhoto.slideshow_big_image_url}" rel="{$aPhoto.photo_id}" >
			{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_75' max_width=240 max_height=240 rel=$aPhoto.photo_id}
		</div>
	{/foreach}
</div>

{foreach from=$aFeatureds item=aPhoto name=aFeatured}
	<input type="hidden" name="{$aPhoto.slideshow_big_image_url}" value="{$aPhoto.link}" rel="{$aPhoto.photo_id}"/>
	<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}{if isset($sPhotoCategory)}category_{$sPhotoCategory}/{/if}" title="{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}" id="ynadvphoto_hidden_featured_photo_a_{$aPhoto.photo_id}"class="ynadvphoto_thickbox photo_holder_image {if Phpfox::getParam('advancedphoto.view_photos_in_theater_mode')} no_ajax_link {/if}" rel="{$aPhoto.photo_id}" >
	</a>	
{/foreach}
{else}	
<!-- Mobile View -->
	<div class="block">
		<div class="title">{phrase var='advancedphoto.featured_photos'}</div>
		<div class="content">
			{foreach from=$aFeatureds item=aPhoto name=aFeatured}
				{template file='advancedphoto.block.mobile-detail'}
			{/foreach}
			<div class="clear"></div>
		</div>
	</div>
{/if}
