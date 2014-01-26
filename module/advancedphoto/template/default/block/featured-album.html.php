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
<h1 class="featured_albums">{phrase var='advancedphoto.featured_albums'}</h1>
<div id="galleria">
	{foreach from=$aFeatureds item=aAlbum name=aFeatured}
		<a href="{$aAlbum.slideshow_big_image_url}">
			{img server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_75' max_width=240 max_height=240}
		</a>
	{/foreach}
</div>
{foreach from=$aFeatureds item=aAlbum name=aFeatured}
	<input type="hidden" name="{$aAlbum.slideshow_big_image_url}" value="{$aAlbum.link}" />
{/foreach}

{else}

<h1 class="featured_albums">{phrase var='advancedphoto.featured_albums'}</h1>
{foreach from=$aFeatureds item=aAlbum name=aFeatured}
	<div class="mobile-bg-img">
		<a href="{$aAlbum.link}">
			{img server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_150' height=70}
		</a>
	</div>
{/foreach}
<div class="clear"></div>
{/if}