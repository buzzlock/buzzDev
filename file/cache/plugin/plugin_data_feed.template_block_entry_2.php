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
} /* Nothing to show */ defined(\'PHPFOX\') or exit(\'NO DICE!\');
	$aFeed = $this->getVar(\'aFeed\');
	if(isset($aFeed[\'social_agent_full_name\']) && !empty($aFeed[\'social_agent_full_name\']))
	{
	  echo \'<li><span>&middot;</span></li>\';
	  echo \'<li><a href="\'.$aFeed[\'service_feed_link\'].\'" title="\'.$aFeed[\'social_agent_full_name\'].\'" target="_blank">\'.$aFeed[\'social_agent_full_name\'].\'</a></li>\';
	} if (Phpfox::isModule(\'suggestion\') && (!PHpfox::isMobile() || (PHpfox::isMobile() && PHpfox::getParam(\'suggestion.support_mobile\'))) && Phpfox::isUser()) {
    /* not support pages_comment */
    if ((isset($this->_aVars[\'aFeed\'][\'type_id\']) && $this->_aVars[\'aFeed\'][\'type_id\'] != \'pages_comment\') || !isset($this->_aVars[\'aFeed\'][\'type_id\'])) {
        if (isset($this->_aVars[\'aFeed\'][\'type_id\'])) {
            $sModule = $this->_aVars[\'aFeed\'][\'type_id\'];
        } else {
            $sModule = $this->_aVars[\'aFeed\'][\'like_type_id\'];
            $this->_aVars[\'aFeed\'][\'type_id\'] = \'\';
        }
        
        $aModule = explode("_", $sModule);
        
        if (!Phpfox::getService(\'suggestion\')->isNotificationMessage($sModule) && Phpfox::getService(\'suggestion\')->isSupportModule($aModule[0]) && Phpfox::getUserParam(\'suggestion.enable_friend_suggestion\')) {
            
            $iId = rand(0, 10000000);
            /* $this->_aVars[\'aTransactions\'] */
            $iFeedId = $this->_aVars[\'aFeed\'][\'feed_id\'];

           if(isset($this->_aVars[\'aFeed\'][\'type_id\']) && $this->_aVars[\'aFeed\'][\'type_id\']!=\'event\' && $this->_aVars[\'aFeed\'][\'type_id\']!=\'fevent\' && $this->_aVars[\'aFeed\'][\'type_id\']!=\'forum\' && $this->_aVars[\'aFeed\'][\'type_id\']!=\'forum_reply\')
                (!isset($this->_aVars[\'aFeed\'][\'comment_type_id\']) ? $this->_aVars[\'aFeed\'][\'comment_type_id\'] = 0 : true);
            if(isset($this->_aVars[\'aFeed\'][\'type_id\']) && $this->_aVars[\'aFeed\'][\'type_id\']==\'blog\')
            {
                if(isset($this->_aVars[\'aFeed\'][\'blog_id\']) && $this->_aVars[\'aFeed\'][\'blog_id\']>0)
                    $this->_aVars[\'aFeed\'][\'item_id\'] = $this->_aVars[\'aFeed\'][\'blog_id\'];
            }
            $aModule = explode("_", $sModule);
            $iItemId = (int) $this->_aVars[\'aFeed\'][\'item_id\'];
            $sLinkCallback = $this->_aVars[\'aFeed\'][\'feed_link\'];
            if (!isset($this->_aVars[\'aFeed\'][\'feed_title\'])) $this->_aVars[\'aFeed\'][\'feed_title\'] = \'\';
            $sTitle = urlencode($this->_aVars[\'aFeed\'][\'feed_title\'] == \'\' ? $this->_aVars[\'aFeed\'][\'type_id\'] : $this->_aVars[\'aFeed\'][\'feed_title\']);
            
            /* fix photo title for photo module */
            if ($sModule == \'photo\') {
                $aPhotoDetail = Phpfox::getService(\'suggestion\')->getPhotoDetail($this->_aVars[\'aFeed\'][\'item_id\']);
                $sTitle = $aPhotoDetail[\'title\'];
            }

            if ($sModule == \'foxfeedspro\') {
                if(phpfox::isModule(\'foxfeedspro\'))
                {
                    $aFoxFeedsProDetail = Phpfox::getService(\'suggestion\')->getFoxFeedsProDetail($this->_aVars[\'aFeed\'][\'item_id\']);
                    if(isset($aFoxFeedsProDetail[\'item_alias\']) && $aFoxFeedsProDetail[\'item_alias\']!="")
                        $sTitle = urlencode($aFoxFeedsProDetail[\'item_alias\']);
                }
            }
            
            $sPrefix = (isset($this->_aVars[\'aFeed\'][\'feed_display\']) ? \'pages_\' : \'\');
            $iUserId = $this->_aVars[\'aFeed\'][\'user_id\'];
            
            if ($sModule == \'contest\')
            {
                if(phpfox::isModule(\'contest\'))
                {
                    $aContestDetail = Phpfox::getService(\'suggestion\')->getContestDetail($this->_aVars[\'aFeed\'][\'item_id\']);
                    if(isset($aContestDetail[\'contest_name\']) && $aContestDetail[\'contest_name\']!="")
                        $sTitle = urlencode($aContestDetail[\'contest_name\']);
                }
            }
            
            if ($sModule == \'fundraising\')
            {
                if(phpfox::isModule(\'fundraising\'))
                {
                    $aFundRaisingDetail = Phpfox::getService(\'suggestion\')->getFundRaisingDetail($this->_aVars[\'aFeed\'][\'item_id\']);
                    if(isset($aFundRaisingDetail[\'title\']) && $aFundRaisingDetail[\'title\']!="")
                        $sTitle = urlencode($aFundRaisingDetail[\'title\']);
                }
            }
            
            if ($sModule == \'coupon\')
            {
                if(phpfox::isModule(\'coupon\'))
                {
                    $aDetail = Phpfox::getService(\'suggestion\')->getCouponDetail($this->_aVars[\'aFeed\'][\'item_id\']);
                    if(isset($aDetail[\'title\']) && $aDetail[\'title\']!="")
                        $sTitle = urlencode($aDetail[\'title\']);
                }
            }
            
            $sTitle = base64_encode($sTitle);
            ?>
            <?php if (Phpfox::getUserParam(\'suggestion.enable_friend_suggestion\')) { ?>    
                <li> <span>·</span> <a id="suggestion_link_<?php echo $iId; ?>" href="/">
                <?php echo Phpfox::getPhrase(\'suggestion.suggest_to_friends_2\'); ?>
                    </a></li>
                    <?php } ?>
            <script language="javascript">        
                $Behavior.loadClick<?php echo $iId; ?> = function(){
                    $(\'#suggestion_link_<?php echo $iId; ?>\').click(function(e){
                        e.preventDefault();
                        <?php if ($aModule[0] != \'friend\') { ?>    
                            suggestion_and_recommendation_tb_show("...",$.ajaxBox(\'suggestion.friends\',\'iFriendId=\'+<?php echo $iItemId; ?>+\'&sSuggestionType=suggestion\'+\'&sModule=suggestion_<?php echo  $aModule[0]; ?>&sLinkCallback=<?php echo  $sLinkCallback; ?>&sTitle=<?php echo  $sTitle; ?>&sPrefix=<?php echo  $sPrefix; ?>&sExpectUserId=<?php echo  $iUserId; ?>\'));
                        <?php } ?>
                    });
                };    
            </script>
        <?php
        }
    }
}/* end chck module */ '; ?>