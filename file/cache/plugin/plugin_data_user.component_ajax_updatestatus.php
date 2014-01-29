<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = ' if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'facebook\']) & $aVals[\'connection\'][\'facebook\'] == \'1\' && !empty($aVals[\'user_status\']))
	{
		$this->call("FB.api(\'/me/feed\', \'post\', {message: \'" . str_replace(\'\\\'\', \'\\\\\\\'\', html_entity_decode($aVals[\'user_status\'], null, \'UTF-8\')) . "\'}, function(response){});");		
	}
	
	if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'twitter\']) & $aVals[\'connection\'][\'twitter\'] == \'1\' && !empty($aVals[\'user_status\']))
	{		
		Phpfox::getLib(\'twitter\')->post(html_entity_decode($aVals[\'user_status\'], null, \'UTF-8\'));		
	} if(phpfox::isModule(\'socialpublishers\'))
{
    $sUrl = phpfox::getLib(\'url\')->makeUrl(phpfox::getUserBy(\'user_name\'));
    $sType = \'status\';
    $iUserId = phpfox::getUserId();
    $sMessage = html_entity_decode($aVals[\'user_status\']);
    $aVals[\'url\'] = $sUrl;
    $aVals[\'content\'] = $sMessage;
    $aVals[\'title\'] = $sMessage;
    phpfox::getService(\'socialpublishers\')->showPublisher($sType,$iUserId,$aVals);
} '; ?>