<?php
/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Company
 * @package          Module_AdvMarketplace
 * @version          3.01
 *
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{if count($aTags)}
	<div style="width:95%;">
		{foreach from=$aTags item=aTag}
			<a href="{$aTag.link}" style="font-size:{$aTag.value}px;" title="{$aTag.key|parse|clean}">{$aTag.key}</a>  
		{/foreach}
	</div>
{else}
	<div class="extra_info">
		{phrase var='tag.no_tags_have_been_found'}
	</div>
{/if}