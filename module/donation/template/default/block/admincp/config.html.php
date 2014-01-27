<?php
/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Donation
 * @version 		$Id: ajax.class.php 1 2012-02-15 10:33:17Z YOUNETCO $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<form id="postform_id"  method="post">
<div class="message" style="display:none;"></div>
<div class="error_message" style="display:none;"></div>
<div class="general">
	<div class="table" style="height:30px;">
	    <div class="table_left" style="width:35%; float:left;">
	        {phrase var='donation.enable_donation_on_this_page'} 
	    </div>
	    <div class="table_right" style="width:65%; float:right">			
	        <div class="item_is_active_holder">
	            <input type="hidden" name="iPageId" id="iPageId" value="{$iPageId}">
	            {if $iActive}
	                <span class="js_item_active item_is_active"><input type="radio" class="checkbox" checked="checked" name="donation" value="1"> {phrase var='donation.yes'}</span>
	                <span class="js_item_active item_is_not_active"><input type="radio" class="checkbox" name="donation" value="0"> {phrase var='donation.no'}</span>
	            {else}
	                <span class="js_item_active item_is_active"><input type="radio" class="checkbox" name="donation" value="1"> {phrase var='donation.yes'}</span>
	                <span class="js_item_active item_is_not_active"><input type="radio" class="checkbox" checked="checked" name="donation" value="0"> {phrase var='donation.no'}</span>
	            {/if}            
	        </div>
	    </div>    
	</div>
	<div class="table" style="height:30px;">
	    <div class="table_left" style="width:35%; float:left;">
	        {phrase var='donation.input_your_paypal_email_account'}
	    </div>
	    <div class="table_right" style="width:65%; float:right">
	       <input type="text" name="email" id="email" value="{$sEmail}" style="width:170px" />
	    </div>
	</div>
	<div class="table" style="height:120px;">
	    <div class="table_left" style="width:35%; float:left;">
	        {phrase var='donation.purpose_of_donation'}
	    </div>
	    <div class="table_right" style="width:65%; float:right">
				<textarea style="width: 360px;" rows="6"  name="content">{$content}</textarea>
	     </div>
	</div>
</div>
<div class="clear"> </div>
<div class="terms">
	<div class="table" style="height:120px;" >
		<div class="table_left" style="width:35%; float:left;">
			{phrase var='donation.terms_and_conditions'}
		</div>
		<div class="table_right" style="width:65%; float:right">
			<textarea style="width: 360px;" rows="6"  name="term_of_service">{$sTermOfService}</textarea>
	     </div>
	</div>
</div>
<div class="clear"> </div>
<div class="emails">
	<div class="table">
		<h3>{phrase var='donation.email_template'}</h3>
		<div class="table_left">
			{phrase var='donation.subject'}
		</div>
		<div class="table_right" style="width:65%; float:right">
			<input type="text" id="email_subject"  name="email_subject" value="{$sSubject}" />
		</div>
		<div class='clear'> </div>
		<div class="table_left" >
			{phrase var='donation.content'}
		</div>
		<div class="table_right" style="width:65%; float:right">
			{editor id='email_content' rows='15'}
		</div>
		<div class="clear"></div>
		<div class="extra_info table">
		{phrase var='donation.keyword_substitutions'}:
		<ul>
			<li>{phrase var='donation.123_full_name_125_recipient_s_full_name'}</li>
			<li>{phrase var='donation.123_user_name_125_recipient_s_user_name'}</li>
			<li>{phrase var='donation.123_site_name_125_site_s_name'}</li>
		</ul>
	</div>
	</div>
        <div class="clear"> </div>
	<div class="table" style="height:10px;">
	    <div class="table_left"></div>
	    <div class="table_right">
		<input type="button" class="button" name="btnUpdate" id="btnUpdate" value="{phrase var='donation.update'}" />		
	</div>
</div>
</div>
</form>
{literal}
<script type="text/javascript">
    $Behavior.DonationConfig = function() {
        $(document).ready(function () {
            Editor.sEditorId= 'email_content';
        });
        
        $('#btnUpdate').click(function(){
            var iPageId = $('#iPageId').val();
            $("#postform_id").ajaxCall('donation.updateConfig');
        });

    }
	
    
    function showTerms()
    {
    	$('.general').css('display', 'none');
    	$('.emails').css('display', 'none');
    	$('.terms').css('display', '');
    }
    function showGenerals()
    {
    	$('.emails').css('display', 'none');
    	$('.terms').css('display', 'none');
    	$('.general').css('display', '');
    }
    function showEmails()
    {
		Editor.sEditorId= 'email_content';
		$Core.loadInit();
    	$('.terms').css('display', 'none');
    	$('.general').css('display', 'none');
    	$('.emails').css('display', '');
		 $('#btnUpdate').click(function(){
			var iPageId = $('#iPageId').val();
			$("#postform_id").ajaxCall('donation.updateConfig');
		});
    }
</script>
<style type="text/css">
.emails, .general, .terms
{
	border-bottom: 1px solid #DFDFDF;
	padding-top:10px;
	padding-bottom:10px;
}
.table_left
{
	width:35%;
	float:left;
}
#email_subject
{
	margin-bottom: 5px;
	width: 418px;
}
</style>
{/literal}