<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style type="text/css">
.socialpublishers_status
{
    margin-bottom:4px;
}
.socialpublishers_status img
{
    margin-right:4px;
}
.socialpublishers_content
{
padding:2px;
}
.socialpublishers_content img.avatar
{
    margin-right:4px;
}
fieldset.socialpublishers_content {
    border: 1px solid #D7D7D7;
    margin: 4px 6px 0 54px;
    padding: 3px;
}
fieldset.socialpublishers_content legend {
    border: 1px solid #D7D7D7;
    font-weight: bold;
    padding: 0.2em 0.5em;
    
}

.socialpublishers_share_providers
{
    margin:10px 0px 0px 32px;
}
.socialpublishers_provider_popup
{    
    margin-left:20px;
}
.socialpublishers_provider_img_popup
{
    margin-top:4px;
}
.socialpublishers_share_providers_checkbox
{
    line-height: 12px;
    padding-left: 35px;
    padding-top: 12px;
    vertical-align: middle;
}
.socialpublishers_share_providers_checkbox input[type="checkbox"]
{
    margin:0px;
}
.socialpublishers_button_control
{
    margin-top:10px;
    margin-left:48px;
}
.socialpublishers_button_control .optional
{
    float:left;
    margin-left: 5px;
}
textarea.socialpublishers_status_text
{
	width: 85%;
}
</style>
<script>
    function openauthsocialbridge(url,ele)
    {
        return openauthpublishers(url);
    }
</script>
{/literal}
<script type="text/javascript">$Core.loadStaticFile("{jscript file='socialbridge.js' module='socialbridge'}");</script>
<script>$Core.loadStaticFile(oParams['sJsHome']+'module/socialpublishers/static/jscript/socialpublishers.js');</script>
<form id="socialpublishers_form_share" name="socialpublishers_form_share">
<div class="socialpublishers_status">
    {if isset($aUser.user_name) && !empty($aUser.user_name)}
        {img user=$aUser suffix='_50_square' max_width=50 max_height=50 align='left'}
    {else}
        {img user=$aUser suffix='_50_square' max_width=50 max_height=50 href='' align='left'}
    {/if}
    <textarea cols="41" rows="2" name="val[status]" class="socialpublishers_status_text">{$sPostMessage}</textarea>
</div>
<div class="clear"></div>
<fieldset class="socialpublishers_content">
    <legend>{phrase var='socialpublishers.share_content'}</legend>
    <div class="socialpublishers_content">
    {if isset($aParams.img)}
    <img src="{$aParams.img}" alt="" align="left" class="avatar" width="50px" heigth="50px"/>
    {/if}
    <span>
       {if isset($aParams.url)}<a href="{$aParams.url}">{$aParams.url|shorten:60:'...'}</a><br/>{/if}
       {if isset($aParams.content)}{$aParams.content}{/if}
    </span>
    <input type="hidden" value="{if isset($aParams.img)}{$aParams.img}{/if}" name="val[img]" id="socialpublishers_form_share_img"/>
    <input type="hidden" value="{if isset($aParams.content)}{$aParams.content|clean|shorten:150}{/if}" name="val[content]" id="socialpublishers_form_share_content"/>
    <input type="hidden" value="{if isset($aParams.url)}{$aParams.url}{/if}" name="val[url]" id="socialpublishers_form_share_url"/>
    <input type="hidden" value="{if isset($aParams.type)}{$aParams.type}{/if}" name="val[type]" id="socialpublishers_form_share_type"/>
</div>
</fieldset>
<div class="clear"></div>

{if count($aPublisherProviders)}
<ul class="socialpublishers_share_providers">
    {foreach from=$aPublisherProviders index=iKey name=apu item=aPublisherProvider}
        <li class="socialpublishers_provider_popup" style="background:url({$sCoreUrl}module/socialpublishers/static/image/{$aPublisherProvider.name}.png) no-repeat scroll left center;height:40px;">
            
                {if $aPublisherProvider.connected }
                   <div class="socialpublishers_share_providers_checkbox">
                   <input type="checkbox" {if (isset($aPublisherProvider.is_checked) && $aPublisherProvider.is_checked == 1)|| !isset($aPublisherProvider.is_checked)} checked="checked"{/if} value="{$aPublisherProvider.name}" name="val[provider][{$aPublisherProvider.name}]"/>
                   <span id="showpopup_span_connected_{$aPublisherProvider.name}">{phrase var='socialpublishers.connected_as' full_name=''} {$aPublisherProvider.profile.full_name|clean|shorten:18...}</span>
                   </div>
                {else}    
                   <div class="socialpublishers_share_providers_checkbox">
                   <input type="checkbox" value="{$aPublisherProvider.name}" name="val[provider][{$aPublisherProvider.name}]" onclick="openauthsocialbridge('{url link='socialpublishers.sync' service=$aPublisherProvider.name redirect=0}',this);" id="showpopup_checkbox_connected_{$aPublisherProvider.name}"/>
                   <span id="showpopup_span_connected_{$aPublisherProvider.name}">{phrase var='socialpublishers.not_connected'} (<a href="javascript:void(0);" onclick="openauthsocialbridge('{url link='socialpublishers.sync' service=$aPublisherProvider.name redirect=0}',this);">{phrase var='socialpublishers.connect'}</a>)</span>
                   </div>
                {/if}
            
        </li>
    {/foreach}
</ul>
{/if}

<div class="clear"></div>
<div class="socialpublishers_button_control">
    <span id="socialpublishers_button_control_span">
    <input type="button" class="button optional" name="socialpublishersshare" value="{phrase var='socialpublishers.publish'}" onclick="return submitf(this);"/>
    <input type="button" class="button button_off optional" name="socialpublisherscancel" value="{phrase var='core.cancel'}" onclick ="return cancelf(this);"/>
    </span>
    <span class="optional"  style="padding-top:4px;"><input type="checkbox" name="val[no_ask]" value="1">{phrase var='socialpublishers.don_t_ask_me_again'}</span>
</div>
<div class="clear"></div>
</form>
{literal}
<script>

    var span = $('.js_box_close span.js_box_history');    
    $('.js_box_close').html(span);    
    $('.js_box_close').css("diplay","none");    
    $('.js_box_close').css("height","0px");    
    function submitf(f)
    {
        $('#socialpublishers_button_control_span').html($.ajaxProcess('no_message'));
        $('#socialpublishers_button_control_span').css("float","left");
        $('#socialpublishers_button_control_span').css("margin-right","5px");
        $('#socialpublishers_form_share').ajaxCall('socialpublishers.publish');
        return true;
    }
    function cancelf(f)
    {
    	$('#socialpublishers_button_control_span').html($.ajaxProcess('no_message'));
    	$('#socialpublishers_button_control_span').css("float","left");
        $('#socialpublishers_button_control_span').css("margin-right","5px");
    	$('#socialpublishers_form_share').ajaxCall('socialpublishers.cancelpublish');
        js_box_remove($(f).parent());
        return true;
    }
</script>
{/literal}
{unset var1=$aUser var2=$aPublisherProviders var3=$sParams}