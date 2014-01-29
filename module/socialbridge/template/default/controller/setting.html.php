<?php
defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<style type="text/css">
    .socialbridge_provider_img {
        width: 248px;
        border: 1px solid #CCCCCC;
        height: 90px;
    }
    .socialbridge_provider{
        float: left;
        margin: 0 30px;
        position: relative;
    }
    .socialbridge_provider .text {
        border: 1px solid #CCCCCC;
        display: block;
        padding: 3px;
        width: 242px;
        text-align: center;
        overflow: hidden;
    }
</style>
{/literal}
{literal}
<script type="text/javascript">
	function confirmDisconnect(providername,link){
		if(confirm("{/literal}{phrase var='socialbridge.are_you_sure_you_want_to_disconnect_this_account'}{literal}"))
		{
			window.location = link;
		}
	}
</script>
{/literal}
{if count($aProviders)}
<div id="privacy_holder_table" class="p_4">
    <div align="center" class="page_section_menu_holder" id="js_setting_block_connections" style="display:none">
        {foreach from=$aProviders index=iKey name=Provider item=aProvider}
        {if isset($aProvider)}
        <div class="socialbridge_provider">
            <a href="{if isset($aProvider.Agent)}{url link='socialbridge.setting'}{else}javascript:void(openauthsocialbridge('{url link='socialbridge.sync' service=$aProvider.name status='connect' redirect=1}'));{/if}">
                <img src="{$sCoreUrl}module/socialbridge/static/image/{$aProvider.service}.jpg" alt="{$aProvider.name}" class="socialbridge_provider_img"/>
            </a>
            <div class="text">
                {if isset($aProvider.connected) and $aProvider.connected }
                <div class="socialbridge_connect_link" id="socialbridge_connect_link_{$aProvider.name}">
                    {if isset($aProvider.profile.img_url)}<img src="{$aProvider.profile.img_url}" alt="{$aProvider.profile.full_name}" align="left" height="32"/>{/if}
                    {phrase var='socialbridge.connected_as' full_name=''} {$aProvider.profile.full_name|clean|shorten:18...}<br/>
                    <a href="#" onclick="return confirmDisconnect('{$aProvider.service}','{url link='socialbridge.setting' disconnect=$aProvider.service}');">{phrase var='socialbridge.click_here'}</a> {phrase var='socialbridge.to'} {phrase var='socialbridge.disconnect'}.
                </div>
                {else}
                <div class="socialbridge_connect_link" id="socialbridge_connect_link_{$aProvider.name}">
                    <a href="javascript:void(openauthsocialbridge('{url link='socialbridge.sync' service=$aProvider.service status='connect' redirect=1}'));">{phrase var='socialbridge.click_here'}</a> {phrase var='socialbridge.to'} {phrase var='socialbridge.connect'}.
                </div>
                {/if}
            </div>
        </div>
        {if is_int($phpfox.iteration.Provider/3) || Phpfox::isMobile()}
        <div class="clear"></div>
        {/if}
        {/if}
        {/foreach}
    </div>
    {plugin call='socialbridge.template_controller_setting'}
</div>
{if !empty($sTab)}
{literal}
<script type="text/javascript">
    $Behavior.pageSectionMenuRequest = function() {
        $Core.pageSectionMenuShow('#js_setting_block_{/literal}{$sTab}{literal}');
    }
</script>
{/literal}
{/if}
{else}
<div class="pulic_message">{phrase var='socialbridge.there_are_no_social_providers_were_enable_please_contact_to_admin_site_to_get_more_information'}</div>
{/if}