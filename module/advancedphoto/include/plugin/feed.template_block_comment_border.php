<?php
if ((Phpfox::getLib('module')->getFullControllerName() == 'advancedphoto.view' && !PHPFOX_IS_AJAX) && Phpfox::isUser() && !Phpfox::isMobile())
{
	if (($this->_aVars['aForms']['user_id'] == Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.can_tag_own_photo')) || ($this->_aVars['aForms']['user_id'] != Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.can_tag_other_photos')))
	{
		echo '<div class="feed_comment_extra"><a href="#" id="js_tag_photo">' . Phpfox::getPhrase('advancedphoto.tag_this_photo') . '</a></div>';
	}
}
?>