<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
{foreach from=$aPhpfoxNews name=news item=aNews}
<div class="p_bottom_10">
	<a href="{$aNews.link}" target="_blank">{$aNews.title|clean}</a>
	<div class="extra_info">
		{$aNews.posted_on}		
	</div>
</div>
{/foreach}