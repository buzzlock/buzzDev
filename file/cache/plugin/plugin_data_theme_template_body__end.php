<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::getParam(\'facebook.enable_facebook_connect\'))
{
	// echo \'<div id="fb-root"></div>\';
} if (Phpfox::isUser() && !Phpfox::isAdminPanel() && Phpfox::isModule(\'notification\'))
{
    $iDelayTime = (int) Phpfox::getParam(\'fanot.display_notification_seconds\') * 1000;
    $iRefreshTime = (int) Phpfox::getParam(\'fanot.notification_refresh_time\') * 1000;
    $str = \'\';
	$str .= \'<div id="fanot_box" class="fanotui"></div>\';
	
	echo $str;

	?>

    <script language="javascript">        
        $Behavior.ynfanotInitVar = function(){
        	$Core.fanot.fanotDelay = <?php echo abs($iDelayTime); ?>;
			$Core.fanot.fanotUpdateDelay = <?php echo abs($iRefreshTime); ?>;
        };    
    </script>
	
	<?php
	
} if(Phpfox::isAdminPanel())
{
    ?>
    <script type="text/javascript">
    $Behavior.fixFeventMenu = function(){
        $("div.main_sub_menu_holder_header").each(function(i,e){
            if(e.innerHTML == \'Fevent\'){
                e.innerHTML = \'Advanced Event\';
                return;
            }
        });
    }
    </script>
    <?php
} if (Phpfox::getParam(\'janrain.enable_janrain_login\'))
{
	echo "


<script type=\\"text/javascript\\">
(function() {
    if (typeof window.janrain !== \'object\') window.janrain = {};
    if (typeof window.janrain.settings !== \'object\') window.janrain.settings = {};
    
    janrain.settings.tokenUrl = \'" . Phpfox::getLib(\'url\')->makeUrl(\'janrain.rpx\') . "\';

    function isReady() { janrain.ready = true; };
    if (document.addEventListener) {
      document.addEventListener(\\"DOMContentLoaded\\", isReady, false);
    } else {
      window.attachEvent(\'onload\', isReady);
    }

    var e = document.createElement(\'script\');
    e.type = \'text/javascript\';
    e.id = \'janrainAuthWidget\';

    if (document.location.protocol === \'https:\') {
      e.src = \'https://rpxnow.com/js/lib/" . Phpfox::getService(\'janrain\')->getName() . "/engage.js\';
    } else {
      e.src = \'http://widget-cdn.rpxnow.com/js/lib/" . Phpfox::getService(\'janrain\')->getName() . "/engage.js\';
    }

    var s = document.getElementsByTagName(\'script\')[0];
    s.parentNode.insertBefore(e, s);
})();
</script>

			
			
			";
} if(Phpfox::isModule(\'socialmediaimporter\') && defined(\'PHPFOX_IS_PAGES_VIEW\') && defined(\'PAGE_TIME_LINE\') && Phpfox::getLib(\'request\')->get(\'req3\')==\'photo\')
{
?>
<script type="text/javascript">
    $Behavior.createImportPhotoButton = function()
    {
        $(\'.profile_header_timeline\').find(\'#section_menu>ul:last\').append(\'<li><a href="<?php echo Phpfox::getLib(\'url\')->makeUrl(\'socialmediaimporter.connect\'); ?>" class="ajax_link"><?php echo Phpfox::getPhrase(\'socialmediaimporter.import_photos\'); ?></a></li>\');
    };
</script>
<?php
} '; ?>