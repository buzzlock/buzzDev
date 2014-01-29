<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script type="text/javascript">
	function updateSocialStreamSetting(oObj)
	{		
		$(oObj).ajaxCall('socialstream.updateSetting');
		return false;
	}
</script>
{/literal}
<div align="left" class="page_section_menu_holder" id="js_setting_block_socialstream" style="display:none">  
    <form method="post" action="#" onsubmit="return updateSocialStreamSetting(this);">
        {if $aFacebook.connected }
            <div class="table">
                <div class="table_left">
                    {phrase var='socialstream.facebook_settings'}
                    <div class="extra_info">
                        {phrase var='socialstream.enable_this_setting_to_get_feed_from_your_facebook_account'}
                    </div>
                </div>
                <div class="table_right">
                    <div class="item_is_active_holder">
                        <span class="js_item_active item_is_active"><input value="1" name="val[facebook]" class="checkbox" type="radio"{if $aFacebookSetting.enable} checked="checked"{/if} /> {phrase var='user.yes'}</span>
                        <span class="js_item_active item_is_not_active"><input value="0" name="val[facebook]" class="checkbox" type="radio"{if !$aFacebookSetting.enable} checked="checked"{/if} /> {phrase var='user.no'}</span>
                    </div>
                </div>
            </div>
            <div class="table" style="border-bottom: 1px solid #DFDFDF">
                <div class="table_left">
                    {phrase var='socialstream.facebook_privacy'}
                    <div class="extra_info">
                        {phrase var='socialstream.control_who_can_see_your_facebook_s_feeds'}
                    </div>
                </div>
                <div class="table_right">
                    {module name='socialstream.privacy' provider='facebook' privacy_name='privacy_facebook' default_privacy=$aFacebookSetting.privacy privacy_type='full' privacy_no_custom='true'}
                </div>
            </div>
            <input type="hidden" name="val[facebook_setting]" value="{$aFacebookSetting.setting_id}" />
        {/if}
        {if $aTwitter.connected }
            <div class="table">
                <div class="table_left">
                    {phrase var='socialstream.enable_twitter'}
                    <div class="extra_info">
                        {phrase var='socialstream.enable_this_setting_to_get_feed_from_your_twitter_account'}
                    </div>
                </div>
                <div class="table_right">
                    <div class="item_is_active_holder">
                        <span class="js_item_active item_is_active"><input value="1" name="val[twitter]" class="checkbox" type="radio"{if $aTwitterSetting.enable} checked="checked"{/if} /> {phrase var='user.yes'}</span>
                        <span class="js_item_active item_is_not_active"><input value="0" name="val[twitter]" class="checkbox" type="radio"{if !$aTwitterSetting.enable} checked="checked"{/if} /> {phrase var='user.no'}</span>
                    </div>
                </div>
            </div>
            <div class="table" style="border-bottom: 1px solid #DFDFDF">
                <div class="table_left">
                    {phrase var='socialstream.twitter_privacy'}
                    <div class="extra_info">
                        {phrase var='socialstream.control_who_can_see_your_twitter_s_feeds'}
                    </div>
                </div>
                <div class="table_right">
                    {module name='socialstream.privacy' provider="twitter"  privacy_name='privacy_twitter' default_privacy=$aTwitterSetting.privacy privacy_type='full' privacy_no_custom='true'}
                </div>
            </div>
            <input type="hidden" name="val[twitter_setting]" value="{$aTwitterSetting.setting_id}" />
        {/if}
        {if $aTwitter.connected || $aFacebook.connected}
            <br/>
            <div class="table clear">
                <input type="submit" class="button" value="{phrase var='core.update'}"/>
            </div>
        {else}
            {phrase var='socialbridge.you_are_not_connected_to_any_providers'}
        {/if}
  </form>
</div>
