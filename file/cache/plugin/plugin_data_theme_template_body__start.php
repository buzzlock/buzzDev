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
<?php defined(\'PHPFOX\') or exit(\'NO DICE!\');

if (\'event\' == Phpfox::getLib(\'request\')->getInt(\'req1\') && $iEventId = Phpfox::getLib(\'request\')->getInt(\'req2\'))
{
	if (Phpfox::isModule(\'socialpublishers\'))
	{
		$sIdCache = Phpfox::getLib(\'cache\') -> set("socialpublishers_feed_" . Phpfox::getUserId());
		$aFeed = Phpfox::getLib(\'cache\') -> get($sIdCache);

		if ($aFeed && isset($aFeed[\'params\']) && $aFeed[\'params\'])
        {
            $aExistSettings = Phpfox::getService(\'socialpublishers.modules\')->getUserModuleSettings(Phpfox::getService(\'socialpublishers\')->getRealUser(Phpfox::getUserId()), \'event\');
            if (!isset($aExistSettings[\'no_ask\']) || $aExistSettings[\'no_ask\'] == 0)
            {
                if (!isset($aFeed[\'is_show\']) || $aFeed[\'is_show\'] == 0)
                {
                    echo "<script type=\\"text/javascript\\">
                        \\$Behavior.loadThemePublisher = function(){
                            window.parent.\\$Core.box(\'socialpublishers.share\', 500);
                        };
                    </script>";
                }
            }
        }
	}
} ?>
<script type="text/javascript">
    oCore[\'core.disable_hash_bang_support\'] = 1;
</script>
<?php '; ?>