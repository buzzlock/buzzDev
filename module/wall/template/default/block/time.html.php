<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Feed
 * @version 		$Id: entry.html.php 4171 2012-05-16 07:10:36Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="timeline_dates">
	<ul>
		<li class="parent active"><a href="#" onclick="$.ajaxCall('wall.viewMore', '{if defined('PHPFOX_IS_USER_PROFILE') && isset($aUser.user_id)}&profile_user_id={$aUser.user_id}{/if}{if isset($aFeedCallback.module)}&callback_module_id={$aFeedCallback.module}&callback_item_id={$aFeedCallback.item_id}{/if}&resettimeline=1'+ '&viewId=' + wallFillterViewId, 'GET'); return false;" class="no_ajax_link">{phrase var='feed.now'}</a></li>
		{foreach from=$aTimelineDates item=aTimelineDate}
		<li class="parent">
			<a href="#" onclick="$.ajaxCall('wall.viewMore', '{if defined('PHPFOX_IS_USER_PROFILE') && isset($aUser.user_id)}&profile_user_id={$aUser.user_id}{/if}{if isset($aFeedCallback.module)}&callback_module_id={$aFeedCallback.module}&callback_item_id={$aFeedCallback.item_id}{/if}&year={$aTimelineDate.year}&forceview=1&resettimeline=1'+ '&viewId=' + wallFillterViewId, 'GET'); $('#timeline_dates ul li ul').hide(); $('#timeline_dates li').removeClass('active'); $(this).parent().addClass('active'); $(this).parent().find('ul').show(); return false;" class="no_ajax_link">{$aTimelineDate.year}</a>
			{if isset($aTimelineDate.months) && count($aTimelineDate.months)}
			<ul>
			{foreach from=$aTimelineDate.months item=aMonth}
				<li><a href="#" onclick="$.ajaxCall('wall.viewMore', '{if defined('PHPFOX_IS_USER_PROFILE') && isset($aUser.user_id)}&profile_user_id={$aUser.user_id}{/if}{if isset($aFeedCallback.module)}&callback_module_id={$aFeedCallback.module}&callback_item_id={$aFeedCallback.item_id}{/if}&year={$aTimelineDate.year}&month={$aMonth.id}&forceview=1&resettimeline=1'+ '&viewId=' + wallFillterViewId, 'GET'); return false;" class="no_ajax_link">{$aMonth.phrase}</a></li>
			{/foreach}
			</ul>
			{/if}
		</li>
		{/foreach}
	</ul>
</div>
{module name='report.profile'}