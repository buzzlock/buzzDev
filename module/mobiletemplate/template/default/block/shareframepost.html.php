<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="label_flow_menu">
	{if isset($frame) && $frame == 'post'}	
		{module name='mobiletemplate.feedshare' type=$sBookmarkType url=$sBookmarkUrl}
	{elseif isset($frame) && $frame == 'bookmark'}
		{module name='mobiletemplate.sharebookmark' type=$sBookmarkType url=$sBookmarkUrl title=$sBookmarkTitle}		
	{/if}		
</div>
<script type="text/javascript">$Core.loadStaticFile('{jscript file='switch_legend.js'}');</script>
<script type="text/javascript">$Core.loadStaticFile('{jscript file='switch_menu.js'}');</script>
<script type="text/javascript">$Core.loadInit();</script>