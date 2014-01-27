<div class="table">
    <div class="table_left">
        {if $aField.is_required==1}{required}{/if}{phrase var=$aField.phrase_var_name}
    </div>
    <div class="table_right">
        {if $aField.var_type=='text'}
        <input id="js_jp_cf_{$aField.field_id}" type="text" name="val[custom][{$aField.field_id}]" maxlength="255" />
        
        {elseif $aField.var_type=='textarea'}
        <textarea id="js_jp_cf_{$aField.field_id}" cols="35" rows="4" name="val[custom][{$aField.field_id}]"></textarea>
        
        {elseif $aField.var_type=='select'}
        <select id="js_jp_cf_{$aField.field_id}" name="val[custom][{$aField.field_id}][]">
            {if !$aField.is_required}
            <option value="">{phrase var='jobposting.select'}:</option>
            {/if}
            {foreach from=$aField.option key=opId item=opPhrase}
            <option value="{$opId}">{phrase var=$opPhrase}</option>
            {/foreach}
        </select>
        
        {elseif $aField.var_type=='multiselect'}
        <select id="js_jp_cf_{$aField.field_id}" name="val[custom][{$aField.field_id}][]" size="4" multiple="yes">
            {foreach from=$aField.option key=opId item=opPhrase}
            <option value="{$opId}">{phrase var=$opPhrase}</option>
            {/foreach}
        </select>
        
        {elseif $aField.var_type=='checkbox'}
            {foreach from=$aField.option key=opId item=opPhrase}
            <label><input id="js_jp_cf_{$aField.field_id}" type="checkbox" name="val[custom][{$aField.field_id}][]" value="{$opId}" /> {phrase var=$opPhrase}</label><br />
            {/foreach}
        
        {elseif $aField.var_type=='radio'}
            {foreach from=$aField.option key=opId item=opPhrase}
            <label><input id="js_jp_cf_{$aField.field_id}" type="radio" name="val[custom][{$aField.field_id}][]" value="{$opId}" /> {phrase var=$opPhrase}</label><br />
            {/foreach}
        {/if}
    </div>
</div>