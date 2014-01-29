<?php
defined('PHPFOX') or exit('NO DICE!');

?>
<div class="socialbridge_privacy_form">
  <div class="{if $sPrivacyFormType == 'mini'}privacy_setting_mini{else}privacy_setting{/if} privacy_setting_div">
      <div><input type="hidden" name="val{if !empty($sPrivacyArray)}[{$sPrivacyArray}]{/if}[{$sPrivacyFormName}]" value="{$aSelectedPrivacyControl.value}" /></div>
      <a href="#" class="privacy_setting_active{if $sPrivacyFormType == 'mini'} js_hover_title{/if}">{$aSelectedPrivacyControl.phrase}<span class="js_hover_info">{$aSelectedPrivacyControl.phrase}</span></a>
      <div class="privacy_setting_holder">
          <ul>
          {foreach from=$aPrivacyControls item=aPrivacyControl}
              <li><a href="#"{if isset($aPrivacyControl.onclick)} onclick="{$aPrivacyControl.onclick} return false;"{/if} rel="{$aPrivacyControl.value}" {if isset($aPrivacyControl.is_active)}class="is_active_image"{/if}>{$aPrivacyControl.phrase}</a></li>
          {/foreach}
          </ul>
      </div>
  </div>
  {if !empty($sPrivacyFormInfo)}
  <div class="extra_info">
      {$sPrivacyFormInfo}
  </div>
  {/if}
</div>