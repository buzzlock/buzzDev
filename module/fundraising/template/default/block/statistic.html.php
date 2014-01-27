<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<script language="JavaScript" type="text/javascript">
    $Behavior.ynfrInitializeStatisticJs = function(){
        $("#js_from_date_listing").datepicker({
            dateFormat: 'mm/dd/yy',
            onSelect: function(dateText, inst) {
                var $dateTo = $("#js_to_date_listing").datepicker("getDate");
                var $dateFrom = $("#js_from_date_listing").datepicker("getDate");
                if($dateTo)
                {
                    $dateTo.setHours(0);
                    $dateTo.setMilliseconds(0);
                    $dateTo.setMinutes(0);
                    $dateTo.setSeconds(0);
                }

                if($dateFrom)
                {
                    $dateFrom.setHours(0);
                    $dateFrom.setMilliseconds(0);
                    $dateFrom.setMinutes(0);
                    $dateFrom.setSeconds(0);
                }

                if($dateTo && $dateFrom && $dateTo < $dateFrom) {
                    tmp = $("#js_to_date_listing").val();
                    $("#js_to_date_listing").val($("#js_from_date_listing").val());
                    $("#js_from_date_listing").val(tmp);
                }
                return false;
            }
        });
        $("#js_to_date_listing").datepicker({
            dateFormat: 'mm/dd/yy',
            onSelect: function(dateText, inst) {
                var $dateTo = $("#js_to_date_listing").datepicker("getDate");
                var $dateFrom = $("#js_from_date_listing").datepicker("getDate");

                if($dateTo)
                {
                    $dateTo.setHours(0);
                    $dateTo.setMilliseconds(0);
                    $dateTo.setMinutes(0);
                    $dateTo.setSeconds(0);
                }

                if($dateFrom)
                {
                    $dateFrom.setHours(0);
                    $dateFrom.setMilliseconds(0);
                    $dateFrom.setMinutes(0);
                    $dateFrom.setSeconds(0);
                }

                if($dateTo && $dateFrom && $dateTo < $dateFrom) {
                    tmp = $("#js_to_date_listing").val();
                    $("#js_to_date_listing").val($("#js_from_date_listing").val());
                    $("#js_from_date_listing").val(tmp);
                }
                return false;
            }
        });

        $("#js_from_date_listing_anchor").click(function() {
            $("#js_from_date_listing").focus();
            return false;
        });

        $("#js_to_date_listing_anchor").click(function() {
            $("#js_to_date_listing").focus();
            return false;
        });
    };
</script>
{/literal}
{*
<div class="ynfr  ">
    <div class="statistic-left"></div>
    <div class="statistic-right">
        <ul>
            <li><a href="#" onclick="$('.ynfr-chart-block').show(); $('.ynfr-list-block').hide(); $(this).parents('ul').find('.active').removeClass('active'); $(this).addClass('active'); return false;">Chart</a></li>
            <li><a class="active" href="#"  onclick="$('.ynfr-list-block').show(); $('.ynfr-chart-block').hide(); $(this).parents('ul').find('.active').removeClass('active'); $(this).addClass('active'); return false;">List</a></li>
        </ul>
    </div>
</div>
<div class="ynfr-chart-block" style="display: none;">
    {if $aTransactions && isset($aTransactions) && count($aTransactions) || $aCampaignStats && isset($aCampaignStats) && count($aCampaignStats)}
    {literal}
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    {/literal}
        {literal}
        <script type="text/javascript">
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['', 'Donation Amount'],
                    {/literal}
                    {if $aTransactions && isset($aTransactions) && count($aTransactions)}
                    {foreach from=$aTransactions key=iKey item=aTransaction}
                        {if count($aTransactions) == $iKey+1}
                            {literal}['{/literal}{$aTransaction.time_stamp|date:"fundraising.statistic_time_stamp"}{literal}',{/literal}{$aTransaction.amount}{literal}]{/literal}
                        {else}
                            {literal}['{/literal}{$aTransaction.time_stamp|date:"fundraising.statistic_time_stamp"}{literal}',{/literal}{$aTransaction.amount}{literal}],{/literal}
                        {/if}
                    {/foreach}
                    {elseif $aCampaignStats && isset($aCampaignStats) && count($aCampaignStats)}
                    {foreach from=$aCampaignStats key=iKey item=aCampaignStat}
                        {if count($aCampaignStat) == $iKey+1}
                            {literal}['{/literal}{$aCampaignStat.time_stamp|date:"fundraising.statistic_time_stamp"}{literal}',{/literal}{$aCampaignStat.amount}{literal}]{/literal}
                        {else}
                            {literal}['{/literal}{$aCampaignStat.time_stamp|date:"fundraising.statistic_time_stamp"}{literal}',{/literal}{$aCampaignStat.amount}{literal}],{/literal}
                        {/if}
                    {/foreach}
                    {/if}
                    {literal}
                ]);

                var options = {
                    width: {/literal}{if $aTransactions && isset($aTransactions) && count($aTransactions)}{literal} 700{/literal}{else}{literal}600{/literal}{/if}{literal},
                    height: 500,
                    pointSize: 6,
                    fontSize: 10
                };

                var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script>
        {/literal}
    <div id="chart_div" style="width: {if $aTransactions && isset($aTransactions) && count($aTransactions)} 700px{else}600px{/if}; height: 500px;"></div>
    {else}
    <div>{phrase var='fundraising.there_are_no_transaction'}</div>
    {/if}

</div>
*}
<div class="ynfr-list-block {if phpfox::isMobile()}statitic-mobile{/if}">
        <form class="ynfr" method="post" action="{url link=$sUrl}">
            <div class="statistic-left">
                <div class="table">
                    <div class="table_left">
                        {phrase var='fundraising.from_date'}:
                    </div>
                    <div class="table_right">
                        <input name="search[fromdate]" id="js_from_date_listing" type="text" value="{if $sFromDate}{$sFromDate}{/if}" />
                        <a href="#" id="js_from_date_listing_anchor">
                            <img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
                        </a>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="table">
                    <div class="table_left">
                        {phrase var='fundraising.to_date'}:
                    </div>
                    <div class="table_right">
                        <input name="search[todate]" id="js_to_date_listing" type="text" value="{if $sToDate}{$sToDate}{/if}" />
                        <a href="#" id="js_to_date_listing_anchor">
                            <img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
                        </a>
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="table">
                    <div class="table_left">
                        {phrase var='fundraising.keyword'}:
                    </div>
                    <div class="table_right ynfr-static-key">
                        {$aFilters.keyword}
                        <div class="clear"></div>
                    </div>
                </div>

                <div class="table_clear">
                    <input type="submit" name="search[submit]" value="{phrase var='fundraising.filter'}" class="button" />
                </div>
            </div>
        </form>
        <div class="clear"></div>
        {pager}
        {if $aTransactions && isset($aTransactions) && count($aTransactions)}
        <table class="ynfr" colspan='1' cellpadding="5" cellspacing="5">
            <tr>
				{if !phpfox::isMobile()}
					<th>{phrase var='fundraising.date'}</th>
					<th>{phrase var='fundraising.amount'}</th>
					<th>{phrase var='fundraising.transaction_id'}</th>
					<th>{phrase var='fundraising.status'}</th>
					<th>{phrase var='fundraising.donor'}</th>
					<th>{phrase var='fundraising.email_address'}</th>
					<th>{phrase var='fundraising.option'}</th>
				{else}
					<th>{phrase var='fundraising.date'}</th>
					<th>{phrase var='fundraising.amount'}</th>
					<th>{phrase var='fundraising.status'}</th>
					<th>{phrase var='fundraising.donor'}</th>
					<th>{phrase var='fundraising.option'}</th>
				{/if}
                
            </tr>
            {foreach from=$aTransactions key=iKey item=aTransaction}
            <tr id="js_row{$aTransaction.transaction_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
				{if !phpfox::isMobile()}
					<td>
						{$aTransaction.time_stamp|date}
					</td>
					<td>
						{$aTransaction.amount}
					</td>
					<td>
						{$aTransaction.paypal_transaction_id}
					</td>
					<td>
						{$aTransaction.status}
					</td>
					<td>
						{if $aTransaction.is_guest}{$aTransaction.guest_full_name}{else}{$aTransaction.full_name}{/if}
					</td>
					<td>
						{if $aTransaction.is_guest}{$aTransaction.guest_email_address}{else}{$aTransaction.email}{/if}
					</td>
					<td>
						<a href="{url link='current' view='detail' transaction=$aTransaction.transaction_id}">{phrase var='fundraising.view_details'}</a>
					</td>
				{else}
					<td>
						{$aTransaction.time_stamp|date}
					</td>
					<td>
						{$aTransaction.amount}
					</td>
					<td>
						{$aTransaction.status}
					</td>
					<td>
						{if $aTransaction.is_guest}{$aTransaction.guest_full_name}{else}{$aTransaction.full_name}{/if}
					</td>
					<td>
						<a href="{url link='current' view='detail' transaction=$aTransaction.transaction_id}">{phrase var='fundraising.view_details'}</a>
					</td>
				{/if}
            </tr>
            {/foreach}
        </table>
        {elseif $aCampaignStats && isset($aCampaignStats) && count($aCampaignStats)}
        <table colspan='1' cellpadding="5" cellspacing="5">
            <tr>
                <th>{phrase var='fundraising.campaign'}</th>
                <th>{phrase var='fundraising.status'}</th>
                <th>{phrase var='fundraising.owner'}</th>
                <th>{phrase var='fundraising.donor'}</th>
                <th>{phrase var='fundraising.transaction_id'}</th>
                <th>{phrase var='fundraising.amount'}</th>
                <th>{phrase var='fundraising.date'}</th>
                <th>{phrase var='fundraising.option'}</th>
            </tr>
            {foreach from=$aCampaignStats key=iKey item=aCampaignStat}
            <tr id="js_row{$aCampaignStat.transaction_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
                <td>
                    {$aCampaignStat.title}
                </td>
                <td>
                    {$aCampaignStat.status}
                </td>
                <td>
                    {$aCampaignStat.owner}
                </td>
                <td>
                    {$aCampaignStat.donor}
                </td>
                <td>
                    {$aCampaignStat.paypal_transaction_id}
                </td>
                <td>
                    {$aCampaignStat.amount}
                </td>
                <td>
                    {$aCampaignStat.time_stamp|date}
                </td>
                <td>
                    <a href="{url link='current' view='detail' transaction=$aCampaignStat.transaction_id}">{phrase var='fundraising.view_details'}</a>
                </td>
            </tr>
            {/foreach}
        </table>
        {else}
        <div>{phrase var='fundraising.there_are_no_transaction'}</div>
        {/if}
        {pager}
    </div>