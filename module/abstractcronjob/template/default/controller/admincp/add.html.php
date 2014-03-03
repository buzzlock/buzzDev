

{*<h1>Manage Cronjobs by Abstract Enterprises</h1>*}
<h2>Check out our other plugins at <a href="http://phpfoxmods.net" target="_blank"><u>http://phpfoxmods.net</u></a></h2>


{if $bCronCreatedError == true}
<div class="error_message">Please enter all fields!</div>
{/if}

    <form method="post" action="">
        
        {* Header Row *}
        <div class="table_header">
        Add New Cron Job 
        </div>
        
            <div class="table">
                <div class="table_left">
                    Product:
                </div>
                <div class="table_right">
                   	<select name="product_id">
                    {foreach from=$aProducts item=aProduct}
                        <option value="{$aProduct.product_id}"
                        {if isset($aNewCron.product_id) && $aNewCron.product_id == $aProduct.product_id}selected{/if}  >{$aProduct.title}</option>
                    {/foreach}
                    </select>    
                </div>
                <div class="clear"></div>
            </div>
            
            <div class="table">
                <div class="table_left">
                    Module:
                </div>
                <div class="table_right">
                   <select name="module_id">
                    {foreach from=$aModules key=sModule item=iModuleId}
                        <option value="{$sModule}" 
                        {if isset($aNewCron.module_id) && $aNewCron.module_id == $iModuleId}selected{/if} >{translate var=$sModule prefix='module'}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="clear"></div>
            </div>
             {* BEGIN Row *}
            <div class="table">
                <div class="table_left">
                    On?:
                </div>
                <div class="table_right">
                   
                   <input type="radio" name="is_active" value="0" 
                   {if !isset($aNewCron.is_active) || (isset($aNewCron.is_active) && $aNewCron.is_active == 0)}checked{/if} /> Off<br>
                   <input type="radio" name="is_active" value="1" 
                   {if isset($aNewCron.is_active) && $aNewCron.is_active == 1}checked{/if} /> On
                    
                </div>
                <div class="clear"></div>
            </div>
            {* END Row *}
            
            {* BEGIN Row *}
            <div class="table">
                <div class="table_left">
                    Frequency:
                </div>
                <div class="table_right">
                   Every 
                   <input type="text" size="3" name="every" value="{if isset($aNewCron.every)}{$aNewCron.every}{/if}" /> 
                   
                   <select name="type_id">
                   	<option value="1" {if isset($aNewCron.type_id) && $aNewCron.type_id == 1}selected{/if} >Minutes</option>
                    <option value="2" {if isset($aNewCron.type_id) && $aNewCron.type_id == 2}selected{/if} >Hours</option>
                    <option value="3" {if isset($aNewCron.type_id) && $aNewCron.type_id == 3}selected{/if} >Days</option>
                    <option value="4" {if isset($aNewCron.type_id) && $aNewCron.type_id == 4}selected{/if} >Months</option>
                    <option value="5" {if isset($aNewCron.type_id) && $aNewCron.type_id == 5}selected{/if} >Years</option>
                   </select>
                    
                </div>
                <div class="clear"></div>
            </div>
            {* END Row *}
            
            {* BEGIN Row *}
            <div class="table">
                <div class="table_left">
                    Php Code:
                </div>
                <div class="table_right">
                   
                   <textarea name="php_code" style="width:98%;height:200px;" 
                   id="php_code">{if isset($aNewCron.php_code)}{$aNewCron.php_code}{/if}</textarea>
                    
                </div>
                <div class="clear"></div>
            </div>
            {* END Row *}
            
              
        
        {* Submit Button *}
        <div class="table_clear">
            <input type="hidden" name="abstract_form_posted" value="CRON"  />
            <input type="submit" value="Create" class="button" />
        </div>
        
        
    </form>