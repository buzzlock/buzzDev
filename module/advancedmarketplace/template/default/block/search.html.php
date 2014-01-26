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
 * @package         YouNet_Listing
 */
?>
{literal}
<script type="text/javascript" language="JavaScript">
    $Core.remakePostUrl = function(){
        var sort = $("#search_sort").val();
        var show = $("#search_show").val();
        var when = $("#search_when").val();
        var location = $("#search_location").val();
        var city = $("#search_city").val();
        var zipcode = $("#search_zipcode").val();
        var country = $('#country_iso').val();
        var childid= $('#js_country_child_id_value').val();

        if(childid==null)
        {
            childid=0;
        }
        var url = window.location.href;

        if(url.match(/\/search.*?\//g))
        {

        }
        else
        {
            url = url.replace(/\/advancedmarketplace\//g, '/advancedmarketplace/search/');
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
            url = url.replace(/\/child_id_.*?\//g, '/childid_'+childid+'/');
        }
        else
        {
            url += 'childid_'+childid+'/';
        }
        $("#event_search_form").attr('action', url);
    }
</script>
{/literal}
{literal}
<style type="text/css">
	#country_iso{
   		width: 150px;
	}
</style>
{/literal}
{literal}
<script type="text/javascript" language="JavaScript">
    $Behavior.chsDSE = function() {
        var country = $("#country_iso");
        country.val("{/literal}{$sCountry}{literal}");
    }
</script>
{/literal}
<form id="event_search_form" method="post" onsubmit="$Core.remakePostUrl(); if($('#search_keywords').val()=='{phrase var='advancedmarketplace.keywords'}...'){l}$('#search_keywords').val('');{r}">
    <input type="hidden" value="1" name="search[submit]">
    
    <table class="search_table" border="0" cellpadding="0" cellspacing="5">
        <tr>
            <td>{phrase var='advancedmarketplace.keywords'}:</td>
            <td>
                <input id="search_keywords" type="text" name="search[search]" class="search_keyword">
            </td>
        </tr>
       
        <tr>
            <td>{phrase var='advancedmarketplace.sort'}:</td>
            <td>
                <select id="search_sort" onchange="$Core.remakePostUrl();">
                    <option {if $sSort=='latest'}selected{/if} value="latest">{phrase var='advancedmarketplace.latest'}</option>
                    <option {if $sSort=='most-viewed'}selected{/if} value="most-viewed">{phrase var='advancedmarketplace.most_viewed'}</option>
                    <option {if $sSort=='most-liked'}selected{/if} value="most-liked">{phrase var='advancedmarketplace.most_liked'}</option>
                    <option {if $sSort=='most-talked'}selected{/if} value="most-talked">{phrase var='advancedmarketplace.most_discussed'}</option>
                    <option {if $sSort=='most-reviewed'}selected{/if} value="most-reviewed">{phrase var='advancedmarketplace.most_reviewed'}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>{phrase var='advancedmarketplace.show'}:</td>
            <td>
                <select id="search_show" onchange="$Core.remakePostUrl();">
                    {foreach from=$aShows item=aShow}
                        <option {if $sShow==$aShow.value}selected{/if} value="{$aShow.value}">{$aShow.label}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td>{phrase var='advancedmarketplace.when'}:</td>
            <td>
                <select id="search_when" onchange="$Core.remakePostUrl();">
                    <option {if $sWhen=='all-time'}selected{/if} value="all-time">{phrase var='core.all_time'}</option>
                    <option {if $sWhen=='this-month'}selected{/if} value="this-month">{phrase var='core.this_month'}</option>
                    <option {if $sWhen=='this-week'}selected{/if} value="this-week">{phrase var='core.this_week'}</option>
                    <option {if $sWhen=='today'}selected{/if} value="today">{phrase var='core.today'}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>{phrase var='advancedmarketplace.street'}:</td>
            <td>
                <input id="search_location" value="{$sLocation}" type="text" name="search[location]" class="search_keyword">
            </td>
        </tr>
        <tr>
            <td>{phrase var='advancedmarketplace.city'}:</td>
            <td>
                <input id="search_city" type="text" value="{$sCity}" name="search[city]" class="search_keyword">
            </td>
        </tr>
        <tr>
            <td>{phrase var='advancedmarketplace.zip_postal_code'}:</td>
            <td>
                <input id="search_zipcode" type="text" value="{if isset($sZipCode)}{$sZipCode}{/if}" name="search[zipcode]" class="search_keyword">
            </td>
        </tr>

        <tr>
            <td>
                <label for="country_iso">{phrase var='advancedmarketplace.country'}:</label>
            </td>
            <td >
                {select_location}
                {module name='core.country-child'}
            </td>
        </tr>
    </table>
    
    <div class="p_top_8">
        <input name="search[submit]" value="{phrase var='advancedmarketplace.submit'}" class="button" type="submit" />
        <input name="search[reset]" value="{phrase var='advancedmarketplace.reset'}" class="button" type="reset" /> 
    </div>    
</form>