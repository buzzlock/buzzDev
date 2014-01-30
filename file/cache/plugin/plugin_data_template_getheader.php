<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'defined(\'PHPFOX\') or exit(\'NO DICE!\');

if (!Phpfox::isMobile() && Phpfox::isUser() && !Phpfox::isAdminPanel() && Phpfox::isModule(\'notification\'))
{
        PhpFox::getLib(\'template\')->setHeader(array(
            \'fanot.js\' => \'module_fanot\', 
			\'fanot.css\' => \'module_fanot\'
                )
        );
} if (Phpfox::isModule(\'fevent\') && !Phpfox::isAdminPanel())
{
    $sJdpickerPhrases = Phpfox::getService(\'fevent\')->getJdpickerPhrases();
    Phpfox::getLib(\'template\')->setHeader(array(
        \'<script type="text/javascript">\'.$sJdpickerPhrases.\'</script>\',
        \'jquery.jdpicker.js\' => \'module_fevent\'
    ));
} $tmpVar = \'\';

if (Phpfox::isMobile()) 
{
    $aActiveStyle = Phpfox::getService(\'mobiletemplate\')->getActiveMobileStyle();
	if(isset($aActiveStyle) && isset($aActiveStyle[\'style_id\']) && $aActiveStyle[\'style_parent_id\'] > 0)
	{
		$aStyleExtend = Phpfox::getService(\'mobiletemplate\')->getParentStyleFolderByParentID($aActiveStyle);
		if(isset($aStyleExtend) && isset($aStyleExtend[\'parent_style_folder\']))
		{
			$aActiveStyle[\'parent_style_folder\'] = $aStyleExtend[\'parent_style_folder\'];
		}
	}
	
    $this->setStyle($aActiveStyle);
} /*
if (Phpfox::getLib(\'request\')->get(\'req1\') == \'videochannel\')
{	
	$bCanUploadVideo = Phpfox::getUserParam(\'videochannel.can_upload_videos\');
	if(empty($bCanUploadVideo))
	{
		echo "<style>#section_menu ul li:first-child{display:none;}</style>";			
	}
	$bCanAddChannel = Phpfox::getUserParam(\'videochannel.can_add_channels\');
	if(empty($bCanAddChannel))
	{
		echo "<style>#section_menu ul li:last-child{display:none;}</style>";
	}
}
*/ defined(\'PHPFOX\') or exit(\'NO DICE!\');

if ((Phpfox::getLib(\'module\')->getFullControllerName() != \'pages.view\' )) 
{
	if (
		( Phpfox::getLib(\'module\')->getFullControllerName() == \'core.index-member\' 
			|| Phpfox::getLib(\'module\')->getFullControllerName() == \'feed.index\' 
			|| Phpfox::getLib(\'module\')->getFullControllerName() == \'profile.index\'
		)
		|| (Phpfox::getParam(\'core.wysiwyg\') == \'default\')
	) {
		if (Phpfox::isModule(\'wall\'))
		{
				echo " <script type=\\"text/javascript\\"> 
				var addImg = \'" . Phpfox::getLib(\'image.helper\')->display(array(\'theme\' => \'ajax/add.gif\', \'class\' => \'v_middle\')) . "\';
				var isDoFilterAdvWall = false; 
				var isReloadActivityFeedAdvWall = false;
				 </script> ";
					
				PhpFox::getLib(\'template\')->setHeader(array(
					\'jquery.caret.1.02.js\' => \'module_wall\',
					\'wall.js\' => \'module_wall\',
					\'extra.js\' => \'module_wall\',
					\'wall.css\' => \'module_wall\'
						)
				);
		}
	}
} '; ?>