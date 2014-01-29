<?php if (Phpfox::isModule('suggestion') && Phpfox::isUser()){?>

<script lang="javascript">
    var _iFriendId = "";  
    
    $('.table_clear_button').find('input').eq(0).click(function(e){            
        e.preventDefault();
        var _html = $(this).attr('onclick');
        _iFriendId = /user_id=[\d]+/i.exec(_html)+"";
        _iFriendId = _iFriendId.replace(/user_id=/g,"");

        $.ajaxCall('suggestion.approve','iItemId='+<?php  echo Phpfox::getUserId();?>+'&iApprove=1&sModule=suggestion_friend&iFriendId='+_iFriendId+'&bAddFriend=0');
        <?php if (Phpfox::getService('suggestion')->isAllowSuggestionPopup() && Phpfox::getUserParam('suggestion.enable_friend_suggestion') && Phpfox::getUserParam('suggestion.enable_friend_suggestion_popup')){?>
            if(_iFriendId!="null"){suggestion_and_recommendation_tb_show("...",$.ajaxBox('suggestion.friends','iFriendId='+_iFriendId+'&sSuggestionType=suggestion'+'&sModule=suggestion_friend'));}
        <?php }elseif(Phpfox::getService('suggestion')->isAllowRecommendationPopup() && Phpfox::getUserParam('suggestion.enable_friend_recommend')){?>
            suggestion_and_recommendation_tb_show("...",$.ajaxBox('suggestion.friends','iFriendId='+_iFriendId+'&sSuggestionType=recommendation'));
        <?php }?>
        return true;
    });
    
    $('.table_clear_button').find('input').eq(1).click(function(e){
        e.preventDefault();
        var _html = $(this).attr('onclick');
        _iFriendId = /user_id=[\d]+/i.exec(_html)+"";
        _iFriendId = _iFriendId.replace(/user_id=/g,"");
        $.ajaxCall('suggestion.approve','iItemId='+<?php  echo Phpfox::getUserId();?>+'&iApprove=2&sModule=suggestion_friend&iFriendId='+_iFriendId);
        return true;
    });    
</script>

<?php }/*end check module*/?>