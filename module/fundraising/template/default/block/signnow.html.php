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
</style>
{/literal}
<div class="signature_goal">
    <div class="sig_goal_tit"><span>{phrase var='fundraising.signature_goal'}</span></div>
    <div class="sig_goal_cont">
        <span class="text1"><span class="total_sign">{$aItem.total_sign|number_format}</span> {phrase var='fundraising.out_of'} {$aItem.signature_goal|number_format}</span>
         </br><span class="text2">{phrase var='fundraising.signatures'}</span>        
    </div>
    {if $aItem.status == 1}
      <div class="error_message" style="text-align: center">{phrase var='fundraising.deadline_has_been_reached'}</div>
    {elseif $aItem.status == 2 && $aItem.is_approved == '1' }
      {if $aItem.can_sign == 1}
      <div id="sign_now_{$aItem.campaign_id}">   
        <div class="sign_now">
           <div class="sign_now_r">    
               <a href="#" onclick="$Core.box('fundraising.sign',400,'&id={$aItem.campaign_id}'); return false;">{phrase var='fundraising.sign_now'}</a>
           </div>
       </div>	
     </div>
     {/if}
     <div id="signed_{$aItem.campaign_id}" {if $aItem.can_sign != 2} style="display: none" {/if}>
         <div class="signed">
              <div class="signed_r">
                    {phrase var='fundraising.signed'}
              </div>
         </div>
     </div>	
   {/if}
</div>

