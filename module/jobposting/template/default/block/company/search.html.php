<div class="yns adv-search-block" id ="jobposting_adv_search">
	<form method="post" action="">
		<input type="hidden" value="1" name="search[flag_advancedsearch]"/>
		
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.company_name'}</span>:
			<div class="p_4">
				<input type="text" name="search[name]" value="{if isset($aForms.name)}{$aForms.name}{/if}" id="name" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.location'}</span>:
			<div class="p_4">
				<input type="text" name="search[location]" value="{if isset($aForms.location)}{$aForms.location}{/if}" id="location" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.company_size'}</span>:
			<div class="p_4">
				<input type="text" name="search[company_size]" value="{if isset($aForms.company_size)}{$aForms.company_size}{/if}" id="company_size" size="22" maxlength="200" />
			</div>
		</div>
		<div class="p_top_4">
			<span class="ynjp_browse_title">{phrase var='jobposting.industry'}</span>:
			<div class="p_4">
				{$aIndustryBlock}
			</div>
		</div>
		<div class="p_top_8">
			<input type="submit" id="filter_submit" name="search[submit]" value="{phrase var='jobposting.search'}" class="button" />		
		</div>		
	</form>
</div>