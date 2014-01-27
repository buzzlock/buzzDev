<div class="yns subscribe-block" id ="jobposting_subscribe">
	<form method="post" action="{if $req3=='add'}{url link='jobposting.subscribe.add'}{else}{url link='jobposting.subscribe.edit'}{/if}" name="jobposting_subscribe_form" id="jobposting_subscribe_form">
		<!-- Keywords -->
		<div class="ynjp_subscribeJ_holder">
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.keyword'}</span>:
				<div class="p_4">
					<input type="text" name="val[keywords]" value="{if isset($aForms.keywords)}{$aForms.keywords}{/if}" id="keywords" size="22" maxlength="200" />
				</div>
			</div>
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.company'}</span>:
				<div class="p_4">
					<input type="text" name="val[company]" value="{if isset($aForms.company)}{$aForms.company}{/if}" id="company" size="22" maxlength="200" />
				</div>
			</div>
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.location'}</span>:
				<div class="p_4">
					<input type="text" name="val[location]" value="{if isset($aForms.location)}{$aForms.location}{/if}" id="location" size="22" maxlength="200" />
				</div>
			</div>
		</div>
		<div class="ynjp_subscribeJ_holder">
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.industry'}</span>:
				<div class="p_4">
					{$aIndustryBlock1}
				</div>
			</div>
		</div>
		<div class="ynjp_subscribeJ_holder">
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.language_preference'}</span>:
				<div class="p_4">
					<input type="text" name="val[language_prefer]" value="{if isset($aForms.language_prefer)}{$aForms.language_prefer}{/if}" id="language_prefer" size="22" maxlength="200" />
				</div>
			</div>
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.education_preference'}</span>:
				<div class="p_4">
					<input type="text" name="val[education_prefer]" value="{if isset($aForms.education_prefer)}{$aForms.education_prefer}{/if}" id="education_prefer" size="22" maxlength="200" />
				</div>
			</div>
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.working_place'}</span>:
				<div class="p_4">
					<input type="text" name="val[working_place]" value="{if isset($aForms.working_place)}{$aForms.working_place}{/if}" id="working_place" size="22" maxlength="200" />
				</div>
			</div>
		</div>
		<div class="ynjp_subscribeJ_holder">
			<div class="p_top_4">
				<span class="ynjp_browse_title">{phrase var='jobposting.expire_before'}</span>:
				<div class="p_4 ynjp_searchDatePicker">
					<a href="#" id="js_from_date_filter_anchor" class="ynjp_DatePicker_img">
						<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
					</a>
					<span class="ynjp_search_DatePicker_holder">
						<input size="18" name="val[searchdate]" id="js_from_date_filter" class="ynjp_inputDatePicker" type="text" value="{if isset($aForms.searchdate)}{$aForms.searchdate}{/if}" />
					</span>
				</div>
			</div>				
			<div class="p_top_8">
				<span class="ynjp_browse_title">&nbsp;</span>
				<input type="submit" id="filter_submit" name="val[submit]" value="{phrase var='jobposting.save'}" class="button" />		
			</div>
		</div>
	</form>
</div>

{literal}
<script type="text/javascript">
	popup = 1;
	$Core.loadInit();
</script>
{/literal}