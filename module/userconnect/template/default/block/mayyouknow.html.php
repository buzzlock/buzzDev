<?php
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
?>

{literal}
<script type="text/javascript">
$.fn.ajaxCallTwo = function(sCall, sExtra, bNoForm, sType)
{	
    if (empty(sType))
    {
        sType = 'POST';
    };
    var sUrl = getParam('sJsAjax');
    if (typeof oParams['im_server'] != 'undefined' && sCall.indexOf('im.') > (-1))
    {
        sUrl = getParam('sJsAjax').replace(getParam('sJsHome'),getParam('im_server'));
    };	
    var sParams = '&' + getParam('sGlobalTokenName') + '[ajax]=true&' + getParam('sGlobalTokenName') + '[call]=' + sCall + '' + (bNoForm ? '' : this.getForm());
    if (sExtra)
    {
        sParams += '&' + ltrim(sExtra, '&');
    };

    if (!sParams.match(/\[security_token\]/i))
    {
        sParams += '&' + getParam('sGlobalTokenName') + '[security_token]=' + oCore['log.security_token'];
    };
	
    sParams += '&' + getParam('sGlobalTokenName') + '[is_admincp]=' + (oCore['core.is_admincp'] ? '1' : '0');
    sParams += '&' + getParam('sGlobalTokenName') + '[is_user_profile]=' + (oCore['profile.is_user_profile'] ? '1' : '0');
    sParams += '&' + getParam('sGlobalTokenName') + '[profile_user_id]=' + (oCore['profile.user_id'] ? oCore['profile.user_id'] : '0');	

    if (getParam('bJsIsMobile')){
        sParams += '&js_mobile_version=true';
    };
	
    oCacheAjaxRequest = $.ajax(
    {
        type: sType,
        url: sUrl,
        dataType: "script",	
        data: sParams,
        async:false			
    });
    return oCacheAjaxRequest;
};

$.ajaxCallTwo = function(sCall, sExtra, sType)
{
    return $.fn.ajaxCallTwo(sCall, sExtra, true, sType);
};

</script>

<style type="text/css">
    .frpr {
        display: block;
	margin-bottom: 0;
	margin-left: 3px;
	margin-right: 3px;
	margin-top: 3px;
	overflow-x: hidden;
	overflow-y: hidden;
	padding-bottom: 10px;
	padding-left: 0;
	padding-right: 0;
	padding-top: 10px;
	position: relative;
	}
    .frpr_k{
        display: block;
	margin-bottom: 0;
	margin-left: 3px;
	margin-right: 3px;
	margin-top: 3px;
	overflow-x: hidden;
	overflow-y: hidden;
	padding-bottom: 10px;
	padding-left: 0;
	padding-right: 0;
	padding-top: 10px;
	position: relative;
	border-top: 1px solid #ccc;
    }
    .cm_user
    {
        position: absolute;
	top:8px;
	left:4px;
    }
    .cm_user a img
    {
        width:50px;
	height:50px;
    }
    .comment_mini div.cm_info
    {
        min-height: 50px;
	width:350px;
    }
    .cm_info .extra_info
    {
        padding:5px 0px 0px 0px;
    }
    .cm_info .extra_info span
    {
        color:#484848;
    }
    .cm_info .extra_info span a
    {
        color: #3578A2;
    }

    div.cm_info{
        margin-left: 63px;
	margin-right:3px;
	color: #575757;
	min-height:50px;
    }
    #sidebar div.cm_info
    {
        margin-right:0px;
	margin-left: 65px;
    }
    .cm_info .extra_info .interact_time
    {
        color:#6F8CDC;
    }
</style>
{/literal}

{if $user_id == $profile_id}
    {assign var="iFlag" value=0}
    {foreach from=$aUser_YouKnow key=iKey value=User_YouKnow}
        {if $iKey < $iLimit}
            {assign var="iFlag" value=$iKey+1}
            <input id="you_know_flag" type="hidden" value="1" /> 
            <div id="show_user_you_know_{$iKey}" class="cm_info_visi">	
                <div id="js_item_youknow_{$User_YouKnow.user_id}" {if $user_id_YouKnow!=$User_YouKnow.user_id}class="frpr_k"{else}class="frpr"{/if}>
                    <div class="cm_user">
                        {img user=$User_YouKnow suffix='_50_square' max_width=50 max_height=50}
                    </div>
                    <div class="cm_info">
                        <div style="margin:4px">
                            <a href="{url link=''}{$User_YouKnow.user_name}/"><strong>{$User_YouKnow.full_name}</strong></a>
			</div>
			<div style="padding-top: 5px;">
                            <div id="link_send_request_{$iKey}"><a class="ajax_link" href="" onclick="$.ajaxCallTwo('userconnect.addRequest', 'user_id={$User_YouKnow.user_id}&key={$iKey}&flag={$iFlag}&check=' + $('#you_know_flag').val(),'GET'); return false;"  title="{phrase var='profile.add_to_friends'}">{img theme='misc/user_add.png' alt='' class='v_middle'} {phrase var='user.add_to_friends'}</a> </div>
                        </div>
                    </div>
                    <div class="clear"></div>    
		</div>
            </div>   
        {else}
            <div style="display: none" id="js_item_youknow_{$User_YouKnow.user_id}" class="frpr_k">
                <input id="key_hidden_{$User_YouKnow.user_id}" type="hidden" value={$iKey}/>
                    <div class="cm_user">
                        {img user=$User_YouKnow suffix='_50_square' max_width=50 max_height=50}
                    </div>
                    <div class="cm_info">
                        <div style="margin:4px">
                            <a href="{url link=''}{$User_YouKnow.user_name}/"><strong>{$User_YouKnow.full_name}</strong></a>
			</div>
                        <div style="padding-top: 5px;">
                            <div id="link_send_request_{$iKey}"><a class="ajax_link" href="" onclick="$.ajaxCallTwo('userconnect.addRequest', 'user_id={$User_YouKnow.user_id}&key={$iKey}&flag=' + $('#key_hidden_{$User_YouKnow.user_id}').val(),'GET'); return false;" title="{phrase var='profile.add_to_friends'}">{img theme='misc/user_add.png' alt='' class='v_middle'} {phrase var='user.add_to_friends'}</a> </div>
			</div>
                    </div>
                    <div class="clear"></div>    
            </div>
	{/if}
    {/foreach}
{/if}