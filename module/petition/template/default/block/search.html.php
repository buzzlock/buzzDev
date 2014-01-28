<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style type="text/css">
    /*** Search Block***/
    table.search_table tr td{
        padding: 2px 0;
    }
</style>
<script type="text/javascript">
$Core.remakePostUrl = function(){            
    var cat  = $("#search_category").val();    
    var catText  = $("#search_category").find('option:selected').text();
    var status = $("#search_status").val();
    
    var url = '{/literal} {$sUrl} {literal}';
    var view = '{/literal} {$sView} {literal}';
        
    if(cat != 0)
    {
        if(url.match(/\/category\/.*?\/.*?\//g))
        {
            url = url.replace(/\/category\/.*?\/.*?\//g, 'category/'+cat+'/'+catText+'/');
        }
        else
        {
            url += 'category/'+cat+'/'+catText+'/';
        }   
    }
    
    if(status >= 0)
    {
        if(url.match(/\/status_.*?\//g))
        {
            url = url.replace(/\/status_.*?\//g, '/status_'+status+'/');
        }
        else
        {
            url += 'status_'+status+'/';
        }   
    }
    
    if($('#chk_search_by_date').attr('checked') == 'checked')
    {
        var end_time = $('#end_time_search').val();
        end_time = end_time.replace(/\//g,'_');
            
        var start_time  = $('#start_time_search').val();
        start_time   = start_time.replace(/\//g,'_');
        
        
        if(start_time != '')
        {
            if(url.match(/\/from_.*?\//g))
            {
                url = url.replace(/\/from_.*?\//g, '/from_'+start_time+'/');
            }
            else
            {
                url += 'from_'+start_time+'/';
            }   
        }
        
        if(end_time != '')
        {
            if(url.match(/\/to_.*?\//g))
            {
                url = url.replace(/\/to_.*?\//g, '/to_'+end_time+'/');
            }
            else
            {
                url += 'to_'+end_time+'/';
            }   
        }
    }
    if(view != '')
    {
        url += 'view_'+ view +'/';
    }
    $("#petition_search_form").attr('action', url);
}
</script>
<style type="text/css">
    .petition_date_picker{
        background: url({/literal}{$corepath}{literal}module/petition/static/image/calendar.gif) no-repeat top left;            
    }    
</style>
{/literal}
<div class="petition_date_picker"></div>
<form id="petition_search_form" method="post" onsubmit="$Core.remakePostUrl(); if($('#search_keywords').val()=='{phrase var='petition.search_petition_dot'}'){l}$('#search_keywords').val('');{r}">
    <input type="hidden" value="1" name="search[submit]">
    
    <table class="search_table" border="0" cellpadding="0" cellspacing="5">
        <tr>
            <td>{phrase var='petition.keywords'}:</td>            
        </tr>
        <tr>
            <td>
                <input id="search_keywords" type="text" name="search[search]" class="search_keyword" style="width: 90%" value="{if isset($aSearchTool.search.actual_value)}{$aSearchTool.search.actual_value|clean}{else}{$aSearchTool.search.default_value}{/if}
" onfocus="if($('#search_keywords').val()=='{phrase var='petition.search_petition_dot'}'){l}$('#search_keywords').val('');{r}" onblur="if($('#search_keywords').val()==''){l}$('#search_keywords').val('{phrase var='petition.search_petition_dot'}');{r}">
            </td>
        </tr>
               
        <tr>
            <td>{phrase var='petition.category'}:</td>
        </tr>
        <tr>
            <td>
                <select id="search_category" onchange="$Core.remakePostUrl();">
                <option {if $iCategoryPetitionView==0}selected{/if} value="0">{phrase var='petition.all'}</option>
                {foreach from=$aCategories item=aCategory}
                    <option {if $iCategoryPetitionView==$aCategory.category_id}selected{/if} value="{$aCategory.category_id}">{$aCategory.name}</option>
                {/foreach}
                </select>
            </td>
        </tr>
        {if $sView != 'pending'}
        <tr>
            <td>{phrase var='petition.petition_status'}:</td>
        </tr>
        <tr>
            <td>
                <select id="search_status" onchange="$Core.remakePostUrl();">
                <option {if $iStatus==0}selected{/if} value="0">{phrase var='petition.all'}</option>
                <option {if $iStatus==1}selected{/if} value="1">{phrase var='petition.closed'}</option>
                <option {if $iStatus==2}selected{/if} value="2">{phrase var='petition.on_going'}</option>
                <option {if $iStatus==3}selected{/if} value="3">{phrase var='petition.victory'}</option>                
                </select>
            </td>
        </tr>
        {/if}
        <tr>
            <td>
                <input id="chk_search_by_date" type="checkbox" onclick="$('.search_by_date').toggle();"/> {phrase var='petition.search_by_date'}.                
            </td>
        </tr>
        <tr>        
        <tr class="search_by_date">
            <td>{phrase var='petition.from'}:</td>
        </tr>
        <tr class="search_by_date">
            <td>
                <div style="position: relative;">
                    {*select_date prefix='start_time_search_' id='_start_time-search' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true*}
                    <input type="text" id="start_time_search" style="cursor: pointer; width: 95px;" value="{$aForms.start_time_search}"/>                    
                    <div class="js_datepicker_image" id="start_time_picker" style="cursor: pointer; margin-left: 5px;"></div>
                </div>
            </td>
        </tr>
        
        <tr class="search_by_date">
            <td>{phrase var='petition.to'}:</td>
        </tr>
        <tr class="search_by_date">
            <td>
                <div style="position: relative;">
                    {*select_date prefix='end_time_search_' id='_end_time-search' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true*}
                    <input type="text" id="end_time_search" style="cursor: pointer; width: 95px;" value="{$aForms.end_time_search}"/>                    
                    <div class="js_datepicker_image" id="end_time_picker" style="cursor: pointer; margin-left: 5px;"></div>
                </div>
            </td>
        </tr>
    </table>
    
    <div class="p_top_8">
        <input name="search[submit]" value="{phrase var='petition.submit'}" class="button" type="submit" />
    </div>    
</form>
{literal}
<script type="text/javascript">
   $Behavior.setDateTimePicker = (function(){
      $('#end_time_search').datepicker({
          dateFormat: "mm/dd/yy"
      });
      $('#end_time_picker').click(function(){$('#end_time_search').focus();});
      $('#start_time_search').datepicker({
          dateFormat: "mm/dd/yy"        
      });
      $('#start_time_picker').click(function(){$('#start_time_search').focus();});
    
      if('{/literal}{$iChecked}{literal}' == 'true')
      {
          $('#chk_search_by_date').attr('checked','checked');
          $('.search_by_date').show();
      }
      else{
          $('.search_by_date').hide();
      }
  });
</script>
{/literal}
