<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style type="text/css">
    .signature_goal{background: #f6f5e8;text-align: center;padding:5px;}
    .sig_goal_tit{font-size: 18px;color: #7B7A74;padding:10px;border-bottom: 1px solid #dfdfdf;}
    .signature_goal .text1{font-size: 24px;font-weight: bold;}
    .signature_goal .text2{font-family: Scriptinas Pro;font-size: 34px;font-style: oblique;}
    .signature_goal .text3{font-size: 12px;}
    .sign_now
    {
        background: url({/literal}{$corepath}{literal}module/fundraising/static/image/signin-l.png) no-repeat left;
        margin-left: 52px;
        margin-top: 5px;
        padding-left: 14px;
        line-height: 38px;
    }
    .sign_now_r
    {
        background: url({/literal}{$corepath}{literal}module/fundraising/static/image/signin-r.png) no-repeat right;
        width: 120px;
    }
    .sign_now a{font-size: 16px;font-weight: bold;color: #fff; padding: 0 13px; text-decoration: none;}
    
    .signed
    {
        background: url({/literal}{$corepath}{literal}module/fundraising/static/image/signed-l.png) no-repeat left;
        margin-left: 52px;
        margin-top: 5px;
        padding-left: 14px;
        line-height: 38px;
        font-size: 16px;font-weight: bold;color: #fff; padding: 0 9px; text-align: center;
    }
    .signed_r
    {
        background: url({/literal}{$corepath}{literal}module/fundraising/static/image/signed-r.png) no-repeat right;
        width: 120px;
    }
    
    .fundraising_victory{
        background: url({/literal}{$corepath}{literal}module/fundraising/static/image/victory.png) no-repeat top left;
        width: 115px; height: 115px;
        position: absolute;
        top: 6px;
        left: 0;
    }
    .fundraising_closed{
        background: url({/literal}{$corepath}{literal}module/fundraising/static/image/closed.png) no-repeat top left;
        width: 115px; height: 115px;
        position: absolute;
        top: 6px;
        left: 0;
    }
</style>
{/literal}
{if $aDirect.status != 2}
<div class="fundraising_image" style="min-height: 115px;">
    {if $aDirect.status == 3}
        <div class="fundraising_victory"></div>
    {else if $aDirect.status == 1}
        <div class="fundraising_closed"></div>
    {/if}
{else}
<div class="fundraising_image" style="min-height: 115px;">    
{/if}
    <a href="{permalink module='fundraising' id=$aDirect.campaign_id title=$aDirect.title}" title="{$aDirect.title|clean}">{img server_id=$aDirect.server_id path='core.url_pic' file=$aDirect.image_path suffix='_300' max_width=235 max_height=300 class='photo_holder'}</a>
</div>
<div class="fundraising_detail">
    <a class="link"  href="{permalink module='fundraising' id=$aDirect.campaign_id title=$aDirect.title}" class="row_sub_link" title="{$aDirect.title|clean}">{$aDirect.title|clean|shorten:50:'...'|split:20}</a>		
    <div class="extra_info">{phrase var='fundraising.created_by'} {$aDirect|user} {phrase var='fundraising.in'} <a href="{$aDirect.category.link}">{$aDirect.category.name}</a>
    
        <br/>{phrase var='fundraising.target'}: {$aDirect.target|shorten:75:'...'} 
        <br/>{phrase var='fundraising.fundraising_goal'}: {$aDirect.fundraising_goal|shorten:75:'...'}
        
        <br/><span class="total_sign">{$aDirect.total_sign}</span>{phrase var='fundraising.total_sign_signatures' total_sign=''} - {phrase var='fundraising.total_like_likes' total_like=$aDirect.total_like} - {phrase var='fundraising.total_view_views' total_view=$aDirect.total_view}        
    </div>    
    <div class="item_content">
        {$aDirect.short_description|shorten:200:'...'}
    </div>    
</div>
{if $aDirect.status == 2}
{if $aDirect.can_sign == 1} 
<div id="sign_now_{$aDirect.campaign_id}">   
   <div class="sign_now">
      <div class="sign_now_r">    
          <a href="#" onclick="$Core.box('fundraising.sign',400,'&id={$aDirect.campaign_id}'); return false;">{phrase var='fundraising.sign_now'}</a>
      </div>
  </div>	
</div>
{/if}
<div id="signed_{$aDirect.campaign_id}" {if $aDirect.can_sign != 2} style="display: none" {/if}>
    <div class="signed">
         <div class="signed_r">
               {phrase var='fundraising.signed'}
         </div>
    </div>
</div>
{/if}