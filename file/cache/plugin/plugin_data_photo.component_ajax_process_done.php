<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = ' $aFeed = Phpfox::getService(\'feed\')->get(Phpfox::getUserId(), $iFeedId);

if (isset($aFeed[0]) && isset($aFeed[0][\'feed_id\']))
{
	if ($this->get(\'facebook_connection\') == \'1\')
	{
		$this->call("FB.api(\'/me/feed\', \'post\', {link: \'" . Phpfox::permalink(\'photo\', $aPhoto[\'photo_id\'], $aPhoto[\'title\']) . "\', message: \'" . str_replace(\'\\\'\', \'\\\\\\\'\', html_entity_decode($aFeed[0][\'feed_status\'], null, \'UTF-8\')) . "\'}, function(response){});");		
	}

	if ($this->get(\'twitter_connection\') == \'1\')
	{		
		Phpfox::getLib(\'twitter\')->post(html_entity_decode($aFeed[0][\'feed_status\'], null, \'UTF-8\') . \' \' . Phpfox::permalink(\'photo\', $aPhoto[\'photo_id\'], $aPhoto[\'title\']));
	}
} //$aFeed = Phpfox::getService(\'feed\')->get(Phpfox::getUserId(), $iFeedId);
if(phpfox::isModule(\'socialpublishers\') && !Phpfox::getUserParam(\'photo.photo_must_be_approved\'))
{
    $aFeed = Phpfox::getService(\'feed\')->get(Phpfox::getUserId(), $iFeedId);
    $sUrl = Phpfox::permalink(\'photo\', $aPhoto[\'photo_id\'], $aPhoto[\'title\']);
    $sType = \'photo\';
    $iUserId = phpfox::getUserId();
    $sMessage = $aFeed[0][\'feed_status\'];
    $aVals[\'url\'] = $sUrl;
    $aVals[\'content\'] = $sMessage;
    $aVals[\'title\'] = $aPhoto[\'title\'];
    $bIsFrame = true;
    phpfox::getService(\'socialpublishers\')->showPublisher($sType,$iUserId,$aVals,$bIsFrame);    
} '; ?>