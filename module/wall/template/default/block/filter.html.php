{literal}
<style type="text/css">
    #yn_wall_custom_form_share {
    display: block;
    position: relative;
    width: 100%;
    z-index: 10;
    {/literal}{if $bIsUsersProfilePage}height: 25px;{else}height: 15px;{/if}{literal}
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
{/literal}
<div id="yn_wall_custom_form_share" style="float: {if isset($float) }{$sFloat}{else}right{/if};">
    <input type="hidden" id="userId" value="{$iUserId}"/>
    <div class="header_filter_holder header_bar_menu yn_wall_filter">
        <div class="header_bar_float">
            <div class="header_bar_drop_holder">
                <input id="feed_type_id" value="all" type="hidden" />
                <ul class="header_bar_drop" style="float: right">
                    <li><span>{phrase var='wall.filter'}:</span></li>
                    <li><a id="feed_type_id_label" class="header_bar_drop" href="#">{phrase var='wall.all_feeds'}</a></li>
                </ul>
                {if $havingSocialStream && Phpfox::isModule('socialbridge') && (!$bIsUsersProfilePage || $bOwnProfile)}
				<ul class="yn_wall_get_feeds" style="float: {if isset($float) }{$sFloat}{else}right{/if}; margin-right: 5px; line-height: 23px;">
				    {if $bIsLogged}
				    <li>
				        <img class="socialstream_get_feeds_link" onclick="return getSocialStreamFeeds();" src="{$corePath}/module/socialstream/static/image/default/default/refresh.png" style="vertical-align:middle; cursor: pointer; " alt="{phrase var='socialstream.get_feeds'}"/>
				        <img class="socialstream_get_feeds_img" src="{$corePath}/module/socialstream/static/image/default/default/refresh.gif" style="vertical-align:middle; display: none;" alt="{phrase var='socialstream.get_feeds'}"/>
				    </li>
				    {/if}
				    <li><a href="{url link='socialbridge.setting'}" title="{phrase var='socialstream.social_stream_settings'}" style="text-decoration: none"><img src="{$corePath}/module/socialstream/static/image/default/default/facebook_icon.png" style="vertical-align: middle"/></a></li>
				    <li><a href="{url link='socialbridge.setting'}" title="{phrase var='socialstream.social_stream_settings'}" style="text-decoration: none"><img src="{$corePath}/module/socialstream/static/image/default/default/twitter_icon.png" style="vertical-align: middle"/></a></li>
				</ul>
				{/if}
                <div class="clear"></div>
                <div class="action_drop_holder" style="display: none;">
                    <ul class="action_drop">
                        {foreach from=$aFeedTypes key="sTypeId" item="sType"}
                        <li><a class="ajax_link" href="javascript:void(0)" onclick="feed_filter('type', '{$sTypeId}', this);">{$sType}</a></li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="clear"></div>
    </div>
</div>
{literal}