<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style type="text/css">
	.item_tag{
		display: none !important;
	}
	.item_tag_holder{
		border-top: none !important;
		margin-top: 0 !important;
		padding-top: 0 !important;
	}
</style>
{/literal}
<div class="item_view">	
	{if $aItem.is_approved != 1}
	<div class="message js_moderation_off" id="js_approve_message">
		{phrase var='petition.this_petition_is_pending_an_admins_approval'}
	</div>
	{/if}
	
	{if Phpfox::getUserParam('petition.can_approve_petitions')
		|| (Phpfox::getUserParam('petition.edit_own_petition') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('petition.edit_user_petition')
		|| (Phpfox::getUserParam('petition.delete_own_petition') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('petition.delete_user_petition')
            || ($aItem.module_id == 'pages' && Phpfox::getService('pages')->isAdmin('' . $aItem.item_id . ''))
	}	
	<div class="item_bar">
		<div class="item_bar_action_holder">
			{if $aItem.is_approved != 1 && Phpfox::getUserParam('petition.can_approve_petitions')}
				<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>			
				<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('petition.approve', 'inline=true&amp;id={$aItem.petition_id}'); return false;">{phrase var='petition.approve'}</a>
			{/if}
                  {if (Phpfox::getUserParam('petition.edit_own_petition') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('petition.edit_user_petition')
                        || (Phpfox::getUserParam('petition.delete_own_petition') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('petition.delete_user_petition')
                        || ($aItem.module_id == 'pages' && Phpfox::getService('pages')->isAdmin('' . $aItem.item_id . ''))
                  }	
			<a href="#" class="item_bar_action"><span>{phrase var='petition.actions'}</span></a>		
			<ul>
				{template file='petition.block.link'}
			</ul>
                  {/if}
		</div>		
	</div>
	{/if}
	{if phpfox::isMobile()}
		{module name='petition.images'}
	{/if}
	<div class="info_holder">
		<div class="info">
			<div class="info_left">{phrase var='petition.target'}</div>
			<div class="info_right">{$aItem.target}</div>
		</div>
		<div class="info">
			<div class="info_left">{phrase var='petition.petition_goal'}</div>
			<div class="info_right">{$aItem.petition_goal}</div>
		</div>
		<div class="info">
			<div class="info_left">{phrase var='petition.start_date'}</div>
			<div class="info_right">{$aItem.start_time|date:'petition.petition_time_stamp'}</div>
		</div>
		<div class="info">
			<div class="info_left">{phrase var='petition.end_date'}</div>
			<div class="info_right">{$aItem.end_time|date:'petition.petition_time_stamp'}</div>
		</div>
		{if Phpfox::getUserId() != $aItem.user_id}
		<div class="info">
			<div class="info_left">{phrase var='petition.created_by'}</div>
			<div class="info_right">{$aItem|user}</div>
		</div>
		{/if}
		{if isset($aItem.category)}
		<div class="info">
			<div class="info_left">{phrase var='petition.in'}</div>
			<div class="info_right"><a href="{$aItem.category.link}">{$aItem.category.name}</a></div>
		</div>
		{/if}
		
		<div class="info">
			<div class="info_left">{phrase var='petition.stats'}</div>
			<div class="info_right">
				<span class="total_sign">{$aItem.total_sign}</span>{phrase var='petition.total_sign_signatures' total_sign=''}, {phrase var='petition.total_like_likes' total_like=$aItem.total_like}, {phrase var='petition.total_view_views' total_view=$aItem.total_view}
			</div>
		</div>		
		{if Phpfox::isModule('tag') && !defined('PHPFOX_IS_PAGES_VIEW') && isset($aItem.tag_list)}
		<div class="info">
			<div class="info_left">{phrase var='petition.topic_s'}</div>
			<div class="info_right">
				{module name='tag.item' sType=$sTagType sTags=$aItem.tag_list iItemId=$aItem.petition_id iUserId=$aItem.user_id}
			</div>
		</div>
		{/if}
	</div>	
	{if phpfox::isMobile()}
		{module name='petition.signnow'}
		{module name='petition.detail' sType=description id=$aItem.petition_id}
		{module name='petition.detail' sType=letter id=$aItem.petition_id}
		{module name='petition.detail' sType=signatures id=$aItem.petition_id}
		{module name='petition.detail' sType=news id=$aItem.petition_id}
	{else}
	{module name='petition.detail' sType=description id=$aItem.petition_id}
	{/if}
	{plugin call='petition.template_controller_view_end'}
	<div id="petition_comment_block">
		<div {if $aItem.is_approved != 1}style="display:none;" class="js_moderation_on"{/if}>		
			{module name='feed.comment'}
		</div>
	</div>
</div>
