<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{literal}
<style type="text/css">
   .row_title_info .username_listing
   {
       margin-bottom: 5px;
   }
   .username_listing a
   {
       font-size: 12px;
   }
   .button_follow
   {
       background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/follow.png) repeat-x center;
       color: #000000;
        font-size: 12px;
        line-height: 20px;
        padding: 3px 11px;
        border: 1px solid #dfdfdf;
   }
   .owner_left{width: 55px;}
   .inline_list ul{
		display: inline;
   }
   .margin-bottom-10 {
		margin-bottom: 10px;
   }
</style>
{/literal}
<div class="today_listing margin-bottom-10">
    <div class="content_listing">
        <div class="row_title_image">
			{img user=$aListing suffix='_50_square' max_width='50' max_height='50'}

			{*if ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_edit_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_edit_other_listing')
			|| ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_delete_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_delete_other_listings')
			|| (Phpfox::getUserParam('advancedmarketplace.can_feature_listings'))
			}
			<!--div class="row_edit_bar_parent">
				<div class="row_edit_bar_holder">
					<ul>
						{template file='advancedmarketplace.block.menu'}
					</ul>
				</div>
				<div class="row_edit_bar">
					<a href="#" class="row_edit_bar_action"><span>{phrase var='advancedmarketplace.actions'}</span></a>
				</div>
			</div-->
			{/if}

			{if Phpfox::getUserParam('event.can_approve_events') || Phpfox::getUserParam('event.can_delete_other_event')}<a href="#{$aListing.listing_id}" class="moderate_link" rel="advancedmarketplace">Moderate</a>{/if*}
		</div>

		<div class="row_title_info">
			<div class="username_listing"><a href="#">{$aListing|user}</a></div>
			{if phpfox::getUserId() != $aListing.user_id && phpfox::getParam('advancedmarketplace.can_follow_listings')}
				{if $bFollow != 'follow'}
					<div id="js_follow_{$iFollower}" >
						<input onclick="$(this).addClass('disabled').attr('disabled','disabled'); follow('follow',{$aListing.user_id},{$iFollower}); return false;" type="button" class="button"  value="{phrase var='advancedmarketplace.follow'}" />
					</div>
				{else}
					<div id="js_follow_{$iFollower}" >
						<input onclick="$(this).addClass('disabled').attr('disabled','disabled');follow('unfollow',{$aListing.user_id},{$iFollower}); return false;" type="button" class="button" id="js_follow_{$iFollower}" value="{phrase var='advancedmarketplace.unfollow'}" />
					</div>
				{/if}
			{/if}
		</div>
    </div>
    <div class="owner_listing_info" style="padding-top: 10px;">
        <table>
			{if isset($aListing.tag_list)}
				<tr class="extra_info">
					<td class="" colspan="2">
						{module name='tag.item' sType=$sTagType sTags=$aListing.tag_list iItemId=$aListing.listing_id iUserId=$aListing.user_id}
					</td>
				</tr>
			{/if}
            <tr class="extra_info">
				<td class="" colspan="2">
					<span class="item_tag">{phrase var='advancedmarketplace.last_updated'}: </span>
					<span>
						{if isset($aListing.update_timestamp)}
							{$aListing.update_timestamp|date:'advancedmarketplace.advancedmarketplace_view_time_stamp'}
						{else}
							{$aListing.time_stamp|date:'advancedmarketplace.advancedmarketplace_view_time_stamp'}
						{/if}
					</span>
				</td>
            </tr>
			<tr class="extra_info">
				<td class="inline_list">
					<span class="item_tag">{phrase var='advancedmarketplace.category'}</span>: {$aListing.categories|category_display}
				</td>
			</tr>
        </table>
        <div>{$aListing.total_view} {phrase var='advancedmarketplace.view_s'}</div>
    </div>
</div>
