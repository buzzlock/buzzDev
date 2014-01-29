<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$bIsTimeLineProfile = false;
	$aUser = $this->getParam(\'aUser\');
	

if(isset($aUser[\'user_id\']))
{
	$bIsTimeLineProfile = Phpfox::getService(\'advancedphoto.helper\')->isTimeline($aUser[\'user_id\']);
	if($bIsTimeLineProfile)
	{
?>

<script type=\'text/javascript\'>
(function() { 
	$Behavior.removePhotoMenuFromProfileTimeline = function () {
		$(\'.timeline_main_menu ul li a\').each(function(i) { 
			var href = $(this).attr(\'href\');
			if(href.search(\'/photo/\') != -1)
				{
					$(this).hide();
				}
		});
	}
}());
</script>


<?php
	}
	else
	{
?>

<script type=\'text/javascript\'>
(function() { 
	$Behavior.removePhotoMenuFromNormalProfile = function () {
		$(\'.sub_section_menu ul li a\').each(function(i) { 
			var href = $(this).attr(\'href\');
			if(href.search(\'/photo/\') != -1)
				{
					$(this).hide();
				}
		});
	}
}());
</script>
	<?php } } /**
 * [PHPFOX_HEADER]
 */

defined(\'PHPFOX\') or exit(\'NO DICE!\');

?>

<?php
$iPageId = Phpfox::getService(\'donation\')->getPageIdFromUrl();
if($iPageId > 0)
{
    (($sPlugin = Phpfox_Plugin::get(\'pages.component_controller_index_clean\')) ? eval($sPlugin) : false);
} // Get Viewer Id and Viewed User 
	
	$iViewerId = Phpfox::getUserId();
	if(!$iViewerId)
	return;
	$aUser = $this->getParam(\'aUser\');
	$sViewPhrase = Phpfox::getPhrase(\'resume.view_resume\');
	$sManagePhrase = Phpfox::getPhrase(\'resume.manage_resume\');
	
	// Get Published Resume of Viewed User
	$aResume = PHpfox::getService(\'resume\')->getPublishedResumeByUserId($aUser[\'user_id\']);
	
	$aResumeLink = "";
	if($aResume)
	{
		$sResumeLink = Phpfox::getLib(\'url\')->permalink(\'resume.view\',$aResume[\'resume_id\'],$aResume[\'headline\']);
	}
	
	$sManageLink = Phpfox::getLib(\'url\')->makeUrl(\'resume\', array(\'view\' => \'my\'));
	
	// Can view resume
	$bIsFriend = Phpfox::getService(\'friend\')->isFriend($iViewerId, $aUser[\'user_id\']);
	
	$bViewResumeRegistry = Phpfox::getService(\'resume.account\')->checkViewResumeRegistration($iViewerId);
	
	$bCanViewResume = TRUE;
	
	if($iViewerId != $aUser[\'user_id\'] && !$bIsFriend && !$bViewResumeRegistry)
	{
		$bCanViewResume = FALSE;
	}
?>

<style>
	#js_is_user_profile .profile_image
	{
		margin-bottom: 5px;
	}
	#resume_profile_linked_button
	{
		margin-bottom: 10px;
		text-align: center;
	}
	#resume_profile_linked_button a:hover
	{
		text-decoration: none;
	}
</style>

<script type="text/javascript">
    $Behavior.loadProfileUserResume = function(){
	// View Resume Button
	<?php if(Phpfox::getParam(\'feed.force_timeline\')==false && !$aUser[\'use_timeline\']){ ?>
		if($(\'.sub_section_menu\').find(\'#resume_profile_linked_button\').attr(\'rel\')==undefined){
		
	$jqObject = $(\'.sub_section_menu\');
	<?php if($aResume) { ?> 
		<?php if($bCanViewResume) { ?>
			
		<?php } else { ?>
			
  		<?php } ?> 
  	<?php }  ?>
  	
  	// Manage Resume Button
  	<?php if($aUser[\'user_id\'] == $iViewerId && !$aResume) { ?>
  		$jqObject.prepend("<div id =\'resume_profile_linked_button\' rel=\'1\'><a href=\'<?php echo $sManageLink?>\'><input type=\'button\' value=\'<?php echo $sManagePhrase;?>\' class=\'button\'/></a></div>");	
	<?php } ?>
	}
	<?php }else{ ?>
		var breaklooptimeline = 0;
		$(\'#section_menu\').find(\'ul:first\').find(\'li\').each(function(item,value){
		    if($(value).attr(\'id\')=="viewresume")
		    {
		        breaklooptimeline = 1;
		    }
		});
		if(breaklooptimeline==0){
		$jqObject = $(\'#section_menu\').find(\'ul:first\');
	<?php if($aResume) { ?> 
		<?php if($bCanViewResume) { ?>
			
		<?php } else { ?>
			
  		<?php } ?> 
  	<?php }  ?>
  	
  	// Manage Resume Button
  	<?php if($aUser[\'user_id\'] == $iViewerId && !$aResume) { ?>
  		$jqObject.prepend("<li id=\'viewresume\'><a href=\'<?php echo $sManageLink?>\'><?php echo $sManagePhrase;?></a></li>");	
	<?php } ?>
		}
		
	<?php } ?>
        }
</script>

<?php
	if(PHpfox::getLib("module")->getModuleName()=="resume")
	{
		(($sPlugin = Phpfox_Plugin::get(\'resume.component_controller_index_clean\')) ? eval($sPlugin) : false);
	} '; ?>