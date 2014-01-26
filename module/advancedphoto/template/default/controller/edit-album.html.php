<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: edit-album.html.php 3661 2011-12-05 15:42:26Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if $sSlideStyle = $aForms.yn_slide_type}{/if}
<div id="js_photo_block_detail" class="js_photo_block page_section_menu_holder">
	<form method="post" action="{url link='advancedphoto.edit-album' id=$aForms.album_id}" onsubmit="if(trim($('#name').val()) == '') {l} alert('{phrase var='advancedphoto.name_can_not_be_blank_please_fill'}'); return false; {r}">
		<div id="js_custom_privacy_input_holder_album">
			{module name='privacy.build' privacy_item_id=$aForms.album_id privacy_module_id='photo_album'}
		</div>	
		{template file='advancedphoto.block.form-album'}
		<div class="table_clear">
			<input type="submit" value="{phrase var='advancedphoto.update'}" class="button" />
		</div>
	</form>
</div>

<div id="js_photo_block_photo" class="js_photo_block page_section_menu_holder" style="display:none;">
	<form method="post" action="{url link='advancedphoto.edit-album.photo' id=$aForms.album_id}">
		{foreach from=$aPhotos item=aForms}
			{template file='advancedphoto.block.edit-photo'}
		{/foreach}
	
		<div class="photo_table_clear">
			<input type="submit" value="{phrase var='advancedphoto.save_changes'}" class="button" />
		</div>
	</form>
</div>

{if $aPhotos}
<div id="js_photo_block_slideshow" class="js_slideshow_block page_section_menu_holder" style="display:none;">
	<form method="post" action="{url link='advancedphoto.edit-album.slideshow' id=$aForms.album_id}">
		<div class="slider-twrapper">
			<div class="slider-wrapper theme-default">
				<div id="slider" class="nivoSlider" eff="{$sSlideStyle}">	
					{if $count = 0}{/if}
					{foreach from=$aPhotos item=aPhoto name=photos}
						{if ($count = $count + 1)}{/if}
						<img vx="vx" src="{*
							*}{if $count == 1}{*
								*}{img return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_slide1024'}{*
							*}{else}{*
								*}buff{*
							*}{/if}{*
							*}" ref="{*
							*}{img return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_slide1024'}{*
						*}" datax-thumb="{*
							*}{img return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_75'}{*
						*}" alt="" />
					{/foreach}
				</div>
			</div>
			<!--[if IE]><style>.effects-slide{l}float: none!important;{r}</style><![endif]-->
			<div class="effects-slide">
				<div class="effects">
					<a class="{if $sSlideStyle == "boxRandom"}active{/if}" title="{phrase var="advancedphoto.box_random"}" href="#" ref="boxRandom"><span class="sl boxRandom">boxRandom</span></a>
					<a class="{if $sSlideStyle == "fade"}active{/if}" title="{phrase var="advancedphoto.fade"}" href="#" ref="fade"><span class="sl fade">fade</span></a>
					<!--a class="{if $sSlideStyle == "sliceDown"}active{/if}" title="{phrase var="advancedphoto.slice_down"}" href="#" ref="sliceDown"><span class="sl sliceDown">slice Down</span></a-->
					<a class="{if $sSlideStyle == "sliceDownRight"}active{/if}" title="{phrase var="advancedphoto.slice_down_right"}" href="#" ref="sliceDownRight"><span class="sl sliceDownRight">sliceDownRight</span></a>
					<a class="{if $sSlideStyle == "sliceDownLeft"}active{/if}" title="{phrase var="advancedphoto.slice_down_left"}" href="#" ref="sliceDownLeft"><span class="sl sliceDownLeft">sliceDownLeft</span></a>
					<!--a class="{if $sSlideStyle == "sliceUp"}active{/if}" title="{phrase var="advancedphoto.slice_up"}" href="#" ref="sliceUp"><span class="sl sliceUp">sliceUp</span></a>
					<a href="#" ref="sliceUpRight">sliceUpRight</a>
					<a href="#" ref="sliceUpLeft">sliceUpLeft</a>
					<a href="#" ref="sliceUpDown">sliceUpDown</a>
					<a href="#" ref="sliceUpDownRight">sliceUpDownRight</a>
					<a href="#" ref="sliceUpDownLeft">sliceUpDownLeft</a-->
					<a class="{if $sSlideStyle == "fold"}active{/if}" title="{phrase var="advancedphoto.fold"}" href="#" ref="fold"><span class="sl fold">fold</span></a>
					<a class="{if $sSlideStyle == "slideInRight"}active{/if}" title="{phrase var="advancedphoto.slide_in_right"}" href="#" ref="slideInRight"><span class="sl slideInRight">slideInRight</span></a>
					<a class="{if $sSlideStyle == "slideInLeft"}active{/if}" title="{phrase var="advancedphoto.slide_in_left"}" href="#" ref="slideInLeft"><span class="sl slideInLeft">slideInLeft</span></a>
					<a class="{if $sSlideStyle == "boxRain"}active{/if}" title="{phrase var="advancedphoto.box_rain"}" href="#" ref="boxRain"><span class="sl boxRain">boxRain</span></a>
					<a class="{if $sSlideStyle == "boxRainGrow"}active{/if}" title="{phrase var="advancedphoto.box_rain_grow"}" href="#" ref="boxRainGrow"><span class="sl boxRainGrow">boxRain Grow</span></a>
					<!--a class="{if $sSlideStyle == "boxRainReverse"}active{/if}" title="{phrase var="advancedphoto.box_rain_reverse"}" href="#" ref="boxRainReverse"><span class="sl boxRainReverse">box Rain Reverse</span></a>
					<a class="{if $sSlideStyle == "boxRainGrowReverse"}active{/if}" title="{phrase var="advancedphoto.box_rain_grow_reverse"}" href="#" ref="boxRainGrowReverse"><span class="sl boxRainGrowReverse">box Rain Grow Reverse</span></a-->				
				</div>
				<input type="hidden" name="val[yn_slide_type]" value="{$aAlbum.yn_slide_type}" />
				<div class="table_clear">
					<input type="submit" value="{phrase var='advancedphoto.update'}" class="button" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		
	</form>
</div>
{/if}