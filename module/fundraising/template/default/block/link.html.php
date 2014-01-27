<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if $aCampaign.can_edit_campaign}

		
	{if !$aCampaign.is_closed }

	   {if $aCampaign.module_id == 'pages'}
		  <li><a href="{url link="fundraising.add" id=""$aCampaign.campaign_id"" module="pages" item=""$aCampaign.item_id""}">{phrase var='fundraising.edit'}</a></li>
	   {else}
		  <li><a href="{url link="fundraising.add" id=""$aCampaign.campaign_id""}">{phrase var='fundraising.edit'}</a></li>
	   {/if}

   {/if}

   
{/if}

{if $aCampaign.can_email_to_all_donors}
<li> 
	<a href="#" title="{phrase var='fundraising.send_mail_to_all_donors'}" onclick="$Core.box('fundraising.sendMailToAllDonors',800,'&campaign_id={$aCampaign.campaign_id}'); return false;">{phrase var='fundraising.send_mail_to_all_donors'}</a>
</li>
{/if}

{if $aCampaign.can_view_statistic}
      <li><a href="{url link="fundraising.list."$aCampaign.campaign_id}">{phrase var='fundraising.view_statistics'}</a></li>
 {/if}
{if $aCampaign.can_feature_campaign}
		 <li id="js_fundraising_feature_{$aCampaign.campaign_id}">
        {if $aCampaign.is_featured}
                <a href="#" title="{phrase var='fundraising.un_feature_this_fundraising'}" onclick="$.ajaxCall('fundraising.feature', 'campaign_id={$aCampaign.campaign_id}&amp;type=0', 'GET'); return false;">{phrase var='fundraising.un_feature'}</a>
        {else}
                <a href="#" title="{phrase var='fundraising.feature_this_fundraising'}" onclick="$.ajaxCall('fundraising.feature', 'campaign_id={$aCampaign.campaign_id}&amp;type=1', 'GET'); return false;">{phrase var='fundraising.feature'}</a>
        {/if}
        </li>
{/if}

{if $aCampaign.can_highlight_campaign}
        <li id="js_fundraising_highlight_{$aCampaign.campaign_id}">
        {if $aCampaign.is_highlighted}
                <a href="#" title="{phrase var='fundraising.un_highlight_this_campaign'}" onclick="$.ajaxCall('fundraising.highlight', 'campaign_id={$aCampaign.campaign_id}&amp;type=0', 'GET'); return false;">{phrase var='fundraising.un_highlight'}</a>
        {else}
                <a href="#" title="{phrase var='fundraising.highlight_this_campaign'}" onclick="$.ajaxCall('fundraising.highlight', 'campaign_id={$aCampaign.campaign_id}&amp;type=1', 'GET'); return false;">{phrase var='fundraising.highlight'}</a>
        {/if}
        </li>
{/if}

{if $aCampaign.can_close_campaign}
        <li id="js_fundraising_close_{$aCampaign.campaign_id}">
			{if $aCampaign.user_id == Phpfox::getUserId()}
				<a href="#" title="{phrase var='fundraising.close_this_campaign'}" onclick="if(confirm('{phrase var='fundraising.are_you_sure_info'}')) $.ajaxCall('fundraising.closeCampaign', '&campaign_id={$aCampaign.campaign_id}&amp;is_owner=1', 'GET'); return false;">{phrase var='fundraising.close'}</a>
			{else}
				<a href="#" title="{phrase var='fundraising.close_this_campaign'}" onclick="if(confirm('{phrase var='fundraising.are_you_sure_info'}')) $Core.box('fundraising.closeCampaign', 800,'&campaign_id={$aCampaign.campaign_id}&amp;is_owner=0', 'GET'); return false;">{phrase var='fundraising.close'}</a>	
			{/if}
        </li>
{/if}



{if $aCampaign.can_delete_campaign }
	   {if isset($bFundraisingView) && $bFundraisingView == true}
		  <li class="item_delete"><a href="{url link='fundraising' delete=$aCampaign.campaign_id}" class="sJsConfirm">{phrase var='fundraising.delete'}</a></li>
	   {else}
		  <li class="item_delete"><a href="#" title="{phrase var='fundraising.delete'}" onclick="if (confirm('{phrase var='fundraising.are_you_sure_you_want_to_delete_this_fundraising' phpfox_squote=true}')) $.ajaxCall('fundraising.inlineDelete', 'item_id={$aCampaign.campaign_id}'); return false;">{phrase var='fundraising.delete'}</a></li>
	   {/if}
{/if}
