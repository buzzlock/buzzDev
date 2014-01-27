<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<script type="text/javascript">
	{literal}
	$Behavior.ynfrInitializeCategoryJs = function() {
		ynfundraising.addCategoryJsEventListener();
	}
	
	{/literal}
</script>

<div id="js_fundraising_block_main" class="js_fundraising_block page_section_menu_holder">
	
	<div class="table">
		<div class="table_left">
			<label for="category">{required}{phrase var='fundraising.category'}:</label>
		</div>
		<div class="table_right">
			{$sCategories}
		</div>
	</div>
	
	<div class="table">
		<div class="table_left">
			<label for="title">{required}{phrase var='fundraising.campaign_name'}: </label>
		</div>
		<div class="extra_info ynfr_extra_info">
			{phrase var='fundraising.you_can_enter_maximum_number_characters', number=255}
		</div>
		<div class="table_right">
			<input type="text" class="ynfr required ynfr_campaign_title_max_length" name="val[title]" value="{value type='input' id='title'}" id="title" size="60" />
		</div>
	</div>

	<div class="table">
		<div class="table_left">
			<label for="short_description">{required}{phrase var='fundraising.short_description'}:</label>
		</div>
		<div class="extra_info ynfr_extra_info">
				{phrase var='fundraising.you_can_enter_maximum_number_characters', number=160}
		</div>
		<div class="table_right">
			<textarea cols="59" rows="10" name="val[short_description]" class="js_edit_fundraising_form ynfr required ynfr_campaign_short_description_max_length" id="short_description" style="height:70px;">{value id='short_description' type='textarea'}</textarea>
		</div>
	</div>

	<div class="table">
		<div class="table_left">
			<label for="description">{phrase var='fundraising.main_description'}</label>
			
			{if Phpfox::isModule('attachment') && !Phpfox::isMobile()}
			<div class="extra_info">
				<script type="text/javascript">$Behavior.loadAttachmentStaticFilesFundraising = function(){l}$Core.loadStaticFile('{jscript file='share.js' module='attachment'}');{r}</script>                
				<div class="global_attachment">
					<div class="global_attachment_header">
						<ul class="global_attachment_list">
							<li class="global_attachment_title">{phrase var='attachment.insert'}:</li>
							<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'attachment.add', 500, '&amp;category_id={$aAttachmentShare.type}&amp;attachment_custom=photo');" class="js_global_position_photo js_hover_title">{img theme='feed/photo.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.insert_a_photo'}</span></a></li>
							{if Phpfox::isModule('emoticon')}
							<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'emoticon.preview', 400, '&amp;editor_id=' + Editor.getId());" class="js_hover_title">{img theme='editor/emoticon.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.insert_emoticon'}</span></a></li>
							{/if}
						</ul>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			{/if}
		</div>
		<div class="table_right">
            {literal}
            <script type="text/javascript">
            $Behavior.loadContentResumeFundraising = function(){ 
                $("#description").click(function() {
                    Editor.setId('description');
                });
            }      
            </script>
            {/literal}
			{editor id='description'}
		</div>
	</div>
	{plugin call='fundraising.template_controller_add_textarea_end'}
    <div class="table">
        <div class="table_left">
            <label for="paypal_account">{required}{phrase var='fundraising.your_paypal_account'}:</label>
        </div>
        <div class="table_right">
            <input type="text" class="ynfr required email" name="val[paypal_account]"  {if $bIsEdit && $aForms.user_id != Phpfox::getUserId()} disabled=true{/if}  value="{value type='input' id='paypal_account'}" id="paypal_account" size="60" />
        </div>
    </div>
	<div class="table">
		<div class="table_left">
			<label for="financial_goal">{phrase var='fundraising.campaign_goal_financial_goal'}:</label>
		</div>
		<div class="table_right">
			{if !$bIsEdit}
			<input type="text" name="val[financial_goal]" class="ynfr required number ynfr_positive_number" value="{$iDefaultFundraising}" id="financial_goal" size="60" />
			{else}
			
			<input type="text" name="val[financial_goal]"class="ynfr required number ynfr_positive_number" value="{value type='input' id='financial_goal'}" id="financial_goal" size="60" />
			{/if}
            <div class="extra_info">
                {phrase var='fundraising.set_0_for_unlimit_goal'}
            </div>
		</div>
	</div>
    <div class="table">
        <div class="table_left">
            <label for="financial_goal">{phrase var='fundraising.currency'}:</label>
        </div>
        <div class="table_right">
            <select id="donation_select_currency" class="ynfr required" {if $bIsEdit && $aForms.is_draft != 1} title="{phrase var='fundraising.can_not_edit_currency_ongoing_campaign'}"disabled="disabled" {/if} name='val[selected_currency]'>
                {foreach from=$aCurrentCurrencies key=key item=aCurrency}
                <option value="{$aCurrency.currency_id}">
                    {$aCurrency.currency_id}
                </option>
                {/foreach}
            </select>
        </div>
    </div>
	<div class="table">
		<div class="table_left">
			{phrase var='fundraising.expired_date'}:
			<div class="extra_info ynfr_extra_info">
				{phrase var='fundraising.you_can_set_expired_date'}
			</div>
		</div>
		<div class="table_right">
			<div class="ynfr_expired_time" style="position: relative; {if $bIsEdit && ($aForms.unlimit_time == 'checked' || !$aForms.end_time) } display: none; {/if}">
				{select_date prefix='expired_time_' id='_expired_time' start_year='current_year' end_year='+10' field_separator=' / ' field_order='MDY' default_all=true}
			</div>
            <div class="extra_info_custom" style="margin-top:10px; font-size:12px;">
                <input type="checkbox" name="val[unlimit_time]" onclick="disable($(this));" value="1" id="unlimit_time" class="checkbox v_middle" {if $bIsEdit} {if !$aForms.end_time} checked="checked"{/if} {/if} /> {phrase var='fundraising.set_to_unlimit_time'}
            </div>
		</div>
	</div>

	<div class="table">
		<div class="table_left">
			<label for="minimum_amount">{phrase var='fundraising.minimum_donation'}:</label>
		</div>
		<div class="table_right">
			{if !$bIsEdit}
			<input type="text" name="val[minimum_amount]" class="number ynfr_positive_number" value="{$iDefaultMinFundraising}" id="minimum_amount" />
			{else}
			<input type="text" name="val[minimum_amount]" class="number ynfr_positive_number" value="{value type='input' id='minimum_amount'}" id="minimum_amount" />
			{/if}
		</div>
	</div>
    {literal}
    <script type="text/javascript">
        function disable(a) {
            if(a.attr('checked'))
                $('.ynfr_expired_time').hide();
            else
                $('.ynfr_expired_time').show();
        }
    </script>
    {/literal}
	<div class="table">
		<div class="table_left">
			<label>{phrase var='fundraising.list_predefine'}:</label>
			<div class="extra_info ynfr_extra_info">
				{phrase var='fundraising.enter_up_to_preselect'}
			</div>
		</div>
		<div class="table_right">
			<div class="p_4 predefined_holder" id="">
				{if !$bIsEdit}
					{foreach from=$aTempPredefined key=iKey item=aPredefined}
						{template file='fundraising.block.campaign.predefine-main-info-form'}
					{/foreach}
				{else}
                    {foreach from=$aForms.predefined_amount_list key=iKey item=aPredefined}
                    {if isset($aPredefined) && !empty($aPredefined)}
                       {template file='fundraising.block.campaign.predefine-main-info-form'}
                    {/if}
                    {/foreach}
                    {if !isset($aForms.predefined_amount_list)}
                        {foreach from=$aTempPredefined key=iKey item=aPredefined}
                            {template file='fundraising.block.campaign.predefine-main-info-form'}
                        {/foreach}
                    {/if}
				{/if}
			</div>
		</div>
	</div>
	
	<div class="table">
		<div class="table_left">
			<input value="1" type="checkbox" name="val[allow_anonymous]" id="allow_anonymous" class="checkbox v_middle" {if $bIsEdit} {$aForms.allow_anonymous} {else} checked="checked"{/if} /> {phrase var='fundraising.allow_anonymous'}
		</div>
		<div class="table_right">
		</div>
	</div>

	<div class="table">
		<div class="table_left">
			{required}<label for="location_venue">{phrase var='fundraising.location_venue'}:</label>
		</div>
		<div class="extra_info ynfr_extra_info">
			{phrase var='fundraising.please_fill_your_address_here'}
		</div>
		<div class="table_right">
			<input type="text" name="val[location_venue]" class="ynfr required" value="{value type='input' id='location_venue'}" id="location_venue" size="40" maxlength="200" />
			<div class="extra_info">
                {if !$bIsEdit}
                <a href="#" id="js_link_show_add" onclick="$(this).hide(); $('#js_mp_add_city').show(); $('#js_link_hide_add').show(); return false;">{phrase var='fundraising.add_city_zip'}</a>
                <a href="#" id="js_link_hide_add" style="display: none;" onclick="$(this).hide(); $('#js_mp_add_city').hide(); $('#js_link_show_add').show(); return false;">{phrase var='fundraising.hide_add_city_zip'}</a>
                {/if}
			</div>
		</div>

		
	</div>

	<div id="js_mp_add_city" {if !$bIsEdit} style="display:none;"{/if} >

		 <div class="table" style="display:none">
			<div class="table_left">
				<label for="address">{phrase var='fundraising.address'}</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[address]" value="{value type='input' id='address'}" id="address" size="30" maxlength="200" />
			</div>
		</div>

		<div class="table">
			<div class="table_left">
				<label for="city">{phrase var='fundraising.city'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[city]" value="{value type='input' id='city'}" id="city" size="20" maxlength="200" />
			</div>
		</div>
		<div class="table">
			<div class="table_left">
				<label for="postal_code">{phrase var='fundraising.zip_postal_code'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[postal_code]" value="{value type='input' id='postal_code'}" id="postal_code" size="10" maxlength="20" />
			</div>
		</div>
		<div class="table">
			<div class="table_left">
				{required}<label for="country_iso">{phrase var='fundraising.country'}:</label>
			</div>
			<div class="table_right">
				{select_location}
				{module name='core.country-child'}
			</div>
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			<input id="refresh_map" type="button" value="{phrase var='fundraising.refresh_map'}" onclick="inputToMap();"/>
		</div>
		<div class="table_right">
			<input type="hidden" name="val[gmap][latitude]" value="{value type='input' id='input_gmap_latitude'}" id="input_gmap_latitude" />
			<input type="hidden" name="val[gmap][longitude]" value="{value type='input' id='input_gmap_longitude'}" id="input_gmap_longitude" />
			<div id="mapHolder" style="width: 400px; height: 400px"></div>
		</div>
	</div>
	<div class="clear"></div>
	<br/>

	{if empty($sModule) && Phpfox::isModule('privacy') && Phpfox::getUserParam('fundraising.can_set_allow_list_on_campaigns')}
	<div class="table">
		<div class="table_left">
			{phrase var='fundraising.privacy'}:
		</div>
		<div class="table_right">
			{module name='privacy.form' privacy_name='privacy' privacy_info='fundraising.control_who_can_see_this_fundraising'  default_privacy='fundraising.default_privacy_setting'}
		</div>
	</div>
	{/if}

	{if empty($sModule) && Phpfox::isModule('comment') && Phpfox::isModule('privacy') && Phpfox::getUserParam('fundraising.can_control_comments_on_campaigns')}
	<div class="table">
		<div class="table_left">
			{phrase var='fundraising.comment_privacy'}:
		</div>
		<div class="table_right">
			{module name='privacy.form' privacy_name='privacy_comment' privacy_info='fundraising.control_who_can_comment_on_this_fundraising' privacy_no_custom=true}
		</div>
	</div>
	{/if}

	{if empty($sModule)  && Phpfox::isModule('privacy') && Phpfox::getUserParam('fundraising.can_control_donate_on_campaigns')}
	<div class="table">
		<div class="table_left">
			{phrase var='fundraising.donate_privacy'}
		</div>
		<div class="table_right">
			{module name='privacy.form' privacy_name='privacy_donate' privacy_info='fundraising.control_who_can_donate_on_this_fundraising' privacy_no_custom=true}
		</div>
	</div>
	{/if}

	<div class="table_clear">
		<ul class="table_clear_button">
            {if $bIsEdit && $aForms.is_draft == 1}
            <li><input type="submit" name="val[draft_update]" value="{phrase var='fundraising.update'}" class="button" /></li>
            <li><input type="submit" name="val[draft_publish]" onclick="return confirm('{phrase var='fundraising.confirm_publish_campaign'}')"value="{phrase var='fundraising.publish'}" class="button button_off" /></li>
            {else}
			<li><input type="submit" name="val[{if $bIsEdit}update{else}publish{/if}]" {if !$bIsEdit} onclick="return confirm('{phrase var='fundraising.confirm_publish_campaign'}')" {/if} value="{if $bIsEdit}{phrase var='fundraising.update'}{else}{phrase var='fundraising.publish'}{/if}" class="button" /></li>
            {/if}
            {if !$bIsEdit}<li><input type="submit" name="val[draft]" value="{phrase var='fundraising.save_as_draft'}" class="button button_off" /></li>{/if}
		</ul>
		<div class="clear"></div>
	</div>

	{if Phpfox::getParam('core.display_required')}
	<div class="table_clear">
		{required} {phrase var='core.required_fields'}
	</div>
	{/if}

</div>

<script type="text/javascript">

$Behavior.initializeValidateCustomClassYnfr = function() {l} 
	{if isset($aForms.minimum_amount)}
		$.validator.addClassRules("ynfr_sponsor_level_amount", {l}range:[{$aForms.minimum_amount},100000000]{r});
	{/if}

	jQuery.validator.addMethod('greater_than_minimum', function(value, element) {l}
				if(value < parseInt($('#minimum_amount').val()) && value != '')	
				{l}
				
					return false;
				{r}

				return true;
			{r}, '{phrase var='fundraising.must_greater_than_minimum'}' 
		);


	jQuery.validator.addClassRules("greater_than_minimum", {l}
			greater_than_minimum: {l}greater_than_minimum: true{r}
	{r});

	jQuery.validator.messages.range = "{phrase var='fundraising.please_enter_an_amount_greater_or_equal'}" + ' {l}0{r} ' + "" ;
	jQuery.validator.messages.maxlength = "{phrase var='fundraising.maximum_number_of_characters_for_this_field_is_semicolon'}" + ' {l}0{r} ';
{r}
</script>

