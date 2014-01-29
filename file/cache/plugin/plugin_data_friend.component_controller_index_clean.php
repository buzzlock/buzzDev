<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isModule(\'suggestion\') && Phpfox::isUser() && Phpfox::getUserParam(\'suggestion.enable_friend_suggestion\')){?>
<script lang="javascript">
    $Behavior.append_suggestion_member_page = function(){
        var _iUserId = \'\';    
        $(\'div[class*="friend_row_holder"]\').each(function(){                                
            var _id = $(this).find(\'input\').eq(0).val();            
            var _iCurrentId = \'<?php  echo (int)Phpfox::getUserId();?>\';
            if (_id != \'\'){
                if (_id != _iCurrentId && !isNaN(_id)){                
                    $(this).find(\'.friend_user_name\').append(\'<BR><BR><span id="user_member_page_\'+_id+\'"></span>\');
                    _iUserId += _id+",";         
                }  
            } 
        });  
        _iUserId = substr(_iUserId, 0, _iUserId.length-1)+\'\';   
        $.ajaxCall(\'suggestion.appendSuggest\',\'sUserId=\'+_iUserId);        
    };
</script>
<?php }/*end check module*/ '; ?>