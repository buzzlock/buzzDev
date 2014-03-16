<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:24 pm */ ?>
<?php
  
 echo '
<script type="text/javascript">
	function updateSocialStreamSetting(oObj)
	{		
		$(oObj).ajaxCall(\'socialstream.updateSetting\');
		return false;
	}
</script>
'; ?>

<div align="left" class="page_section_menu_holder" id="js_setting_block_socialstream" style="display:none">  
    <form method="post" action="#" onsubmit="return updateSocialStreamSetting(this);">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
<?php if ($this->_aVars['aFacebook']['connected']): ?>
            <div class="table">
                <div class="table_left">
<?php echo Phpfox::getPhrase('socialstream.facebook_settings'); ?>
                    <div class="extra_info">
<?php echo Phpfox::getPhrase('socialstream.enable_this_setting_to_get_feed_from_your_facebook_account'); ?>
                    </div>
                </div>
                <div class="table_right">
                    <div class="item_is_active_holder">
                        <span class="js_item_active item_is_active"><input value="1" name="val[facebook]" class="checkbox" type="radio"<?php if ($this->_aVars['aFacebookSetting']['enable']): ?> checked="checked"<?php endif; ?> /> <?php echo Phpfox::getPhrase('user.yes'); ?></span>
                        <span class="js_item_active item_is_not_active"><input value="0" name="val[facebook]" class="checkbox" type="radio"<?php if (! $this->_aVars['aFacebookSetting']['enable']): ?> checked="checked"<?php endif; ?> /> <?php echo Phpfox::getPhrase('user.no'); ?></span>
                    </div>
                </div>
            </div>
            <div class="table" style="border-bottom: 1px solid #DFDFDF">
                <div class="table_left">
<?php echo Phpfox::getPhrase('socialstream.facebook_privacy'); ?>
                    <div class="extra_info">
<?php echo Phpfox::getPhrase('socialstream.control_who_can_see_your_facebook_s_feeds'); ?>
                    </div>
                </div>
                <div class="table_right">
<?php Phpfox::getBlock('socialstream.privacy', array('provider' => 'facebook','privacy_name' => 'privacy_facebook','default_privacy' => $this->_aVars['aFacebookSetting']['privacy'],'privacy_type' => 'full','privacy_no_custom' => 'true')); ?>
                </div>
            </div>
            <input type="hidden" name="val[facebook_setting]" value="<?php echo $this->_aVars['aFacebookSetting']['setting_id']; ?>" />
<?php endif; ?>
<?php if ($this->_aVars['aTwitter']['connected']): ?>
            <div class="table">
                <div class="table_left">
<?php echo Phpfox::getPhrase('socialstream.enable_twitter'); ?>
                    <div class="extra_info">
<?php echo Phpfox::getPhrase('socialstream.enable_this_setting_to_get_feed_from_your_twitter_account'); ?>
                    </div>
                </div>
                <div class="table_right">
                    <div class="item_is_active_holder">
                        <span class="js_item_active item_is_active"><input value="1" name="val[twitter]" class="checkbox" type="radio"<?php if ($this->_aVars['aTwitterSetting']['enable']): ?> checked="checked"<?php endif; ?> /> <?php echo Phpfox::getPhrase('user.yes'); ?></span>
                        <span class="js_item_active item_is_not_active"><input value="0" name="val[twitter]" class="checkbox" type="radio"<?php if (! $this->_aVars['aTwitterSetting']['enable']): ?> checked="checked"<?php endif; ?> /> <?php echo Phpfox::getPhrase('user.no'); ?></span>
                    </div>
                </div>
            </div>
            <div class="table" style="border-bottom: 1px solid #DFDFDF">
                <div class="table_left">
<?php echo Phpfox::getPhrase('socialstream.twitter_privacy'); ?>
                    <div class="extra_info">
<?php echo Phpfox::getPhrase('socialstream.control_who_can_see_your_twitter_s_feeds'); ?>
                    </div>
                </div>
                <div class="table_right">
<?php Phpfox::getBlock('socialstream.privacy', array('provider' => 'twitter','privacy_name' => 'privacy_twitter','default_privacy' => $this->_aVars['aTwitterSetting']['privacy'],'privacy_type' => 'full','privacy_no_custom' => 'true')); ?>
                </div>
            </div>
            <input type="hidden" name="val[twitter_setting]" value="<?php echo $this->_aVars['aTwitterSetting']['setting_id']; ?>" />
<?php endif; ?>
<?php if ($this->_aVars['aTwitter']['connected'] || $this->_aVars['aFacebook']['connected']): ?>
            <br/>
            <div class="table clear">
                <input type="submit" class="button" value="<?php echo Phpfox::getPhrase('core.update'); ?>"/>
            </div>
<?php else: ?>
<?php echo Phpfox::getPhrase('socialbridge.you_are_not_connected_to_any_providers'); ?>
<?php endif; ?>
  
</form>

</div>

