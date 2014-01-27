

{literal}
<script type="text/javascript">
	function radioresume(itype){
		if(itype==0){
			$('#div_photo_resume').show();
			$('#div_list_resume').hide();
		}
		else
		{
			$('#div_photo_resume').hide();
			$('#div_list_resume').show();
		}
	}
</script>{/literal}
	
<div class="ynjp_apply_header_holder" style="border-top: 1px solid #DFDFDF;padding-top:8px;">
	<div class="ynjp_applyformTitle"> {$aCompany.form_title} </div>
	<p class="ynjp_applyformDesc"> {$aCompany.form_description} </p>
</div>
<div class="clear"> </div>
<div class="ynjp_apply_Jobheader_holder">
	<a title="Postcards" href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}" class="ynjp_applyform_infoThumb">
		{if $aCompany.logo_path==""}
			{img server_id=$aCompany.server_id path='core.url_pic' file="jobposting/".$aCompany.image_path suffix='_50' max_width='50' max_height='50' class='js_mp_fix_width'}
		{else}
			{img server_id=$aCompany.server_id path='core.url_pic' file="jobposting/".$aCompany.logo_path suffix='_50' max_width='50' max_height='50' class='js_mp_fix_width'}
		{/if} 
	</a>
	<div class="ynjp_applyJobform_Info">
		<div class="ynjp_applyJobTitle"> {$aJob.title} </div>
		<p class="ynjp_applyformInfo"> <strong>{$aCompany.name}</strong> - {$aCompany.industrial_phrase} </p>
	</div>
</div>
<div class="clear"> </div>

<form method="post" enctype="multipart/form-data" action="{permalink module='jobposting.applyjob' id=$aJob.job_id title=$aJob.title}" id="form_apply_job" class="ynjp_apply_Job_form">

	{if isset($aCompany.candidate_name_enable) && $aCompany.candidate_name_enable==1}
<div class="table">
	<div class="table_left">
		{if isset($aCompany.candidate_name_require) && $aCompany.candidate_name_require==1}{required}{/if}{phrase var='jobposting.your_name'}
	</div>
	<div class="table_right">
		<input type="text" name="val[name]" value="{if isset($aForms.name)}{$aForms.name}{/if}"/>
	</div>
</div>
{/if}

{if isset($aCompany.candidate_photo_enable) && $aCompany.candidate_photo_enable==1}
<div class="table">
	<div class="table_left">
		{if isset($aCompany.candidate_photo_require) && $aCompany.candidate_photo_require==1}{required}{/if}{phrase var='jobposting.your_photo'}
	</div>
	<div class="table_right">
		<input id="image" type="file" name="image">
	</div>
</div>
{/if}

{if isset($aCompany.candidate_email_enable) && $aCompany.candidate_email_enable==1}
<div class="table">
	<div class="table_left">
		{if isset($aCompany.candidate_email_require) && $aCompany.candidate_email_require==1}{required}{/if}{phrase var='jobposting.your_email'}
	</div>
	<div class="table_right">
		<input type="text" name="val[email]" value="{if isset($aForms.email)}{$aForms.email}{/if}"/>
	</div>
</div>
{/if}

{if isset($aCompany.candidate_telephone_enable) && $aCompany.candidate_telephone_enable==1}
<div class="table">
	<div class="table_left">
		{if isset($aCompany.candidate_telephone_require) && $aCompany.candidate_telephone_require==1}{required}{/if}{phrase var='jobposting.your_telephone'}
	</div>
	<div class="table_right">
		<input type="text" name="val[telephone]" value="{if isset($aForms.telephone)}{$aForms.telephone}{/if}"/>
	</div>
</div>
{/if}

{if count($aFields)}
    {foreach from=$aFields item=aField}
        {template file='jobposting.block.custom.form'}
    {/foreach}
{/if}

<div class="table">
	<div class="table_left">
		{required}{phrase var='jobposting.resume'}
	</div>
	<div class="table_right">
		<input {if !$module_resume || !$aCompany.resume_enable}style="display:none"{/if} onclick="radioresume(0);" value="0" type="radio" name="val[resume_type]" checked="true"/> {phrase var='jobposting.upload_file'}
		<br/>
		{if $module_resume && $aCompany.resume_enable}
			<input onclick="radioresume(1);" value="1" type="radio" name="val[resume_type]" {if isset($aForms.resume_type) && $aForms.resume_type==1}checked="true"{/if}/> {phrase var='jobposting.use_my_resume'}
			<br/>
		{/if}
		
		<div id="div_photo_resume" {if isset($aForms.resume_type) && $aForms.resume_type==1}style="display:none"{/if}>
			<input id="resume" type="file" name="resume">
			<div>{phrase var='jobposting.format_ms_word_pdf_zip_500kb_maximum'}</div>
		</div>
		
		<div id="div_list_resume" {if !isset($aForms.resume_type) || $aForms.resume_type==0}style="display:none"{/if}>
			{if $module_resume && $aCompany.resume_enable}
				{if count($aResumes)>0}
					<select name="val[list_resume]">
						{foreach from = $aResumes item=aResume}
							<option value="{$aResume.resume_id}">{$aResume.headline}</option>
						{/foreach}
					</select>
				{else}
					{phrase var='jobposting.sorry_you_don_t_have_any_resume_click' link=$resumeaddlink} 
				{/if}
			{/if}
		</div>
	</div>
</div>

<div class="table ynjp_applyForm_submit">
	<div class="table_left">
		&nbsp;
	</div>
	<div class="table_right">
		<input type="submit" class="button" value="{phrase var='jobposting.apply'}"/>
	</div>
</div>
</form>



