<?php
defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script type="text/javascript">
    function viewTutorial(ele)
    {
        if(ele)
        {
            if($(ele).html() == oTranslations['socialbridge.view'])
            {
                $('#'+ele.rel).slideDown();
                $(ele).html(oTranslations['socialbridge.hide']);
            }
            else
            {
                $('#'+ele.rel).slideUp();
                $(ele).html(oTranslations['socialbridge.view']);
            }

        }

    }
</script>
<style type="text/css">
    .tip{
        margin-top: 0px;
    }
    div.tip_tutorial
    {
        padding-bottom:4px;
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
</style>
<script>
    function checkSelected(f){
        var n = $(f).find("input:checked").length;
        if(n <=0)
        {
            alert("{/literal}{phrase var='socialbridge.no_selected_migrate_options'}{literal}");return false;
        }
        else
        {
            if(confirm("{/literal}{phrase var='core.are_you_sure'}{literal}"))
            {
                return true;
            }
        }
        return false;
    }
</script>
{/literal}
{* <!-- TURN OFF MIGRATE FUNCTION-->
{if phpfox::isModule('opensocialconnect') || phpfox::isModule('socialpublishers')}
<div class="table_header">
    {phrase var='socialbridge.migrate_settings'}
</div>
<div lass="table">
    <div class="tip">
        {phrase var='socialbridge.to_upgrade_new_settings_from_social_connect_or_social_publishers_settings_please_click_to_migrate'}
    </div>
    <div class="error_message">
        {phrase var='socialbridge.if_both_social_connect_and_social_publlishers_settings_are_configured_after_upgrading_the_new_se'}
    </div>
    <div class="error_message">
        {phrase var='socialbridge.for_twitter_api_settings_strong_must_change_callback_url_to_strong'}<strong style="color:red;">{$sCallBackUrl}</strong>
    </div>
    <form action="{url link='admincp.socialbridge.providers'}" method="post" onsubmit="return checkSelected(this);">
        {if phpfox::isModule('opensocialconnect')}
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.migrate_from'}</div>
            <div class="table_right" style="margin-left:200px"><input type="checkbox" checked value="1" name="migrate[socialconnect]" />{phrase var='socialbridge.social_connect'}</div>
        </div>
        {/if}
        {if phpfox::isModule('socialpublishers')}
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.migrate_from'}</div>
            <div class="table_right" style="margin-left:200px"><input type="checkbox" checked value="1" name="migrate[socialpublishers]" />{phrase var='socialbridge.social_publishers'}</div>
        </div>
        {/if}
        <div class="table_clear">
            <input type="submit" value="{phrase var='socialbridge.migrate'}" class="button" />
        </div>
    </form>
</div>
<div class="p_4 clear"></div>
{/if}
*}
{foreach from=$aPublisherProviders index=iKey item=aPublisherProvider}
<div class="table_header">
    {$aPublisherProvider.title} {phrase var='socialbridge.setting'}
</div>
<div class="table">
    {if $aPublisherProvider.name == 'facebook' }
    <div class="tip" id="tip_facebook"><a href="javascript:void(0)" onclick="viewTutorial(this);" rel="tip_facebook_tutorial">{phrase var='socialbridge.view'}</a> {phrase var='socialbridge.tutorial_how_to_get_facebook_api_key'}</div>
    <div id="tip_facebook_tutorial" class="tip_tutorial" style="display: none">
        <ul>
            {phrase var='socialbridge.to_get_your_facebook_api_id'}
            <li>{phrase var='socialbridge.for_site_url_you_should_use_this_url'} <strong>{$sCoreUrl}</strong> </li>
            <li>{phrase var='socialbridge.do_follow_facebook_steps_to_complete_create_new_application'}</li>
            <li>{phrase var='socialbridge.go_to_the_following_url_select_your_app_and_edit'}</li>
            <li>{phrase var='socialbridge.select_advanced_setting_and_edit_migrations_settings_2'}</li>
        </ul>
    </div>
    <form action="{url link='admincp.socialbridge.providers'}" method="post" enctype="multipart/form-data" id="provider_facebook">
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.application_id'}</div>
            <div class="table_right" style="margin-left:200px"><input type="text" size="40" value="{if isset($aPublisherProvider.params.app_id)}{$aPublisherProvider.params.app_id}{/if}" name="facebook[app_id]"></div>
        </div>
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.facebook_secret'}</div>
            <div class="table_right" style="margin-left:200px"><input type="text" size="60" value="{if isset($aPublisherProvider.params.secret)}{$aPublisherProvider.params.secret}{/if}" name="facebook[secret]"></div>
        </div>

        <div class="table">
            <div class="table_left" style="width: 200px;">
                {required}{phrase var='socialbridge.enable_facebook_connect'}
            </div>
            <div class="table_right" style="margin-left:200px">
                <div class="item_can_be_closed_holder">
                    <span class="item_is_active">
                        <input type="radio" name="facebook[is_active]" value="1"  {if isset($aPublisherProvider.is_active) && $aPublisherProvider.is_active == 1}checked{/if}/> {phrase var='admincp.yes'}
                    </span>
                    <span class=" item_is_not_active">
                        <input type="radio" name="facebook[is_active]" value="0" {if !isset($aPublisherProvider.is_active) || $aPublisherProvider.is_active == 0}checked{/if}/> {phrase var='admincp.no'}
                    </span>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="table">
            <div class="table_left" style="width: 200px;">
                {phrase var='socialbridge.picture_show_on_facebook'}
            </div>
            <div class="table_right" style="margin-left:200px">
                <input type="file" name="fb_pic" />
                {if isset($aPublisherProvider.params.pic)}
                <br/>{img path='photo.url_photo' file=$aPublisherProvider.params.pic max_width=100 max_height=100}
                <br/><input type="checkbox" name="facebook[delete_pic]" value="1" /> {phrase var='core.delete'}
                {/if}
            </div>
            <div class="clear"></div>
        </div>
        {if phpfox::isModule('contactimporter')}
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.maximum_invite_per_day'}</div>
            <div class="table_right" style="margin-left:200px">
            	<input type="text" size="40" value="{if isset($aPublisherProvider.params.maxInvite)}{$aPublisherProvider.params.maxInvite}{else}20{/if}" name="facebook[maxInvite]">
            	<ul style="padding-top: 5px">
            		<li>{phrase var='socialbridge.description_facebook_maximum_invite_per_day'}</li>
            		<li>{phrase var='socialbridge.viewmore_facebook_maximum_invite_per_day'}</li>
            	</ul>
            </div>
        </div>
		{/if}
        <div class="table_clear">
            <input type="submit" value="{phrase var='core.submit'}" class="button" />
        </div>
    </form>
    {/if}
    {if $aPublisherProvider.name == 'twitter' }
    <div class="tip" id="tip_twitter"><a href="javascript:void(0)" onclick="viewTutorial(this);" rel="tip_twitter_tutorial">{phrase var='socialbridge.view'}</a> {phrase var='socialbridge.tutorial_how_to_get_twitter_api_key'}</div>
    <div id="tip_twitter_tutorial" class="tip_tutorial" style="display: none">
        <ul>
            {phrase var='socialbridge.to_get_your_twitter_api'}
            <li>
                <div class="p_4">{phrase var='socialbridge.for_website_you_should_use_this_url'} <strong>{$sCoreUrl}</strong></div>
                <div class="p_4">{phrase var='socialbridge.for_callback_url_must_use'} <strong style="color:red;">{$sCallBackUrl}</strong></div>

            </li>
            {phrase var='socialbridge.do_follow_twitter_steps_to_complete_create_new_application'}
        </ul>
    </div>
    <form action="{url link='admincp.socialbridge.providers'}" method="post" id="provider_twitter">
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.consumer_key'}</div>
            <div class="table_right" style="margin-left:200px"><input type="text" size="40" value="{if isset($aPublisherProvider.params.consumer_key)}{$aPublisherProvider.params.consumer_key}{/if}" name="twitter[consumer_key]"></div>
        </div>
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.consumer_secret'}</div>
            <div class="table_right" style="margin-left:200px"><input type="text" size="60" value="{if isset($aPublisherProvider.params.consumer_secret)}{$aPublisherProvider.params.consumer_secret}{/if}" name="twitter[consumer_secret]"></div>
        </div>

        <div class="table">
            <div class="table_left" style="width: 200px;">
                {required}{phrase var='socialbridge.enable_twitter_connect'}
            </div>
            <div class="table_right" style="margin-left:200px">
                <div class="item_can_be_closed_holder">
                    <span class="item_is_active">
                        <input type="radio" name="twitter[is_active]" value="1" {if isset($aPublisherProvider.is_active) && $aPublisherProvider.is_active == 1}checked{/if} /> {phrase var='admincp.yes'}
                    </span>
                    <span class=" item_is_not_active">
                        <input type="radio" name="twitter[is_active]" value="0" {if !isset($aPublisherProvider.is_active) || $aPublisherProvider.is_active == 0}checked{/if}/> {phrase var='admincp.no'}
                    </span>
                </div>
            </div>
            <div class="clear"></div>
        </div>
		{if phpfox::isModule('contactimporter')}
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.maximum_invite_per_day'}</div>
            <div class="table_right" style="margin-left:200px">
            	<input type="text" size="40" value="{if isset($aPublisherProvider.params.maxInvite)}{$aPublisherProvider.params.maxInvite}{else}250{/if}" name="twitter[maxInvite]">
            	<ul style="padding-top: 5px">
            		<li>{phrase var='socialbridge.description_twtitter_maximum_invite_per_day'}</li>
            		<li>{phrase var='socialbridge.viewmore_twtitter_maximum_invite_per_day'}</li>
            	</ul>
            </div>
        </div>
		{/if}
        <div class="table_clear">
            <input type="submit" value="{phrase var='core.submit'}" class="button" />
        </div>
    </form>
    {/if}
    {if $aPublisherProvider.name == 'linkedin' }
    <div class="tip" id="tip_linkedin"><a href="javascript:void(0)" onclick="viewTutorial(this);" rel="tip_linkedin_tutorial">{phrase var='socialbridge.view'}</a> {phrase var='socialbridge.tutorial_how_to_get_linkedin_api_key'}</div>
    <div id="tip_linkedin_tutorial" class="tip_tutorial" style="display: none">
        <ul>
            {phrase var='socialbridge.to_get_your_linkedin_api_key'}
            <li>{phrase var='socialbridge.for_website_url_you_should_use_this_url_2'} <strong>{$sCoreUrl}</strong></li>
            <li>{phrase var='socialbridge.for_application_use_should_be_strong_style_color_red_social_aggregation_strong'}</li>
            <li>{phrase var='socialbridge.for_live_status_should_be_live'}</li>
            <li>{phrase var='socialbridge.do_follow_linkedin_steps_to_complete_create_new_application2'}</li>
        </ul>
    </div>
    <form action="{url link='admincp.socialbridge.providers'}" method="post" id="provider_linkedin">
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.api_key'}</div>
            <div class="table_right" style="margin-left:200px"><input type="text" size="40" value="{if isset($aPublisherProvider.params.api_key)}{$aPublisherProvider.params.api_key}{/if}" name="linkedin[api_key]"></div>
        </div>
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.secret_key'}</div>
            <div class="table_right" style="margin-left:200px"><input type="text" size="60" value="{if isset($aPublisherProvider.params.secret_key)}{$aPublisherProvider.params.secret_key}{/if}" name="linkedin[secret_key]"></div>
        </div>

        <div class="table">
            <div class="table_left" style="width: 200px;">
                {required}{phrase var='socialbridge.enable_linkedin_connect'}
            </div>
            <div class="table_right" style="margin-left:200px">
                <div class="item_can_be_closed_holder">
                    <span class="item_is_active">
                        <input type="radio" name="linkedin[is_active]" value="1" {if isset($aPublisherProvider.is_active) && $aPublisherProvider.is_active == 1}checked{/if} > {phrase var='admincp.yes'}
                    </span>
                    <span class=" item_is_not_active">
                        <input type="radio" name="linkedin[is_active]" value="0" {if !isset($aPublisherProvider.is_active) || $aPublisherProvider.is_active == 0}checked{/if}/> {phrase var='admincp.no'}
                    </span>
                </div>
            </div>
            <div class="clear"></div>
        </div>
		{if phpfox::isModule('contactimporter')}
        <div class="table">
            <div class="table_left" style="width: 200px;">{phrase var='socialbridge.maximum_invite_per_day'}</div>
            <div class="table_right" style="margin-left:200px">
            	<input type="text" size="40" value="{if isset($aPublisherProvider.params.maxInvite)}{$aPublisherProvider.params.maxInvite}{else}10{/if}" name="linkedin[maxInvite]">
            	<ul style="padding-top: 5px">
            		<li>{phrase var='socialbridge.description_linkedin_maximum_invite_per_day'}</li>
            		<li>{phrase var='socialbridge.viewmore_linkedin_maximum_invite_per_day'}</li>
            	</ul>
            </div>
        </div>
		{/if}
        <div class="table_clear">
            <input type="submit" value="{phrase var='core.submit'}" class="button" />
        </div>
    </form>
    {/if}
</div>
{/foreach}
