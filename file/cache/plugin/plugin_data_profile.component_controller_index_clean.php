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
} '; ?>