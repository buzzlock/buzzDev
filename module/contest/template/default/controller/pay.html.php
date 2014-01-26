

<form method="post" action="{url link='current'}" >

	{if $aYnContestFees.publish}
		<input type="checkbox" id="publish_fee" class='yncontest_fee' fee_value='{$aYnContestFees.publish}' name='val[publish_fee]'>{phrase var='contest.publish_contest_money' money_text=$aYnContestFees.publish} <br>
	{/if}

	{if $aYnContestFees.feature}
		<input type="checkbox" id="feature_fee" class='yncontest_fee' fee_value= '{$aYnContestFees.feature}' name='val[feature_fee]'>{phrase var='contest.register_service_feature_contest_money' money_text=$aYnContestFees.feature} <br>
	{/if}

	{if $aYnContestFees.ending_soon}
		<input type="checkbox" id="ending_soon_fee"  class='yncontest_fee' fee_value= '{$aYnContestFees.ending_soon}' name='val[ending_soon_fee]'>{phrase var='contest.register_service_ending_soon_contest_money' money_text=$aYnContestFees.ending_soon} <br>
	{/if}

	{if $aYnContestFees.premium}
		<input type="checkbox" id="premium_fee" class='yncontest_fee' fee_value= '{$aYnContestFees.premium}' name='val[premium_fee]'>{phrase var='contest.register_service_premium_contest_money' money_text=$aYnContestFees.premium} <br>
	{/if}


	{phrase var='contest.total_fee'} : <span id='yn_contest_total_fee'> 0 </span>

	<div class="table_clear">
		<ul class="table_clear_button">

			<li> <input type='submit' class="button" name='val[publish]' value='{phrase var='contest.publish'}'> </li>

			<li> <input type='button' class="button" name='val[cancel]' value='{phrase var='contest.cancel'}' onclick="location.href='{url link='contest'}'"> </li>

		</ul>
		<div class="clear"></div>
	</div>

</form>

<script type="text/javascript">

$Behavior.initializeYnContestAddRemoveFee = function() {l} 

	yncontest.pay.bindOnclickAddRemoveFees();

{r}
</script>