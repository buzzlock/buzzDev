<div class="table_left">
	{*{if $aField.is_required}
		{required}
		<input type="hidden" name="customfield_req[{$aField.field_id}]" value="{$sPhraseVarName}" />
		<div style="display: none;" id="msg_{$aField.field_id}" class="validstp">{phrase var=$sPhraseVarName}</div>
	{/if}*} {$sDisplay}
</div>
<div class="table_right">
</div>
