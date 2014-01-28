<?php
/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Donationpages
 * @version 		$Id: ajax.class.php 1 2012-02-15 10:33:17Z YOUNETCO $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script type="text/javascript">
$(document).ready(function ()
	{
		Editor.sEditorId= 'contact_description';
		$Core.loadInit();
	});
	
    $('#btnContactUpdate').click(function(){
		$(this).addClass('disabled').attr('disabled','disabled');
        $("#core_js_pagecontacts_config").ajaxCall('pagecontacts.addConfig');
    });
</script>
<style>
	.yn_pagecontact_topic_title
	{
		color: #666666;
		font-size: 12px;
		font-weight: bold;
	}
</style>
{/literal}
<div style="display:none;" id="hiddenQuestion">
	<div id="js_quiz_layout_default">
		{template file="pagecontacts.block.topic"}
	</div>
</div>

<form id="core_js_pagecontacts_config" method="post">
	<div>
		<input type="hidden" name="val[page_id]" value="{$iPageId}" />
	</div>
	<div class="error_message" style="display:none;width:60%; margin-bottom:10px;"></div>
	<div class="table">
	    <div class="table_left">
			{phrase var='pagecontacts.enable_contact_form'}:
	    </div>
	    <div class="table_right">			
	        <div class="item_is_active_holder">
	            {if $bIsActive}
	                <span class="js_item_active item_is_active"><input type="radio" class="checkbox" checked="checked" name="val[is_active]" value="1">{phrase var='pagecontacts.yes'}</span>
	                <span class="js_item_active item_is_not_active"><input type="radio" class="checkbox" name="val[is_active]" value="0">{phrase var='pagecontacts.no'}</span>
	            {else}
	                <span class="js_item_active item_is_active"><input type="radio" class="checkbox" name="val[is_active]" value="1">{phrase var='pagecontacts.yes'}</span>
	                <span class="js_item_active item_is_not_active"><input type="radio" class="checkbox" checked="checked" name="val[is_active]" value="0">{phrase var='pagecontacts.no'}</span>
	            {/if}            
	        </div>
	    </div>    
	</div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='pagecontacts.description'}:
		</div>
		<div class="table_right">
			<textarea  cols="80" rows="10" name="val[contact_description]">{if isset($aForms.contact_description)}{$aForms.contact_description}{/if}</textarea>
		</div>
		<div class="clear"></div>
	</div>
	<div class="yn_pagecontact_topic_title">{required}{phrase var='pagecontacts.topics'}:</div>
	<div id="js_quiz_container">
		{if isset($aForms.topics)}
			{foreach from=$aForms.topics item=Topic name=topic}
				{template file="pagecontacts.block.topic"}
			{/foreach}
		{else}
			
		{/if}			
	</div>
	<div class="pagecontacts_add_new_question" style="margin:10px 0;">
		<a href="#" id="js_add_question">{phrase var='pagecontacts.add_another_topic'}</a>				
	</div>
	<div class="table_clear">
	    <input type="button" class="button" name="btnUpdate" id="btnContactUpdate" value="{phrase var='pagecontacts.submit'}" />
	</div>
</form>