<?php
/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Suggestion
 * @version 		$Id: ajax.class.php 1 2011-11-25 15:29:17Z YOUNETCO $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<form action="" method="post" name="frmIncoming" id="frmIncoming">
    <input type="hidden" name="iUserId" id="iUserId" value="<?= Phpfox::getUserId(); ?>" />
    <div id="sKey" style="display:none;">{$sKey}</div>
    {if count($aRows)>0}
    {foreach from=$aRows item=aRow}
    <p id="process_{$aRow.suggestion_id}" style="line-height: 1.5em; border-bottom:1px solid #CCCCCC; padding: 5px 0; position:relative; float:left; width: 100%">
        <span style="width:550px; float: left;">{$aRow.message}</span>
        {if ($sView=='incoming')} 
        <span class="ajaxLoader hide" style="position: absolute; right:120px;"><img src="{$sFullUrl}theme/frontend/default/style/default/image/ajax/add.gif" /></span>
        <input type="button" class="button" style="position: absolute; right:80px; top:12px;" value="{$aRow.accept}" onclick="doProcess(this, 1, {$aRow.friend_user_id}, {$aRow.friend_friend_user_id}, 'process_{$aRow.suggestion_id}','{$aRow.sub_module_id}', '{$aRow.url}'); return false;"></input>
        <input type="button" class="button" style="position: absolute; right:10px; top:12px;" value="{$aRow.ignore}" onclick="doProcess(this, 2, {$aRow.friend_user_id}, {$aRow.friend_friend_user_id}, 'process_{$aRow.suggestion_id}','{$aRow.sub_module_id}','{$aRow.url}'); return false;"></input>
        {/if}
    </p>
    {/foreach}
    {else}
    {phrase var='suggestion.no_suggestion_at_this_time'}
    {/if}
<div class="float:none">&nbsp;</div>
    {pager}
    <input type="hidden" id="sHideKey" name="sHideKey" value="{$sDefaultKey}" />
</form>
<script language="javascript">
$Behavior.headerbarfloatSuggestion = function(){l}    
    {if $bShowFilter}
    $('.header_bar_float').eq(0).hide();
    {/if}        
    $('.header_bar_float').eq(1).hide();
    $('.header_bar_search_holder').find('input').eq(0).val($('#sKey').html());    
   
    {r}
	
	{literal}
    function doProcess(target, iApprove, iFriendId, iItemId, iProcessId, sModule, sUrl){
        $(target).parent().find('input[class="button"]').hide();
        $(target).parent().find('span[class*="ajaxLoader"]').show();        
        $.ajaxCall('suggestion.approve','iApprove='+iApprove+'&iFriendId='+iFriendId+'&iItemId='+iItemId+'&sModule='+sModule+'&iProcessId='+iProcessId+'&sUrl='+sUrl);
    }    
    if ($('.header_bar_search_holder').find('input').val() != $('#sHideKey').val()){
        $('.header_bar_search_holder').find('input').css('color','#000000');
    }

    {/literal}
</script>    

{literal}
<style>
    .hide{display: none}
    .show{display: block}
    .pager_outer{float:left; width:100%;}    
</style>  
{/literal}