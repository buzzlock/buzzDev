<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
{foreach from=$aFeatured item=aMiniVideo}
{template file='videochannel.block.mini'}
{/foreach}

{if $bViewMore}
<div style="padding-top: 10px; text-align:right;"><a href="{url link=$sLink sort='featured'}">{phrase var='videochannel.view_all'}</a></div>
{/if}