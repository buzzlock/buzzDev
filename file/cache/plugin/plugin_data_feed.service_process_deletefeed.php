<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::getLib(\'request\')->get(\'module\') == \'fevent\')
{
	$aEvent = Phpfox::getService(\'fevent\')->getForEdit($aFeed[\'parent_user_id\'], true);
	if (isset($aEvent[\'event_id\']) && $aEvent[\'user_id\'] == Phpfox::getUserId())
	{
		define(\'PHPFOX_FEED_CAN_DELETE\', true);
	}
} if (Phpfox::getLib(\'request\')->get(\'module\') == \'pages\')
{
	$aPage = Phpfox::getService(\'pages\')->getPage($aFeed[\'parent_user_id\']);
	if (isset($aPage[\'page_id\']) && Phpfox::getService(\'pages\')->isAdmin($aPage))
	{
		define(\'PHPFOX_FEED_CAN_DELETE\', true);
	}
} defined(\'PHPFOX\') or exit(\'NO DICE!\');
  if($aFeed[\'type_id\'] == \'socialstream_facebook\' || $aFeed[\'type_id\'] == \'socialstream_twitter\')
  {	
	$bCanDelete = false;
	if (Phpfox::getUserParam(\'feed.can_delete_own_feed\') && ($aFeed[\'user_id\'] == Phpfox::getUserId()))
	{
		$bCanDelete = true;
	}
	
	if (defined(\'PHPFOX_FEED_CAN_DELETE\'))
	{
		$bCanDelete = true;
	}
	
	if (Phpfox::getUserParam(\'feed.can_delete_other_feeds\'))
	{
		$bCanDelete = true;
	}		
  
	if ($bCanDelete === true)
	{
	  $this->database()->delete(Phpfox::getT(\'socialstream_feeds\'),\'feed_id=\' . (int)$aFeed[\'item_id\']);	
	}	
  } if (Phpfox::isModule(\'suggestion\') && Phpfox::isUser()){
    
    $sTypeId = $aFeed[\'type_id\'];
    $aTypeId = explode(\'_\', $sTypeId);

    $iUserId = $aFeed[\'user_id\'];
    $sModule = \'suggestion_\' . $aTypeId[0];
    $iItemId = $aFeed[\'item_id\'];

    /*delete each suggestion notification detail*/
    $aSuggestionList = Phpfox::getService(\'suggestion\')->getSuggestionListByUserId($iUserId, $iItemId, $sModule);
    if (count($aSuggestionList)>0){
        foreach($aSuggestionList as $aSuggestion){
            $iSuggestionId = $aSuggestion[\'suggestion_id\'];
            Phpfox::getService(\'suggestion.process\')->deleteNotification($sModule, $iItemId, $iSuggestionId);
        }
    }
    Phpfox::getService(\'suggestion.process\')->delete($iUserId, $sModule, $iItemId);
    
    
} /*end check module*/ if (Phpfox::getLib(\'request\')->get(\'module\') == \'\' && $aFeed[\'parent_user_id\'] == Phpfox::getUserId())
{
	define(\'PHPFOX_FEED_CAN_DELETE\', true);
} '; ?>