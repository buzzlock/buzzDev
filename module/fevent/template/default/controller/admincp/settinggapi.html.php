<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>
{literal}
<script type="text/javascript">
    function viewTutorial(ele)
    {
        if(ele)
        {
            if($(ele).html() == oTranslations['fevent.view'])
            {
                $('#'+ele.rel).slideDown();
                $(ele).html(oTranslations['fevent.hide']);
            }
            else
            {
                $('#'+ele.rel).slideUp();
                $(ele).html(oTranslations['fevent.view']);
            }
        }
    }
</script>
<style type="text/css">
    div.tip{
        margin-top: 0px;
    }
    div.tip a, div.tip_tutorial a
    {
        color: blue;
    }
    div.tip_tutorial
    {
        padding-bottom: 4px;
        border-bottom: 1px solid #CFCFCF;
    }
    div.tip_tutorial ul
    {
        counter-reset: li;
    }
    div.tip_tutorial ul li
    {
        list-style: decimal-leading-zero outside none;
        margin: 0 0 0 26px;
        padding: 4px 0;
        position: relative;
    }
    div.tip_tutorial ul li ul li
    {
        list-style: disc outside none;
        margin: 0 0 0 26px;
        padding: 4px 0;
        position: relative;
    }
</style>
{/literal}
{$sCreateJs}
<form method="post" action="{url link='admincp.fevent.settinggapi'}" id="js_form" onsubmit="{$sGetJsForm}">
    <div class="table_header">
    	{phrase var='fevent.google_api_details'}
    </div>
    <div class="tip">{phrase var='fevent.in_google_api_settings_you_must_change_redirect_uri_to'} <span style="color:blue;">{$sRedirectUri}</span></div>
    <div class="tip" id="tip_google"><a href="javascript:void(0)" onclick="viewTutorial(this);" rel="tip_google_tutorial">{phrase var='fevent.view'}</a> {phrase var='fevent.tutorial_how_to_register_google_api'}</div>
    <div id="tip_google_tutorial" class="tip_tutorial" style="display: none">
        <ul>
            <li>{phrase var='fevent.go_to_google_apis_console'}</li>
            <li>{phrase var='fevent.create_a_project'}</li>
            <li>{phrase var='fevent.active_calendar_api_service'}</li>
            <li>{phrase var='fevent.create_an_oauth_client_id' RedirectUri=$sRedirectUri CoreHost=$sCoreHost}</li>
            <li>{phrase var='fevent.get_google_api_detail'}</li>
        </ul>
    </div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='fevent.client_id'}:
		</div>
        <div class="table_right">
            <input type="text" id="oauth2_client_id" name="val[oauth2_client_id]" value="{value type='input' id='oauth2_client_id'}" size="60" />
        </div>
    </div>
    <div class="table">
        <div class="table_left">
			{required}{phrase var='fevent.client_secret'}:
		</div>
        <div class="table_right">
            <input type="text" id="oauth2_client_secret" name="val[oauth2_client_secret]" value="{value type='input' id='oauth2_client_secret'}" size="60" />
        </div>
    </div>
    <div class="table">
        <div class="table_left">
			{required}{phrase var='fevent.api_key'}:
		</div>
        <div class="table_right">
            <input type="text" id="developer_key" name="val[developer_key]" value="{value type='input' id='developer_key'}" size="60" />
        </div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='core.submit'}" class="button" />
	</div>
</form>