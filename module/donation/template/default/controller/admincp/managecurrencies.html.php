<form method='post' action="{url link='admincp.donation.managecurrencies'}">
{foreach from=$aCurrencies key=key item=sCurrency}
{if ($key % 3 eq 0) and ($key != 0 )}
<div class="clear"/>
{/if}
<div class="yn_donation_currency_checkbox" >
<input type='checkbox' value="{$sCurrency}" name="aVals[aCurrencies][]" 
	  {if in_array($sCurrency ,$aCurrentCurrencies)} checked='1' {/if} 
/> {$sCurrency} 
</div>
{/foreach}
<div class='clear' />
<div class='extra_info'>
	{phrase var='donation.if_no_currency_is_chosen_usd_will_be_used_as_default_currency'}
</div>
<div class='clear' />
<input type="hidden" name="aVals[bIsEditForm]" value="1"/>
<input type="submit" value="{phrase var='donation.save'}" class="button" />
</form>
