<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Ajax_Ajax extends Phpfox_Ajax
{
	public function delete()
	{
		if (Phpfox::getService('advancedmarketplace.process')->delete($this->get('id')))
		{
			$this->call('$(\'#js_mp_item_holder_' . $this->get('id') . '\').html(\'<div class="message" style="margin:0px;">' . Phpfox::getPhrase('advancedmarketplace.successfully_deleted_listing') . '</div>\').fadeOut(5000);');
		}
	}

	public function setDefault()
	{
		if (Phpfox::getService('advancedmarketplace.process')->setDefault($this->get('id')))
		{

		}
	}

	public function deleteImage()
	{
		if (Phpfox::getService('advancedmarketplace.process')->deleteImage($this->get('id')))
		{
			// $this->call("window.location = window.location;");
		}
	}

	public function listInvites()
	{
		Phpfox::getBlock('advancedmarketplace.list');

		$this->html('#js_mp_item_holder', $this->getContent(false));
	}

	public function feature()
	{
		$aListing = Phpfox::getService('advancedmarketplace')->getListing($this->get('listing_id'));
		if($aListing["post_status"] == 2) {
			// $this->alert(Phpfox::getPhrase('advancedmarketplace.listing_successfully_featured'), Phpfox::getPhrase('advancedmarketplace.feature'), 300, 150, true);
			return false;
		}
		if (Phpfox::getService('advancedmarketplace.process')->feature($this->get('listing_id'), $this->get('type')))
		{
			// js_mp_item_holder_4
			if ($this->get('type'))
			{
				$this->addClass('#js_mp_item_holder_' . $this->get('listing_id'), 'row_featured');
				$this->alert(Phpfox::getPhrase('advancedmarketplace.listing_successfully_featured'), Phpfox::getPhrase('advancedmarketplace.feature'), 300, 150, true);
			}
			else
			{
				$this->removeClass('#js_mp_item_holder_' . $this->get('listing_id'), 'row_featured');
				$this->alert(Phpfox::getPhrase('advancedmarketplace.listing_successfully_un_featured'), Phpfox::getPhrase('advancedmarketplace.un_feature'), 300, 150, true);
			}
		}
	}

	public function sponsor()
	{
		$aListing = Phpfox::getService('advancedmarketplace')->getListing($this->get('listing_id'));
		if($aListing["post_status"] == 2) {
			// $this->alert(Phpfox::getPhrase('advancedmarketplace.listing_successfully_featured'), Phpfox::getPhrase('advancedmarketplace.feature'), 300, 150, true);
			return false;
		}
	    if (Phpfox::getService('advancedmarketplace.process')->sponsor($this->get('listing_id'), $this->get('type')))
	    {
		if ($this->get('type') == '1')
		{
		    Phpfox::getService('ad.process')->addSponsor(array('module' => 'advancedmarketplace', 'item_id' => $this->get('listing_id')));
		    // listing was sponsored
		    $sHtml = '<a href="#" title="' . Phpfox::getPhrase('advancedmarketplace.unsponsor_this_listing') . '" onclick="$(\'#js_sponsor_phrase_' . $this->get('listing_id') . '\').hide(); $.ajaxCall(\'advancedmarketplace.sponsor\', \'listing_id=' . $this->get('listing_id') . '&amp;type=0\', \'GET\'); return false;">'.Phpfox::getPhrase('advancedmarketplace.unsponsor_this_listing').'</a>';
		}
		else
		{
		    Phpfox::getService('ad.process')->deleteAdminSponsor('advancedmarketplace', $this->get('listing_id'));
		    $sHtml = '<a href="#" title="' . Phpfox::getPhrase('advancedmarketplace.unsponsor_this_listing') . '" onclick="$(\'#js_sponsor_phrase_' . $this->get('listing_id') . '\').show(); $.ajaxCall(\'advancedmarketplace.sponsor\', \'listing_id=' . $this->get('listing_id') . '&amp;type=1\', \'GET\'); return false;">'.Phpfox::getPhrase('advancedmarketplace.sponsor_this_listing').'</a>';
		}
		$this->html('#js_sponsor_' . $this->get('listing_id'), $sHtml)->alert($this->get('type') == '1' ? Phpfox::getPhrase('advancedmarketplace.listing_successfully_sponsored') : Phpfox::getPhrase('advancedmarketplace.listing_successfully_un_sponsored'));
		if($this->get('type') == '1')
		{
		    $this->addClass('#js_mp_item_holder_' . $this->get('listing_id'), 'row_sponsored');
		}
		else
		{
		    $this->removeClass('#js_mp_item_holder_' . $this->get('listing_id'), 'row_sponsored');
		}
	    }
	    //js_mp_item_holder_
	}

	public function approve()
	{
		if (Phpfox::getService('advancedmarketplace.process')->approve($this->get('listing_id')))
		{
			$this->alert(Phpfox::getPhrase('advancedmarketplace.listing_has_been_approved'), Phpfox::getPhrase('advancedmarketplace.listing_approved'), 300, 100, true);
			$this->hide('#js_item_bar_approve_image');
			$this->hide('.js_moderation_off');
			$this->show('.js_moderation_on');
		}
	}

	public function moderation()
	{
		Phpfox::isUser(true);

		switch ($this->get('action'))
		{
			case 'approve':
				Phpfox::getUserParam('advancedmarketplace.can_approve_listings', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('advancedmarketplace.process')->approve($iId);
					$this->remove('#js_mp_item_holder_' . $iId);
				}
				$this->updateCount();
				$sMessage = Phpfox::getPhrase('advancedmarketplace.listing_s_successfully_approved');
				break;
			case 'delete':
				Phpfox::getUserParam('advancedmarketplace.can_delete_other_listings', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('advancedmarketplace.process')->delete($iId);
					$this->slideUp('#js_mp_item_holder_' . $iId);
				}
				$sMessage = Phpfox::getPhrase('advancedmarketplace.listing_s_successfully_deleted');
				break;
			case 'feature':
				Phpfox::getUserParam('advancedmarketplace.can_feature_listings', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('advancedmarketplace.process')->feature($iId, 1);
					$this->addClass('#js_mp_item_holder_' . $iId, 'row_featured');
				}
				$sMessage = Phpfox::getPhrase('advancedmarketplace.listing_s_successfully_featured');
				break;
			case 'un-feature':
				Phpfox::getUserParam('advancedmarketplace.can_feature_listings', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('advancedmarketplace.process')->feature($iId, 0);
					$this->removeClass('#js_mp_item_holder_' . $iId, 'row_featured');
				}
				$sMessage = Phpfox::getPhrase('advancedmarketplace.listing_s_successfully_un_featured');
				break;
		}

		$this->alert($sMessage, 'Moderation', 300, 150, true);
		$this->hide('.moderation_process');
	}

	public function sponsorHelp()
	{
	    Phpfox::getBlock('advancedmarketplace.sponsorhelp');

	}

	public function ratePopup()
	{
		$iPage = $this->get('page');
		// echo $iPage;exit;
		phpfox::isUser(true);
	    Phpfox::getBlock('advancedmarketplace.rate', array(
			"iId" => $this->get('id'),
			"page" => $iPage
		));
	}

	public function todaylistingPopup()
	{
		// Phpfox::getLib('cache')->remove();
	    Phpfox::getBlock('advancedmarketplace.admincp.todaylisting', array(
			"iId" => $this->get('id')
		));
	}

	public function massUploadProcess(){
		$this->call("$(\".error_message\").remove();");

        $iEditId = $this->get('iEditId');
        $sInviteLink = $this->get('sInviteLink');

        $aListing = Phpfox::getService('advancedmarketplace')->getListing($iEditId);

        if(!Phpfox::getUserParam('advancedmarketplace.listing_approve') && ($aListing['post_status'] == 1) && !Phpfox::getService('advancedmarketplace')->isListingOnFeed($iEditId)) {

            $aCallback = ($this->get('callback_module') ? Phpfox::callback($this->get('callback_module') . '.addList', $this->get('callback_item_id')) : null);

            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->callback($aCallback)->add('advancedmarketplace', $iEditId, $aListing['privacy'], (isset($aListing['privacy_comment']) ? (int) $aListing['privacy_comment'] : 0)) : null);

            Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'advancedmarketplace');
        }

        if($sInviteLink)
            $this->call('window.location.href = "' . $sInviteLink . '";');
	}

	public function toggleActiveGroup()
	{
		if (Phpfox::getService('advancedmarketplace.custom.process')->toggleGroupActivity($this->get('id')))
		{
			$this->call('$Core.custom.toggleGroupActivity(' . $this->get('id') . ')');
		}
	}

	public function toggleActiveField()
	{
		if (Phpfox::getService('advancedmarketplace.custom.process')->toggleFieldActivity($this->get('id')))
		{
			$this->call('$Core.custom.toggleFieldActivity(' . $this->get('id') . ')');
		}
	}

	public function featureSelected()
	{
		$iType = $this->get('type');
		$iListingId = $this->get('listing_id');
		if(isset($iType) && $iType == 1)
		{
			/*$iType = 0;
			Phpfox::getService('advancedmarketplace.process')->feature($this->get('listing_id'), $iType);
			$this->call("$('#js_listing_is_feature_".$iListingId."').show();");
			$this->call("$('#is_selected_active_".$iListingId."').val(".$iType.");");*/
		}
		else
		{
			$iType = 1;
			Phpfox::getService('advancedmarketplace.process')->feature($this->get('listing_id'), $iType);
			$this->call("$('#js_listing_is_un_feature_".$iListingId."').show();");
			$this->call("$('#is_selected_active_".$iListingId."').val(".$iType.");");
		}
		$this->call('_bh.pop();');

	}
	public function advMarketRating()
	{
		$rating = $this->get('rating');
		$iListingId = $rating["listing_id"];
		$iRate = isset($rating['star'])?$rating['star']:0;
		$sComment = $rating['comment'];
		if(!$iRate && !$sComment) {
			$this->alert(PHPFOX::getPhrase("advancedmarketplace.cannot_add_rating_to_a_review_created_without_a_rating"));
			return false;
		}
		$bCanRate = PHPFOX::getService("advancedmarketplace.process")->rate($iListingId, $iRate, $sComment);
		if($bCanRate !== false)
		{

			// reload ajx...

			$iPage = $this->get('page');$iPage = $iPage?$iPage:1;
			$iSize = 2;
			// var_dump($iPage);
			List($iCount, $aRating) = PHPFOX::getService("advancedmarketplace")->frontend_getListingReview($iListingId, ($iSize * $iPage), 0);
			$aParams = array(
				"aListing" => PHPFOX::getService("advancedmarketplace")->getListing($iListingId),
				"aRating" => $aRating,
				"iCount" => $iCount,
				"iPage" => $iPage,
				"iSize" => $iSize
			);
			Phpfox::getBlock('advancedmarketplace.review', $aParams);
			// $this->html('#yn_listingrating .content', $this->getContent(false));
			$this->call(sprintf("$(\"#yn_listingrating\").html(\"%s\");", str_replace("\"", "\\\"",$this->getContent(false))));
			$this->call('$Behavior.advancedmarketplaceRating();');
			$this->call('$Behavior.advmarket_ratingJS();');
			$this->call('$(".review-count").html(' . $iCount . ');');
			$this->call('$(".ssbt").removeClass("ssbt");');
			$this->alert(Phpfox::getPhrase('advancedmarketplace.thank_for_your_rating'));
		}
	}

	public function advMarketTodayListing()
	{
		$iListingId = $this->get('id');
		$aDate = $this->get('todaylistingitem');

		PHPFOX::getService("advancedmarketplace.process")->todaylisting((int)$iListingId, $aDate);
		$this->alert(Phpfox::getPhrase('advancedmarketplace.today_listing_added_successfully'));
	}

	public function deleteField()
	{

		if (Phpfox::getService('advancedmarketplace.custom.process')->deleteField($this->get('id')))
		{

			$this->call('$(\'#js_field_' . $this->get('id') . '\').parents(\'li:first\').remove();');
		}
	}

	public function showSubcats(){
		$subcats = Phpfox::getService('advancedmarketplace')->loadSubcatByParentID($this->get('pid'));
		Phpfox::getLib('template')
			->assign(array('aSubcats' => $subcats))
			->getTemplate('comment.block.mini');
		$this->after('#advmarketplace_ajx_catspros', $this->getContent(false));
	}

	public function sponsorSelected()
	{
		$iType = $this->get('type');
		$iListingId = $this->get('listing_id');
		if(isset($iType) && $iType == 1)
		{
			/*$iType = 0;
			Phpfox::getService('advancedmarketplace.process')->sponsor($this->get('listing_id'), $iType);
			$this->call("$('#js_listing_is_sponsor_".$iListingId."').show();");
			$this->call("$('#is_selected_active_".$iListingId."').val(".$iType.");");*/
		}
		else
		{
			$iType = 1;
			Phpfox::getService('advancedmarketplace.process')->sponsor($this->get('listing_id'), $iType);
			$this->call("$('#js_listing_is_un_sponsor_".$iListingId."').show();");
			$this->call("$('#is_selected_active_".$iListingId."').val(".$iType.");");
		}
		$this->call('_ch.pop();');

	}

	public function follow()
	{
		phpfox::isUser(true);
		if(phpfox::isUser())
		{
			$bType = $this->get('type');
			$sType  = '';
			$iUserId = $this->get('user_id');
			$iFollower = $this->get('user_follow_id');
			$str = '';

			if($bType == 'follow')
			{
				$this->call("$('#js_follow_".$iFollower."').disable;");
				Phpfox::getService('advancedmarketplace.process')->addFollow($iUserId, $iFollower);
				//$this->call("$('#js_unfollow_".$iFollower."').show();");
				$str = Phpfox::getPhrase('advancedmarketplace.unfollow');
				$sType = 'unfollow';
			}
			else {
				$this->call("$('#js_follow_".$iFollower."').disable;");
				Phpfox::getService('advancedmarketplace.process')->removeFollow($iUserId, $iFollower);
				//$this->call("$('#js_unfollow_".$iFollower."').hide();");
				//$this->call("$('#js_follow_".$iFollower."').show();");
				$str = Phpfox::getPhrase('advancedmarketplace.follow');
				$sType = 'follow';
			}
			$disabled = "disabled";
			$this->html('#js_follow_' . $iFollower, '<input type="button" class="button" onclick="$(this).addClass('."'".$disabled."'".').attr('."'".$disabled."'".','."'".$disabled."'".');follow('."'".$sType."'".','.$iUserId.','.$iFollower.'); return false;" value="'.$str.'" />');
		}

	}

	// nhanlt
	public function listingdetail(){
		Phpfox::getBlock('advancedmarketplace.listingdetail', array(
			"aListing" => PHPFOX::getService("advancedmarketplace")->getListing($this->get('lid'))
		));
		$this->call(sprintf("$(\"#yn_advmarket_wrapper\").html($(\"%s\"));", str_replace("\"", "\\\"",$this->getContent(false))));
	}

	//nhanlt
	public function review(){
		$iPage = $this->get('page');
		$iPage = isset($iPage)?($iPage):0;
		$iSize = 2;

		List($iCount, $aRating) = PHPFOX::getService("advancedmarketplace")->frontend_getListingReview($this->get('lid'), $iSize, $iPage);
		Phpfox::getBlock('advancedmarketplace.review', array(
			"aListing" => PHPFOX::getService("advancedmarketplace")->getListing($this->get('lid')),
			"aRating" => $aRating,
			"iCount" => $iCount,
			"iPage" => $iPage,
			"iSize" => $iSize
		));
		$this->call(sprintf("$(\"#yn_advmarket_wrapper\").html($(\"%s\"));", str_replace("\"", "\\\"",$this->getContent(false))));
	}

	//nhanlt
	public function reviewpaging(){
		$iPage = $this->get('page', 0);
		$iSize = 2;

		List($iCount, $aRating) = PHPFOX::getService("advancedmarketplace")->frontend_getListingReview($this->get('lid'), $iSize * $iPage, 0);
		$aParams = array(
			"aListing" => PHPFOX::getService("advancedmarketplace")->getListing($this->get('lid')),
			"aRating" => $aRating,
			"iCount" => $iCount,
			"iPage" => $iPage,
			"iSize" => $iSize
		);
		Phpfox::getBlock('advancedmarketplace.review', $aParams);
		// $this->html('#yn_listingrating .content', $this->getContent(false));
		$this->call(sprintf("$(\"#yn_listingrating\").html(\"%s\");", str_replace("\"", "\\\"",$this->getContent(false))));
		$this->call('$Behavior.advmarket_ratingJS();');
		$this->call('$Behavior.advancedmarketplaceRating();');
		$this->call('$(".ssbt").removeClass("ssbt");');
	}

	//nhanlt
	public function showmanagecustomfieldpopup(){
		$aParams = array(
			"lid" => $this->get("lid")
		);
		// remove cache for "fresh phrase"...
		Phpfox::getLib('cache')->remove();
		Phpfox::getBlock('advancedmarketplace.admincp.managecustomfield', $aParams);
	}

	//nhanlt
	public function addCustomFieldGroup(){
        $sText = PHPFOX::getPhrase("advancedmarketplace.default_custom_field_group_name");
		$iListingId = $this->get("lid");

		$sKeyVar = PHPFOX::getService("advancedmarketplace.customfield.process")->addDefaultCustomFieldGroup($iListingId, $sText);
		$aParams = array(
			"sKeyVar" => $sKeyVar,
			"sText" => $sText,
			"is_active" => "1",
		);
		Phpfox::getBlock('advancedmarketplace.admincp.customfield.customfieldgroup', $aParams);
		$this->call(sprintf("processCustomGroupSample(\"%s\");", str_replace("\"", "\\\"",$this->getContent(false))));
	}

	//nhanlt
	public function editCustomFieldGroup(){
		$sCusfGroupId = $this->get("cusfgroupid");
		$sValue = $this->get("value");
		PHPFOX::getService("advancedmarketplace.customfield.process")->updateCustomFieldName($sCusfGroupId, $sValue);
		$this->call(sprintf("$(\".ajxloader\").hide();$(\".ref_%s\").removeClass(\"changed\");", $sCusfGroupId));
		$this->call("$.ajaxCall(\"advancedmarketplace.loadCustomFields\", \"cusfgroupid=\" + \"$sCusfGroupId\");");
	}

	//nhanlt
	public function deleteCustomFieldGroup(){
		$sCusfGroupId = $this->get("cusfgroupid");
		PHPFOX::getService("advancedmarketplace.customfield.process")->deleteCustomFieldGroup($sCusfGroupId);
		$this->call(sprintf("$(\"li.pref_%s\").remove();", $sCusfGroupId));
		$this->call(sprintf("$(\".ajxloader\").hide();"));
	}

	//nhanlt
	public function loadCustomFields(){
		Phpfox::getLib('cache')->remove();
		$sCusfGroupId = $this->get("cusfgroupid");
		$aCustomFields = PHPFOX::getService("advancedmarketplace.customfield.advancedmarketplace")->loadCustomFields($sCusfGroupId);
		// echo "<!--";
		// var_dump($aCustomFields);exit;
		// echo "-->";
		$aParams = array(
			"aCustomFields" => $aCustomFields,
			"sKeyVar" => $sCusfGroupId,
		);
		Phpfox::getBlock('advancedmarketplace.admincp.customfield.groupcustomfields', $aParams);
		$this->call(sprintf("$(\".ajxloader\").hide();"));
		// $this->html("#yn_jh_groupcustomfields", $this->getContent(false));
		$this->call(sprintf("processCustomGroupFieldSample(\"%s\");", str_replace("\"", "\\\"",$this->getContent(false))));
		$this->call(sprintf("$(\".ajxloader\").hide();"));
		if(count($aCustomFields) <= 0) {
			$this->call(sprintf("$(\".yn_jh_saveall\").hide();"));
		}
	}

	//nhanlt
	public function addCustomField(){
		Phpfox::getLib('cache')->remove();
		$sText = PHPFOX::getPhrase("advancedmarketplace.default_custom_field_name");
		$sCusfGroupId = $this->get("cusfgroupid");
		$aCustomFields = PHPFOX::getService("advancedmarketplace.customfield.process")->addCustomFields($sCusfGroupId, $sText);
		// $aCustomFields["phrase_var_name"] = "advancedmarketplace.default_custom_field_name";
		$aParams = array(
			"aCellCustomFields" => $aCustomFields,
			"sKeyVarCell" => $sCusfGroupId,
			"isAdd" => true
		);
		// var_dump($aParams);exit;
		Phpfox::getBlock('advancedmarketplace.admincp.customfield.customfieldcell', $aParams);
		$this->call(sprintf("processCustomFieldSample(\"%s\");", str_replace("\"", "\\\"",$this->getContent(false))));
		$this->call(sprintf("$(\".ajxloader\").hide();"));
	}

	//nhanlt
	public function addOption(){
		Phpfox::getLib('cache')->remove();
		$sText = PHPFOX::getPhrase("advancedmarketplace.default_custom_field_option_name");
		$iCusfieldId = $this->get("cusfieldid");
		$sFieldType = $this->get("field_type");
		$sKeyVar = PHPFOX::getService("advancedmarketplace.customfield.process")->addCustomFieldOption($iCusfieldId, $sFieldType, $sText);
		$aParams = array(
			"iCusfieldId" => $iCusfieldId,
			"sTextOption" => $sText,
			"sKeyVarOption" => "advancedmarketplace." . $sKeyVar,
		);
		Phpfox::getBlock('advancedmarketplace.admincp.customfield.customfieldoption', $aParams);
		$this->call(sprintf("processCustomFieldOptionSample(\"%s\", $iCusfieldId);", str_replace("\"", "\\\"",$this->getContent(false))));
		$this->call(sprintf("$(\".ajxloader\").hide();"));
	}

	//nhanlt
	public function saveAllCustomField(){
		$aCustomFields = $this->get("customfield");
		PHPFOX::getService("advancedmarketplace.customfield.process")->updateMultiCustomFields($aCustomFields);
		$this->call(sprintf("$(\".ajxloader\").hide();"));

		$this->call(sprintf("var msgdiv = $(\"<div>\").addClass('msg').html('%s');", Phpfox::getPhrase('advancedmarketplace.all_custom_field_has_been_saved_successfully')));
		$this->call("$('#jh_yn_cusfield_submitform').prepend(msgdiv);");
		$this->call("setTimeout(function(){msgdiv.fadeOut(500, function(){msgdiv.remove();});}, 600);");
	}

	//nhanlt
	public function setSwitchOnOffCustomFieldGroup(){
		$sCusfGroupId = $this->get("cusfgroupid");
		$sState = PHPFOX::getService("advancedmarketplace.customfield.process")->setSwitchOnOffCustomFieldGroup($sCusfGroupId);
		$this->call(sprintf("$(\".ajxloader\").hide();"));
		$this->call(sprintf("processSwitchFieldGroupStatus(\"%s\", \"%s\");", $sCusfGroupId, $sState));
	}

	//nhanlt
	public function frontend_loadCustomFields(){
		Phpfox::getLib('cache')->remove();
		$iCatId = $this->get("catid");
		$iListingId = $this->get("lid");
		$aCustomFields = PHPFOX::getService("advancedmarketplace.customfield.advancedmarketplace")->frontend_loadCustomFields($iCatId, $iListingId);
		$cfInfors = PHPFOX::getService("advancedmarketplace")->backend_getcustomfieldinfos();
		// var_dump($iListingId);exit;
		$aParams = array(
			"aCustomFields" => $aCustomFields,
			"cfInfors" => $cfInfors,
		);
		Phpfox::getBlock('advancedmarketplace.frontend.customfield', $aParams);
		$this->html("#advmarketplace_js_customfield_form", str_replace("\\n", "", $this->getContent(false)));
	}

	// nhanlt
	public function updateCustomGroupOrder() {
		$aCustomGroupVars = $this->get("customfieldgroup");
		PHPFOX::getService("advancedmarketplace.customfield.process")->updateCustomGroupOrder($aCustomGroupVars);
	}

	// nhanlt
	public function deleteReview() {
		$iReviewId = $this->get("rid");
		PHPFOX::getService("advancedmarketplace.process")->deleteReview($iReviewId);
		$this->call("$(\"#rw_ref_" . $iReviewId . "\").remove();");
	}

	// nhanlt
	public function removeCustomField() {
		$sCustomFieldAlias = $this->get("cusfieldalias");
		PHPFOX::getService("advancedmarketplace.customfield.process")->deleteCustomField($sCustomFieldAlias);
	}

	// nhanlt
	public function deleteTodayListings() {
		$aTodayListingIds = $this->get("deleteitem");
		foreach($aTodayListingIds as $aTodayListingId) {
			phpfox::getService('advancedmarketplace.process')->deleteTodayListing($aTodayListingId);
		}
		$this->call("window.location = window.location;");
	}
	
	public function gmap()
	{
		Phpfox::getBlock('advancedmarketplace.gmap');
	}
	
	public function reloadGmap()
	{
		
		$sLocation = $this->get('location');
		$sCity = $this->get('city');
		$sRadius = (int)$this->get('radius');
		
		if($sLocation=="Location...")
			$sLocation="";
		if($sCity!="" && $sCity!="City...")
			$sLocation=$sLocation." , ".$sCity;
		
		list($aCoordinates, $sGmapAddress) = Phpfox::getService('advancedmarketplace.process')->address2coordinates($sLocation);
		
		$radius=0;
		if (is_int($sRadius))
		{
			$radius=$sRadius;
		}
		
		$sIds = $this->get('ids');

		
		$sIds = trim($sIds, ',');
		$aIds = array();
		$aIds = explode(',', $sIds);

		foreach($aIds as $iKey => $sId)
		{
			$aIds[$iKey] = (int)$sId;
		}
		
		$aIds[0] = 1;
		$aListings = Phpfox::getService('advancedmarketplace')->getListingsByIds($aIds);
		
		$sJson = json_encode($aListings);
		
		$this->call('panGmapTo('.$aCoordinates[1].','.$aCoordinates[0].','.$radius.','.$sJson.');'); // lat, lng
	}
	
	public function getListingsForGmap()
	{
		$sIds = $this->get('ids');
		$sIds = 1;
		$sIds = trim($sIds, ',');
		$aIds = array();
		$aIds = explode(',', $sIds);
		foreach($aIds as $iKey => $sId)
		{
			$aIds[$iKey] = (int)$sId;
		}
		
		$aListings = Phpfox::getService('advancedmarketplace')->getListingsByIds($aIds);
		
		$sJson = json_encode($aListings);
		$this->call('displayMarkers("'.str_replace('"', '\\"', $sJson).'");');
	}
}
?>