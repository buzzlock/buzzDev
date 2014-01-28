<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if(PHPFOX::getLib("request")->get("req1") == "advancedmarketplace") { ?>
	<?php if(phpfox::getParam(\'advancedmarketplace.can_print_a_listing\') == true) {?>
	<li><span>&middot;</span></li>
	<li>
		<a class="remove_otheraction" target="_blank" href="<?php echo PHPFOX::getLib("url")->permalink(\'advancedmarketplace.print\', $this->_aVars["aListing"]["listing_id"]); ?>">
			<?php echo PHPFOX::getPhrase("advancedmarketplace.print"); ?>
		</a>
	</li>
	<?php } ?>
	<?php if (((int)PHPFOX::getUserId()) !== ((int)$this->_aVars["aListing"]["user_id"])) { ?>
		<li><span>&middot;</span></li>
		<li>
			<a class="remove_otheraction" target="_blank" onclick="tb_show(\'<?php echo PHPFOX::getPhrase("advancedmarketplace.rating"); ?>\', $.ajaxBox(\'advancedmarketplace.ratePopup\', \'height=300&width=550&id=<?php echo $this->_aVars["aListing"]["listing_id"]; ?>\')); return false;" href="#">
				<?php echo PHPFOX::getPhrase("advancedmarketplace.review"); ?>
			</a>
		</li>
		<li><span>&middot;</span></li>
		<li>
			<a class="remove_otheraction" target="_blank" onclick="$Core.composeMessage({user_id: <?php echo $this->_aVars["aListing"]["user_id"]; ?>}); return false;" href="#">
				<?php echo PHPFOX::getPhrase("advancedmarketplace.contact_seller"); ?>
			</a>
		</li>
	<?php } ?>
<?php } if (isset($this->_aVars[\'aFeed\']) && isset($this->_aVars[\'aFeed\'][\'comment_type_id\']) && $this->_aVars[\'aFeed\'][\'comment_type_id\'] == \'advancedphoto\' && isset($this->_aVars[\'aFeed\'][\'feed_display\']) && $this->_aVars[\'aFeed\'][\'feed_display\'] == \'view\')
{	
	if (isset($this->_aVars[\'aForms\']) && $this->_aVars[\'aForms\'][\'allow_download\'] == \'1\')
	{
		echo \'<li><span>&middot;</span></li>\';
		echo \'<li><a href="\' . Phpfox::permalink(array(\'advancedphoto\', \'download\'), $this->_aVars[\'aForms\'][\'photo_id\'], $this->_aVars[\'aForms\'][\'title\']) . \'" class="no_ajax_link">\' . Phpfox::getPhrase(\'advancedphoto.download\') . \'</a></li>\';		
	}	
	if (Phpfox::getUserParam(\'advancedphoto.can_view_all_photo_sizes\'))
	{
		echo \'<li><span>&middot;</span></li>\';
		// echo \'<li><a href="#" onclick="js_box_remove(this); $Core.box(\\\'advancedphoto.viewAllSizes\\\', \\\'full\\\', \\\'id=\' . $this->_aVars[\'aForms\'][\'photo_id\'] . \'\\\'); return false;">View All Sizes</a></li>\';
		echo \'<li><a href="\' . Phpfox::permalink(array(\'advancedphoto\', \'all\'), $this->_aVars[\'aForms\'][\'photo_id\'], $this->_aVars[\'aForms\'][\'title\']) . \'" class="no_ajax_link">\' . Phpfox::getPhrase(\'advancedphoto.view_all_sizes\') . \'</a></li>\';
	}
} if (isset($this->_aVars[\'aFeed\']) && isset($this->_aVars[\'aFeed\'][\'comment_type_id\']) && $this->_aVars[\'aFeed\'][\'comment_type_id\'] == \'photo\' && isset($this->_aVars[\'aFeed\'][\'feed_display\']) && $this->_aVars[\'aFeed\'][\'feed_display\'] == \'view\')
{
	if (isset($this->_aVars[\'aForms\']) && $this->_aVars[\'aForms\'][\'allow_download\'] == \'1\')
	{
		echo \'<li><span>&middot;</span></li>\';
		echo \'<li><a href="\' . Phpfox::permalink(array(\'photo\', \'download\'), $this->_aVars[\'aForms\'][\'photo_id\'], $this->_aVars[\'aForms\'][\'title\']) . \'" class="no_ajax_link">\' . Phpfox::getPhrase(\'photo.download\') . \'</a></li>\';		
	}	
	if (isset($this->_aVars[\'aForms\']) && Phpfox::getUserParam(\'photo.can_view_all_photo_sizes\') && !Phpfox::isMobile())
	{
		echo \'<li><span>&middot;</span></li>\';
		// echo \'<li><a href="#" onclick="js_box_remove(this); $Core.box(\\\'photo.viewAllSizes\\\', \\\'full\\\', \\\'id=\' . $this->_aVars[\'aForms\'][\'photo_id\'] . \'\\\'); return false;">View All Sizes</a></li>\';
		echo \'<li><a href="\' . Phpfox::permalink(array(\'photo\', \'all\'), $this->_aVars[\'aForms\'][\'photo_id\'], $this->_aVars[\'aForms\'][\'title\']) . \'" class="no_ajax_link">\' . Phpfox::getPhrase(\'photo.view_all_sizes\') . \'</a></li>\';
	}
} '; ?>