<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$aRequest = Phpfox::getLib(\'request\')->get(\'core\');
 $sView = Phpfox::getLib(\'request\')->get(\'viewId\'); 
 $iPage = (int)Phpfox::getLib(\'request\')->get(\'page\'); 
 
 if($sView == \'\' || ($sView == \'all\' && !$aRequest[\'is_user_profile\']))
 {
  $iTotalFeeds = (int) Phpfox::getComponentSetting(($bIsProfile > 0 ?  $iUserId : Phpfox::getUserId() ), \'feed.feed_display_limit_\' . ($bIsProfile > 0 ? \'profile\' : \'dashboard\'), Phpfox::getParam(\'feed.feed_display_limit\')); 
 $iCount = Phpfox::getLib(\'database\')->select(\'count(*)\')->from(Phpfox::getT(\'feed\'))->execute(\'getSlaveField\');
 while(empty($aRows) && ($iFeedPage*$iTotalFeeds) < $iCount - $iTotalFeeds)
 {
 $iFeedPage++;
 $aRows = Phpfox::getService(\'feed\')->callback($aFeedCallback)->get(($bIsProfile > 0 ? $iUserId : null), ($this->request()->get(\'feed\') ? $this->request()->get(\'feed\') : null), $iFeedPage);
 }
 
 if (($this->request()->getInt(\'status-id\') 
 || $this->request()->getInt(\'comment-id\') 
 || $this->request()->getInt(\'link-id\')
 || $this->request()->getInt(\'poke-id\')
 ) 
 && isset($aRows[0]))
 {
 $aRows[0][\'feed_view_comment\'] = true;
 $this->setParam(\'aFeed\', array_merge(array(\'feed_display\' => \'view\', \'total_like\' => $aRows[0][\'feed_total_like\']), $aRows[0])); 
 } 
 } //	THIS PLUGIN IS NOT USED '; ?>