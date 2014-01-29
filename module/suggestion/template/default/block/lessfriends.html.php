<?php
/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Suggestion
 * @version 		$Id: ajax.class.php 1 2011-11-25 15:29:17Z YOUNETCO $
 */

defined('PHPFOX') or exit('NO DICE!');
?>
{if count($aFriends)>0}
<div id="bIsAllowSuggestion" style="display:none;">{$bIsAllowSuggestion}</div>
<div id="suggsetionFriends">
    <div id="iCurrentUserId" style="display:none">{$iCurrentUserId}</div>
    <div id="sTitle" style="display:none">{$sTitle}</div>
<p style="background-color:#BAFC8B; padding:5px;">{phrase var='suggestion.your_friends_have_only_a_few_friends'}</p>

{foreach from=$aFriends key=iKey item=aFriend}
<p style="line-height:5px;">&nbsp;</p>
<p style="position: absolute;">{$aFriend.img}</p>
<p style="padding: 0 0 15px 60px; border-bottom:1px solid #f2f2f2;">    
        <strong>{$aFriend.user_link)}</strong><BR>
        <b>{$aFriend.total_friends}</b> {phrase var='suggestion.friends'}<BR>        
            {$aFriend.url}
</p>
{/foreach}

</div>
{literal}
<script language="javascript">
        $Behavior.loadFewFriendClick = function(){
            $('.suggest-user').click(function(e){        
                e.preventDefault();
                _iFriendId = $(this).attr('rel');
                if (parseInt($('#bIsAllowSuggestion').html()) == 1)
                    suggestion_and_recommendation_tb_show($('#sTitle').html(),$.ajaxBox('suggestion.friends','iFriendId='+_iFriendId));
                else
                    suggestion_and_recommendation_tb_show($('#sTitle').html(),$.ajaxBox('suggestion.friends','iFriendId='+_iFriendId+'&sSuggestionType=recommendation'));
            });
        };
</script>
{/literal}
{else}
{literal}
<style>#js_block_border_suggestion_lessfriends{display:none;}</style>
{/literal}
<p style="display:none;">{phrase var='suggestion.no_friends'}</p>
{/if}