<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$aCompany = Phpfox::getService(\'jobposting.company\')->getCompany(Phpfox::getUserId());	
	if(isset($aCompany[\'company_id\']))
		$jobmenu = 1;
	else
		$jobmenu = 0;
	$core_path = Phpfox::getParam("core.path");
	$icon = $core_path.\'theme/frontend/default/style/default/image/layout/section_menu_add.png\';
	$title = Phpfox::getPhrase(\'jobposting.create_your_company\');
	$url = Phpfox::getLib(\'url\')->makeUrl(\'jobposting.company.add\');
	if($jobmenu==1)
	{
		$title = Phpfox::getPhrase(\'jobposting.create_a_new_job\');
		$url = Phpfox::getLib(\'url\')->makeUrl(\'jobposting.add\');
	}
	
?>

<script type="text/javascript">
	$Behavior.ynjpAddButton = function() {
		if($(\'#btnAdd\').length)
		{
			//	do nothing
		} else 
		{
			var $section_menu = $(\'#breadcrumb_content\');
			if($section_menu.length==0)
				$section_menu = $(\'.main_breadcrumb_holder\');
			if($(\'#section_menu\').length==0)
			{
				$section_menu.append("<div id=\'section_menu\'><ul><li class=\'jobposting_plugin\'><a id=\'btnAdd\' href=\'<?php echo $url; ?>\'><img class=\'v_middle\' alt=\'\' src=\'<?php echo $icon; ?>\'><?php echo $title; ?></a></li></ul></div>");
			}	
			else
			{
				$section_menu.find(\'ul:last\').append("<li class=\'jobposting_plugin\'><a id=\'btnAdd\' href=\'<?php echo $url; ?>\'><img class=\'v_middle\' alt=\'\' src=\'<?php echo $icon; ?>\'><?php echo $title; ?></a></li>");	
			}
		}		
	}
</script> '; ?>