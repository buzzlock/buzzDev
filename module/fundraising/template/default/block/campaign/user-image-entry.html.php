<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

?>

{if isset($aUser.is_guest) && ($aUser.is_guest || $aUser.is_anonymous)}
<a onclick="return false;" {if $aUser.is_anonymous} title="{phrase var='fundraising.anonymous_upper'}" {else} title="{$aUser.donor_name}" {/if}">
	<img src="{$sNoimageUrl}" class="js_hover_title"  width="32" height="32">
	</a>
{else}
	{img user=$aUser suffix='_50_square' max_width=32 max_height=32 class='js_hover_title' title='aaa'}
{/if}