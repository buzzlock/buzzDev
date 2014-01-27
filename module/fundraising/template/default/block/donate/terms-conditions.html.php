<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<div class="ynfr term">
<p class="select-amount extra_info">{phrase var='fundraising.campaign_s_terms_and_conditions'}</p>
<p>
{$aCampaign.term_condition}
</p>
</div>
<p class="ynfr term-agree"><input type="checkbox" class="required"  name="val[is_agree]" >{phrase var='fundraising.i_have_read_and_agreed_with_all_terms_and_conditions'}       </p>
