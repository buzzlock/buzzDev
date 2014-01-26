<style>
.yc_list_action_2 li{l}
    margin:5px 12px 5px 5px;
    background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, {$aButtonColor.bgcolor_1}), color-stop(1, {$aButtonColor.bgcolor_2}));
    background:-moz-linear-gradient(top, {$aButtonColor.bgcolor_1} 5%, {$aButtonColor.bgcolor_2} 100%);
    background:-webkit-linear-gradient(top, {$aButtonColor.bgcolor_1} 5%, {$aButtonColor.bgcolor_2} 100%);
    background:-o-linear-gradient(top, {$aButtonColor.bgcolor_1} 5%, {$aButtonColor.bgcolor_2} 100%);
    background:-ms-linear-gradient(top, {$aButtonColor.bgcolor_1} 5%, {$aButtonColor.bgcolor_2} 100%);
    background:linear-gradient(to bottom, {$aButtonColor.bgcolor_1} 5%, {$aButtonColor.bgcolor_2} 100%);
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='{$aButtonColor.bgcolor_1}', endColorstr='{$aButtonColor.bgcolor_2}',GradientType=0);
    background-color:{$aButtonColor.bgcolor_1};
    -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
    -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
    box-shadow:inset 0px 1px 0px 0px #ffffff;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    border:1px solid {$aButtonColor.border_color};
{r}
.yc_list_action_2 li a,
.yc_list_action_2 li a:hover{l}
    color: {$aButtonColor.text_color};
{r}
</style>

{literal}
<script type="text/javascript">  
   $Behavior.ynContestShowContestProfileImage = function(){
         $('.js_contest_click_image').click(function(){
               var oNewImage = new Image();
               oNewImage.onload = function(){
                     $('#js_marketplace_click_image_viewer').show();
                     $('#js_marketplace_click_image_viewer_inner').html('<img src="' + this.src + '" style="max-width: 580px; max-height: 580px" alt="" />');            
                     $('#js_marketplace_click_image_viewer_close').show();
               };
               oNewImage.src = $(this).attr('href');
               
               return false;
         });
         
         $('#js_marketplace_click_image_viewer_close a').click(function(){
               $('#js_marketplace_click_image_viewer').hide();
               return false;
         });
   }
</script>
{/literal}

<div id="js_marketplace_click_image_viewer" style="width: 600px;">
    <div id="js_marketplace_click_image_viewer_inner">
        {phrase var='contest.loading'}
    </div>
    <div id="js_marketplace_click_image_viewer_close">
        <a href="#">{phrase var='contest.close'}</a>
    </div>
</div>

<div class="yc large_item image_hover_holder">
    <div class="yc_view_image ycontest_photo">
        <ul class="list_itype">
        {if $aContest.contest_status == 1}
            <li class="itype endraft">{phrase var='contest.draft'}</li>
        {elseif $aContest.contest_status == 2}
            <li class="itype enpending">{phrase var='contest.pending'}</li>
        {elseif $aContest.contest_status == 3}
            <li class="itype endenied">{phrase var='contest.denied'}</li>
        {elseif $aContest.contest_status == 5}
            <li class="itype enclosed">{phrase var='contest.closed'}</li>
        {else}
            {if $aContest.is_feature}<li class="itype enfeatured">{phrase var='contest.featured'}</li>{/if}
            {if $aContest.is_premium}<li class="itype enpremium">{phrase var='contest.premium'}</li>{/if}
            {if $aContest.is_ending_soon}<li class="itype endinsoon">{phrase var='contest.ending_soon'}</li>{/if}
        {/if}
        </ul>

        <a class="large_item_image js_contest_click_image no_ajax_link" href="{img server_id=$aContest.server_id return_url=true path='core.url_pic' file='contest/'.$aContest.image_path suffix='' max_width=150}" title="{$aContest.contest_name|clean*}">
            {img path='core.url_pic' file="contest/".$aContest.image_path suffix='_160' max_width='170' class='js_mp_fix_width'}
        </a>
    </div>
</div>

<div class='clear'> </div>
<ul class="yc_list_action_1">
    {if $aContest.can_invite_friend}
        <li>
            <a href="#" title ="{phrase var='contest.invite_friends_to_this_contest'}" onclick="$Core.box('contest.showInvitePopup',800,'&contest_id={$aContest.contest_id}'); return false;">{phrase var='contest.invite'}</a>
        </li>
    {/if}
    {if $aContest.can_follow_contest}
        <li id="yncontest_photo_follow_link">
            {if !$aContest.is_followed} 
                <a href="#" title ="{phrase var='contest.follow_this_contest'}" onclick="$.ajaxCall('contest.followContest','contest_id={$aContest.contest_id}&amp;type=1', 'GET'); return false;">{phrase var='contest.follow'}</a>
            {else}
                <a href="#" title ="{phrase var='contest.un_follow_this_contest'}" onclick="$.ajaxCall('contest.followContest','contest_id={$aContest.contest_id}&amp;type=0', 'GET'); return false;">{phrase var='contest.un_follow'}</a>
            {/if}
        </li>

    {/if}

    {if $aContest.can_favorite_contest}
        <li id="yncontest_photo_favorite_link">
            {if !$aContest.is_favorited} 
                <a href="#" title ="{phrase var='contest.favorite_this_contest'}" onclick="$.ajaxCall('contest.favoriteContest','contest_id={$aContest.contest_id}&amp;type=1', 'GET'); return false;">{phrase var='contest.favorite'}</a>
            {else}
                <a href="#" title ="{phrase var='contest.un_favorite_this_contest'}" onclick="$.ajaxCall('contest.favoriteContest','contest_id={$aContest.contest_id}&amp;type=0', 'GET'); return false;">{phrase var='contest.un_favorite'}</a>
            {/if}
        </li>
    {/if}
</ul>
<ul class="yc_list_action_2">
    {if $aContest.can_submit_entry}
    <li>
        <a class="yc_sub_entry" href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name action=add}" title="{phrase var='contest.submit_an_entry'}"> {phrase var='contest.submit_an_entry'} </a>
    </li>
    {/if}
        {if $aContest.is_joined}
        <li>
        <a class="yc_joined" href="#" title="{phrase var='contest.leave_this_contest'}" onclick="$.ajaxCall('contest.leaveContest', 'contest_id={$aContest.contest_id}', 'GET'); return false;">{phrase var='contest.leave'}</a>
        </li>
        {elseif $aContest.can_join_contest}
        <li>
        <a class="yc_joined" href="#" title="{phrase var='contest.join_this_contest'}" onclick="yncontest.join.showJoinContestPopup({$aContest.contest_id}, '{phrase var='contest.terms_and_conditions'}'); return false;">{phrase var='contest.join'}</a>
        </li>
        {/if}
    <li>
        <a class="yc_promotes" href="#" title="{phrase var='contest.promote_this_contest'}" onclick="$Core.box('contest.getPromoteContestBox',600,'&contest_id={$aContest.contest_id}'); return false;">{phrase var='contest.promote'}</a>
    </li>
</ul>

<div class="clear" style="margin-bottom: 15px;"></div>