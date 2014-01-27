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
} '; ?>