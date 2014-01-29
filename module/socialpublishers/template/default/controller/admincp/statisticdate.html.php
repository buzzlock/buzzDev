<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: index.html.php 1544 2010-04-07 13:20:17Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" action="{url link='admincp.socialpublishers.statisticdate'}">
    <div class="table_header">
        {phrase var='socialpublishers.search_filter'}
    </div>
    {*<div class="table">
        <div class="table_left">
            {phrase var='socialpublishers.search_for_statistic_date'}
        </div>
        <div class="table_right">
            {select_date prefix='start_' start_year='2005' end_year='+10' field_separator=' / ' field_order='MDY' bUseDatepicker=false}
            <div style="display: none;">{$aFilters.statistic_date}</div>
        </div>
        <div class="clear"></div>
    </div>*}
    <div class="table">
        <div class="table_left">
            {phrase var='socialpublishers.limit_per_page'}
        </div>
        <div class="table_right">
            {$aFilters.display}
        </div>
        <div class="clear"></div>
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='socialpublishers.sort'}
        </div>
        <div class="table_right">
            {$aFilters.sort} {$aFilters.sort_by}
        </div>
        <div class="clear"></div>
    </div>
    <div class="table_clear">
        <input type="submit" name="search[submit]" value="{phrase var='core.submit'}" class="button" />
        <input type="submit" name="search[reset]" value="{phrase var='core.reset'}" class="button" />	
    </div>
</form>
{pager}

<form method="post" action="{url link='admincp.socialpublishers.statisticdate'}">
    {if count($aItems)}
        <table>
            <tr>
                <th>{phrase var='socialpublishers.id'}</th>
                <th>{phrase var='socialpublishers.date'}</th>
                <th>{phrase var='socialpublishers.facebook'}</th>
                <th>{phrase var='socialpublishers.twitter'}</th>
                <th>{phrase var='socialpublishers.linkedin'}</th>
            </tr>
            {foreach from=$aItems key=iKey item=aItem}
                <tr id="js_row{$aItem.id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
                    <td>{$aItem.id}</td>
                    <td>{$aItem.statistic_date|date:'core.global_update_time'}</td>
                    <td>{$aItem.total_facebook_post}</td>
                    <td>{$aItem.total_twitter_post}</td>
                    <td>{$aItem.total_linkedin_post}</td>
                </tr>
            {/foreach}
        </table>
    {else}
        <div class="p_4">
            {phrase var='socialpublishers.no_statistic_date_has_been_created'}
        </div>
    {/if}
</form>

{pager}