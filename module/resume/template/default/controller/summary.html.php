<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

{if !isset($aForms.country_iso) || $aForms.country_iso==""}
	{literal}
	<script type="text/javascript">
		$Behavior.LoadSelectCountry_Summary = function() 
		{
				
				try{
					document.getElementById('country_iso').selectedIndex = 0;
					document.getElementById('authorized_country_iso').selectedIndex = 0;
					$('#js_country_child_id_value').val(0);
				}catch(ex)
				{
					
				}
		};
	</script>
{/literal}
{/if}
<table class="ynf-loaction-added template_multi_location" style="display: none;">
   <tr class="ynf-location-item">
        <td style="width:250px">
            <span class="label_country_iso"></span>
            <input class="value_country_iso" type="hidden" name="val[authorized_country_iso][]" value="" />        	
        </td>
        <td class="template_country_child" style="width:200px">
        	<span class="label_country_child"></span>
            <input class="value_country_child" type="hidden" name="val[authorized_country_child][]" value="" />        	
        </td>
        <td style="width:400px">
        	<span class="label_location"></span>
            <input class="value_location" type="hidden" name="val[authorized_location][]" value="" />
        </td>
        <td style="width:200px">
        	<span class="label_level_id"></span>
            <input class="value_level_id" type="hidden" name="val[authorized_level_id][]" value="" />
        </td>
        <td style="width:200px">
            <span class="label_other_level"></span>
            <input class="value_other_level" type="hidden" name="val[authorized_other_level][]" value="" />
        </td>
       	<td style="width:50px">
       		<a href="javascript:void(0);" onclick="return removeLocation(this);">
            	{img theme='misc/delete.png' class='v_middle'}
        	</a>
       	</td>   	 
       
   </tr>
</table>
{literal}
<script type="text/javascript">
function removeLocation(e) {
    console.log($(e).parent().parent());
    $(e).parent().parent().remove();
}

function resetTemplate()
{
    $('.template_multi_location .label_country_iso').html('');
    $('.template_multi_location .value_country_iso').val('');
        
    $('.template_multi_location .label_other_level').parent().show();
    $('.template_multi_location .label_level_id').parent().show();
    
    $('.template_multi_location .label_country_child').html('');
    $('.template_multi_location .value_country_child').val('');
    
    $('.template_multi_location .label_location').html('');
    $('.template_multi_location .value_location').val('');
    
    $('.template_multi_location .label_level_id').html('');
    $('.template_multi_location .value_level_id').val(0);
    
    $('.template_multi_location .label_other_level').html('');
    $('.template_multi_location .value_other_level').val('');
}

function resetInput()
{
    $('#authorized_country_iso').val('');
        
    var oCountryChild = $('#authorized_js_country_child_id_value');
    if (oCountryChild && oCountryChild.attr('type') != 'hidden')
    {
        oCountryChild.val('0');
    }
    
    $('#authorized_location').val('');
    $('#select_authorized_level_id').val(0)
    $('#authorized_other_level').val('');
    $('#div_authorized_other_level').hide();
}

function addLocation()
{
    if ($('.multi_location_holder .ynf-location-item').length >= 6)
    {
        alert(oTranslations['resume.you_reach_the_maximum_of_total_predefined']);
        return;
    }
    
    var sCountryChildLabel = '';
    var iCountryChildValue = 0;
    
    var oCountryChild = $('#authorized_js_country_child_id_value');
    if (oCountryChild)
    {
        iCountryChildValue = oCountryChild.val();
        sCountryChildLabel = $('#authorized_js_country_child_id_value :selected').text();
    }
    
    var sCountryIso = $('#authorized_country_iso').val();
    var sCountryIsoLabel = $('#authorized_country_iso option:selected').text();
    
    var sLocation = $('#authorized_location').val();    
    
    var iPosition = $('#select_authorized_level_id').val();
    var iPositionLabel = $('#select_authorized_level_id option:selected').text();

    var sOtherPosition = $('#authorized_other_level').val();    
    if (sCountryIso.length == 0)
    {
        alert('Please select country!');
        return;
    }
    $('.multi-location-label').show();
    $('.multi_location_holder').show();
    
    $('.template_multi_location .label_country_iso').html(sCountryIsoLabel);
    $('.template_multi_location .value_country_iso').val(sCountryIso);
    
    $('.template_multi_location .label_country_child').html(sCountryChildLabel);
    $('.template_multi_location .value_country_child').val(iCountryChildValue);
    
    $('.template_multi_location .label_location').html(sLocation);
    $('.template_multi_location .value_location').val(sLocation);
    
    $('.template_multi_location .value_level_id').val(iPosition);
    $('.template_multi_location .value_other_level').val(sOtherPosition);
    
    if (iPosition != 0)
    {
        $('.template_multi_location .label_level_id').html(iPositionLabel);
        $('.template_multi_location .label_other_level').parent().hide();
    }
    else
    {
        $('.template_multi_location .label_level_id').parent().hide();
        $('.template_multi_location .label_other_level').html(sOtherPosition);
    }
    
    var oTemplateLocation = $('.template_multi_location tbody');
    
    $('.multi_location_holder table tbody').append(oTemplateLocation.html());
    
    resetTemplate();
    resetInput();
}

$Behavior.loadsummary = function(){
	
	$('#select_authorized_level_id').bind('change',function(){
		document.getElementById('div_authorized_other_level').style.display = "none";
		$('#authorized_other_level').val("");
	});
	
	$('#summary_other').bind('click',function(event){
		event.preventDefault();
		$('#div_authorized_other_level').toggle();
		document.getElementById('select_authorized_level_id').selectedIndex = 0;
	});
};

$Behavior.checklimitCategory = function(){ 
	$('#js_category_content').find('.checkbox').bind('click',function(){
		   	var res_max_cats = 0;
		    $('#js_category_content').find('.checkbox').each(function(i,val){
		    if(val.checked)
		        res_max_cats +=1;
		    });
		    if(res_max_cats > {/literal}{$iMaxCategories}{literal})
		    {
		        this.checked = false;
		        alert("{/literal}{phrase var='resume.you_can_only_select_number_categories' number = $iMaxCategories}{literal}");
		    }
	});
}
</script>
{/literal}


<div class="summary-session">
{template file='resume.block.menu_add'}
</div>
<div>
<h3 class="yns add-res">
<ul class="yns menu-add">
	<li>{required}{phrase var='resume.summary'}</li>
</ul>
<ul class="yns action-add">
	{if $bIsEdit}<li><a class="page_section_menu_link" href="{url link='resume.view'}{$id}/">{phrase var='resume.view_my_resume'}</a></li>{/if}
</ul>
</h3>
</div>

<form name='js_resume_summary_form' method="post" action="{url link='resume.summary'}id_{$id}/">

<div id="headline">
	<div class="summary_label"> 
		<strong>{phrase var='resume.resume_name'}</strong>
	</div>
	<div class="summary_content">
		<div class="table" >
			<div class="table_left table_left_add">
			{required}<label for="headline">{phrase var='resume.resume_name'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[headline]" value="{value type='input' id='headline'}" id="headline" size="40" maxlength="100" />
			</div>
		</div>
	</div>
		
	<div class="summary_label">
		<strong>{phrase var="resume.authorized_to_work_in"}</strong>
	</div>
	<div class="summary_content">
		<div class="table">
			<div class="table_left table_left_add">
				<label for="country_iso">{phrase var='resume.country'}:</label>
			</div>
			<div class="table_right">
				{select_location name='authorized_country_iso'}
				{module name='resume.country-child'}	
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="city">{phrase var='resume.location'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="" value="" id="authorized_location" size="20" maxlength="200" />
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="authorized_level_id">{phrase var='resume.position'}:</label>
			</div>
			<div class="table_right">
				<select id="select_authorized_level_id" name="val[authorized_level_id]" >
						<option value="0">{phrase var="resume.select"}</option>
					{foreach from=$aLevel item=level}
						<option value="{$level.level_id}" {if $aForms.authorized_level_id == $level.level_id} selected {/if} >{$level.name}</option>
					{/foreach}
				</select>
				{phrase var='resume.or'} <a href="javascript:void(0);" id='summary_other'>{phrase var='resume.other'}</a>
				<div id="div_authorized_other_level" style="margin-top: 3px;{if $aForms.authorized_other_level==""}display: none{/if}">
					<input type="text" name="val[authorized_other_level]" value="" id="authorized_other_level" size="20" maxlength="200" />
				</div>
			</div>

		</div>
        
        <div class="table">
			<div class="table_right ">
				<input type="button" class="button" onclick="addLocation();" value = "{phrase var='resume.add_location'}"/>
			</div>			
		</div>
        <div class="table">
            <div class="table_left table_left_add multi-location-label" {if !isset($aForms.authorized) || count($aForms.authorized) == 0 } style="display: none;" {/if} >
                <label>{phrase var='resume.multi_location'}:</label>
            </div>
            {if count($aCountryChildren) == 0} <div style="display: none;" class="no-country-child">&nbsp;</div> {/if}
            <div class="table_right multi_location_holder" {if !isset($aForms.authorized) || count($aForms.authorized) == 0 } style="display: none;" {/if}>
                <table class="ynf-loaction-added">
                     <tr class="ynf-location-item">
                        <td style="width:250px">{phrase var='resume.country'}:</td>
                        <td style="width:200px">{phrase var='resume.state_province'}:</td>
                        <td style="width:400px">{phrase var='resume.location'}:</td>
                        <td style="width:200px">{phrase var='resume.position'}:</td>
                        <td style="width:50px">&nbsp;</td>
                    </tr>
                    {if count($aForms.authorized) > 0}
                    {foreach from=$aForms.authorized item=aLocation}
                        <tr class="ynf-location-item">
                            <td style="width:250px"> 
                                <span class="label_country_iso">{$aLocation.label_country_iso}</span>
                                 <input class="value_country_iso" type="hidden" name="val[authorized_country_iso][]" value="{$aLocation.country_iso}" />
                            </td>

                            <td class="field_country_child" style="width:200px">
                                <span class="label_country_child">{if $aLocation.label_country_child!=""}{$aLocation.label_country_child}{else}{/if}</span>
                                <input class="value_country_child" type="hidden" name="val[authorized_country_child][]" value="{$aLocation.country_child}" />        	
                            </td>

                            <td style="width:400px">
                                <span class="label_location">{if $aLocation.location}{$aLocation.location}{else}{/if}</span>
                                <input class="value_location" type="hidden" name="val[authorized_location][]" value="{$aLocation.location}" />
                            </td>
                            {if !empty($aLocation.label_level_id)}   
                            <td {if empty($aLocation.label_level_id)} style="display: none;width:0px" {else} style="width:200px" {/if} >
                                <span class="label_level_id">{$aLocation.label_level_id}</span>
                                <input class="value_level_id" type="hidden" name="val[authorized_level_id][]" value="{$aLocation.level_id}" />
                            </td>
                            {else}
                            
                            <td style="width:200px">
                                <span class="label_other_level">{if $aLocation.other_level}{$aLocation.other_level}{else}{/if}</span>
                                <input class="value_other_level" type="hidden" name="val[authorized_other_level][]" value="{$aLocation.other_level}" />
                            </td>
                            {/if}
                            
                            <td style="width:50px">
                                <a href="javascript:void(0);" onclick="return removeLocation(this);">
                                 {img theme='misc/delete.png' class='v_middle'}
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                    {/if}
                </table>
            </div>
        </div>        
	</div>
	<div class="summary_label">
		<strong>{phrase var="resume.your_information"}</strong>
	</div>
	<div class="summary_content">
		<div class="table">
			<div class="table_left">
				<label for="country_iso">{phrase var='resume.location'}: {if !isset($aForms.country_phrase) || $aForms.country_phrase==""}{phrase var='resume.none'}{else}{$aForms.country_phrase}{/if}</label>
			</div>
			<div class="table_right" style="display:none">
				{select_location}
				{module name='core.country-child'}
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left">
				<label for="city">{phrase var='resume.city'}: {if !isset($aForms.city) || $aForms.city==""}{phrase var='resume.none'}{else}{$aForms.city}{/if}</label>
			</div>
			<div class="table_right" style="display:none">
				<input type="text" name="val[city]" value="{value type='input' id='city'}" id="city" size="20" maxlength="200" />
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left">
				<label for="postal_code">{phrase var='resume.zip_code'}: {if !isset($aForms.zip_code) || $aForms.zip_code==""}{phrase var='resume.none'}{else}{$aForms.zip_code}{/if}</label>
			</div>
			<div class="table_right" style="display:none">
				<input type="text" name="val[zip_code]" value="{value type='input' id='zip_code'}" id="zip_code" size="10" maxlength="20" />
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="categories">{required}{phrase var='resume.categories'}:</label>
			</div>
			<div class="table_right">
				<div style="margin-bottom: 7px;">
					{phrase var="resume.maximum_selected_category_number_is_number" number= $iMaxCategories}
				</div>
				<div class="label_flow label_hover labelFlowContent" style="height:100px;" id="js_category_content">
				{if $bIsEdit}
						{module name='resume.add-category-list' resume_id=$aForms.resume_id}
					{else}
						{module name='resume.add-category-list' resume_id=0}
					{/if}
				</div>
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="highest_level">{phrase var='resume.highest_level'}:</label>
			</div>
			<div class="table_right">
				<select name="val[level_id]">
						<option value="0">{phrase var="resume.select"}</option>
					{foreach from=$aLevel item=level}
						<option value="{$level.level_id}" {if $aForms.level_id == $level.level_id} selected {/if} >{$level.name}</option>
					{/foreach}
				</select>
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="year_exp">{phrase var='resume.years_of_experience'}:</label>
			</div>
			<div class="table_right">
				<select name="val[year_exp]">
					{for $i=0;$i<=50;$i++}
					<option value={$i} {if $aForms.year_exp==$i}selected=selected{/if}>{$i}</option>
					{/for}
				</select>
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="summary">{required}{phrase var='resume.summary'}:</label>
			</div>
			<div class="table_right">
				{editor id='summary' rows='3'}
			</div>
		</div>
		
		<div class="table">
			<div class="table_left table_left_add"></div>
			<div class="table_right ">
				<input type="submit" class="button" value = "{phrase var='resume.update'}"/>
			</div>			
		</div>	
	</div>		
</div>
</form>



