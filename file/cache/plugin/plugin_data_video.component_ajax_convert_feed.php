<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$aFeed = Phpfox::getService(\'feed\')->get(Phpfox::getUserId(), $iFeedId);

if (isset($aFeed[0]) && isset($aFeed[0][\'feed_id\']))
{
	if ($this->get(\'facebook_connection\') == \'1\')
	{
		$this->call("FB.api(\'/me/feed\', \'post\', {link: \'" . $aFeed[0][\'feed_link\'] . "\', message: \'" . str_replace(\'\\\'\', \'\\\\\\\'\', html_entity_decode($aFeed[0][\'feed_content\'], null, \'UTF-8\')) . "\'}, function(response){});");		
	}

	if ($this->get(\'twitter_connection\') == \'1\')
	{		
		Phpfox::getLib(\'twitter\')->post(html_entity_decode($aFeed[0][\'feed_content\'], null, \'UTF-8\') . \' \' . $aFeed[0][\'feed_link\']);
	}
} $iPageId = Phpfox::getLib(\'session\')->get(\'socialintegration_pageId\');
if(phpfox::isModule(\'socialpublishers\') && ($iPageId ? 1 : !Phpfox::getUserParam(\'video.approve_video_before_display\')))
{
    if($iPageId)
    {
        $aCallBack = array(
			\'module\' => \'pages\',
			\'item_id\' => $iPageId,
			\'table_prefix\' => \'pages_\'
		);	
        $aFeed = Phpfox::getService(\'feed\')->callback($aCallBack)->get(Phpfox::getUserId(), $iFeedId);    
    }
    else
    {
        $aFeed = Phpfox::getService(\'feed\')->get(Phpfox::getUserId(), $iFeedId);    
    }
    if(count($aFeed))
    {
        $aVideo = Phpfox::getService(\'video\')->getVideo($aFeed[0][\'item_id\'], true);
        $sUrl = $aFeed[0][\'feed_link\'];
        $sType = \'video\';
        $iUserId = phpfox::getUserId();
        $sMessage = (isset($aFeed[0][\'feed_status\']) && !empty($aFeed[0][\'feed_status\'])) ? $aFeed[0][\'feed_status\'] : isset($aFeed[0][\'title\']) ? $aFeed[0][\'title\'] : "";        
        $aVals[\'url\'] = $sUrl;
        $aVals[\'content\'] = $sMessage;
        $aVals[\'title\'] = $aVideo[\'title\'];
        $bIsFrame = true;
        phpfox::getService(\'socialpublishers\')->showPublisher($sType,$iUserId,$aVals,$bIsFrame);
    }    
} '; ?>