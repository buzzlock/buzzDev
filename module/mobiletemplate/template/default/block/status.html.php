<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright       [PHPFOX_COPYRIGHT]
 * @author          Raymond_Benc
 * @package         Phpfox
 * @version         $Id: index.html.php 4031 2012-03-20 15:08:25Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="mt_status_share">
	<form enctype="multipart/form-data" id="mt_js_activity_feed_form" action="#" method="post">
	<div class="ym-sub-header">
	    <table>
	        <tr>
	            <td class="ym-left-head">
	                <button class="btn btn-head" onclick="ynmtMobileTemplate.cancelStatusShareFromHomepage(); return false;">{phrase var='mobiletemplate.cancel'}</button>
	            </td>
	            <td class="ym-center-head">
	                <p>{phrase var='mobiletemplate.update_status'}</p>
	            </td>
	            <td class="ym-right-head">
	                <div class="ym-main-header-right" style="padding-top:0">
			        <input class="btn btn-head" type="submit" id="btnShareStatus" onclick="ynmtMobileTemplate.userUpdateStatus(); return false;" value="{phrase var='mobiletemplate.post'}">
			        <input class="btn btn-head" type="submit" id="btnSharePhoto" onclick="ynmtMobileTemplate.sharePhoto(); if(ynmtMobileTemplate.isChooseFileInPhoto === true) {l} return true; {r} else {l} return false; {r}" value="{phrase var='mobiletemplate.post'}">
	               </div>
	            </td>
	        </tr>
	    </table>
	    
	    
	</div>
	<div class="ym-sub-content">
	<div style="display: block;" class="activity_feed_form" id="mt_js_activity_feed_div">
	    
			<div>
				<input type="hidden" name="val[location][latlng]" id="mt_val_location_latlng" value="">
				<input type="hidden" name="val[location][name]" id="mt_val_location_name" value="">
				<input type="hidden" value="0" name="val[privacy]" id="selectedPrivacy">
				<span id="mt_activity_feed_link_form" style="display: none;">{url link='mobile.photo.frame'}</span>
				
				<input type="hidden" value="" name="val[callback_item_id]">
				<input type="hidden" value="" name="val[callback_module]">
				<input type="hidden" value="" name="val[parent_user_id]">
				<input type="hidden" value="" name="core[security_token]">
				<input type="hidden" value="" name="val[group_id]">
				<input type="hidden" value="upload_photo_via_share" name="val[action]">
				<input type="hidden" value="" name="val[iframe]">
				<input type="hidden" value="" name="val[method]">
				<input type="hidden" value="" name="val[connection][facebook]">
				<input type="hidden" value="" name="val[connection][twitter]">
			</div>
	        <div id="js_custom_privacy_input_holder"></div>
	        <div class="activity_feed_form_holder">
	            <div style="display:none;" id="mt_activity_feed_upload_error">
	                <div id="mt_activity_feed_upload_error_message" class="error_message"></div>
	            </div>
	
	            <div style="display: block;" id="global_attachment_status" class="global_attachment_holder_section">
	                <div id="mt_user_status">
	                    <textarea class="mentions-input" placeholder="{phrase var='core.what_s_on_your_mind'}" name="val[user_status]" rows="8" cols="60"></textarea>
	                </div>
	                <div id="mt_status_info" style="display: none;">
	                	<textarea class="mentions-input" placeholder="{phrase var='photo.say_something_about_this_photo'}" name="val[status_info]" rows="8" cols="60"></textarea>
	                </div>
	            </div>
					
	        </div>
	        <div id="mt_js_location_feedback"></div>
	        <div class="ym-input-photo" id="mt_global_attachment_photo_file_input">   
	               {phrase var='mobiletemplate.photo'} <input type="file" value="" id="sharePhotoInput" name="image[]" onchange="ynmtMobileTemplate.onChangeSharePhoto(); return false;">
	        </div>
	        
	        {if isset($fullControllerName) && $fullControllerName != 'pages.view' && $fullControllerName != 'event.view'}
	        <div class="activity_feed_form_button" style="display: block;">
	            <div>
	            	<div>
	            		<input type="hidden" value="non_custom" name="selectedPrivacyType" id="selectedPrivacyType">
	            	</div>
	            	<ul class="ym-form-list">
	            		{if Phpfox::getParam('feed.enable_check_in') && Phpfox::getParam('core.google_api_key') != '' }
	            	    <li>
	            	        <input class="icon-checkin-status" type="submit" id="btnCheckIn" onclick="ynmtMobileTemplate.clickCheckin(); return false;" value="">
	            	    </li>
	            	    {/if}
	            	    <li>
                            <input id="btnPhotoIcon" class="icon-photo-status" onclick="return false;" value="">
                        </li>
                        {if isset($fullControllerName) && $fullControllerName != 'event.view'}
	            	    <li class="ym-privacy">
	            	        <a href="#" id="ym-privacy-a" onclick="ynmtMobileTemplate.clickYmPrivacyA(); return false;"><i class="icon-public"></i></a>	            	        
	            	    </li>
	            	    {/if}
	            	</ul>
	            	</div>
	            	<div class="clear"></div>
	            	{if isset($fullControllerName) && $fullControllerName != 'event.view'}
	            	<div class="ym-privacy-content">
                        <ul id="privacyList" style="display: none;">
                            <li class="ym-privacy-head">{phrase var='mobiletemplate.audience'}</li>
                            {foreach from=$aPrivacyControls  name=privacy item=aPrivacy}
                                <li id="privacy-control-{$aPrivacy.value}" class="privacy-control" onclick="ynmtMobileTemplate.clickPrivacyControl('non_custom', '{$aPrivacy.value}');">
                                    {if $aPrivacy.value == 0}
                                        <i class="icon-public"></i>
                                    {elseif $aPrivacy.value == 1}
                                        <i class="icon-ff"></i>
                                    {elseif $aPrivacy.value == 2}
                                        <i class="icon-fof"></i>
                                    {elseif $aPrivacy.value == 3}
                                        <i class="icon-onlyme"></i>
                                    {elseif $aPrivacy.value == 4}
                                        <i class="icon-st-custom"></i>
                                    {/if}                                        
                                    {$aPrivacy.phrase}
                                 </li>
                            {/foreach}
                        </ul>
                    </div>
                    {/if}
	            </div>
	            {/if}
	
	        </div>
	        
	    <div class="mt_activity_feed_form_iframe"></div>
	</div>
	</div>
	</form>
</div>

{if isset($fullControllerName) && $fullControllerName != 'pages.view' && $fullControllerName != 'event.view'}
<div id="mt_check_in" style="display: none;">
	<div class="ym-sub-header">
	    <table>
	        <tr>
	            <td class="ym-left-head">
	                <button class="btn-head" onclick="ynmtMobileTemplate.cancelCheckinFromHomepage(); return false;">{phrase var='mobiletemplate.cancel'}</button>
	            </td>
	            <td class="ym-center-head">
	                <p>{phrase var='mobiletemplate.where_are_you'}</p>
	            </td>
	            <td class="ym-right-head"></td>
	        </tr>
	    </table>
	    
	    
	</div>
	<div class="ym-sub-content">
	    <div class="ym-place-search">
	        <table>
	            <tr>
	                <td class="ym-input-place">
	                    <input id="checkinTextSearch" name="checkinTextSearch" placeholder="{phrase var='core.mobile_search'}"/>
	                </td>
	                <td class="ym-submit-place">
	                    <button class="btn yn-button-sub" onclick="ynmtPlaces.displaySearching(); ynmtPlaces.aPlaces = []; ynmtPlaces.loadEstablishments('textSearchRequest'); return false;">{phrase var='mobiletemplate.search'}</button>
	                </td>
	            </tr>
	        </table>
	    </div>
	    <div id="mt_places_search_results" style="display: none;"></div>
	    <div id="mt_places_search_results_loading"> {img theme='ajax/add.gif'} {phrase var='mobiletemplate.searching'}</div>
	    <div id="mt_js_feed_check_in_map" style="display: none;" >
	    </div>
	</div>	
</div>
{/if}
