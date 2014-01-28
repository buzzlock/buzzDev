<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<?php $rcount = 0; ?>
<div class="content">
	<ul class="action" style="padding-top: 5px;">
	{foreach from=$aTopDownloads item=aSong}
			<?php $rcount++; ?>
			<li style="color: #3B5998; clear: both;">
				{*<span style="float: left; color: #000000;"><?php echo $rcount; ?>.&nbsp;</span>*}
				<a class="first" href="{url link='musicsharing.listen.music_'.$aSong.song_id}">{$aSong.title}
					<br />
					<span style="color: #8F8F8F; font-size: 10px;">{phrase var='musicsharing.download'}(s): {$aSong.download_count}</span>
				</a>
			</li>
	{/foreach}
	</ul>
</div>