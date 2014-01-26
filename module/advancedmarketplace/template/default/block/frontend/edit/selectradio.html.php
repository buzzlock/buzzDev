<div class="table_left">
	{if $aField.is_required}
		{required}
		<input type="hidden" name="customfield_req[{$aField.field_id}]" value="{$sPhraseVarName}" />
		<div style="display: none;" id="msg_{$aField.field_id}" class="validstp">{phrase var=$sPhraseVarName}</div>
	{/if} <span for="title">{phrase var=$sPhraseVarName}</span>
</div>
<div class="table_right">
	{foreach from=$aField.options key=iKey item=aOption}
		<label for="custom_field_{$aField.field_id}_{$iKey}"><input id="custom_field_{$aField.field_id}_{$iKey}"{if $aField.data == $aOption} checked="checked"{/if} name="customfield[{$aField.field_id}]" type="radio" value="{$aOption}" />{phrase var=$aOption}</label>
	{/foreach}
</div>