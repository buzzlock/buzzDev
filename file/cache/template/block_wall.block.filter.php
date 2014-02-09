<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 9, 2014, 5:51 am */ ?>
<?php echo '
<style type="text/css">
    #yn_wall_custom_form_share {
    display: block;
    position: relative;
    width: 100%;
    z-index: 10;
    ';  if ($this->_aVars['bIsUsersProfilePage']): ?>height: 25px;<?php else: ?>height: 15px;<?php endif;  echo '
    }
    .header_bar_drop li{
        display: inline;
    }

    .header_filter_holder{
        top:0px;
    }
    #content .block .title .header_filter_holder {
        color: inherit;
        font-size: 12px;
        font-weight: normal;
    }

    .header_filter_holder{
        background: none repeat scroll 0 0 transparent !important;
        border-bottom: 0 none !important;
        position: absolute !important;
        top: -5px;
        padding: 0 !important;
    }
    .yn_wall_get_feeds li{
        display: inline;
        margin: 0 1px;
    }
    .yn_wall_get_feeds span{
        color: #3B5998 !important;
        outline: 0 none !important;
        text-decoration: none !important;
        cursor: pointer;
    }
    .yn_wall_get_feeds span:hover{
        text-decoration: underline !important;
    }

    div#yn_wall_custom_form_share div.header_bar_menu ul.action_drop
    {
        overflow: hidden;
    }
</style>
'; ?>

<div id="yn_wall_custom_form_share" style="float: <?php if (isset ( $this->_aVars['float'] )):  echo $this->_aVars['sFloat'];  else: ?>right<?php endif; ?>;">
    <input type="hidden" id="userId" value="<?php echo $this->_aVars['iUserId']; ?>"/>
    <div class="header_filter_holder header_bar_menu yn_wall_filter">
        <div class="header_bar_float">
            <div class="header_bar_drop_holder">
                <input id="feed_type_id" value="all" type="hidden" />
                <ul class="header_bar_drop" style="float: right">
                    <li><span><?php echo Phpfox::getPhrase('wall.filter'); ?>:</span></li>
                    <li><a id="feed_type_id_label" class="header_bar_drop" href="#"><?php echo Phpfox::getPhrase('wall.all_feeds'); ?></a></li>
                </ul>
<?php if ($this->_aVars['havingSocialStream'] && Phpfox ::isModule('socialbridge') && ( ! $this->_aVars['bIsUsersProfilePage'] || $this->_aVars['bOwnProfile'] )): ?>
				<ul class="yn_wall_get_feeds" style="float: <?php if (isset ( $this->_aVars['float'] )):  echo $this->_aVars['sFloat'];  else: ?>right<?php endif; ?>; margin-right: 5px; line-height: 23px;">
<?php if ($this->_aVars['bIsLogged']): ?>
				    <li>
				        <img class="socialstream_get_feeds_link" onclick="return getSocialStreamFeeds();" src="<?php echo $this->_aVars['corePath']; ?>/module/socialstream/static/image/default/default/refresh.png" style="vertical-align:middle; cursor: pointer; " alt="<?php echo Phpfox::getPhrase('socialstream.get_feeds'); ?>"/>
				        <img class="socialstream_get_feeds_img" src="<?php echo $this->_aVars['corePath']; ?>/module/socialstream/static/image/default/default/refresh.gif" style="vertical-align:middle; display: none;" alt="<?php echo Phpfox::getPhrase('socialstream.get_feeds'); ?>"/>
				    </li>
<?php endif; ?>
				    <li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('socialbridge.setting'); ?>" title="<?php echo Phpfox::getPhrase('socialstream.social_stream_settings'); ?>" style="text-decoration: none"><img src="<?php echo $this->_aVars['corePath']; ?>/module/socialstream/static/image/default/default/facebook_icon.png" style="vertical-align: middle"/></a></li>
				    <li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('socialbridge.setting'); ?>" title="<?php echo Phpfox::getPhrase('socialstream.social_stream_settings'); ?>" style="text-decoration: none"><img src="<?php echo $this->_aVars['corePath']; ?>/module/socialstream/static/image/default/default/twitter_icon.png" style="vertical-align: middle"/></a></li>
				</ul>
<?php endif; ?>
                <div class="clear"></div>
                <div class="action_drop_holder" style="display: none;">
                    <ul class="action_drop">
<?php if (count((array)$this->_aVars['aFeedTypes'])):  foreach ((array) $this->_aVars['aFeedTypes'] as $this->_aVars['sTypeId'] => $this->_aVars['sType']): ?>
                        <li><a class="ajax_link" href="javascript:void(0)" onclick="feed_filter('type', '<?php echo $this->_aVars['sTypeId']; ?>', this);"><?php echo $this->_aVars['sType']; ?></a></li>
<?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="clear"></div>
    </div>
</div>
<?php echo ''; ?>

