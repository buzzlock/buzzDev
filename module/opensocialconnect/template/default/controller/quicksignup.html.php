<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style>
div#header_user_register_holder {
	display:none;
}
</style>
<script type="text/javascript">
$Behavior.termsAndPrivacy = function()
{
	$('#js_terms_of_use').click(function()
	{
		{/literal}
		tb_show('{phrase var='user.terms_of_use' phpfox_squote=true phpfox_squote=true phpfox_squote=true phpfox_squote=true phpfox_squote=true phpfox_squote=true}', $.ajaxBox('page.view', 'height=410&width=600&title=terms')); 
		{literal}
		return false;
	});
	
	$('#js_privacy_policy').click(function()
	{
		{/literal}
		tb_show('{phrase var='user.privacy_policy' phpfox_squote=true phpfox_squote=true phpfox_squote=true phpfox_squote=true phpfox_squote=true phpfox_squote=true}', $.ajaxBox('page.view', 'height=410&width=600&title=policy')); 
		{literal}
		return false;
	});
	$('#fwpasswordcheckbox').click(function(){
		console.log($(this));
		if($(this).checked || $(this).attr("checked") =="checked")
		{
			$('#manual_password').slideUp();
		}
		else
		{
			$('#manual_password').slideDown();
		}
	});
}
</script>
{/literal}
{if isset($step) && $step =='syncuser'}
   <form method="post" action="{url link='opensocialconnect.syncuser'}" id="js_form">
    <div id="main_registration_form">
        <h1>{phrase var='user.sign_up_for_ssitetitle' sSiteTitle=$sSiteTitle} {phrase var='opensocialconnect.by_using'} {$aService.title}</h1>
        <div class="extra_info">
            {phrase var='user.join_ssitetitle_to_connect_with_friends_share_photos_and_create_your_own_profile' sSiteTitle=$sSiteTitle}
        </div>
        <div id="main_registration_form_holder">
            <div class="main_break" style="margin-bottom:5px;">
                 {phrase var='opensocialconnect.this_email_already_exists_do_you_want_to_synchronize_with_this_account' email=$sEmail}
            </div>
            <div class="table_clear">
                <input type="hidden" value="{$aData.identity}" name="val[identity]"/>
                <input type="hidden" value="{$aData.service}" name="val[service]"/>    
                <input type="hidden" value="{$iSyncUserId}" name="val[user_id]"/>    
                <input type="hidden" value="{$sEmail}" name="val[email]"/>    
                <input type="submit" value="{phrase var='opensocialconnect.synchronize'}" class="button_register" id="js_registration_submit" name="synchronize"/>
                <input type="submit" value="{phrase var='core.no'}" class="button_register disable" id="js_registration_submit" name="cancel" />
            </div>
        </div>
    </div>
    </form> 
{else}
{$sCreateJs}
    <form method="post" action="{url link='opensocialconnect.quicksignup'}" id="js_form"{if isset($sGetJsForm)} onsubmit="{$sGetJsForm}"{/if} enctype="multipart/form-data">
    <div id="main_registration_form">
	    <h1>{phrase var='user.sign_up_for_ssitetitle' sSiteTitle=$sSiteTitle} {phrase var='opensocialconnect.by_using'} {$aService.title}</h1>
	    <div class="extra_info">
		    {phrase var='user.join_ssitetitle_to_connect_with_friends_share_photos_and_create_your_own_profile' sSiteTitle=$sSiteTitle}
	    </div>
	    <div id="main_registration_form_holder">
		    <div class="main_break">
			    {template file='opensocialconnect.block.registerform'}
		    </div>
		    <div class="table_clear">
			    <input type="hidden" value="{if isset($aData.gender)}{$aData.gender}{else}0{/if}" name="val[gender]"/>
			    <input type="hidden" value="{$aData.identity}" name="val[identity]"/>
			    <input type="hidden" value="{$aData.service}" name="val[service]"/>	
			    <input type="submit" value="{phrase var='user.sign_up'}" class="button_register" id="js_registration_submit" />
		    </div>
	    </div>
    </div>
    </form>
{/if}
