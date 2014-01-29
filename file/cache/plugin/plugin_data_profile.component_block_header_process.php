<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'defined(\'PHPFOX\') or exit(\'NO DICE!\');

// This plugin is used to display 3 buttons in profile-timeline.

if (Phpfox::isModule(\'musicsharing\'))
{
    $sModule = $this->request()->get(\'req3\');

    if ($sModule == \'musicsharing\')
    {
        if (defined(\'PHPFOX_IS_PAGES_ADD\'))
        {
            return false;
        }
        $aUser = $this->getParam(\'aUser\');

        if ($aUser === null)
        {
            $aUser = $this->getParam(\'aPage\');
        }

        $aUser[\'is_header\'] = true;
        $aUser[\'is_liked\'] = (!isset($aUser[\'is_liked\']) || $aUser[\'is_liked\'] === null || ($aUser[\'is_liked\'] < 1) ) ? false : true;
        if (!isset($aUser[\'user_id\']))
        {
            return false;
        }

        if (!defined(\'PAGE_TIME_LINE\') && !defined(\'PHPFOX_IS_PAGES_VIEW\'))
        {
            
        }
        else if ((isset($aUser[\'use_timeline\']) && $aUser[\'use_timeline\']) || defined(\'PHPFOX_IS_PAGES_VIEW\'))
        {
            $this->request()->set(\'req3\', \'\');

            if (Phpfox::isModule($sModule) && Phpfox::hasCallback($sModule, \'getPageSubMenu\'))
            {
                if (defined(\'PHPFOX_IS_PAGES_VIEW\'))
                {
                    $aPage = $this->getParam(\'aPage\');
                }

                $aMenu = Phpfox::callback($sModule . \'.getPageSubmenu\', (defined(\'PHPFOX_IS_PAGES_VIEW\') ? $aPage : $aUser));

                foreach ($aMenu as $iKey => $aSubMenu)
                {
                    $aMenu[$iKey][\'module\'] = $sModule;
                }

                $this->template()->assign(array(
                    \'aSubMenus\' => $aMenu
                ));
            }
        }
    }
} if (Phpfox::isModule(\'suggestion\') && Phpfox::isUser()){
    $sSuggestToFriends = Phpfox::getPhrase(\'suggestion.suggest_to_friends_2\');
    $sUserName = Phpfox::getUserBy(\'user_name\');
    $sUserName = $this->request()->get(\'req1\');
    $aUser = Phpfox::getService(\'suggestion\')->getUserBy(\'user_name\',$sUserName);
    
    if(is_array($aUser) && count($aUser)>0){
        $iFriendId = $aUser[\'user_id\'];
        $bIsFriend = Phpfox::getService(\'suggestion\')->isMyFriend($iFriendId);        
    }else{
        $iFriendId = Phpfox::getUserId();
        $bIsFriend = false;
    }
    if ($bIsFriend){
    ?>
    <script language="javascript">
    $Behavior.loadProfileHeaderSuggestion = function(){
        if($(\'#suggestion_profile_btn\').length <= 0){
            <?php if (Phpfox::getUserParam(\'suggestion.enable_friend_suggestion\')){?>
                $(\'.profile_header\').find(\'#section_menu\').eq(0).find(\'ul\').eq(0).prepend(\'<li id="suggestion_profile_btn"><a onclick="suggestion_and_recommendation_tb_show(\\\'...\\\',$.ajaxBox(\\\'suggestion.friends\\\',\\\'iFriendId=<?php  echo $iFriendId;?>&sSuggestionType=suggestion&sModule=suggestion_friend\\\')); return false;" href="#"><?php  echo $sSuggestToFriends?></a></li>\');
            <?php }elseif(Phpfox::getUserParam(\'suggestion.enable_friend_recommend\')){?>
                $(\'.profile_header\').find(\'#section_menu\').eq(0).find(\'ul\').eq(0).prepend(\'<li id="suggestion_profile_btn"><a onclick="suggestion_and_recommendation_tb_show(\\\'...\\\',$.ajaxBox(\\\'suggestion.friends\\\',\\\'iFriendId=<?php  echo $iFriendId;?>&sSuggestionType=recommendation\\\')); return false;" href="#"><?php  echo $sSuggestToFriends?></a></li>\');
            <?php }?>            
        }
    };
    </script>
    <?php }?>

<?php } /*end check module*/ '; ?>