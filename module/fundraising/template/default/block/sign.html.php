<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{$sCreateJs}

<script type="text/javascript">
{literal}
  function Validation_form_sign()
  {
      if ( Validation_js_form_sign())
      {        
        $('#js_form_sign').ajaxCall('fundraising.signFundraising');
        $('#fundraising_btn_sign').html('{/literal}{img theme="ajax/add.gif" alt="" class="v_middle"}{literal}');
        $('.js_box_close').remove();
      }        
      return false;
  }
{/literal}
</script>

<form id="js_form_sign" method="post" action="#" onsubmit="return Validation_form_sign();">
    <input type="hidden" name="val[campaign_id]" value="{$aFundraising.campaign_id}"/>
    <div class="table">
        <div class="table_left">
            {phrase var='fundraising.your_location'}
        </div>
        <div class="table_right">            
            <input type="text" name="val[location]" id="location" style="width: 98%" value="">
        </div>
    </div>
    <div class="table">
        <div class="table_left">
                {phrase var='fundraising.add_a_reason'}                
        </div>
        <div class="table_right">
            <div class="extra_info">
                    {phrase var='fundraising.why_are_you_signing'}
            </div>
            <textarea cols="40" rows="8" name="val[signature]" id="signature" style="width:98%; height:100px;"></textarea>
        </div>
    </div>
    
    <div class="table_clear">
        <ul class="table_clear_button">
            <li id="fundraising_btn_sign"><input type="submit" name="val[sign]" value="{phrase var='fundraising.sign_fundraising'}" class="button"/></li>        
        </ul>
        <div class="clear"></div>
    </div>
</form>

<div id="js_thank_message" style="display: none">
    <p align="center">{phrase var='fundraising.thank_you_for_signing_the_fundraising'}</p>
    <p align="center"><strong>{$aFundraising.title}</strong></p>
    <br/>
    <p align="center">
        <input type="button" class="button" value="{phrase var='fundraising.ok'}" onclick="return js_box_remove(this);"/>
        <input type="button" class="button" value="{phrase var='fundraising.invite_friends'}" onclick="js_box_remove(this); $Core.box('fundraising.inviteBlock',800,'&id={$aFundraising.campaign_id}'); return false;"/>
    </p>
</div>