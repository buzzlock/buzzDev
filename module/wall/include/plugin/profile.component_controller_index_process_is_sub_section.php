<?php
if(Phpfox::isModule('wall'))
{
	Phpfox::getLib('template')->setHeader("<script type='text/javascript'>if(typeof(oCore) != 'undefined'){ oCore['profile.user_id'] = " . $aRow['user_id'] . "; oCore['profile.is_user_profile'] = 1;}</script>");
	if($sSection == 'wall')
	{
		$bIsSubSection = false;	
	}
}
?>