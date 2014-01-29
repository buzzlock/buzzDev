<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		YOUNETCO
 * @author  		AnNT
 * @package 		YouNet SocialStream
 * @version 		3.03
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<script type="text/javascript">
$Core.remakePostUrl = function()
{
    var keyword = js_socialstream_statuser.keyword.value;
    var type = js_socialstream_statuser.type.value;
    var url = js_socialstream_statuser.action + 'keyword_' + keyword + '/' + 'type_' + type + '/';
    
    window.location.href = url;
}
</script>
{/literal}

{if empty($aServices)}
<div class="message">{phrase var='socialstream.no_providers_found'}</div>
{else}
<form name="js_socialstream_statuser" action="{url link='admincp.socialstream.statuser'}" onsubmit="$Core.remakePostUrl(); return false;">
<div class="st table table_clear">
    <div class="st_select">
		<div class="st_item">
			 <label>{phrase var='socialstream.search'}: </label>
			 <input id="keyword" type="text" name="keyword" value="{value type='input' id='keyword'}" /> 
		</div>
		<div class="st_item">
			 <label>{phrase var='socialstream.within'}: </label>
			 <select id="type" name="type">
                <option value="en"{if $aForms.type=='en'} selected="selected"{/if}>{phrase var='socialstream.email_name'}</option>
                <option value="e"{if $aForms.type=='e'} selected="selected"{/if}>{phrase var='socialstream.email'}</option>
                <option value="n"{if $aForms.type=='n'} selected="selected"{/if}>{phrase var='socialstream.name'}</option>
             </select>
		</div>
		<div class="st_item">
			<input type="submit" class="button" value="{phrase var='socialstream.go'}" />
		</div>
        <div class="clear"></div>
    </div>
</div>
</form>

<table id="js_socialstream_stat_by_user" cellpadding="0" cellspacing="0">
<tr>
	<th>{phrase var='socialstream.display_name'}</th>
    {foreach from=$aServices item=aService}
    <th align="center">{$aService.title} {phrase var='socialstream.feeds'}</th>
    {/foreach}
</tr>
{if empty($aStats)}
<tr>
	<td colspan="100%">
		{if $bIsSearch}{phrase var='socialstream.user_does_not_exist_or_does_not_get_any_feed'}{else}{phrase var='socialstream.there_are_no_feeds_to_statistics'}{/if}
	</td>
</tr>
{else}
{foreach from=$aStats name=stats item=aStat}
<tr{if is_int($phpfox.iteration.stats/2)} class="tr"{/if}>
	<td>{$aStat|user}</td>
    {foreach from=$aServices item=aService}
    {assign var=service value=$aService.name}
    <td align="center">{$aStat[$service]}</td>
    {/foreach}
</tr>
{/foreach}
{/if}
</table>
{pager}
{/if}
