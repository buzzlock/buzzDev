<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: view.html.php 4582 2012-08-01 08:25:38Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if isset($aForms.view_id) && $aForms.view_id == 1}
<div class="message js_moderation_off">
	{phrase var='advancedphoto.image_is_pending_approval'}
</div>
{/if}
{if $bIsTheater && !Phpfox::isMobile()}
<div id="photo_view_theater_mode" class="photo_view_box_holder">
	<div class="photo_view_in_photo">
		<b>{phrase var='advancedphoto.in_this_photo'}:</b> <span id="js_photo_in_this_photo"></span>		
	</div>				
	
	<div id="js_photo_box_view_bottom_ad">
		{module name='ad.display' block_id='photo_theater'}
				
		<a href="#" onclick="$('#js_photo_box_view_more').slideToggle(); return false;" class="photo_box_photo_detail">{phrase var='advancedphoto.photo_details'}</a>
		<div id="js_photo_box_view_more">
			<div class="js_photo_box_view_more_padding">
				{module name='advancedphoto.detail' is_in_photo=true}
			</div>
		</div>									
	</div>
	
	<div class="photo_view_box_comment">			
		<div class="photo_view_box_comment_padding">
			<div id="js_photo_view_box_title">
				<div class="row_title">
					<div class="row_title_image">
						{img user=$aForms suffix='_50_square' max_width=50 max_height=50}
					</div>
					<div class="row_title_info" style="position:relative;">					
						<div class="photo_view_box_user">{$aForms|user:'':'':50} </div>
						<ul class="extra_info_middot">
							<li>{$aForms.time_stamp|convert_time}</li>
							{if isset($aForms.yn_location) && $aForms.yn_location}
								{phrase var='advancedphoto.taken_at'} <b >{$aForms.yn_location}</b> 	
							{/if}
							{if !empty($aForms.album_id)} 
							<li> {phrase var='advancedphoto.in'} <a href="{$aForms.album_url}" class="no_ajax_link">{$aForms.album_title|clean|split:45|shorten:75:'...'}</a> </li>						
							{/if}
						</ul>
					</div>
				</div>
				
				{if (Phpfox::getUserParam('advancedphoto.can_edit_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo')
					|| (Phpfox::getUserParam('advancedphoto.can_delete_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_delete_other_photos')
				}
				<div class="item_bar">
					<div class="item_bar_action_holder">
						{if $aForms.view_id == '1' && Phpfox::getUserParam('advancedphoto.can_approve_photos')}
							<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>
							<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('advancedphoto.approve', 'inline=true&amp;id={$aForms.photo_id}'); return false;">{phrase var='advancedphoto.approve'}</a>
						{/if}
						<a href="#" class="item_bar_action"><span>{phrase var='advancedphoto.actions'}</span></a>		
						<ul>
							{module name='advancedphoto.menu'}
						</ul>			
					</div>		
				</div>	    
				{/if}			
				
				{if $aForms.description}
				<div id="js_photo_description_{$aForms.photo_id}" class="extra_info">
					{$aForms.description|clean|shorten:200:'advancedphoto.read_more':true|emoticon}
				</div>
				{/if}
			</div>
					
			{if Phpfox::isModule('tag') && isset($aForms.tag_list)}
			{module name='tag.item' sType='advancedphoto' sTags=$aForms.tag_list iItemId=$aForms.photo_id iUserId=$aForms.user_id}
			{/if}			
						
			{plugin call='advancedphoto.template_default_controller_view_extra_info'}			
			
			<div id="js_photo_view_comment_holder" {if $aForms.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
				{module name='advancedphoto.yncomment'}
			</div>	
		</div>
	</div>

	<div class="photo_view_box_image photo_holder_image" {if isset($aPhotoStream.next.photo_id)}onclick="tb_show('', '{$aPhotoStream.next.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}', this);" rel="{$aPhotoStream.next.photo_id}"{/if}>		
		 <div id="photo_view_tag_photo" style="z-index:1000">
			<a href="#" id="js_tag_photo">{phrase var='advancedphoto.tag_this_photo'}</a>
		</div>
		<div id="photo_view_ajax_loader">{img theme='ajax/loader.gif'}</div>
			{if $aPhotoStream.total > 1}
			<div class="photo_next_previous">
				<ul>
				{if isset($aPhotoStream.previous.photo_id)}
				<li class="previous"><a href="{$aPhotoStream.previous.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}"{if $bIsTheater} class="ynadvphoto_thickbox photo_holder_image no_ajax_link" rel="{$aPhotoStream.previous.photo_id}"{/if}>{phrase var='advancedphoto.previous'}</a></li>
				{/if}	

				{if isset($aPhotoStream.next.photo_id)}
				<li class="next"><a href="{$aPhotoStream.next.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}"{if $bIsTheater} class="ynadvphoto_thickbox photo_holder_image no_ajax_link" rel="{$aPhotoStream.next.photo_id}"{/if}>{phrase var='advancedphoto.next'}</a></li>
				{/if}
				</ul>
				<div class="clear"></div>
			</div>
			{/if}				
		
			<div class="photo_view_box_image_holder" style="position:absolute;">			
				{if isset($aPhotoStream.next.photo_id)}
				<a href="{$aPhotoStream.next.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}"{if $bIsTheater} class="ynadvphoto_thickbox photo_holder_image no_ajax_link" rel="{$aPhotoStream.next.photo_id}"{/if}>
				{/if}
					{if $aForms.user_id == Phpfox::getUserId()}
						{img id='js_photo_view_image' server_id=$aForms.server_id path='photo.url_photo' file=$aForms.destination suffix='_1024' max_width=1024 max_height=1024 title=$aForms.title time_stamp=true onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
					{else}
						{img id='js_photo_view_image' server_id=$aForms.server_id path='photo.url_photo' file=$aForms.destination suffix='_1024' max_width=1024 max_height=1024 title=$aForms.title onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
					{/if}

				{if isset($aPhotoStream.next.photo_id)}
				</a>
				{/if}			
			</div>
		</div>
	<div class="clear"></div>
</div>


{if $bIsTheater}
<script type="text/javascript">
$Behavior.ynResizeImageToFitScreen = function() {l}
	var iPhotoWidth = {$aForms.width};
	var iPhotoHeight = {$aForms.height};
	var oImage = $('#js_photo_view_image');
	if(iPhotoWidth > iPhotoHeight)
	{l}
		var realWidth = screen.width - 400 - 50;
		oImage.css('max-width', realWidth);
		oImage.css('max-height', realWidth * (iPhotoHeight) / (iPhotoWidth));
	{r}
	else
	{l}
		var realHeight = screen.height - 250;
		oImage.css('max-height', realHeight);
		oImage.css('max-width', realHeight * (iPhotoWidth) / (iPhotoHeight));
	{r}
	
{r}
</script>
{/if}


<script type="text/javascript">
$Behavior.autoLoadPhoto = function(){l}
	
	{literal}
	//$('#main_core_body_holder').hide();
	
	$('#photo_view_ajax_loader').hide();
	$('.js_box_image_holder_full').find('.js_box').show();
	$('.js_box_image_holder_full').find('.js_box').width($(window).width() - 40);
	$('.js_box_image_holder_full').find('.js_box_content').height(getPageHeight() - 70);		
	$('.js_box_image_holder_full').css('position', 'fixed');
	
	var iCommentBoxMaxHeight = 300;

	iCommentBoxMaxHeight = (($('.js_box_image_holder_full').find('.js_box_content').height() - ($('#js_photo_view_box_title').height() + $('#js_photo_box_view_bottom_ad').height())) - 170);	
		
	$('.js_box_image_holder_full').find('.js_feed_comment_view_more_holder:first').css({
		'max-height': iCommentBoxMaxHeight + 'px',
		overflow: 'auto'
	});		
		
	$('.photo_view_box_comment').css('min-height', $('.js_box_image_holder_full').find('.js_box').height());	
	$('.js_box_image_holder_full').find('.js_box').css({
		'top': 0,
		'left': '16px'	    		
	});
	
	$('.photo_view_box_image_holder').css({
		top: '50%',
		'margin-top': '-' + ($('#js_photo_view_image').height() / 2) + 'px',
		left: '50%',
		'margin-left': '-' + ($('#js_photo_view_image').width() / 2) + 'px'		
	});			
   
	$('.js_box_image_holder_full_loader').hide();
	
	$('.photo_view_box_image').height($('.js_box_image_holder_full').find('.js_box_content').height());
	$('#photo_view_theater_mode').find('.js_comment_feed_textarea:first').focus(function(){
		$(this).height(50);
		$('#js_ad_space_photo_theater').hide();
		$(this).addClass('no_resize_textarea');
		return true;
	});
	
	{/literal}
	
	$Core.photo_tag.init({l}{$sPhotoJsContent}{r});
	$Behavior.autoLoadPhoto = function(){l}{r}
{r}
</script>
			
{else}
<div class="item_view photo_item_view" {if $bIsTheater} id="photo_view_theater_mode"{/if}>
	<div id="js_album_outer_content">
		
		{if !$bIsTheater}
	    <div class="item_info">
			{phrase var='advancedphoto.time_stamp_by_full_name' time_stamp=$aForms.time_stamp|convert_time full_name=$aForms|user:'':'':35} 
			{if isset($aForms.yn_location) && $aForms.yn_location}
			<span> </span>{phrase var='advancedphoto.taken_at'} <b >{$aForms.yn_location}</b> 	
			{/if}
			{if !empty($aForms.album_id)} <br /> {phrase var='advancedphoto.in'} <a href="{$aForms.album_url}">{$aForms.album_title|clean|split:45|shorten:75:'...'}</a>{/if}
	    </div>
	    {/if}
	    {if (Phpfox::getUserParam('advancedphoto.can_edit_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo')
	    	|| (Phpfox::getUserParam('advancedphoto.can_delete_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_delete_other_photos')
	    }
		<div class="item_bar">
			<div class="item_bar_action_holder">
				{if $aForms.view_id == '1' && Phpfox::getUserParam('advancedphoto.can_approve_photos')}
					<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>
					<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('advancedphoto.approve', 'inline=true&amp;id={$aForms.photo_id}'); return false;">{phrase var='advancedphoto.approve'}</a>
				{/if}
				<a href="#" class="item_bar_action"><span>{phrase var='advancedphoto.actions'}</span></a>		
				<ul>
					{template file='advancedphoto.block.menu'}
				</ul>			
			</div>		
		</div>	    
		{/if}
		{if !$bIsTheater}
		{if $aPhotoStream.total > 1}	
	    <div class="photo_next_previous">
			<ul>
			<li class="photo_stream_info">{phrase var='advancedphoto.photo_current_of_total' current=$aPhotoStream.current total=$aPhotoStream.total}</li>
			{if isset($aPhotoStream.previous.photo_id)}
			<li class="previous"><a href="{$aPhotoStream.previous.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}">{phrase var='advancedphoto.previous'}</a></li>
			{/if}	
		
			{if isset($aPhotoStream.next.photo_id)}
			<li class="next"><a href="{$aPhotoStream.next.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}">{phrase var='advancedphoto.next'}</a></li>
			{/if}
			</ul>
			<div class="clear"></div>
		</div>
		{/if}			
		{/if}
	
		<div class="t_center" id="js_photo_view_holder_process"></div>
		<div class="t_center" id="js_photo_view_holder">
		
		{if $aPhotoStream.total > 1 && $bIsTheater}
	    <div class="photo_next_previous">
			<ul>
			{if isset($aPhotoStream.previous.photo_id)}
			<li class="previous"><a href="{$aPhotoStream.previous.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}"{if $bIsTheater} class="ynadvphoto_thickbox photo_holder_image no_ajax_link" rel="{$aPhotoStream.previous.photo_id}"{/if}>{phrase var='advancedphoto.previous'}</a></li>
			{/if}	
		
			{if isset($aPhotoStream.next.photo_id)}
			<li class="next"><a href="{$aPhotoStream.next.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}"{if $bIsTheater} class="ynadvphoto_thickbox photo_holder_image no_ajax_link" rel="{$aPhotoStream.next.photo_id}"{/if}>{phrase var='advancedphoto.next'}</a></li>
			{/if}
			</ul>
			<div class="clear"></div>
		</div>
		{/if}		
	
		
			{if (Phpfox::getUserParam('advancedphoto.can_edit_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo')}
			<div class="photo_rotate">
				<ul>					
					<li>
						<a href="#" onclick="$('#menu').remove(); $('#noteform').hide(); $('#js_photo_view_image').imgAreaSelect({left_curly} hide: true {right_curly}); $('#js_photo_view_holder').hide(); $('#js_photo_view_holder_process').html($.ajaxProcess('', 'large')).height($('#js_photo_view_holder').height()).show(); $.ajaxCall('advancedphoto.rotate', 'photo_id={$aForms.photo_id}&amp;photo_cmd=left'); return false;" class="left js_hover_title">
							<span class="js_hover_info">
								{phrase var='advancedphoto.rotate_left'}
							</span></a>
					</li>
					<li>
						<a href="#" onclick="$('#menu').remove(); $('#noteform').hide(); $('#js_photo_view_image').imgAreaSelect({left_curly} hide: true {right_curly}); $('#js_photo_view_holder').hide(); $('#js_photo_view_holder_process').html($.ajaxProcess('', 'large')).height($('#js_photo_view_holder').height()).show(); $.ajaxCall('advancedphoto.rotate', 'photo_id={$aForms.photo_id}&amp;photo_cmd=right'); return false;" class="right js_hover_title "><span class="js_hover_info">{phrase var='advancedphoto.rotate_right'}</span></a>
					</li>
				</ul>
				<div class="clear"></div>
			</div>
			{/if}			
		
			{if isset($aPhotoStream.next.photo_id)}
			<a href="{$aPhotoStream.next.link}{if $iForceAlbumId > 0}albumid_{$iForceAlbumId}{else}{if isset($feedUserId)}userid_{$feedUserId}/{/if}{/if}"{if $bIsTheater} class="ynadvphoto_thickbox photo_holder_image no_ajax_link" rel="{$aPhotoStream.next.photo_id}"{/if}>
			{/if}
			{if Phpfox::isMobile()}
				{if $aForms.user_id == Phpfox::getUserId()}
					{img id='js_photo_view_image' server_id=$aForms.server_id path='photo.url_photo' file=$aForms.destination suffix='_500' max_width=285 max_height=300 title=$aForms.title time_stamp=true onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
				{else}
					{img id='js_photo_view_image' server_id=$aForms.server_id path='photo.url_photo' file=$aForms.destination suffix='_500' max_width=285 max_height=300 title=$aForms.title onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
				{/if}
			{else}
				{if $aForms.user_id == Phpfox::getUserId()}
					{img id='js_photo_view_image' server_id=$aForms.server_id path='photo.url_photo' file=$aForms.destination suffix='_500' max_width=500 max_height=500 title=$aForms.title time_stamp=true onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
				{else}
					{img id='js_photo_view_image' server_id=$aForms.server_id path='photo.url_photo' file=$aForms.destination suffix='_500' max_width=500 max_height=500 title=$aForms.title onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
				{/if}
			{/if}
			
			{if isset($aPhotoStream.next.photo_id)}
			</a>
			{/if}
		
		</div>

		{if $bIsTheater}
		<div class="photo_view_ad">
			{module name='ad.display' block_id='photo_theater'}
		</div>
		
		<div class="photo_view_detail">
			<div class="photo_view_detail_content">
				{if $bIsTheater}
					{if $aPhotoStream.total > 1}
					<div class="extra_info">
						{phrase var='advancedphoto.photo_current_of_total' current=$aPhotoStream.current total=$aPhotoStream.total}
					</div>
					{/if}
				{/if}
				<div class="extra_info">
					{if !empty($aForms.album_id)} {phrase var='advancedphoto.in_the_album'} <a href="{$aForms.album_url}">{$aForms.album_title|clean|split:45|shorten:75:'...'}</a>{/if}
					{if isset($aCallback.theater_mode)}<div class="p_top_4">{$aCallback.theater_mode}</div>{/if}
					<div class="p_top_4">
						{phrase var='advancedphoto.by_full_name_lowercase' full_name=$aForms|user:'':'':20}
					</div>
				</div>
			</div>
			
			{module name='advancedphoto.detail'}
		</div>	
		
		<div class="photo_view_comment">
		{/if}		
			{if $aForms.description}
			<div id="js_photo_description_{$aForms.photo_id}">
				{$aForms.description|clean|shorten:200:'advancedphoto.read_more':true}
			</div>
			{/if}
			
			<div class="extra_info" style="display:none;">
				<b>{phrase var='advancedphoto.in_this_photo'}:</b> <span id="js_photo_in_this_photo"></span>
			</div>		
		
			{if Phpfox::isModule('tag') && isset($aForms.tag_list)}
			{module name='tag.item' sType='advancedphoto' sTags=$aForms.tag_list iItemId=$aForms.photo_id iUserId=$aForms.user_id}
			{/if}	
			
			{plugin call='advancedphoto.template_default_controller_view_extra_info'}
			
			<div {if $aForms.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
				{module name='advancedphoto.yncomment'}
			</div>	
		{if $bIsTheater}
		</div>	
		
		<div class="clear"></div>
		{/if}
	</div>
</div>
<script type="text/javascript">$Behavior.tagPhoto = function() {l} $Core.photo_tag.init({l}{$sPhotoJsContent}{r}); {r};
$Behavior.removeTagBox = function() 
{l} 
	{literal}
	if ($('#noteform').length > 0)$('#noteform').hide(); if ($('#js_photo_view_image').length > 0 && typeof $('#js_photo_view_image').imgAreaSelect == 'function')$('#js_photo_view_image').imgAreaSelect({ hide: true });
	{/literal}
{r}
</script>
{/if}

{if $bIsTheater}
<script type="text/javascript">

		$('.photo_view_box_holder .user_profile_link_span a').addClass('no_ajax_link');
		$('.photo_view_box_holder .row_title .row_title_image a').addClass('no_ajax_link');
		$('.photo_view_box_holder .item_tag_holder a').addClass('no_ajax_link');
		$('.comment_mini_image a').addClass('no_ajax_link');

</script>
{/if}