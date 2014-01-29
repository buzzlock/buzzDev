{literal}
<style type="text/css">
    #yn_socialstream_custom_form_share {
        float: {/literal}{$sFloat}{literal};
        display: block;
        position: relative;
        width: 100%;
        z-index: 10;
        height: 20px;
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
    .yn_socialstream_get_feeds li{
        display: inline;
        margin: 0 1px;
    }
    .yn_socialstream_get_feeds span{
        color: #3B5998 !important;
        outline: 0 none !important;
        text-decoration: none !important;
        cursor: pointer;
    }
    .yn_socialstream_get_feeds span:hover{
        text-decoration: underline !important;
    }

    div#yn_socialstream_custom_form_share div.header_bar_menu ul.action_drop
    {
        overflow: hidden;
    }
    ul.activity_feed_form_attach{min-height: 25px;}

</style>
{/literal}
<div id="yn_socialstream_custom_form_share" {if Phpfox::getService('socialbridge.libs')->timeline()}style="margin-top: 5px;"{/if}>
    <div class="header_filter_holder header_bar_menu yn_social_stream_filter">
        <div class="header_bar_float">
            <div class="header_bar_drop_holder">
                {if count($aFeedTypes) > 0}
                <input id="feed_type_id" value="all" type="hidden" />
                <ul class="header_bar_drop" style="float: {$sFloat}">
                    <li><span>{phrase var='socialstream.filter'}:</span></li>
                    <li><a id="feed_type_id_label" class="header_bar_drop" href="#">{phrase var='socialstream.all_feeds'}</a></li>
                </ul>
                {/if}
                {if !$bIsUsersProfilePage || $bOwnProfile}
                <ul class="yn_socialstream_get_feeds" style="float: {$sFloat}; margin-right: 5px; line-height: 23px;">
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
                {if count($aFeedTypes) > 0}
                <div class="clear"></div>
                <div class="action_drop_holder" style="display: none;">
                    <ul class="action_drop">
                        {foreach from=$aFeedTypes key="sTypeId" item="sType"}
                        {if !is_array($sType)}
                        <li><a class="ajax_link" href="javascript:void(0)" onclick="feed_filter('type', '{$sTypeId}', this);">{$sType}</a></li>
                        {else}
                        {foreach from=$sType key="sChildTypeId" item="sChildType"}
                        <li><a class="ajax_link" href="javascript:void(0)" onclick="feed_filter('type', '{$sChildTypeId}', this);">{$sChildType}</a></li>
                        {/foreach}
                        {/if}
                        {/foreach}
                    </ul>
                </div>
                {/if}
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

{literal}
<script type="text/javascript">
    function feed_filter(type, value, element){
        switch(type){
            case "type":
                $("#feed_type_id").val(value);
                $("#feed_type_id_label").html(element.innerHTML);
                break;
            case "limit":
                $("#feed_limit").val(value);
                $("#feed_limit_label").html(element.innerHTML);
                break;
        }
        var viewId = $("#feed_type_id").val();
        $('#js_feed_content').html('<div id="feed_filtering_animation">{/literal}{img theme="ajax/add.gif" class="v_middle"}{literal}</div>');
        setTimeout("doFilter('"+viewId+"')", 100);
    }
    function doFilter(viewId) {
        $('#activity_feed_updates_link_holder').hide();
        $iReloadIteration = 0;
        $.ajaxCall('socialstream.filterFeed', 'profile_user_id='+oCore['profile.user_id']+'&is_user_profile='+oCore['profile.is_user_profile']  +'&forceview=1&resettimeline=1&viewId=' + viewId + '&userId=' + $('#userId').val(), 'GET');
    }
    function feed_filter_success(){
        $("#feed_filtering_animation").remove();
        reload_wall = 1;
    }

    function getSocialStreamFeeds()
    {
        $('.socialstream_get_feeds_link').hide(0,function(){$('.socialstream_get_feeds_img').show();});
        $.ajaxCall('socialstream.getFeeds');
    }

    $Core.forceLoadOnFeed = function()
    {
        if ($iReloadIteration >= 2)
            return;

        $iReloadIteration++;
        $('#feed_view_more_loader').show();
        $('.global_view_more').hide();
        setTimeout("var viewId = $('#feed_type_id').val(); $.ajaxCall('socialstream.viewMore', $('#js_feed_pass_info').html().replace('&amp;', '&') + '&viewId=' + viewId, 'GET');", 1000);
    }

</script>
{/literal}