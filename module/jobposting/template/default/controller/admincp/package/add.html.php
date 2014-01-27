		

{$sCreateJs}
<div class="table_header">
	{if $bIsEdit}
		{phrase var='jobposting.edit_a_package'}
	{else}
		{phrase var='jobposting.add_new_package'}
	{/if}
		
</div>
<form method="post" enctype="multipart/form-data" action="{url link='admincp.jobposting.package.add'}{if $bIsEdit }id_{$aForms.package_id}/ {/if}" id="js_add_package_form" name="js_add_package_form">			
<div class="table">
	<div class="table_left">
		{required}{phrase var='jobposting.job_posting_package_name'}
	</div>
	<div class="table_right">
		<input type="text" name="val[name]" id ="name" value="{if isset($aForms.name)}{$aForms.name}{/if}" style="width:250px"/>
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{required}{phrase var='jobposting.post_job_number'}
	</div>
	<div class="table_right">
		<input type="text" name="val[post_number]" id ="name" value="{if isset($aForms.post_number)}{$aForms.post_number}{/if}" style="width:250px"/>
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{required}{phrase var='jobposting.valid_period'}
	</div>
	<div class="table_right">
		<input type="text" name="val[expire_number]" id ="name" value="{if isset($aForms.expire_number)}{$aForms.expire_number}{/if}" style="width:250px"/>
		<select name="val[expire_type]">
			<option value="1" {if isset($aForms.expire_type) && $aForms.expire_type==1}selected{/if}>{phrase var='jobposting.day'}</option>
			<option value="2" {if isset($aForms.expire_type) && $aForms.expire_type==2}selected{/if}>{phrase var='jobposting.week'}</option>
			<option value="3" {if isset($aForms.expire_type) && $aForms.expire_type==3}selected{/if}>{phrase var='jobposting.month'}</option>
			<option value="0" {if isset($aForms.expire_type) && $aForms.expire_type==0}selected{/if}>{phrase var='jobposting.never_expires'}</option>
		</select>
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{required}{phrase var='jobposting.package_fee'}
	</div>
	<div class="table_right">
		<input type="text" name="val[fee]" id ="name" value="{if isset($aForms.fee)}{$aForms.fee}{/if}" style="width:250px"/>
	</div>
	<div class="clear"></div>
</div>

	<div class="table_clear">
		    <input type="submit" name="val[submit]" value="Save" class="button" />
		</div>
</form>