<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright      YouNet Company
 * @author         LyTK
 * @package        Module_ProfilePopup
 * @version        3.01
 */
defined('PHPFOX') or exit('NO DICE!');
?>

<div class="uiContextualDialogContent">
        <div class="yn_profilepopup_hovercard_stage" {if !isset($aCoverPhoto)} style="padding-top: 10px;" {/if}>
                <!-- event not found -->
                {if isset($iIsEvent) && $iIsEvent == 0}
                <div class="yn_profilepopup_hovercard_content">
                        <div>
                                <div class="yn_profilepopup_info yn_profilepopup_info_left">
                                        {phrase var='profilepopup.event_not_found'}.
                                </div>
                        </div>
                </div>
                {/if}
                
                <!-- event is private -->
                {if isset($iIsCanView) && $iIsCanView == 0}
                <div class="yn_profilepopup_hovercard_content">
                        {if Phpfox::getParam('profilepopup.enable_thumbnails')}
                        <div class="yn_profilepopup_image">
                                <a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}">{img server_id=$aEvent.server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='_120' max_width='100' max_height='100'}</a>
                        </div>
                        {/if}
                        <div class="yn_profilepopup_main" {if !Phpfox::getParam('profilepopup.enable_thumbnails')}style="margin-left: 0px;"{/if}>
                                <div class="yn_profilepopup_main_title"> <a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}" class="link">{$aEvent.title|clean|split:25}</a></div>
                                <div>
                                        <div class="yn_profilepopup_info yn_profilepopup_info_left">
                                                {phrase var='privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'}
                                        </div>
                                </div>
                        </div>
                </div>
                {/if}
                
                <!-- show profile -->
                {if isset($iIsEvent) && $iIsEvent == 1 && isset($iIsCanView) && $iIsCanView == 1}
                <div class="yn_profilepopup_hovercard_content">
                        {if Phpfox::getParam('profilepopup.enable_thumbnails')}
                        <div class="yn_profilepopup_image">
                                <a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}">{img server_id=$aEvent.server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='_120' max_width='100' max_height='100'}</a>
                        </div>
                        {/if}
                        <div class="yn_profilepopup_main" {if !Phpfox::getParam('profilepopup.enable_thumbnails')}style="margin-left: 0px;"{/if}>
                                <div class="yn_profilepopup_main_title"> <a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}" class="link">{$aEvent.title|clean|split:25}</a></div>
                                <div>
                                        {if count($aAllItems) > 0}
                                        {foreach from=$aAllItems key=iKey item=aItem}
                                        <!-- category -->
                                        {if $aItem.name == 'categories' && intval($aItem.is_active) == 1 && intval($aItem.is_display) == 1 && is_array($aEvent.categories) && count($aEvent.categories)}
                                        <div class="yn_profilepopup_info">
                                                <div class="yn_profilepopup_info_left">{$aItem.lang_name}:&nbsp;</div>
                                                <div class="yn_profilepopup_info_right">{$aEvent.categories[0][0]|convert|clean}&nbsp;</div>
                                        </div>
                                        {/if}
                                        <!-- Time -->
                                        {if $aItem.name == 'event_date' && intval($aItem.is_active) == 1 && intval($aItem.is_display) == 1 && isset($aEvent.event_date) && strlen(trim($aEvent.event_date)) > 0}
                                        <div class="yn_profilepopup_info">
                                                <div class="yn_profilepopup_info_left">{$aItem.lang_name}:&nbsp;</div>
                                                <div class="yn_profilepopup_info_right">{$aEvent.event_date}{if strlen(trim($content_repeat)) > 0}<br />{phrase var='fevent.repeat'}: {$content_repeat}{/if}&nbsp;</div>
                                        </div>
                                        {/if}
                                        <!-- Location -->
                                        {if $aItem.name == 'location' && intval($aItem.is_active) == 1 && intval($aItem.is_display) == 1 && isset($aEvent.location) && strlen(trim($aEvent.location)) > 0}
                                        <div class="yn_profilepopup_info">
                                                <div class="yn_profilepopup_info_left">{$aItem.lang_name}:&nbsp;</div>
                                                <div class="yn_profilepopup_info_right">{$aEvent.location}{if !empty($aEvent.address)}<br />{$aEvent.address|clean}{/if}{if !empty($aEvent.city)}<br />{$aEvent.city|clean}{/if}{if !empty($aEvent.postal_code)}<br />{$aEvent.postal_code|clean}{/if}{if !empty($aEvent.country_child_id)}<br />{$aEvent.country_child_id|location_child}{/if}{if !empty($aEvent.country_iso)}<br />{$aEvent.country_iso|location}{/if}&nbsp;</div>
                                        </div>
                                        {/if}
                                        <!-- Total of Members -->
                                        {if $aItem.name == 'total_of_members' && intval($aItem.is_active) == 1 && intval($aItem.is_display) == 1 && isset($iTotalOfMember) && $iTotalOfMember > 0}
                                        <div class="yn_profilepopup_info">
                                                <div class="yn_profilepopup_info_left">{$aItem.lang_name}:&nbsp;</div>
                                                <div class="yn_profilepopup_info_right">{$iTotalOfMember}&nbsp;</div>
                                        </div>
                                        {/if}
                                        {/foreach}
                                        {/if}
                                </div>
                                {if isset($sShowJoinedFriend) && $sShowJoinedFriend == '1' && $iJoinedFriendTotal > 0}
                                <div class="yn_profilepopup_mutual">
                                        <a href="#" onclick="$Core.box('profilepopup.getJoinedFriends', 300, '&item_type=fevent&item_id={$aEvent.event_id}');return false;">{phrase var='profilepopup.joined_friends_total' total=$iJoinedFriendTotal}</a>                                        
                                        <div class="yn_profilepopup_block_listing_inline">
                                                <ul>
                                                        {foreach from=$aJoinedFriend key=iKey item=aMutual}
                                                        <li>{img user=$aMutual suffix='_50_square' max_width=32 max_height=32 class='js_hover_title'}</li>
                                                        {/foreach}
                                                </ul>
                                        </div>
                                </div>
                                {/if}
                        </div>
                </div>
                {/if}
                
        </div>
        <div class="yn_profilepopup_hovercard_footer">
                {if Phpfox::isUser() &&  isset($iIsEvent) && $iIsEvent == 1 && isset($iIsCanView) && $iIsCanView == 1}
                <ul class="yn_profilepopup_list_horizontal">
                        {if Phpfox::isModule('foxfavorite') && Phpfox::isUser() && isset($sFFModule) && isset($iFFItemId) && $sFFModule == 'fevent'}
                                {if !$bFFIsAlreadyFavorite}
                                        <li class="yn_profilepopup_list_item">
                                                <a title="{phrase var='profilepopup.favorite'}" onclick="ynfbpp.closePopup(); $('#js_favorite_link_unlike_{$iFFItemId}').show(); $('#js_favorite_link_like_{$iFFItemId}').hide(); $.ajaxCall('foxfavorite.addFavorite', 'type={$sFFModule}&amp;id={$iFFItemId}', 'GET'); {if $bEnableCachePopup}window.setTimeout('ynfbpp.refreshPage(null)', 500);{/if} return false;" class="buttonlink yn_profilepopup_icon_favorite" href="#" >{phrase var='profilepopup.favorite'}</a>			
                                        </li>
                                {else}
                                        <li class="yn_profilepopup_list_item">
                                                <a title="{phrase var='profilepopup.unfavorite'}" onclick="ynfbpp.closePopup(); $('#js_favorite_link_like_{$iFFItemId}').show(); $('#js_favorite_link_unlike_{$iFFItemId}').hide(); $.ajaxCall('foxfavorite.deleteFavorite', 'type={$sFFModule}&amp;id={$iFFItemId}', 'GET'); {if $bEnableCachePopup}window.setTimeout('ynfbpp.refreshPage(null)', 500);{/if} return false;" class="buttonlink yn_profilepopup_icon_unfavorite" href="#" >{phrase var='profilepopup.unfavorite'}</a>			
                                        </li>
                                {/if}
                        {/if}
                        
                        {if $aEvent.view_id == 0 && ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event')}
                                <li class="yn_profilepopup_list_item" >
                                        <a title="{phrase var='profilepopup.event_invite'}" class="buttonlink yn_profilepopup_icon_invite" href="{url link='fevent.add.invite' id=$aEvent.event_id}">{phrase var='profilepopup.event_invite'}</a>			
                                </li>                        
                        {/if}		
                        
                        {if !isset($aEvent.rsvp_id) || $aEvent.rsvp_id != 1}
                                <li class="yn_profilepopup_list_item" >
                                        <a title="{phrase var='profilepopup.event_join'}" onclick="ynfbpp.closePopup(); $.ajaxCall('profilepopup.joinFEvent', 'id={$aEvent.event_id}&amp;rsvp=1'); return false;" class="buttonlink yn_profilepopup_icon_join" href="#">{phrase var='profilepopup.event_join'}</a>			
                                </li>                        
                        {else}
                                <li class="yn_profilepopup_list_item" >
                                        <a title="{phrase var='profilepopup.event_leave'}" onclick="ynfbpp.closePopup(); ynfbpp.leaveFEvent({$aEvent.event_id}, '{phrase var='profilepopup.event_confirm_leave'}'); return false;" class="buttonlink yn_profilepopup_icon_leave" href="#">{phrase var='profilepopup.event_leave'}</a>			
                                </li>                        
                        {/if}
                </ul>
                {/if}
                <div class="clearfix"></div>
                <div class="clearfix"></div>                
        </div>
</div>
