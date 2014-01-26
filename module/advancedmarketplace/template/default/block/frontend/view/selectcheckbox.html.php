{if !empty($aField.data)}
<tr>
	<td>{phrase var=$sPhraseVarName}:</td><td>{if $aField.data == "yes"}{phrase var="advancedmarketplace.yes"}{else}{phrase var="advancedmarketplace.no"}{/if}</td>
</tr>
{/if}