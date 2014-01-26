<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: album.html.php 4132 2012-04-25 13:38:46Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="item_view ">
	<div class="item_info">
		<div class="small-info">{phrase var='advancedphoto.by_lowercase'} {$aForms|user:'':'':50} | {if $aForms.yn_location}{phrase var='advancedphoto.taken_at'} <b >{$aForms.yn_location}</b> {/if}{phrase var='advancedphoto.on'} {$aForms.time_stamp|convert_time} </div>
		<ul class="small-action{if phpfox::isMobile()} mobile-action{/if}" >
			{*<li><a href="">{phrase var='advancedphoto.like'}</a></li>*}
			{if $aAlbum.user_id == Phpfox::getUserId() || Phpfox::getUserParam('advancedphoto.can_tag_other_albums')}
			<li><a href="#" onclick="$('#ynadvphoto_album_tag_form_holder').toggle(300); return false;">{phrase var='advancedphoto.tag_people'} </a></li>
			{/if}
		</ul>
	</div>
	{if $aForms.description}
			<div id="js_photo_description_{$aForms.album_id}">
				{$aForms.description|clean|shorten:200:'advancedphoto.read_more':true}
			</div>
	</br>
	{/if}

	{if ((Phpfox::getUserId() == $aForms.user_id && Phpfox::getUserParam('advancedphoto.can_edit_own_photo_album')) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo_albums'))
		|| (Phpfox::getUserId() == $aForms.user_id && $aForms.profile_id == '0')
		|| ($aForms.profile_id == '0' && (((Phpfox::getUserId() == $aForms.user_id && Phpfox::getUserParam('advancedphoto.can_delete_own_photo_album')) || Phpfox::getUserParam('advancedphoto.can_delete_other_photo_albums'))))
	}
	<div class="item_bar">
		<div class="item_bar_action_holder">
			<a href="#" class="item_bar_action"><span>{phrase var='advancedphoto.actions'}</span></a>		
			<ul>
				{template file='advancedphoto.block.menu-album'}
			</ul>			
		</div>		
	</div>
	<!--div class="js_moderation_on">
		<button class="button" href="#" onclick="tb_show('{phrase var="advancedphoto.badge_code"}', $.ajaxBox('advancedphoto.ynalbum.badgeCode', 'aid={$aAlbum.album_id}&width=450&height=300'));return false;">{phrase var="advancedphoto.get_badge_code"}</button>
	</div-->
	{/if}
	<div class="type-view">
		<button class="button" href="#" onclick="tb_show('{phrase var="advancedphoto.badge_code"}', $.ajaxBox('advancedphoto.ynalbum.badgeCode', 'aid={$aAlbum.album_id}&width=450&height=300'));return false;">{phrase var="advancedphoto.get_badge_code"}</button>
		<div id='ynadvphoto_album_tag_form_holder' style="display:none">
			<form id="ynadvphoto_album_tag_form" method="post" action="#" onsubmit="if($.trim($('#ynadvphoto_album_in_this_album').html()) == '') {l} $(this).ajaxCall('advancedphoto.ynalbum.addAlbumTag', 'bIsFirst=1'); {r} else {l} $(this).ajaxCall('advancedphoto.ynalbum.addAlbumTag', 'bIsFirst=0');{r} $('#ynadvphoto_album_tag_input').val(''); return false;">
				<div class="search-name">
					<input name="val[tag][item_id]" type="hidden" value="{$aAlbum.album_id}">
					<input name="val[tag][tag_user_id]" type="hidden" value="0" id="ynadvphoto_album_tag_user_id">
					<input type="text" id="ynadvphoto_album_tag_input" name="val[tag][note]" placeholder="{phrase var='advancedphoto.start_typing_a_friend_s_name'}" onkeyup="$.ajaxCall('friend.searchDropDown', 'search=' + this.value + '&amp;div_id=js_album_tag_search_content&amp;input_id=ynadvphoto_album_tag_user_id&amp;text_id=ynadvphoto_album_tag_input', 'GET');" />
					<div style="display:none;"><div class="input_drop_layer" id="js_album_tag_search_content" style="z-index:1000;"></div></div>
					<div class="extra_info"> <a href="#" onclick="$('#ynadvphoto_album_tag_user_id').val('{$ynadvphotoWatchingUserId}'); $('#ynadvphoto_album_tag_form').ajaxCall('advancedphoto.ynalbum.addAlbumTag'); $('#ynadvphoto_album_tag_user_id').val('0'); return false;"> {phrase var='advancedphoto.click_here_to_tag_as_yourself'} </a> </div>
					
				</div>
				<div class="clear"> </div>
				<input type="submit" value="{phrase var='advancedphoto.save'}" class="button" />
				<input type="button" value="{phrase var='advancedphoto.close'}" onclick="$('#ynadvphoto_album_tag_form_holder').toggle(300); return false;" class="button" style='background:none;background-color:#DDD;color:#333'/>
			</form>
		</div>
		<ul class="album-view">
			<li><a title="{phrase var='advancedphoto.normal_view'}" href="{permalink module='advancedphoto.album' id=$aAlbum.album_id title=$aAlbum.name view=list}"><img src="{$corepath}module/advancedphoto/static/image/list.jpg" /></a></li>
			<li><a title="{phrase var='advancedphoto.comment_view'}" href="{permalink module='advancedphoto.album' id=$aAlbum.album_id title=$aAlbum.name view=comment}"><img src="{$corepath}module/advancedphoto/static/image/comment.jpg" /></a></li>
			{if $aPhotos && !phpfox::isMobile()}
			<li><a title="{phrase var='advancedphoto.slide_view'}" href="{permalink module='advancedphoto.album' id=$aAlbum.album_id title=$aAlbum.name view=slide}"><img src="{$corepath}module/advancedphoto/static/image/unknow.jpg" /></a></li>
			{/if}
		</ul>
	</div>

	{if Phpfox::isModule('tag') && isset($aForms.tag_list)}
		{module name='tag.item' sType='album' sTags=$aForms.tag_list iItemId=$aForms.album_id iUserId=$aForms.user_id}
	{/if}	

	{if $sViewType == "comment"}
		<!-- comment view -->
		{template file="advancedphoto.controller.albumcommentview"}
	{elseif $sViewType == "slide"}
		<!-- slide view -->
		{template file="advancedphoto.controller.albumslideview"}
	{else}
		<!-- list view -->
		{template file="advancedphoto.controller.albumlistview"}
	{/if}
	
	<!-- end -->
	
		

</div>