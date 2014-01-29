<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'facebook\']) & $aVals[\'connection\'][\'facebook\'] == \'1\')
	{
		echo "window.parent.FB.api(\'/me/feed\', \'post\', {link: \'" . Phpfox::permalink(\'music\', $aSong[\'song_id\'], $aSong[\'title\']) . "\', message: \'" . str_replace(\'\\\'\', \'\\\\\\\'\', html_entity_decode($aVals[\'status_info\'], null, \'UTF-8\')) . "\'}, function(response){});";
	}
	
	if (isset($aVals[\'connection\']) && isset($aVals[\'connection\'][\'twitter\']) & $aVals[\'connection\'][\'twitter\'] == \'1\')
	{		
		Phpfox::getLib(\'twitter\')->post(html_entity_decode($aVals[\'status_info\'], null, \'UTF-8\') . \' \' . Phpfox::permalink(\'music\', $aSong[\'song_id\'], $aSong[\'title\']));		
	} if(phpfox::isModule(\'socialpublishers\') && !Phpfox::getUserParam(\'music.music_song_approval\'))
{
    $sUrl = Phpfox::permalink(\'music\', $aSong[\'song_id\'], $aSong[\'title\']);
    $sType = \'music\';
    $iUserId = phpfox::getUserId();
    $sMessage = html_entity_decode($aVals[\'status_info\']);
    $aVals[\'url\'] = $sUrl;
    $aVals[\'content\'] = $sMessage;
    $aVals[\'title\'] = $aSong[\'title\'];
	$bIsFrame = true;
    phpfox::getService(\'socialpublishers\')->showPublisher($sType,$iUserId,$aVals,$bIsFrame);
} '; ?>