

{*<h1>Manage Cronjobs by Abstract Enterprises</h1>*}
<h2>Check out our other plugins at <a href="http://phpfoxmods.net" target="_blank"><u>http://phpfoxmods.net</u></a></h2>


{if $sAction == 'edit'}
    
    <form method="post" action="">
        
        {* Header Row *}
        <div class="table_header">
        Edit Cron Job 
        </div>
        
            <div class="table">
                <div class="table_left">
                    Product:
                </div>
                <div class="table_right">
                   	<select name="product_id">
                    {foreach from=$aProducts item=aProduct}
                        <option value="{$aProduct.product_id}"
                        {if $aCronEdit.product_id == $aProduct.product_id}selected{/if}  >{$aProduct.title}</option>
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
                        {if $aCronEdit.module_id == $iModuleId}selected{/if} >{translate var=$sModule prefix='module'}</option>
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
                   
                   <input type="radio" name="is_active" value="0" {if $aCronEdit.is_active == 0}checked{/if} /> Off<br>
                   <input type="radio" name="is_active" value="1" {if $aCronEdit.is_active == 1}checked{/if} /> On
                    
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
                   <input type="text" size="3" name="every" value="{$aCronEdit.every}" /> 
                   
                   <select name="type_id">
                   	<option value="1" {if $aCronEdit.type_id == 1}selected{/if} >Minutes</option>
                    <option value="2" {if $aCronEdit.type_id == 2}selected{/if} >Hours</option>
                    <option value="3" {if $aCronEdit.type_id == 3}selected{/if} >Days</option>
                    <option value="4" {if $aCronEdit.type_id == 4}selected{/if} >Months</option>
                    <option value="5" {if $aCronEdit.type_id == 5}selected{/if} >Years</option>
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
                   
                   <textarea name="php_code" style="width:98%;height:200px;" id="php_code">{$aCronEdit.php_code}</textarea>
                    
                </div>
                <div class="clear"></div>
            </div>
            {* END Row *}
            
              
        
        {* Submit Button *}
        <div class="table_clear">
            <input type="hidden" name="abstract_form_posted" value="CRON"  />
            <input type="submit" value="Update" class="button" />
        </div>
        
        
    </form>
{else}



<table cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="7" class="table_header">Crons</td>
	</tr>
    <tr>
        <th>Active</th>
        <th>Product</th>
		<th>Module</th>
		{*<th>Last Run</th>*}
		<th>Next Run</th>
        <th>Frequency</th>
        <th></th>
	</tr>
    {foreach from=$aCrons key=iKey item=aCron}
        <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td style="text-align:center;">
            	{if $aCron.is_active == 1}
                	<img src="{$sSiteUrl}theme/adminpanel/default/style/default/image/misc/bullet_green.png" />
                {else}
                	<img src="{$sSiteUrl}theme/adminpanel/default/style/default/image/misc/bullet_red.png" />
                {/if}
            </td>
            <td>{$aCron.product_id}</td>
            <td>{$aCron.module_id}</td>
            {*<td>{$aCron.last_run}</td>*}
            <td>{$aCron.next_run}</td>
            <td>
            Every {$aCron.every} 
            {if $aCron.type_id == 1}Minute(s){/if}
            {if $aCron.type_id == 2}Hour(s){/if}
            {if $aCron.type_id == 3}Day(s){/if}
            {if $aCron.type_id == 4}Month(s){/if}
            {if $aCron.type_id == 5}Year(s){/if}
            </td>
            <td>
            	<a href="{url link="admincp.abstractcronjob.manage.edit."$aCron.cron_id""}">Edit</a> 
        		- <a href="{url link="admincp.abstractcronjob.manage.delete."$aCron.cron_id""}" 
                	onclick="return confirm('Are you sure? This cannot be undone.');">Delete</a> 
                - <a href="{url link="admincp.abstractcronjob.manage.run."$aCron.cron_id""}" 
                	onclick="return confirm('Are you sure?');">Run Now</a>
            </td>
        </tr>    
    {/foreach}
</table>




{/if}




	
    


