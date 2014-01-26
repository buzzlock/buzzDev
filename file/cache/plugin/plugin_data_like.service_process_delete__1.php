<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if($sType == \'advancedphoto\')
{
	if ($iUserId > 0 && $sType == \'pages\')
	{
		if (!Phpfox::getService(\'pages\')->isAdmin($iItemId))
		{				
			return Phpfox_Error::set(\'Unable to remove this user.\');
		}

		$this->database()->delete(Phpfox::getT(\'like\'), \'type_id = \\\'photo\\\' AND item_id = \' . (int) $iItemId . \' AND user_id = \' . $iUserId);
	}
	else
	{
		$iUserId = 0;
		$this->database()->delete(Phpfox::getT(\'like\'), \'type_id = \\\'photo\\\' AND item_id = \' . (int) $iItemId . \' AND user_id = \' . Phpfox::getUserId());
	}
} '; ?>