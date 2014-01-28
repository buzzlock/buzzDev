<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="sub_section_menu">
	<ul class="action">
	{foreach from=$aCats key = key item=aCat}
		<li {if $scat eq $aCat.cat_id}class="active"{/if}>
			<a style="padding: 0px; padding-left: 4px;" href="{url link = 'musicsharing.song.cat_'.$aCat.cat_id.'.catitle_'.$aCat.title}">{$aCat.title} </a> 
		</li>
	{/foreach}
		<li {if $scat eq "others"}class="active"{/if}><a style="padding: 0px; padding-left: 4px;" class="first" href="{url link = 'musicsharing.song.cat_others'}">{phrase var='musicsharing.others'} </a></li>
	</ul>
</div>