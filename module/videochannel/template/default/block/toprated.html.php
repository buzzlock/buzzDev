<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{foreach from=$aTopRated item=aMiniVideo}
{template file='videochannel.block.mini'}
{/foreach}

{if $bViewMore}
<div style="padding-top: 10px; text-align:right;"><a href="{url link=$sLink sort='top-rated'}">{phrase var='videochannel.view_all'}</a></div>
{/if}