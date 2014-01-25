<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>
{literal}
<script type="text/javascript">

$Core.remakePostUrl = function(){
    var keywords = encodeURI(encodeURIComponent($("#search_keywords").val()));
    var sort = $("#search_sort").val();
    var show = $("#search_show").val();
    var when = $("#search_when").val();
    var location = encodeURI(encodeURIComponent($("#search_location").val()));
    var city = encodeURI(encodeURIComponent($("#search_city").val()));
    var zipcode = encodeURI(encodeURIComponent($("#search_zipcode").val()));
    var rangevaluefrom = encodeURI(encodeURIComponent($("#search_range_value_from").val()));
    var rangevalueto = encodeURI(encodeURIComponent($("#search_range_value_to").val()));
    var rangetype = $("#search_range_type").val();
    var country = $('#country_iso').val();
    var childid = $('#js_country_child_id_value').val();
    
    if(country==null)
    {
        country = '';
    }
    if(childid==null)
    {
        childid = 0;
    }
    
    var url = window.location.href;
    if(url.match(/\/keywords_.*?\//g))
    {
        url = url.replace(/\/keywords_.*?\//g, '/keywords_'+keywords+'/');
    }
    else
    {
        url += 'keywords_'+keywords+'/';
    }
    if(url.match(/\/sort_.*?\//g))
    {
        url = url.replace(/\/sort_.*?\//g, '/sort_'+sort+'/');
    }
    else
    {
        url += 'sort_'+sort+'/';
    }
    url = url.replace(/\/page_.*?\//g, '/');
    url = url.replace(/\/date_.*?\//g, '/');
    if(url.match(/\/show_.*?\//g))
    {
        url = url.replace(/\/show_.*?\//g, '/show_'+show+'/');
    }
    else
    {
        url += 'show_'+show+'/';
    }
    if(url.match(/\/when_.*?\//g))
    {
        url = url.replace(/\/when_.*?\//g, '/when_'+when+'/');
    }
    else
    {
        url += 'when_'+when+'/';
    }
    if(url.match(/\/location_.*?\//g))
    {
        url = url.replace(/\/location_.*?\//g, '/location_'+location+'/');
    }
    else
    {
        url += 'location_'+location+'/';
    }
    if(url.match(/\/city_.*?\//g))
    {
        url = url.replace(/\/city_.*?\//g, '/city_'+city+'/');
    }
    else
    {
        url += 'city_'+city+'/';
    }
    if(url.match(/\/zipcode_.*?\//g))
    {
        url = url.replace(/\/zipcode_.*?\//g, '/zipcode_'+zipcode+'/');
    }
    else
    {
        url += 'zipcode_'+zipcode+'/';
    }
    if(url.match(/\/rangevaluefrom_.*?\//g))
    {
        url = url.replace(/\/rangevaluefrom_.*?\//g, '/rangevaluefrom_'+rangevaluefrom+'/');
    }
    else
    {
        url += 'rangevaluefrom_'+rangevaluefrom+'/';
    }
    if(url.match(/\/rangevalueto_.*?\//g))
    {
        url = url.replace(/\/rangevalueto_.*?\//g, '/rangevalueto_'+rangevalueto+'/');
    }
    else
    {
        url += 'rangevalueto_'+rangevalueto+'/';
    }
     if(url.match(/\/rangetype_.*?\//g))
    {
        url = url.replace(/\/rangetype_.*?\//g, '/rangetype_'+rangetype+'/');
    }
    else
    {
        url += 'rangetype_'+rangetype+'/';
    }
    if(url.match(/\/country_.*?\//g))
    {
        url = url.replace(/\/country_.*?\//g, '/country_'+country+'/');
    }
    else
    {
        url += 'country_'+country+'/';
    }
    if(url.match(/\/childid_.*?\//g))
    {
        url = url.replace(/\/childid_.*?\//g, '/childid_'+childid+'/');
    }
    else
    {
        url += 'childid_'+childid+'/';
    }
   
    $("#event_search_form").attr('action', url);
}

{/literal}

</script>

{literal}
<style type="text/css">
	#country_iso{
   		width: 150px;
	}
</style>
{/literal}
<form id="event_search_form" method="post" onsubmit="$Core.remakePostUrl(); if($('#search_keywords').val()=='{phrase var='fevent.keywords'}...'){l}$('#search_keywords').val('');{r}">
    <input type="hidden" value="1" name="search[submit]">
    
    <table class="search_table" border="0" cellpadding="0" cellspacing="5">
        <tr>
            <td>{phrase var='fevent.keywords'}:</td>
            <td>
                <input id="search_keywords" value="{$sKeywords}" type="text" name="search[search]" class="search_keyword">
            </td>
        </tr>
        <tr>
            <td>{phrase var='fevent.sort'}:</td>
            <td>
                <select id="search_sort" onchange="$Core.remakePostUrl();">
                    <option {if $sSort=='latest'}selected{/if} value="latest">{phrase var='fevent.latest'}</option>
                    <option {if $sSort=='most-viewed'}selected{/if} value="most-viewed">{phrase var='fevent.most_viewed'}</option>
                    <option {if $sSort=='most-liked'}selected{/if} value="most-liked">{phrase var='fevent.most_liked'}</option>
                    <option {if $sSort=='most-talked'}selected{/if} value="most-talked">{phrase var='fevent.most_discussed'}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>{phrase var='fevent.show'}:</td>
            <td>
                <select id="search_show" onchange="$Core.remakePostUrl();">
                {foreach from=$aShows item=aShow}
                    <option {if $sShow==$aShow.value}selected{/if} value="{$aShow.value}">{$aShow.label}</option>
                {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td>{phrase var='fevent.when'}:</td>
            <td>
                <select id="search_when" onchange="$Core.remakePostUrl();">
                    <option {if $sWhen=='upcoming'}selected{/if} value="upcoming">{phrase var='core.upcoming'}</option>
                    <option {if $sWhen=='all-time'}selected{/if} value="all-time">{phrase var='core.all_time'}</option>
                    <option {if $sWhen=='this-month'}selected{/if} value="this-month">{phrase var='core.this_month'}</option>
                    <option {if $sWhen=='this-week'}selected{/if} value="this-week">{phrase var='core.this_week'}</option>
                    <option {if $sWhen=='today'}selected{/if} value="today">{phrase var='core.today'}</option>
                    <option {if $sWhen=='past'}selected{/if} value="past">{phrase var='fevent.past'}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>{phrase var='fevent.location'}:</td>
            <td>
                <input id="search_location" value="{$sLocation}" type="text" name="search[location]" class="search_keyword">
            </td>
        </tr>
        <tr>
            <td>{phrase var='fevent.city'}:</td>
            <td>
                <input id="search_city" type="text" value="{if isset($sCity)}{$sCity}{/if}" name="search[city]" class="search_keyword">
            </td>
        </tr>
        <tr>
            <td>{phrase var='fevent.zip_postal_code'}:</td>
            <td>
                <input id="search_zipcode" type="text" value="{if isset($iZipcode)}{$iZipcode}{/if}" name="search[zipcode]" class="search_keyword">
            </td>
        </tr>
        
        <tr>
            <td>{phrase var='fevent.range'}:</td>
            <td>
                <input style="width:34px;" id="search_range_value_from" type="text" value="{if isset($rangevaluefrom)}{$rangevaluefrom}{/if}" name="search[range_value_from]" class="search_keyword">
                {phrase var='fevent.to'} <input style="width:34px;" id="search_range_value_to" type="text" value="{if isset($rangevalueto)}{$rangevalueto}{/if}" name="search[range_value_to]" class="search_keyword">
                <select id="search_range_type">
                	<option value="0">{phrase var='fevent.miles'}</option>
                	<option value="1" {if isset($rangetype) && $rangetype==1}selected{/if}>{phrase var='fevent.km'}</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td><label for="country_iso">{phrase var='fevent.country'}:</label></td>
            <td >
                {select_location}
                {module name='core.country-child'}
            </td>
        </tr>
			
    </table>
    
    <div class="p_top_8">
        <input name="search[submit]" value="{phrase var='fevent.submit'}" class="button" type="submit" />
        <input name="search[reset]" value="{phrase var='fevent.reset'}" class="button" type="reset" /> 
    </div>    
</form>
