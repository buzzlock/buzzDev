<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
<ul class="action">
	{foreach from=$aSingers  item=iType}
		<li>
			<h4 style="padding: 0px;padding-top: 5px; padding-bottom: 5px; padding-left: 4px;color: #333333; font-weight: bold;">{$iType.info.title}</h4>
			{if isset($iType.singer)}
				<ul style="padding-left: 5px; margin: 0px;">
					{foreach from=$iType.singer item = iSinger}
						<li {if $sfsid eq $iSinger.singer_id}class="active"{/if}>
							<a style="padding: 0px;padding-top: 5px; padding-bottom: 5px;padding-left: 5px;" href="{url link ='musicsharing.song.singer_'.$iSinger.singer_id.'.sititle_'.$iSinger.title}">{$iSinger.title}</a>
						</li>
					{/foreach} 
				</ul>   
			{/if} 
		</li>
	{/foreach}
    <li {if $sfsid eq "others"}class="active"{/if}><a style="padding-top: 5px; padding-bottom: 5px; padding-left: 4px; color: black; font-weight: bold;" href="{url link ='musicsharing.song.singer_others'}"><h4>{phrase var='musicsharing.others'}</h4></a></li>
</ul>