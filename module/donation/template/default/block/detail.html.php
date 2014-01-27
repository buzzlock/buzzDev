<?php
/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Donation
 * @version 		$Id: ajax.class.php 1 2012-02-15 10:33:17Z YOUNETCO $
 */
defined('PHPFOX') or exit('NO DICE!');

?>

<div id="alertBox"></div>
<div id="dMessage" class="message" style="display:none;"></div>
<div class="table" style="margin-top: -10px;width:600px; max-height: 700px "> 
	<div id="donation_popup1">

			<p style=" font-weight:bold;">{phrase var='donation.donate'} <input type="text" name="quanlity" id="quanlity" value="0" style="width:40px"> 
			<select id="yn_donation_select_currency" name='yn_donation_select_currency'>
				{foreach from=$aCurrentCurrencies key=key item=sCurrency}
					<option value="{$sCurrency}">
						{$sCurrency}
					</option>
				{/foreach}
			</select>
				<span style="padding-left: 20px;" id="btnBlock">
					<a href="#" onclick="checkQuanlity(); return false;"><img src="{param var='core.url_module'}donation/static/image/donate_button_small.gif" style="vertical-align: middle;" /></a>
				</span>
			</p>   

		 <div class="table_left" style="font-weight: normal;width:570px;">
			<input type="hidden" value="{$iPageId}" name="iPageId" id="iPageId" />
			<input type="hidden" value="{$iUserId}" name="iUserId" id="iUserId" />
			<input type="hidden" value="{$sUrl}" name="sUrl" id="sUrl" />
			<input type="hidden" value="http://localhost/phpfox301donation/pages/3/payment_done" name="return">
			<input type="hidden" value="{phrase var='donation.please_input_number_delimiter_by'}" id="error" />
			<input type="hidden" value="{phrase var='donation.must_agree_to_the_terms_and_conditions_to_continue'}" id="error_confirm" />
		    <input type="hidden" value="{phrase var='donation.must_fill_your_name'}" id="error_guest_name" />
		</div>
			<div class="extra_info"	style="width: 570px">
				{phrase var='donation.notice_multi_currency_conversion'}	
			</div>
			<div class="table_right" style="width:570px; color:#333333;">
			<p  style="font-weight:bold;padding-left:5px; line-height: 2em; font-size:12px; background-color: #ccc">{phrase var='donation.purpose_of_donation'}</p>
			{if $sContent}
			<div  style="width:570px; color:#333333;overflow:auto; max-height:180px;">
			  <p id="purpose" style="overflow:auto; max-height:180px;padding-left:5px; line-height: 2em; font-size:12px;">{$sContent}</p> 	 		
			</div>
			{else}
				</br>
			{/if}
			</div>
		<div class="table_right" style="width:570px; color:#333333;">
			<p style=" font-weight:bold; padding-left:5px; line-height: 2em; font-size:12px; background-color: #ccc">{phrase var='donation.donation_lists'}</p>
		   {module name='donation.donorlist' friend_share=true input='to'}
		</div>
	</div>
	 <div id="donation_popup2" style="display:none;">
	 <div class="table_right" style="width:570px; color:#333333;">
		{if Phpfox::isUser()}

		 <p style="font-weight:bold;padding-left:5px; line-height: 2em; font-size:12px; background-color: #ccc">{phrase var='donation.donation_privacy'} 
					<a href="#" id = 'donation_privacy_label'> 
					{img theme='misc/bullet_arrow_down.png' alt=''}
					</a>
			
		</p>
		
		 <div id="donation_privacy" style="display:yes">
			<input type="hidden" id="bIsGuest" name="bIsGuest"  value="0">
			<div style="clear: both;">
				<span style="float:left;"><input type="checkbox" name="do_not_show_name" id="do_not_show_name" /></span>
				<span style="float:left;margin-top:3px;margin-left:4px;">{phrase var='donation.do_not_show_name_on_donor_list'}</span>
			</div> 	
			
			<div style="clear: both;">
				<span style="float:left;"><input type="checkbox" name="do_not_show_money" id="do_not_show_money" /></span>
				<span style="float:left;margin-top:3px;margin-left:4px;">{phrase var='donation.do_not_show_donation_amount_on_donor_list'}</span>
			</div>
			
			<div style="clear: both;">
				<span style="float:left;"><input type="checkbox" name="do_not_show_feed" id="do_not_show_feed" /></span>
				<span style="float:left;margin-top:3px;margin-left:4px;">{phrase var='donation.do_not_show_feed'}</span>
			</div>
			<div style="clear: both;"></div>
		</div>
		{else}
		<p  style="font-weight:bold;padding-left:5px; line-height: 2em; font-size:12px; background-color: #ccc">{phrase var='donation.donation_guest_information'}</p>
		 <div id="donation_privacy_guest" style="display:yes">
			<div style="height:55px;">
				<div class="table_left">
				<span style="margin-left:4px;">{phrase var='donation.your_name'} *</span>
				</div>
				
				<div class='table_right'>
				<span style="margin-left:4px;"><input type="text" name="guest_name" id="guest_name" /></span>
				<!--
				<span style="margin-left:4px;" name='guest_name_hint' id='guest_name_hint'> {phrase var='donation.guest_name_hint'} </span>
				-->
				</div>
			</div>
			
			<input type="hidden" id="bIsGuest" name="bIsGuest" value="1">
		 
			<div style="clear: both;">
				<span style="float:left;"><input type="checkbox" name="do_not_show_name" id="do_not_show_name" /></span>
				<span style="float:left;margin-top:3px;margin-left:4px;">{phrase var='donation.do_not_show_name_on_donor_list'}</span>
			</div> 	
			
			<div style="clear: both;">
				<span style="float:left;"><input type="checkbox" name="do_not_show_money" id="do_not_show_money" /></span>
				<span style="float:left;margin-top:3px;margin-left:4px;">{phrase var='donation.do_not_show_donation_amount_on_donor_list'}</span>
			</div>
			<div style="clear: both;"></div>
			
		</div>
		
		{/if}
			
		 </div>
		 
		 </br>
	
		
		
		 <div class="table_right" style="width:570px; color:#333333;">
		 <p  style="font-weight:bold;padding-left:5px; line-height: 2em; font-size:12px; background-color: #ccc">{phrase var='donation.terms_and_conditions'}</p>
			{if $sTermOfService}
			 <div  style="width:570px; color:#333333;overflow:auto; max-height:180px;">
			<p id="purpose" style="overflow:auto; max-height:180px;padding-left:5px; line-height: 2em; font-size:12px;">{$sTermOfService}</p> 	
			</div>
			{else}
				</br>
			{/if}
			<div style="clear: both;">
				<span style="float:left;"><input type="checkbox" name="agree" id="agree" /></span>
				<span style="float:left;margin-top:3px;margin-left:4px;">{phrase var='donation.read_and_agree_to_the_terms_and_conditions'}</span>
			</div> 
			<div style="clear: both;"></div>		
		 </div>
		<div class="table_right" style="clear: both;">
			<input id="js_confirm_donation" type="button" class="button" value="{phrase var='donation.confirm'}" onclick="$(this).addClass('disabled').attr('disabled','disabled');checkConfirm();return false;" />
		</div>
	  
	</div>
</div>
{literal}
<script type="text/javascript">
	
    function checkQuanlity(){
        var quanlity = $('#quanlity').val();     
        var iPageId = $('#iPageId').val();
        var iUserId = $('#iUserId').val();
        var sUrl = $('#sUrl').val();
		var isEnable = $('input[name=agree]').is(':checked');

        if(((quanlity - 0) == quanlity && quanlity.length > 0 && parseFloat(quanlity)>0))
		{
		{/literal}

		{literal}
			$('#donation_popup1').css('display','none');
			$('#donation_popup2').css('display','');
		{/literal}
		{literal}
        }
		else
		{
            var ele = $('#alertBox').find('div');
            if (ele.html()==null){               
                var sError = $('#error').val();
                $('#alertBox').append("<div class='error_message'>"+sError+"</div>");
                $('#quanlity').select().focus();
                setTimeout(function(){
                    $('#alertBox').find('div').slideUp(200, function(){
                        $('#alertBox').find('div').remove();
                    });
                }, 2000);
            }
        }
    }
	
	$("#guest_name_hint").hide();
	$("#donation_privacy").hide();
	$("#donation_privacy_label").click(function() {
		if($("#donation_privacy").is(':hidden'))
		{
			$("#donation_privacy").show();
		}
		else
		{
			$("#donation_privacy").hide();
		}
		
		return false;
	});
	/*
	$("input[name=guest_name]").focus( function() {
            
		$("#guest_name_hint").show(200);
	});
	
	$("input[name=guest_name]").blur( function() {
		$("#guest_name_hint").hide(200);
	});
	*/
    $("input[name='agree']").change( function() {
  		var isEnable = $('input[name=agree]').is(':checked');
  		if(isEnable == true)
  		{
  			
  		}
	});
	function checkConfirm()
	{
		 var quanlity = $('#quanlity').val();     
        var iPageId = $('#iPageId').val();
        var iUserId = $('#iUserId').val();
        var sUrl = $('#sUrl').val();
		var isEnable = $('input[name=agree]').is(':checked');
		
		
		if(isEnable)
		{
			if($('input[name=bIsGuest]').val() == 1)
			{
				//var guestnameRegex= /^([A-Za-z0-9\s]){6,50}$/;
				var guestnameRegex= /^(.){1,100}$/;
				if(!guestnameRegex.test($("input[name=guest_name]").val())) {
					var ele = $('#alertBox').find('div');
					if (ele.html()==null){               
						var sError = $('#error_guest_name').val();
						$('#alertBox').append("<div class='error_message'>"+sError+"</div>");
						$('#quanlity').select().focus();
						setTimeout(function(){
							$('#alertBox').find('div').slideUp(200, function(){
								$('#alertBox').find('div').remove();
							});
						}, 2000);
					}
					$('#js_confirm_donation').removeClass('disabled').removeAttr('disabled');
					return false
				}
			}

			var bNotShowMoney =  $('input[name=do_not_show_money]').is(':checked') ? 1 : 0;
			var bNotShowName =  $('input[name=do_not_show_name]').is(':checked') ? 1  : 0;
			var bNotShowFeed =  $('input[name=do_not_show_feed]').is(':checked') ? 1 : 0;
			var sCurrency = $('#yn_donation_select_currency').val();
			var sGuestName = ($('input[name=guest_name]').val()) ? $('input[name=guest_name]').val() : "" ;

			$.ajaxCall('donation.addToDonationLists','iPageId='+iPageId+'&iUserId='+iUserId+'&quanlity='+quanlity
					+ '&sUrl='+sUrl+'&bNotShowMoney=' + bNotShowMoney +'&bNotShowName=' 
					+ bNotShowName +'&bNotShowFeed=' + bNotShowFeed +'&sGuestName=' + sGuestName + 
					'&sCurrency=' + sCurrency);
            $('#btnBlock').html('');
		}
		else
		{
			var ele = $('#alertBox').find('div');
            if (ele.html()==null){               
                var sError = $('#error_confirm').val();
                $('#alertBox').append("<div class='error_message'>"+sError+"</div>");
                $('#quanlity').select().focus();
                setTimeout(function(){
                    $('#alertBox').find('div').slideUp(200, function(){
                        $('#alertBox').find('div').remove();
                    });
                }, 2000);
            }
			$('#js_confirm_donation').removeClass('disabled').removeAttr('disabled');
		}
	}
</script>
<style>
#purpose:first-letter
{ 
font-size:110%;
padding-left: 7px;
text-transform: uppercase;
}
.js_donation:FIRST-LETTER{
text-transform: uppercase;
}

</style>
{/literal}