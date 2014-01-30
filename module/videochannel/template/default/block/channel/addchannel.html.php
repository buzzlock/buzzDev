<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<form id="channel_add" method="post" action="#" onsubmit="$(this).ajaxCall('videochannel.channel.saveChannel','id={$aForms.channel_id}'); $('#img_action').show(); $('.btn_submit').hide(); return false;">
<div class="channel_edit_row {if phpfox::isMobile()}mobile-add-channel{/if}">
	<!-- Channel Information -->
	<div id="channel_info" {if $act == "yes"} style="display: none" {/if} >
		<div class="channel_edit_holder">
			<div class="t_center">
				{if !empty($aForms.img)}				
				<img width="120" height="90" class="js_mp_fix_width photo_holder" alt="{$aForms.title}" src="{$aForms.img}"/>
				{else}
				{img theme='noimage/item.png'}
				{/if}
			</div>
			
			{if ($sModule != 'pages')}
			<div class="p_4">
				{if Phpfox::isModule('privacy') && Phpfox::getUserParam('videochannel.can_set_allow_list_on_videos')}			
				<div class="table">
					<div class="table_left">
						{phrase var='videochannel.privacy'}:
					</div>
					<div class="table_right">
						{module name='privacy.form' privacy_name='privacy' privacy_info='videochannel.control_who_can_view_this_channel' privacy_no_custom=true}						
					</div>
				</div>
				{/if}
				
				{if Phpfox::isModule('comment') && Phpfox::isModule('privacy') && Phpfox::getUserParam('videochannel.can_control_comments_on_videos')}			
				<div class="table">
					<div class="table_left">
						{phrase var='videochannel.comment_privacy'}:
					</div>
					<div class="table_right">										
						{module name='privacy.form' privacy_name='privacy_comment' privacy_info='videochannel.control_who_can_comment_all_videos_on_this_channel' privacy_no_custom=true}
						
					</div>			
				</div>
				{/if}
			</div>
			{/if}
		</div>
		
		<div class="channel_edit_info">
			<div><input type="hidden" name="val[site_id]" value="{$aForms.site_id}" /></div>
			<div><input type="hidden" name="val[url]" value="{$aForms.url}" /></div>
			<div class="table">
				<div class="table_left">
					{required}{phrase var='videochannel.title'}:
				</div>
				<div class="table_right">
					<input type="text" value="{$aForms.title}" name="val[title]"/>
				</div>
			</div>
			<div class="table">
				<div class="table_left">
					{required}{phrase var='videochannel.category'}:
				</div>
				<div class="table_right">				
					{$aForms.aCategories}
				</div>
			</div>
			<div class="table">
				<div class="table_left">
					{phrase var='videochannel.summary'}:
				</div>
				<div class="table_right">
					<textarea cols="35" rows="4" name="val[description]">
						{$aForms.summary|clean}
					</textarea>
					
				</div>
			</div>
		</div>
	</div>
	<!-- End Channel Information -->
	<div class="clear"></div>	
	<!-- Videos list -->	
	<div id="video_list_action" class="brd_bottom clear">
		<h1 style="float: left">{phrase var='videochannel.videos_list'}</h1>
		{if $act != 'no'} 
			<a href="javascript:void(0);" class="selectall" onclick="selectAllVideo(this); return false;">{phrase var='core.select_all'}</a>
			<a href="javascript:void(0);" class="unselectall" onclick="selectAllVideo(this); return false;" style="display: none;" >{phrase var='core.un_select_all'}</a>
		{/if}
	</div>
	<div class="table" id="channel_video_list">
		{if $act == 'no'}
			<script type="text/javascript"> activeId = 0; </script>
			{template file='videochannel.block.channel.videolist'} 	
		{else}
			{img theme='ajax/add.gif'}
		{/if}
	</div>
	<!-- End Videos list -->
</div>
<div class="clear"></div>
{img theme='ajax/add.gif' id='img_action' style='display: none'}
{if isset($sShowCategory)}
	{if $act == 'yes'}
		<script>loadVideoList("{$aForms.url_encode}");</script>
	{/if}	
	<input id='js_channel_btn_update' class="button btn_submit" type="submit" name="val[action]" value="{phrase var='core.update'}"/>
	{if $act == 'no'}
		{if isset($aVideos) && count($aVideos) && (Phpfox::getUserParam('videochannel.can_delete_own_video') || Phpfox::getUserParam('videochannel.can_delete_other_video'))}  
		<input id='js_channel_btn_deleteall' class="button btn_submit" type="button" value="{phrase var='videochannel.delete_all'}" onclick="if(confirm('{phrase var='videochannel.delete_all_videos_belong_to_this_channel'}')) deleteAllVideos({$aForms.channel_id}); return false;" />
		{/if}
	{/if}
	{$sShowCategory}
{else}
	<input id='js_channel_btn_add' class="button btn_submit" type="submit" name="val[action]" value="{phrase var='core.add'}"/>
	<script>loadVideoList("{$aForms.url_encode}");</script>
{/if}

        <div><input type="hidden" name="val[callback_module]" value="{$sModule}" /></div>

        <div><input type="hidden" name="val[callback_item_id]" value="{$iItem}" /></div>
		<div><input type="hidden" name="iIndex" value="{$iIndex}" /></div>

</form>