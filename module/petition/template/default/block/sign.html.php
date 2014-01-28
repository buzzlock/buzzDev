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
        $('#js_form_sign').ajaxCall('petition.signPetition');
        $('#petition_btn_sign').html('{/literal}{img theme="ajax/add.gif" alt="" class="v_middle"}{literal}');
        $('.js_box_close').remove();
      }        
      return false;
  }
{/literal}
</script>

<form id="js_form_sign" method="post" action="#" onsubmit="return Validation_form_sign();">
    <input type="hidden" name="val[petition_id]" value="{$aPetition.petition_id}"/>
    <div class="table">
        <div class="table_left">
            {phrase var='petition.your_location'}
        </div>
        <div class="table_right">            
            <input type="text" name="val[location]" id="location" style="width: 98%" value="">
        </div>
    </div>
    <div class="table">
        <div class="table_left">
                {phrase var='petition.add_a_reason'}                
        </div>
        <div class="table_right">
            <div class="extra_info">
                    {phrase var='petition.why_are_you_signing'}
            </div>
            <textarea cols="40" rows="8" name="val[signature]" id="signature" style="width:98%; height:100px;"></textarea>
        </div>
    </div>
    
    <div class="table_clear">
        <ul class="table_clear_button">
            <li id="petition_btn_sign"><input type="submit" name="val[sign]" value="{phrase var='petition.sign_petition'}" class="button"/></li>        
        </ul>
        <div class="clear"></div>
    </div>
</form>

<div id="js_thank_message" style="display: none">
    <p align="center">{phrase var='petition.thank_you_for_signing_the_petition'}</p>
    <p align="center"><strong>{$aPetition.title}</strong></p>
    <br/>
    <p align="center">
        <input type="button" class="button" value="{phrase var='petition.ok'}" onclick="return js_box_remove(this);"/>
        <input type="button" class="button" value="{phrase var='petition.invite_friends'}" onclick="js_box_remove(this); $Core.box('petition.inviteBlock',800,'&id={$aPetition.petition_id}'); return false;"/>
    </p>
</div>