<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '/**
 * [PHPFOX_HEADER]
 */

defined(\'PHPFOX\') or exit(\'NO DICE!\');
$oDonation = Phpfox::getService(\'donation\');
$iPageId = $oDonation->getPageIdFromUrl();
if ($iPageId == 0) 
{
    $iPageId = $this->request()->get(\'id\');
}
$iDonation = (int) $oDonation->isEnableDonation($iPageId);
$iCurrentUserId = Phpfox::getUserId();
$iUserId = $oDonation->getUserIdOfPage($iPageId);
$sPageTitle = $oDonation->getPageDetail($iPageId); 
$sUrl = urlencode(Phpfox::getLib(\'url\')->getFullUrl());
$sImg = $oDonation->getDonationButtonImagePath();
if ($iPageId > 0 && $iDonation>0 && Phpfox::isModule(\'donation\')) 
{
    if ($oDonation->checkPermissions(\'can_donate\', array(\'iPageId\' => $iPageId)))
    {
        $sDonation = Phpfox::getPhrase(\'donation.donation_for_page_page_name\', array(\'page_name\'=>$sPageTitle));    
?>
<script type="text/javascript">
    $Behavior.DonationShowInPage = function() {
        $().ready(function(){        
            if($(\'#donateBlock\').html()==null)
                $(".sub_section_menu:first").prepend("<p style=\\"text-align:center\\" id=\\"donateBlock\\"><a class=\\"donate\\" onclick=\\"showDonationIndex(); return false;\\" href=\\"#\\"><img src=\\"<?php echo $sImg;?>\\"/></a></p>");
        });
    }
    
    function showDonationIndex(){     
        tb_show(\'<?php echo $sDonation; ?>\',$.ajaxBox(\'donation.detail\',\'iPageId=<?php echo (int) $iPageId; ?>&sUrl=<?php echo $sUrl; ?>\'));
    }
</script>
<?php } 
} 
else if ($iPageId > 0 && $iUserId == $iCurrentUserId) 
{
    if($oDonation->checkPermissions(\'can_add_donation_on_own_page\', array(\'iPageId\' => $iPageId)))
    {
        $aDonation = $oDonation->getDonationConfig($iPageId, $iUserId);
        if (empty($aDonation)) 
        {   
        ?>
        <script type="text/javascript">
            $Behavior.DonationShowInPage = function() {
                $().ready(function(){        
                    if ($(\'#donateBlock\').html() == null)
                    {
                        $(".sub_section_menu:first").prepend("<p id=\\"donateBlock\\" style=\\"text-align:center\\"> <a class=\\"donate\\"  href=\\"<?php echo Phpfox::getLib(\'url\')->makeUrl(\'pages\',array(\'add\', \'id\' =>$iPageId,\'tab\'=>\'donation\'));?>\\"><?php echo Phpfox::getPhrase(\'donation.donation_setting\'); ?></a></p>");
                        $(".sub_section_menu:first").prepend("<p style=\\"text-align:center\\"> <a class=\\"donate\\"  href=\\"<?php echo Phpfox::getLib(\'url\')->makeUrl(\'pages\',array(\'add\', \'id\' =>$iPageId , \'tab\'=>\'donation\'));?>\\"><img src=\\"<?php echo $sImg;?>\\"/></a></p>");  
                    }
                });
            }
        </script>
<?php   }
    }
} defined(\'PHPFOX\') or exit(\'NO DICE!\');
    $iPageId = $this->request()->get(\'req2\');
    if ($iPageId == 0)
    {
	  $iPageId = $this->request()->get(\'id\');
    }

    Phpfox::getLib(\'session\')->set(\'socialintegration_pageId\', $iPageId); $iPageId = (int)$this->request()->getInt(\'req2\');

if ($iPageId>0 && Phpfox::isModule(\'suggestion\')  && Phpfox::isUser() ){        
    /*get detail pages*/
    
    $aPages = Phpfox::getService(\'pages\')->getPage($iPageId);
	$sTitle = base64_encode(urlencode($aPages[\'title\']));
    
    $sSuggestToFriends = Phpfox::getPhrase(\'suggestion.suggest_to_friends_2\');
    $sCurrentUrl = Phpfox::getLib(\'url\')->getFullUrl(); 
    $iUserId = Phpfox::getUserId();
    ?>
    <script language="javascript">
    $().ready(function(){
            <?php if (Phpfox::getUserParam(\'suggestion.enable_friend_suggestion\')){?>
                $(\'#section_menu\').eq(0).append(\'<ul><li id="suggestion_page_btn"><a onclick="suggestion_and_recommendation_tb_show(\\\'...\\\',$.ajaxBox(\\\'suggestion.friends\\\',\\\'iFriendId=<?php  echo $iPageId;?>&sSuggestionType=suggestion&sModule=suggestion_pages&sLinkCallback=<?php  echo $sCurrentUrl;?>&sTitle=<?php  echo $sTitle;?>&sPrefix=pages_\\\')); return false;" href="#"><?php  echo $sSuggestToFriends?></a></li></ul>\');
            <?php }?>            
    });
    </script>    
    <style>
        #js_is_user_profile #js_is_page #section_menu{min-width: 0px;}
		#section_menu ul{float: right;}
    </style>
<?php } /*end check module*/  '; ?>