<?php
    defined('PHPFOX') or exit('NO DICE!');
	$aFeed = $this->getVar('aFeed');
	if(isset($aFeed['social_agent_full_name']) && !empty($aFeed['social_agent_full_name']))
	{
	  echo '<li><span>&middot;</span></li>';
	  echo '<li><a href="'.$aFeed['service_feed_link'].'" title="'.$aFeed['social_agent_full_name'].'" target="_blank">'.$aFeed['social_agent_full_name'].'</a></li>';
	}
?>