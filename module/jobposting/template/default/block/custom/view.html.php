<div class="table">
    <div class="table_left">
        {phrase var=$aField.phrase_var_name}
    </div>
    <div class="table_right">
        {if $aField.var_type=='text' || $aField.var_type=='textarea'}
        {$aField.value}
        {elseif isset($aField.option)}
            {foreach from=$aField.option item=opPhrase}
            {phrase var=$opPhrase}<br />
            {/foreach}
        {/if}
    </div>
</div>