<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');                
?>  
<input TYPE="hidden" NAME="cmd" VALUE="_xclick">
<input TYPE="hidden" NAME="business" VALUE="{$receiver.email}">
<input TYPE="hidden" NAME="amount" VALUE="{$receiver.amount}">
<input TYPE="hidden" NAME="currency_code" VALUE="{$currency}">
<input TYPE="hidden" NAME="description" VALUE="Buy music">
<input type="hidden" name="notify_url" value="{$paramPay.ipnNotificationUrl}"/>
<input type="hidden" name="return" value="{$paramPay.returnUrl}"/>
<input type="hidden" name="cancel_return" value="{$paramPay.cancelUrl}"/>
<input type="hidden" name="no_shipping" value="1"/>
<input type="hidden" name="no_note" value="1"/>