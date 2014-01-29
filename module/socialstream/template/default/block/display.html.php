
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Feed
 * @version 		$Id: display.html.php 4184 2012-05-30 18:21:36Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if Phpfox::getService('socialbridge.libs')->timeline()}
<div class="main_timeline" style="background:url('{img theme='layout/timeline.png' return_url=true}') repeat-y 50%;">
{/if}

{if Phpfox::isUser() && !PHPFOX_IS_AJAX && $sCustomViewType === null}
    {if (Phpfox::getUserBy('profile_page_id') > 0 && defined('PHPFOX_IS_USER_PROFILE')) 
	|| (isset($aFeedCallback.disable_share) && $aFeedCallback.disable_share) 
	|| (defined('PHPFOX_IS_USER_PROFILE') && !Phpfox::getService('user.privacy')->hasAccess('' . $aUser.user_id . '', 'feed.share_on_wall'))
	|| (defined('PHPFOX_IS_USER_PROFILE') && !Phpfox::getUserParam('profile.can_post_comment_on_profile'))
    }

    {else}
        {if !Phpfox::getService('socialbridge.libs')->timeline()}
            {template file='socialstream.block.form'}
        {/if}
    {/if}
{/if}



{if Phpfox::getParam('feed.refresh_activity_feed') > 0 && Phpfox::getLib('module')->getFullControllerName() == 'core.index-member'}
<div id="activity_feed_updates_link_holder">
    <a href="#" id="activity_feed_updates_link_single" class="activity_feed_updates_link" onclick="return $Core.loadMoreFeeds();">{phrase var='feed.1_new_update'}</a>
    <a href="#" id="activity_feed_updates_link_plural" class="activity_feed_updates_link" onclick="return $Core.loadMoreFeeds();">{phrase var='feed.span_id_js_new_update_view_span_new_updates'}</a>
</div>
{/if}



{if Phpfox::isModule('captcha') && Phpfox::getUserParam('captcha.captcha_on_comment')}
    {module name='captcha.form' sType='comment' captcha_popup=true}
{/if}



<div id="feed"><a name="feed"></a></div>

<div id="js_feed_content">
	{if $sCustomViewType !== null}
            <h2>{$sCustomViewType}</h2>
	{/if}
	<div id="js_new_feed_comment"></div>
	<div id="js_new_feed_update"></div>
	{if Phpfox::getService('socialbridge.libs')->timeline()}                
	<div>
		{if PHPFOX_IS_AJAX && !empty($sLastDayInfo) && !Phpfox::getLib('request')->get('resettimeline')}
                    <div class="timeline_date">
                            <span>{$sLastDayInfo}</span>
                    </div>
		{/if}
                <div class="timeline_left">
			{if (!PHPFOX_IS_AJAX && Phpfox::getService('socialbridge.libs')->timeline()) || Phpfox::getLib('request')->get('resettimeline')}
                            {if (Phpfox::isUser() && !PHPFOX_IS_AJAX && $sCustomViewType === null) || Phpfox::getLib('request')->get('resettimeline')}
                                {if (Phpfox::getUserBy('profile_page_id') > 0 && defined('PHPFOX_IS_USER_PROFILE')) 
                                    || (isset($aFeedCallback.disable_share) && $aFeedCallback.disable_share) 
                                    || (defined('PHPFOX_IS_USER_PROFILE') && !Phpfox::getService('user.privacy')->hasAccess('' . $aUser.user_id . '', 'feed.share_on_wall'))
                                    || (defined('PHPFOX_IS_USER_PROFILE') && !Phpfox::getUserParam('profile.can_post_comment_on_profile'))
                                }

                                {else}			
                                    <div class="timeline_feed_row">
                                            <div class="timeline_arrow_left">0</div>
                                            <div class="timeline_float_left">0</div>			
                                            <div class="timeline_holder">
                                                    {template file='socialstream.block.form'}
                                            </div>
                                    </div>
                                    <div class="timeline_left_new"></div>
                                {/if}
                            {/if}
			{/if}
                        
                        
                        
			{foreach from=$aFeedTimeline.left name=iFeed item=aFeed}
                            <div class="timeline_feed_row">
                                    <div class="timeline_arrow_left">{$aFeed.feed_id}</div>
                                    <div class="timeline_float_left">{$aFeed.time_stamp|convert_time}</div>
                                    {template file='socialstream.block.timeline'}                                
                            </div>		
			{/foreach}
		</div>
		<div class="timeline_right">
			
            <div class="timeline_feed_row">
                {if !PHPFOX_IS_AJAX || Phpfox::getLib('request')->get('resettimeline')}
                    {module name='friend.timeline'}
                {/if}
            </div>

			{foreach from=$aFeedTimeline.right name=iFeed item=aFeed}
                            <div class="timeline_feed_row">
                                    <div class="timeline_arrow_right">{$aFeed.feed_id}</div>
                                    <div class="timeline_float_right">{$aFeed.time_stamp|convert_time}</div>
                                    {template file='socialstream.block.timeline'}
                            </div>
			{/foreach}
		</div>		
		<div class="clear"></div>
	</div>	
	
	{if !PHPFOX_IS_AJAX}
		{foreach from=$aTimelineDates item=aTimelineDate}
			<div id="js_timeline_year_holder_{$aTimelineDate.year}"></div>
			{foreach from=$aTimelineDate.months item=aMonth}
			<div id="js_timeline_month_holder_{$aTimelineDate.year}_{$aMonth.id}"></div>
			{/foreach}
		{/foreach}
	{/if}	
	{else}	
	{foreach from=$aFeeds name=iFeed item=aFeed}
	{if isset($aFeed.feed_mini) && !isset($bHasRecentShow)}
	{if $bHasRecentShow = true}{/if}
	<div class="activity_recent_holder">
	<div class="activity_recent_title">
		{phrase var='feed.recent_activity'}
	</div>
	{/if}
	{if !isset($aFeed.feed_mini) && isset($bHasRecentShow)}
	</div>
	{unset var=$bHasRecentShow}
	{/if}
	
	<div class="js_feed_view_more_entry_holder">
		{template file='feed.block.entry'}
		{if isset($aFeed.more_feed_rows) && is_array($aFeed.more_feed_rows) && count($aFeed.more_feed_rows)}
		{foreach from=$aFeed.more_feed_rows item=aFeed}
		{if $bChildFeed = true}{/if}
		<div class="js_feed_view_more_entry" style="display:none;">
			{template file='feed.block.entry'}
		</div>
		{/foreach}
		{unset var=$bChildFeed}
		{/if}
	</div>	
	{/foreach}
	{/if}
	
	{if isset($bHasRecentShow)}
	</div>
	{/if}	
	{if $sCustomViewType === null}
	{if defined('PHPFOX_IN_DESIGN_MODE')}
	
	{else}
	{if count($aFeeds)}
	<div id="feed_view_more">
		<div id="js_feed_pass_info" style="display:none;">page={$iFeedNextPage}{if defined('PHPFOX_IS_USER_PROFILE') && isset($aUser.user_id)}&profile_user_id={$aUser.user_id}{/if}{if isset($aFeedCallback.module)}&callback_module_id={$aFeedCallback.module}&callback_item_id={$aFeedCallback.item_id}{/if}&year={$sTimelineYear}&month={$sTimelineMonth}</div>
		<div id="feed_view_more_loader">{img theme='ajax/add.gif'}</div>
        <a href="{if Phpfox::getLib('module')->getFullControllerName() == 'core.index-visitor'}{url link='core.index-visitor' page=$iFeedNextPage}{else}{url link='current' page=$iFeedNextPage}{/if}" onclick="$(this).hide(); $('#feed_view_more_loader').show(); var viewId = $('#feed_type_id').val(); $.ajaxCall('socialstream.viewMore', 'page={$iFeedNextPage}&viewId='+viewId+'{if defined('PHPFOX_IS_USER_PROFILE') && isset($aUser.user_id)}&profile_user_id={$aUser.user_id}{/if}{if isset($aFeedCallback.module)}&callback_module_id={$aFeedCallback.module}&callback_item_id={$aFeedCallback.item_id}{/if}&year={$sTimelineYear}&month={$sTimelineMonth}', 'GET'); return false;" class="global_view_more no_ajax_link">{phrase var='feed.view_more'}</a>
	</div>
	{else}
	{if Phpfox::getService('socialbridge.libs')->timeline()}
	{module name='user.birth'}
	{else}
	<br />
	<div class="message js_no_feed_to_show">{phrase var='feed.there_are_no_new_feeds_to_view_at_this_time'}</div>
	{/if}
	{/if}
	{/if}
	{/if}
{if !PHPFOX_IS_AJAX || (PHPFOX_IS_AJAX && count($aFeedVals))}
</div>
{/if}
{if Phpfox::getParam('feed.refresh_activity_feed') > 0 && Phpfox::getLib('module')->getFullControllerName() == 'core.index-member'}
<script type="text/javascript">
	$Core.reloadActivityFeed();
</script>
{/if} 

{if Phpfox::getService('socialbridge.libs')->timeline()}
</div>
{/if}