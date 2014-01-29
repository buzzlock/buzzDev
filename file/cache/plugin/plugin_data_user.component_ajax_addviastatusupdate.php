<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'facebook\']) & $aVals[\'connection\'][\'facebook\'] == \'1\')
	{
		$this->call("FB.api(\'/me/feed\', \'post\', {link: \'" . Phpfox::permalink(\'poll\', $iPollId, $aPoll[\'question\']) . "\', message: \'" . str_replace(\'\\\'\', \'\\\\\\\'\', html_entity_decode($aPoll[\'question\'], null, \'UTF-8\')) . "\'}, function(response){});");		
	}
	
	if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'twitter\']) & $aVals[\'connection\'][\'twitter\'] == \'1\')
	{		
		Phpfox::getLib(\'twitter\')->post(html_entity_decode($aPoll[\'question\'], null, \'UTF-8\') . \' \' . Phpfox::permalink(\'poll\', $iPollId, $aPoll[\'question\']));		
	} if(phpfox::isModule(\'socialpublishers\') && !Phpfox::getUserParam(\'poll.poll_requires_admin_moderation\'))
{
    $sUrl = Phpfox::permalink(\'poll\', $iPollId, $aPoll[\'question\']);
    $sType = \'poll\';
    $iUserId = phpfox::getUserId();
    $sMessage = $aPoll[\'question\'];
    $aVals[\'url\'] = $sUrl;
    $aVals[\'content\'] = $sMessage;
    $aVals[\'title\'] = $sMessage;
    phpfox::getService(\'socialpublishers\')->showPublisher($sType,$iUserId,$aVals);
} '; ?>