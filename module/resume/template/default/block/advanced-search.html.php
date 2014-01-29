<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL, TrucPTM
 * @package        Module_Resume
 * @version        3.01
 * 
 */?>
<div class="yns adv-search-block" id ="resume_adv_search" {if !$bIsAdvSearch }style="display:none"{else}style="display:block"{/if}>
	<form method="post" action="{url link='resume'}">
		<input type="hidden" id="form_flag" name="search[form_flag]" {if !$bIsAdvSearch }value="0"{else}value="1"{/if}>
		<!-- Keywords -->
		<div class="table">
			<div class="table_left adv-search-left">
				<label for="keywords">{phrase var='resume.headline'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<input type="text" name="search[keywords]" value="{if isset($aForms.keywords)}{$aForms.keywords}{/if}" id="keywords" size="30" maxlength="200" />
			</div>
		</div>
		<!-- Location -->
		<div class="table">
			<div class="table_left adv-search-left">
				<label for="country_iso">{phrase var='resume.location'}:</label>
			</div>
			<div class="table_right adv-search-right">
				{select_location}
				{module name='core.country-child'}
			</div>
			<div class="clear"></div>
		</div>
		<!-- City -->
		<div class="table">
			<div class="table_left adv-search-left" >
				<label for="city">{phrase var='resume.city'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<input type="text" name="search[city]" value="{if isset($aForms.city)}{$aForms.city}{/if}" id="city" size="30" maxlength="200" />
			</div>
		</div>
		<!-- Company -->
		<div class="table">
			<div class="table_left adv-search-left" >
				<label for="company">{phrase var='resume.company'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<input type="text" name="search[company]" value="{if isset($aForms.company)}{$aForms.company}{/if}" id="company" size="30" maxlength="200" />
			</div>
		</div>
		<!-- School -->
		<div class="table">
			<div class="table_left adv-search-left" >
				<label for="school">{phrase var='resume.school'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<input type="text" name="search[school]" value="{if isset($aForms.school)}{$aForms.school}{/if}" id="school" size="30" maxlength="200" />
			</div>
		</div>
		<!-- Category -->
		<div class="table">
			<div class="table_left adv-search-left">
				<label for="postal_code">{phrase var='resume.categories'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<div class="label_flow label_hover labelFlowContent" style="height:100px;" id="js_category_content">
				<div id="js_add_new_category"></div>
					{foreach from=$aItems item=aItem}
						<label for="js_category{$aItem.category_id}" id="js_category_label{$aItem.category_id}">
							<input value="{$aItem.category_id}" {if in_array($aItem.category_id,$aItemData)}checked= true{/if} type="checkbox" name="search[category][]" id="js_category{$aItem.category_id}" class="checkbox v_middle" /> {$aItem.name|convert|clean}
						</label>
					{foreachelse}
					<div class="p_4">
						{phrase var='resume.no_categories_added'}
					</div>
					{/foreach}
				</div>
			</div>
		</div>	
		<!-- Degree -->
		<div class="table">
			<div class="table_left adv-search-left" >
				<label for="degree">{phrase var='resume.degree'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<input type="degree" name="search[degree]" value="{if isset($aForms.degree)}{$aForms.degree}{/if}" id="degree" size="30" maxlength="200" />
			</div>
		</div>
		<!-- Level -->
		<div class="table">
			<div class="table_left adv-search-left">
				<label for="highest_level">{phrase var='resume.highest_level'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<select name="search[level_id]">
					    <option value ="">{phrase var="resume.select"}</option>
					{foreach from=$aLevels item=aLevel}
						<option value="{$aLevel.level_id}" {if $aForms.level_id == $aLevel.level_id} selected {/if}>{$aLevel.name}</option>
					{/foreach}
				</select>
			</div>
		</div>	
		<!-- Year of Experience -->
		<div class="table">
			<div class="table_left adv-search-left">
				<label for="year_exp">{phrase var='resume.years_of_experience'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<!-- from -->
				<label for="year_exp_from">{phrase var='resume.from'}</label>
				<select name="search[year_exp_from]">
					{for $i=0;  $i<= 50 ; $i++}
						<option value="{$i}" {if $aForms.year_exp_from == $i} selected {/if}>{$i}</option>
					{/for}
				</select>
				<!-- to -->
				<label for="year_exp_to" style="margin-left:10px;">{phrase var='resume.to'}</label>
				<select name="search[year_exp_to]">
					{for $i=0; $i <= 50 ; $i++}
						<option value="{$i}" {if $aForms.year_exp_to == $i} selected {/if}>{$i}</option>
					{/for}
				</select>
			</div>
		</div>
		<!-- Gender -->
		<div class="table">
			<div class="table_left adv-search-left">
				<label for="year_exp">{phrase var='resume.gender'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<input type="radio" name="search[gender]" value="" {if !$aForms.gender}checked{/if} >{phrase var="resume.all"}
				<input type="radio" name="search[gender]" value="1" {if $aForms.gender == 1} checked{/if} style="margin-left: 15px;">{phrase var="resume.male"}
				<input type="radio" name="search[gender]" value="2" {if $aForms.gender == 2} checked{/if} style="margin-left: 15px;">{phrase var="resume.female"}
			</div>
		</div>
		<!-- Skill -->
		<div class="table">
			<div class="table_left adv-search-left" >
				<label for="skill">{phrase var='resume.skill'}:</label>
			</div>
			<div class="table_right adv-search-right">
				<input type="skill" name="search[skill]" value="{if isset($aForms.skill)}{$aForms.skill}{/if}" id="skill" size="30" maxlength="200" />
			</div>
		</div>	
		<!-- Submit Button -->
		
	<div class="table_clear">
		<input type="submit" id="filter_submit" name="search[submit]" value="{phrase var='resume.search'}" class="button" />
		<input type="submit" id="filter_submit" name="search[submit]" value="{phrase var='resume.reset'}" class="button" />
	</div>
	</form>
</div>

{literal}
<script type="text/javascript">
	$Behavior.InitCountry = function()
	{
		try{
			$('#country_iso').attr('name','search[country_iso]');
			$('#js_country_child_id_value').attr('name','search[country_child_id]');
		}catch(ex){
			
		}
	}
</script>
{/literal}


{if !isset($aForms.country_iso) || $aForms.country_iso==""}
	{literal}
	<script type="text/javascript">
		
		$Behavior.LoadSelectCountry_AdSearch = function() 
		{
				
				try{
					document.getElementById('country_iso').selectedIndex = 0;
					document.getElementById('js_country_child_id_value').selectedIndex = 0;
				}catch(ex)
				{
					
				}
		};
	</script>
{/literal}
{/if}

