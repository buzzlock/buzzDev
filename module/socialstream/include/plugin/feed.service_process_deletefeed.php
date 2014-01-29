<?php  
  defined('PHPFOX') or exit('NO DICE!');
  if($aFeed['type_id'] == 'socialstream_facebook' || $aFeed['type_id'] == 'socialstream_twitter')
  {	
	$bCanDelete = false;
	if (Phpfox::getUserParam('feed.can_delete_own_feed') && ($aFeed['user_id'] == Phpfox::getUserId()))
	{
		$bCanDelete = true;
	}
	
	if (defined('PHPFOX_FEED_CAN_DELETE'))
	{
		$bCanDelete = true;
	}
	
	if (Phpfox::getUserParam('feed.can_delete_other_feeds'))
	{
		$bCanDelete = true;
	}		
  
	if ($bCanDelete === true)
	{
	  $this->database()->delete(Phpfox::getT('socialstream_feeds'),'feed_id=' . (int)$aFeed['item_id']);	
	}	
  }  
?>