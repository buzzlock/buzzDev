<?php

$bIsCustomFeedView = PhpFox::getLib('template')->getVar('bIsCustomFeedView');
$sControllerName = Phpfox::getLib('module')->getFullControllerName();
if (!defined('PHPFOX_IN_DESIGN_MODE') && $bIsCustomFeedView == false && Phpfox::isUser() && !Phpfox::isAdminPanel() && Phpfox::isModule('socialstream') && ($sControllerName == 'profile.index' || $sControllerName == 'core.index-member') && !Phpfox::isModule('wall'))
{
    echo "<script type='text/javascript'>$('#js_feed_content').html(''); doFilter('all');</script>";
}
