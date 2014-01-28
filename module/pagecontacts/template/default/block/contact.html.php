<?php
/**
 * @copyright		YouNet Company
 * @author  		MinhNTK
 * @package  		Module_Pagecontacts
 * @version 		3.01
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<form id="core_js_pagecontact" method="post">
	<div class="error_message" style="display:none;"></div>
	<div class="table yn_pagecontact_description">
		<div class="table_left">
			{*{phrase var='pagecontacts.description'}*}
		</div>
		<div class="table_right">
			{$aContact.contact_description}
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='pagecontacts.full_name'}: 
		</div>
		<div class="table_right">
			<input type="text" name="val[full_name]" {if isset($sFullName)}value="{$sFullName}"{/if} size="50" maxlength="250" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='pagecontacts.email'}: 
		</div>
		<div class="table_right">
			<input type="text" name="val[email]" {if isset($sEmail)}value="{$sEmail}"{/if} size="50" maxlength="250" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='pagecontacts.topics'}:
		</div>
		<div class="table_right">
			<select name="val[topic]">
				{foreach from=$aTopics	item=aTopic}
					<option value="{$aTopic.topic_id}">{$aTopic.topic}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='pagecontacts.subject'}: 
		</div>
		<div class="table_right">
			<input type="text" name="val[subject]" size="50" maxlength="250" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='pagecontacts.message'}: 
		</div>
		<div class="table_right">
			<textarea cols="50" name="val[message]" rows="10"></textarea>
		</div>
		<div class="clear"></div>
	</div>
	<input type="button" id="btnContactSend" class="button" value="{phrase var='pagecontacts.submit'}" />
</form>
{literal}
<script type="text/javascript">
	$Behavior.onLoadContactPopUp = function()
	{
		$('#btnContactSend').click(function(){
			$(this).addClass('disabled').attr('disabled','disabled');
			$("#core_js_pagecontact").ajaxCall('pagecontacts.sendMail');
		});
	}
	$Core.loadInit();
</script>
<style>
	.yn_pagecontact_description
	{
		border: 1px solid #CCC;
		padding: 5px;
		margin-bottom: 10px;
	}
</style>
{/literal}