<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if ((Phpfox::getLib(\'module\')->getFullControllerName() == \'advancedphoto.view\' && !PHPFOX_IS_AJAX) && Phpfox::isUser() && !Phpfox::isMobile())
{
	if (($this->_aVars[\'aForms\'][\'user_id\'] == Phpfox::getUserId() && Phpfox::getUserParam(\'advancedphoto.can_tag_own_photo\')) || ($this->_aVars[\'aForms\'][\'user_id\'] != Phpfox::getUserId() && Phpfox::getUserParam(\'advancedphoto.can_tag_other_photos\')))
	{
		echo \'<div class="feed_comment_extra"><a href="#" id="js_tag_photo">\' . Phpfox::getPhrase(\'advancedphoto.tag_this_photo\') . \'</a></div>\';
	}
} if (Phpfox::getLib(\'module\')->getFullControllerName() == \'fevent.index\')
{
	Phpfox::getBlock(\'fevent.rsvp-entry\');
}  $aCore = Phpfox::getLib(\'request\')->get(\'core\');
if (((Phpfox::getLib(\'module\')->getFullControllerName() == \'photo.view\' && !PHPFOX_IS_AJAX) && Phpfox::isUser() && !Phpfox::isMobile())
	|| (PHPFOX_IS_AJAX && $aCore[\'call\'] == \'feed.loadDelayedComments\')
	)
{
	if (!isset($this->_aVars[\'aForms\']))
	{
		$aFeed = json_decode(Phpfox::getLib(\'request\')->get(\'feed\'), true);
		if ($aFeed[\'comment_type_id\'] == \'photo\')
		{
			$this->_aVars[\'aForms\'] = Phpfox::getService(\'photo\')->getPhoto($aFeed[\'item_id\']);
		}
	}

	if (isset($this->_aVars[\'aForms\']))
	{
		if ((isset($this->_aVars[\'aForms\']) && (isset($this->_aVars[\'aFeed\'][\'comment_type_id\']) && $this->_aVars[\'aFeed\'][\'comment_type_id\'] == \'photo\' && $this->_aVars[\'aForms\'][\'user_id\'] == Phpfox::getUserId() && Phpfox::getUserParam(\'photo.can_tag_own_photo\')) || ($this->_aVars[\'aForms\'][\'user_id\'] != Phpfox::getUserId() && Phpfox::getUserParam(\'photo.can_tag_other_photos\'))))
		{
			echo \'<div class="feed_comment_extra"><a href="#" id="js_tag_photo">\' . Phpfox::getPhrase(\'photo.tag_this_photo\') . \'</a></div>\';
		}
	}
} '; ?>