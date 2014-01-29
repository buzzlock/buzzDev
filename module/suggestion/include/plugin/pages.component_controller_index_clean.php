<?php 

$iPageId = (int)$this->request()->getInt('req2');

if ($iPageId>0 && Phpfox::isModule('suggestion')  && Phpfox::isUser() ){        
    /*get detail pages*/
    
    $aPages = Phpfox::getService('pages')->getPage($iPageId);
	$sTitle = base64_encode(urlencode($aPages['title']));
    
    $sSuggestToFriends = Phpfox::getPhrase('suggestion.suggest_to_friends_2');
    $sCurrentUrl = Phpfox::getLib('url')->getFullUrl(); 
    $iUserId = Phpfox::getUserId();
    ?>
    <script language="javascript">
    $().ready(function(){
            <?php if (Phpfox::getUserParam('suggestion.enable_friend_suggestion')){?>
                $('#section_menu').eq(0).append('<ul><li id="suggestion_page_btn"><a onclick="suggestion_and_recommendation_tb_show(\'...\',$.ajaxBox(\'suggestion.friends\',\'iFriendId=<?php  echo $iPageId;?>&sSuggestionType=suggestion&sModule=suggestion_pages&sLinkCallback=<?php  echo $sCurrentUrl;?>&sTitle=<?php  echo $sTitle;?>&sPrefix=pages_\')); return false;" href="#"><?php  echo $sSuggestToFriends?></a></li></ul>');
            <?php }?>            
    });
    </script>    
    <style>
        #js_is_user_profile #js_is_page #section_menu{min-width: 0px;}
		#section_menu ul{float: right;}
    </style>
<?php } /*end check module*/?>