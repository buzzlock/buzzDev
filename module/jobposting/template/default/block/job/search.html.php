{literal}
<script type="text/javascript">
	function submitForm(){
		$('#tmp_end_month').val($('#end_month').val());
		$('#tmp_end_day').val($('#end_day').val());
		$('#tmp_end_year').val($('#end_year').val());
		$('#jobposting_adv_search_form').submit();
	};
</script>
{/literal}

<div class="yns adv-search-block" id ="jobposting_adv_search">
	<form method="post" action="" name="jobposting_adv_search_form" id="jobposting_adv_search_form" >
		<input type="hidden" value="1" name="search[flag_advancedsearch]"/>
		<input type="hidden" value="{if isset($aForms.end_month)}$aForms.end_month{else}0{/if}" id="tmp_end_month" name="search[end_month]"/>
		<input type="hidden" value="{if isset($aForms.end_year)}$aForms.end_year{else}0{/if}" id="tmp_end_year" name="search[end_year]"/>
		<input type="hidden" value="{if isset($aForms.end_day)}$aForms.end_day{else}0{/if}" id="tmp_end_day" name="search[end_day]"/>
		<!-- Keywords -->
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.keyword'}</span>:
			<div class="p_4">
				<input type="text" name="search[keywords]" value="{if isset($aForms.keywords)}{$aForms.keywords}{/if}" id="keywords" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.company'}</span>:
			<div class="p_4">
				<input type="text" name="search[company]" value="{if isset($aForms.company)}{$aForms.company}{/if}" id="company" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.location'}</span>:
			<div class="p_4">
				<input type="text" name="search[location]" value="{if isset($aForms.location)}{$aForms.location}{/if}" id="location" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.industry'}</span>:
			<div class="p_4">
				{$aIndustryBlock}
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.language_preference'}</span>:
			<div class="p_4">
				<input type="text" name="search[language_prefer]" value="{if isset($aForms.language_prefer)}{$aForms.language_prefer}{/if}" id="language_prefer" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.education_preference'}</span>:
			<div class="p_4">
				<input type="text" name="search[education_prefer]" value="{if isset($aForms.education_prefer)}{$aForms.education_prefer}{/if}" id="education_prefer" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.working_place'}</span>:
			<div class="p_4">
				<input type="text" name="search[working_place]" value="{if isset($aForms.working_place)}{$aForms.working_place}{/if}" id="working_place" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.expire_before'}</span>:
			<div style="position: relative;" class="js_event_select">
					{select_date prefix='end_' id='_end' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true time_separator='event.time_separator'}
				</div>
			
		</div>				
		<div class="p_top_8">
			<input type="button" onclick="submitForm();return false;" id="filter_submit" name="search[submit]" value="{phrase var='jobposting.search'}" class="button"/>		
		</div>
	</form>
</div>


