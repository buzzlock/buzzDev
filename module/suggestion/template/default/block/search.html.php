<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Friend
 * @version 		$Id: search.html.php 2860 2011-08-20 19:17:52Z Raymond_Benc $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
{if !$bSearch}
<script type="text/javascript">
    
    var sPrivacyInputName = '{$sPrivacyInputName}';
    var sSearchByValue = '';
    {if $bDisabled}
    {literal}
    /*check if has no friends in list, disabled function related*/
    
    function disabledItem(){
        
        /*disabled select all check box*/
        $('#selectAll').attr('disabled','disabled');
        /*disabled messages*/
        $('#messages').attr('disabled','disabled');
        
        /*disabled button confirm*/
        $('#btnConfirm').attr('disabled','disabled');
        $('#btnConfirm').attr('class','button');        
        $('#btnConfirm').css('background','0');        
        $('#btnConfirm').css('background-color','#CCCCCC');        
        $('#btnConfirm').css('color','#999999');        
        $('#btnConfirm').css('border','1px solid #666666');                
    }
    disabledItem();
    {/literal}
    {/if}
    {literal}
    
    
    $Behavior.searchFriendBlock = function()
    {            
        sSearchByValue = $('.js_is_enter').val();		

        if ($.browser.mozilla) 
        {
            $('.js_is_enter').keypress(checkForEnter);
        } 
        else 
        {
            $('.js_is_enter').keydown(checkForEnter);
        }		
    };

    
    function resetAllSelect(){
        $('.js_cached_friend_name').each(function(){
            $(this).remove();            
        });
        $('#totalChecked').html('0');
        updateCheckBoxes();
    }
    
    updateCheckBoxes();
    $('#totalChecked').html('0');
    function updateFriendsList()
    {		
        updateCheckBoxes();     
                
    }
	 
    function countTotalCheckedFriends(){
        var _iTotalChecked=$('#totalChecked').html();
        
        $('input[id^="js_friends_checkbox"]:checked').each(function(){
            _iTotalChecked++;
        });
        $('#totalChecked').html(_iTotalChecked);
        return parseInt(_iTotalChecked);
    }       
        
        /*
         * increase total checked friend to selected
         */
    function addCheckedFriends(){
        var _iTotalChecked=$('#totalChecked').html();
        _iTotalChecked++;        
        $('#totalChecked').html(_iTotalChecked);        
    }       
        
        /*
         * decrease total checked friend to selected
         */
    function removeCheckedFriends(){
        var _iTotalChecked= $('#totalChecked').html();
        if (_iTotalChecked > 0)
            _iTotalChecked--;        
        $('#totalChecked').html(_iTotalChecked);        
    }       
        
    function removeFromSelectList(sId)
    {
        $('.js_cached_friend_id_' + sId + '').remove();
        $('#js_friends_checkbox_' + sId).attr('checked', false);
        $('#js_friend_input_' + sId).remove();
        $('.js_cached_friend_id_' + sId).remove(); return false;		
		
        return false;
    }
	
    function addFriendToSelectList(oObject, sId)
    {		
        if (oObject.checked)
        {
            iCnt = 0;
            $('.js_cached_friend_name').each(function()
            {			
                iCnt++;
                                
            });			

            if (function_exists('plugin_addFriendToSelectList'))
            {
                plugin_addFriendToSelectList(sId);
            }
            {/literal}
            $('#js_selected_friends').append('<div class="js_cached_friend_name row1 js_cached_friend_id_' + sId + '' + (iCnt ? '' : ' row_first') + '"><span style="display:none;">' + sId + '</span><input type="hidden" name="val[' + sPrivacyInputName + '][]" value="' + sId + '" /><a href="#" onclick="return removeFromSelectList(' + sId + ');">{img theme='misc/delete.gif' class="delete_hover v_middle"}</a> ' + $('#js_friend_' + sId + '').html() + '</div>');			
            {literal}
            addCheckedFriends();
        }
        else
        {
            if (function_exists('plugin_removeFriendToSelectList'))
            {
                plugin_removeFriendToSelectList(sId);
            }			
			
            $('.js_cached_friend_id_' + sId).remove();
            $('#js_friend_input_' + sId).remove();
            removeCheckedFriends();
        }
        
    }
        
    function cancelFriendSelection()
    {
        if (function_exists('plugin_cancelFriendSelection'))
        {
            plugin_cancelFriendSelection();
        }			
		
        $('#js_selected_friends').html('');	
        $Core.loadInit(); 
        tb_remove();
    }
	
    function updateCheckBoxes()
    {
        iCnt = 0;
        $('.js_cached_friend_name').each(function()
        {			
            iCnt++;
            $('#js_friends_checkbox_' + $(this).find('span').html()).attr('checked', true);
        });
        $('#totalChecked').html(iCnt);
                
                
        $('#js_selected_count').html((iCnt / 2));
                
        iTotal = 0;
        var _aUserId = new Array();
        $('span[id^="js_friend_"]').each(function(){        
            var _id = $(this).attr('id').split("_");
            if (_id.length>0){
                _iUserId = _id[2];                                    
                _aUserId[iTotal++] = _iUserId;
            }
        });
       
      $.ajaxCall('suggestion.append_user_image','sUserId='+_aUserId.join(","));
           
    }
	
    function showLoader()
    {                                
        $('#js_friend_search_content').html($.ajaxProcess(oTranslations['friend.loading'], 'large'));
    }	
	
    function checkForEnter(event)
    {
        if (event.keyCode == 13) 
        {
            showLoader(); 
			
            $.ajaxCall('suggestion.searchAjax', 'find=' + $('#js_find_friend').val() + '&amp;input=' + sPrivacyInputName + '');
		
            return false;	
        }
    }
        
    $('#js_find_friend').click(function(){
		
		var abc="{/literal}<?php echo Phpfox::getPhrase('suggestion.search_by_email_full_name'); ?>{literal}";
		
        if ($(this).val() == abc)
            $(this).val('');
            $(this).addClass('bold');
    });
    $('#js_find_friend').blur(function(){
	var def="{/literal}<?php echo Phpfox::getPhrase('suggestion.search_by_email_full_name'); ?>{literal}";
        if ($(this).val() == ''){                    
            $(this).val(def);
            $(this).removeClass('bold');
        }
    });
    
    $('#js_find_friend').keypress(function(e){      
        if(e.which == 13){            
            $('#btnFind').click();
        }
    });
    
    function selectAllFriends(obj){
        _sId = obj.getAttribute('id');
        
        if ($('#'+_sId).attr('checked')=='checked'){//current not checked all               
            $('#js_friend_search_content').find('input[id^="js_friends_checkbox"]').each(function(){                
                
                sId = $(this).val();
                
                if($(this).attr('checked')!='checked'){                    
                    iCnt = 0;
                    $('.js_cached_friend_name').each(function()
                    {			
                        iCnt++; 
                    });

                    if (function_exists('plugin_addFriendToSelectList'))
                    {
                        plugin_addFriendToSelectList(sId);
                    }
                    {/literal}
                    $('#js_selected_friends').append('<div class="js_cached_friend_name row1 js_cached_friend_id_' + sId + '' + (iCnt ? '' : ' row_first') + '"><span style="display:none;">' + sId + '</span><input type="hidden" name="val[' + sPrivacyInputName + '][]" value="' + sId + '" /><a href="#" onclick="return removeFromSelectList(' + sId + ');">{img theme='misc/delete.gif' class="delete_hover v_middle"}</a> ' + $('#js_friend_' + sId + '').html() + '</div>');			
                    {literal}                    
                    addCheckedFriends();
                    $(this).attr('checked','checked');
                }   
                
            });      
        }else{ //current checked all
            $('#js_friend_search_content').find('input[id^="js_friends_checkbox"]').each(function(){
                sId = $(this).val();
                if($(this).attr('checked')=='checked'){
                    removeCheckedFriends();
                    if (function_exists('plugin_removeFriendToSelectList'))
                    {
                        plugin_removeFriendToSelectList(sId);                        
                    }	
                    $('.js_cached_friend_id_' + sId).remove();
                    $('#js_friend_input_' + sId).remove();
                    $(this).attr('checked',null);                    
                }
            });
        }
    }        
    {/literal}
</script>
<div id="searchBlock">
    <div id="js_friend_loader_info"></div>
    <div id="iUserId" style="display:none">{$iFriendId}</div>
    <div id="js_friend_loader">
        {if $sFriendType != 'mail'}
        <div class="p_4">
            <div class="go_left">
                {phrase var='friend.view'}:&nbsp;<select name="view" onchange="showLoader(); if ($('#js_find_friend').val()=='<?php echo Phpfox::getPhrase('suggestion.search_by_email_full_name'); ?>') $(this).ajaxCall('suggestion.searchAjax', 'input={$sPrivacyInputName}'); else $(this).ajaxCall('suggestion.searchAjax', 'input={$sPrivacyInputName}&find='+$('#js_find_friend').val()); return false;">
                    <option value="all">{phrase var='friend.all_friends'}</option>
                    <option value="online"{if $sView == 'online'} selected="selected"{/if}>{phrase var='friend.online_friends'}</option>
                        {if count($aLists)}
                    <optgroup label="{phrase var='friend.friends_list'}">
                        {foreach from=$aLists item=aList}
                        <option value="{$aList.list_id}"{if $sView == $aList.list_id} selected="selected"{/if}>{$aList.name|clean|split:30}</option>
                        {/foreach}
                    </optgroup>
                    {/if}
                </select>
            </div>
            <div class="t_right">
                <input type="text" class="js_is_enter v_middle default_value" name="find" value="{phrase var='suggestion.search_by_email_full_name'}" onfocus="if (this.value == sSearchByValue){literal}{{/literal}this.value = ''; $(this).removeClass('default_value');{literal}}{/literal}" onblur="if (this.value == ''){literal}{{/literal}this.value = sSearchByValue; $(this).addClass('default_value');{literal}}{/literal}" id="js_find_friend" size="30" />
                <input type="button" value="{phrase var='friend.find'}" onclick="showLoader(); $.ajaxCall('suggestion.searchAjax', 'friend_module_id={$sFriendModuleId}&amp;friend_item_id={$sFriendItemId}&amp;find=' + $('#js_find_friend').val() + '&amp;input={$sPrivacyInputName}'); return false;" class="button v_middle" id="btnFind"/>
            </div>
            <div class="clear"></div>
        </div>

        <div class="main_break"></div>
        <div class="separate"></div>

        {else}	
        <input type="text" class="js_is_enter v_middle default_value" name="find" value="{phrase var='suggestion.search_by_email_full_name'}" onfocus="if (this.value == sSearchByValue){literal}{{/literal}this.value = ''; $(this).removeClass('default_value');{literal}}{/literal}" onblur="if (this.value == ''){literal}{{/literal}this.value = sSearchByValue; $(this).addClass('default_value');{literal}}{/literal}" id="js_find_friend" size="30" />
        <input type="button" value="{phrase var='friend.find'}" onclick="showLoader(); $.ajaxCall('suggestion.searchAjax', 'friend_module_id={$sFriendModuleId}&amp;friend_item_id={$sFriendItemId}&amp;find=' + $('#js_find_friend').val() + '&amp;input={$sPrivacyInputName}&amp;type={$sFriendType}'); return false;" class="button v_middle" />	

        <div class="main_break"></div>
        <div class="separate"></div>
        {/if}

        <div class="t_center">
            {foreach from=$aLetters item=sLetter}<span style="padding-right:5px;"><a href="#" onclick="showLoader(); $.ajaxCall('suggestion.searchAjax', 'letter={$sLetter}&amp;input={$sPrivacyInputName}&amp;type={$sFriendType}'); return false;"{if $sActualLetter == $sLetter} style="text-decoration:underline;"{/if}>{$sLetter}</a></span>{/foreach}
        </div>
        <div class="main_break"></div>

        <div class="separate"></div>


        {/if}
        <div id="js_friend_search_content">
            {pager}
            <div class="main_break"></div>
            <div class="label_flow" style="height:180px;">

                {foreach from=$aFriends name=friend item=aFriend}
                <div style='width:120px; height:55px; border:0; float:left; padding:0; margin:0; position: relative; padding: 3px;' class="{if is_int($phpfox.iteration.friend/2)}row1{else}row2{/if}{if $phpfox.iteration.friend == 1} row_first{/if}{if isset($aFriend.is_active)} row_moderate{/if}">

                    {if !isset($aFriend.is_active)}
                    <span class="friend_checkbox"><input type="checkbox" class="checkbox" name="friend[]" class="js_friends_checkbox" id="js_friends_checkbox_{$aFriend.user_id}" value="{$aFriend.user_id}" {if isset($aFriend.canMessageUser) && $aFriend.canMessageUser == false}DISABLED {else} onclick="addFriendToSelectList(this, '{$aFriend.user_id}');"{/if} style="vertical-align:middle;" /></span>
                    {/if}
                    <span id="js_friend_{$aFriend.user_id}"><span class="friend_name">{$aFriend|user}{if isset($aFriend.is_active)} <em>({$aFriend.is_active})</em>{/if}{if isset($aFriend.canMessageUser) && $aFriend.canMessageUser == false} {phrase var='friend.cannot_select_this_user'}{/if}</span></span>
                </div>
                {foreachelse}
                <div class="extra_info">
                    {if $sFriendType == 'mail'}
                    {phrase var='user.sorry_no_members_found'}
                    {else}
                    {phrase var='friend.sorry_no_friends_were_found'}
                    {/if}
                </div>
                {/foreach}

            </div>
            <p><input type="checkbox" value="" name="selectAll" id="selectAll" onclick="selectAllFriends(document.getElementById('selectAll'));" /> {phrase var='suggestion.select_all'}
                <span id="total" style="padding-left:400px">{phrase var='suggestion.selected';} (<span id="totalChecked">0</span>)</span></p>
        </div>

        {if !$bSearch}
        {if $bIsForShare}

        {else}
        {if $sPrivacyInputName != 'invite'}
        <div class="main_break t_right">		
            <input type="button" name="submit" value="{phrase var='friend.use_selected'}" onclick="{literal}if (function_exists('plugin_selectSearchFriends')) { plugin_selectSearchFriends(); } else { $Core.loadInit(); tb_remove(); }{/literal}" class="button" />&nbsp;<input type="button" name="cancel" value="{phrase var='friend.cancel'}" onclick="{literal}if (function_exists('plugin_cancelSearchFriends')) { plugin_cancelSearchFriends(); } else { cancelFriendSelection(); }{/literal}" class="button" />
        </div>
        {/if}
        {/if}
    </div>
</div>
{/if}
{literal}
<style>
    .friend_checkbox{position: absolute; left:55px; bottom:5px;}
    .friend_name{position: absolute; left:57px; top:4px; height:30px; overflow: hidden; line-height: 1.3em;}
    .bold{color:#000000 ! important;}
</style>    
{/literal}