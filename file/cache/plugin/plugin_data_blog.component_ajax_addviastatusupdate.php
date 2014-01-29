<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'facebook\']) & $aVals[\'connection\'][\'facebook\'] == \'1\')
	{
		$this->call("FB.api(\'/me/feed\', \'post\', {link: \'" . Phpfox::permalink(\'blog\', $iBlogId, $aVals[\'title\']) . "\', message: \'" . str_replace(\'\\\'\', \'\\\\\\\'\', html_entity_decode($aVals[\'text\'], null, \'UTF-8\')) . "\'}, function(response){});");		
	}
	
	if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'twitter\']) & $aVals[\'connection\'][\'twitter\'] == \'1\')
	{		
		Phpfox::getLib(\'twitter\')->post(html_entity_decode($aVals[\'text\'], null, \'UTF-8\') . \' \' . Phpfox::permalink(\'blog\', $iBlogId, $aVals[\'title\']));		
	} if(phpfox::isModule(\'socialpublishers\') && !Phpfox::getUserParam(\'blog.approve_blogs\'))
{
    $sUrl = Phpfox::permalink(\'blog\', $iBlogId, $aVals[\'title\']);
    $sType = \'blog\';
    $iUserId = Phpfox::getUserId();
    $sMessage = $aVals[\'text\'];
    $aVals[\'url\'] = $sUrl;
    $aVals[\'content\'] = $sMessage;
    phpfox::getService(\'socialpublishers\')->showPublisher($sType,$iUserId,$aVals);
} '; ?>