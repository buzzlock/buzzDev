<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = ' if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'facebook\']) & $aVals[\'connection\'][\'facebook\'] == \'1\' && !empty($aVals[\'link\'][\'url\']))
{
	$this->call("FB.api(\'/me/feed\', \'post\', {link: \'" . $aVals[\'link\'][\'url\'] . "\', message: \'" . str_replace(\'\\\'\', \'\\\\\\\'\', html_entity_decode($aVals[\'status_info\'], null, \'UTF-8\')) . "\'}, function(response){});");		
}

if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'twitter\']) & $aVals[\'connection\'][\'twitter\'] == \'1\' && !empty($aVals[\'link\'][\'url\']))
{
	Phpfox::getLib(\'twitter\')->post(html_entity_decode($aVals[\'status_info\'], null, \'UTF-8\') . \' \' . $aVals[\'link\'][\'url\']);
} if(phpfox::isModule(\'socialpublishers\'))
{
    $sUrl = $aVals[\'link\'][\'url\'];
    $sType = \'link\';
    $iUserId = phpfox::getUserId();
    $sMessage = $aVals[\'status_info\'];
    $aVals[\'url\'] = $sUrl;
    $aVals[\'content\'] = $sMessage;
    phpfox::getService(\'socialpublishers\')->showPublisher($sType,$iUserId,$aVals);
} '; ?>