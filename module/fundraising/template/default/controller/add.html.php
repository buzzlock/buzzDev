<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<script type="text/javascript">


    $Behavior.initFundraisingFormValidation = function(){
		$('.ynfr_add_edit_form').each(function(index) {
			ynfundraising.initializeValidator($(this));
				
		});
		
			
	};
	
	function plugin_addFriendToSelectList()
	{
		$('#js_allow_list_input').show();
	}

	$Behavior.initFundraisingForm = (function(){
		$('#fundraising_goal').keydown(function (e) {
                  if (e.altKey || e.ctrlKey) {
			    e.preventDefault();
			}
			else if (e.shiftKey && !(e.keyCode >= 35 && e.keyCode <= 40)){
				  e.preventDefault();
			} else {
			    var n = e.keyCode;
			    if (!((n == 8)
			    || (n == 46)
			    || (n >= 35 && n <= 40)
			    || (n >= 48 && n <= 57)
			    || (n >= 96 && n <= 105))
			    ) {
				  e.preventDefault();
			    }
			}
		});
	});

		
    $Behavior.setDescEditor = (function(){
		 Editor.setId("description");

        $("a[rel='js_fundraising_block_main']").bind("click", function(){
            Editor.setId("description");
        });
        $("a[rel='js_fundraising_block_contact_information']").bind("click", function(){
            Editor.setId("contact_about_me");
        });
        $("a[rel='js_fundraising_block_email_conditions']").bind("click", function(){
            Editor.setId("email_message");
        });

     });
</script>
<style type="text/css">
	div.row_focus {
		background: none repeat scroll 0 0 #FEFBD9;
	 }
</style>
{/literal}
<div class="main_break">
	{$sCreateJs}
	<form method="post" class="ynfr_add_edit_form" action="{url link='current'}" id="ynfr_edit_campaign_form"  enctype="multipart/form-data">
		<div id="js_custom_privacy_input_holder">
		{if $bIsEdit && empty($sModule)}
			{module name='privacy.build' privacy_item_id=$aForms.campaign_id privacy_module_id='fundraising'}
		{/if}
		</div>

		<div><input type="hidden" name="val[attachment]" class="js_attachment" value="{value type='input' id='attachment'}" /></div>
		<div><input type="hidden" name="val[selected_categories]" id="js_selected_categories" value="{value type='input' id='selected_categories'}" /></div>
		<div><input type="hidden" name="val[is_approved]" value="{value type='input' id='is_approved'}" /></div>

		{if !empty($sModule)}
			<div><input type="hidden" name="module" value="{$sModule|htmlspecialchars}" /></div>
		{/if}
		{if !empty($iItem)}
			<div><input type="hidden" name="item" value="{$iItem|htmlspecialchars}" /></div>
		{/if}
		{if $bIsEdit}
			<div><input type="hidden" name="id" value="{$aForms.campaign_id}" /></div>
		{/if}
		{plugin call='fundraising.template_controller_add_hidden_form'}

		{module name='fundraising.campaign.form-main-info'}
	</form>	
		{if $bIsEdit}
			{module name='fundraising.campaign.form-gallery' iCampaignId=$aForms.campaign_id}

			{*{module name='fundraising.campaign.form-sponsor-levels' iCampaignId=$aForms.campaign_id}*}

			{module name='fundraising.campaign.form-contact-information' iCampaignId=$aForms.campaign_id}

			{module name='fundraising.campaign.form-email-conditions' iCampaignId=$aForms.campaign_id}
			
			{module name='fundraising.campaign.form-invite-friend' iCampaignId=$aForms.campaign_id}

			{*{module name='fundraising.campaign.form-financial-configuration' iCampaignId=$aForms.campaign_id}*}
			
		{else}
	</form>
		{/if}
	

</div>
<!--P_Check-->
{if $bIsEdit && $sTab != ''}
{literal}
<script type="text/javascript">
    $Behavior.pageSectionMenuRequest = function() {
        if (!bIsFirstRun) {
            $Core.pageSectionMenuShow('#js_fundraising_block_{/literal}{$sTab}{literal}');
            if ($('#page_section_menu_form').length > 0) {
                $('#page_section_menu_form').val('js_fundraising_block_detail');
            }
            bIsFirstRun = true;
        }
    }

    function ClickAll(all) {
        if(all.val() == oTranslations['fundraising.select_all'])
            all.val(oTranslations['fundraising.un_select_all']);
        else
            all.val(oTranslations['fundraising.select_all']);

		$(".label_flow .checkbox").click();
        $(".friend_search_holder").click();
    }
</script>
{/literal}
{/if}

