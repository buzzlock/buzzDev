<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$tmpVar = \'\';

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
} '; ?>