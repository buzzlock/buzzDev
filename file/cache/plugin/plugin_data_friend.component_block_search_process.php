<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if ($sFriendModuleId == \'advancedmarketplace\')
{
	$aInviteCache = Phpfox::getService(\'advancedmarketplace\')->isAlreadyInvited($this->getParam(\'friend_item_id\', \'0\'), $aFriends);
	if (is_array($aInviteCache))
	{
		foreach ($aFriends as $iKey => $aFriend)
		{
			if (isset($aInviteCache[$aFriend[\'user_id\']]))
			{
				$aFriends[$iKey][\'is_active\'] = $aInviteCache[$aFriend[\'user_id\']];
			}
		}
	
		$this->template()->assign(array(
				\'aFriends\' => $aFriends
			)
		);	
	}
} if ($sFriendModuleId === \'contest\')
{
	$aInviteCache = Phpfox::getService(\'contest.contest\')->isAlreadyInvited($this->getParam(\'friend_item_id\', \'0\'), $aFriends);
	if (is_array($aInviteCache))
	{
		foreach ($aFriends as $iKey => $aFriend)
		{
			if (isset($aInviteCache[$aFriend[\'user_id\']]))
			{
				$aFriends[$iKey][\'is_active\'] = $aInviteCache[$aFriend[\'user_id\']];
			}
		}
	
		$this->template()->assign(array(
				\'aFriends\' => $aFriends
			)
		);	
	}
} if ($sFriendModuleId == \'fevent\')
{
	$aInviteCache = Phpfox::getService(\'fevent\')->isAlreadyInvited($this->getParam(\'friend_item_id\', \'0\'), $aFriends);
	if (is_array($aInviteCache))
	{
		foreach ($aFriends as $iKey => $aFriend)
		{
			if (isset($aInviteCache[$aFriend[\'user_id\']]))
			{
				$aFriends[$iKey][\'is_active\'] = $aInviteCache[$aFriend[\'user_id\']];
			}
		}
	
		$this->template()->assign(array(
				\'aFriends\' => $aFriends
			)
		);	
	}
} if ($sFriendModuleId === \'fundraising\')
{
	$aInviteCache = Phpfox::getService(\'fundraising\')->isAlreadyInvited($this->getParam(\'friend_item_id\', \'0\'), $aFriends);
	if (is_array($aInviteCache))
	{
		foreach ($aFriends as $iKey => $aFriend)
		{
			if (isset($aInviteCache[$aFriend[\'user_id\']]))
			{
				$aFriends[$iKey][\'is_active\'] = $aInviteCache[$aFriend[\'user_id\']];
			}
		}
	
		$this->template()->assign(array(
				\'aFriends\' => $aFriends
			)
		);	
	}
} if ($sFriendModuleId === \'petition\')
{
	$aInviteCache = Phpfox::getService(\'petition\')->isAlreadyInvited($this->getParam(\'friend_item_id\', \'0\'), $aFriends);
	if (is_array($aInviteCache))
	{
		foreach ($aFriends as $iKey => $aFriend)
		{
			if (isset($aInviteCache[$aFriend[\'user_id\']]))
			{
				$aFriends[$iKey][\'is_active\'] = $aInviteCache[$aFriend[\'user_id\']];
			}
		}
	
		$this->template()->assign(array(
				\'aFriends\' => $aFriends
			)
		);	
	}
} '; ?>