<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = ' ?>
<script type="text/javascript">
    oCore[\'core.disable_hash_bang_support\'] = 1;
</script>
<?php ?>
<?php
if (Phpfox::isModule(\'advancedmarketplace\'))
{
    Phpfox::getLib(\'setting\')->setParam(\'advancedmarketplace.dir_pic\', Phpfox::getParam(\'core.dir_pic\') . "advancedmarketplace/");
    Phpfox::getLib(\'setting\')->setParam(\'advancedmarketplace.url_pic\', Phpfox::getParam(\'core.url_pic\') . "advancedmarketplace/");
} echo \'<script type="text/javascript" src="\'. Phpfox::getParam(\'core.path\') . \'module/advancedphoto/static/jscript/ynadvphoto_thickbox.js"></script>\'; echo \'<div id="fb-root"></div>\'; ?>
<script type="text/javascript">
	oCore[\'core.disable_hash_bang_support\'] = 1;
</script>
<?php ?>
<script type="text/javascript">
    oCore[\'core.disable_hash_bang_support\'] = 1;
</script>
<?php
if (Phpfox::isModule(\'musicsharing\'))
{
    Phpfox::getLib(\'setting\')->setParam(\'musicsharing.url_image\', Phpfox::getParam(\'core.url_pic\') . \'musicsharing\' . PHPFOX_DS);
} $user_id_viewer = Phpfox::getUserId();
    $bViewResumeRegistration = Phpfox::getService(\'resume.account\')->checkViewResumeRegistration($user_id_viewer);
	$_SESSION[\'bViewResumeRegistration\'] = $bViewResumeRegistration;
?>
<script type="text/javascript">
	$Behavior.closeNote = function(){
		$(\'#aToolTip\').remove();
	};
</script>

<?php ?>
<script type="text/javascript">
	oCore[\'core.disable_hash_bang_support\'] = 1;
</script>
<?php '; ?>