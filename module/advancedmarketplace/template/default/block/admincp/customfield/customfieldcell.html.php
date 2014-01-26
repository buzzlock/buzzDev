<div class="yn_jh_customfield">
	{*<pre>
	<?php var_dump($this->_aVars["aCellCustomFields"]); ?>
	</pre>*}
	<table class="gray_rows">
		<tr><td colspan="2">
			<strong>{if $isAdd}{$aCellCustomFields.text}{else}{phrase var=$aCellCustomFields.phrase_var_name}{/if}</strong>
			<input class="fieldactive" type="hidden" value="{$aCellCustomFields.is_active}" name="customfield[{$aCellCustomFields.field_id}][is_active]" />
			<div style="float: right;">
				<a href="#" title="{if $aCellCustomFields.is_active == "1"}{phrase var='advancedmarketplace.switch_off'}{else}{phrase var='advancedmarketplace.switch_on'}{/if}" class="bullet {if $aCellCustomFields.is_active == "1"}on{else}off{/if} onoffswitch" ref="{$sKeyVarCell}">&nbsp;</a>
				<a href="#" title="{phrase var='advancedmarketplace.move_up'}" class="btn up moveup">&nbsp;</a>
				<a href="#" title="{phrase var='advancedmarketplace.move_down'}" class="btn down movedown">&nbsp;</a>
				<a href="#" title="{phrase var='advancedmarketplace.delete'}" class="btn delete btndelete" ref="{$aCellCustomFields.field_id}">&nbsp;</a>
			<div>
		</td></tr>
		<tr>
			<td><label>{phrase var='advancedmarketplace.field_name'}: </label></td>
			<td>
				<input name="customfield[{$aCellCustomFields.field_id}][field_name]" type="text" value="{if $isAdd}{$aCellCustomFields.text}{else}{phrase var=$aCellCustomFields.phrase_var_name}{/if}" />
				<input name="customfield[{$aCellCustomFields.field_id}][var_field_name]" type="hidden" value="{$aCellCustomFields.phrase_var_name}" />
			</td>
		</tr>
		<tr>
			<td><label>{phrase var='advancedmarketplace.field_type'}: </label></td>
			<td>
				<select class="field_type" name="customfield[{$aCellCustomFields.field_id}][field_type]">
					<option{if $aCellCustomFields.var_type === NULL} selected="selected"{/if} value="">{phrase var='advancedmarketplace.select_type'}</option>
					{foreach from=$aCustomFieldInfors key=iKey item=aInfor}
						<option{if $aCellCustomFields.var_type === $iKey} selected="selected"{/if} value="{$iKey}">{phrase var=$iKey}</option>
					{/foreach}
				</select>
				<div class="tag"></div>
				<div class="sub_tags options_anchor_{$aCellCustomFields.field_id}">
					{if isset($aCellCustomFields.options)}
						{foreach from=$aCellCustomFields.options item=aOption}
							{module name="advancedmarketplace.admincp.customfield.customfieldoption" iCusfieldId=$aCellCustomFields.field_id sKeyVarOption=$aOption}
						{/foreach}
					{/if}
					<a href="#" class="add_option" ref="{$aCellCustomFields.field_id}" title="{phrase var='advancedmarketplace.add_an_option'}">+ {phrase var='advancedmarketplace.add_an_option'}</a>
					<img class="ajxloader" src="{$corepath}module/advancedmarketplace/static/image/default/ajxloader.gif" />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><label><input type="checkbox" name="customfield[{$aCellCustomFields.field_id}][is_require]" value="required"{if $aCellCustomFields.is_required == 1} checked="checked"{/if} /> {phrase var='advancedmarketplace.is_required'}</label></td>
		</tr>
	</table>
</div>

{*foreach from=$aCustomFieldInfors key=iKey item=aInfor}
	<pre>
		<?php var_dump($this->_aVars["aInfor"]); ?>
	</pre>
{/foreach*}