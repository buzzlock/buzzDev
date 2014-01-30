<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="{if isset($phpfox.iteration.minivideos)}{if is_int($phpfox.iteration.minivideos/2)}row1{else}row2{/if}{if $phpfox.iteration.minivideos == 1 && !isset($bIsLoadingMore)} row_first{/if}{else}row1 row_first row_no_border{/if}">
	<div style="float:left; width:90px;" class="t_center">
		<a href="{permalink module='videochannel' id=$aMiniVideo.video_id title=$aMiniVideo.title}">{img server_id=$aMiniVideo.image_server_id path='video.url_image' file=$aMiniVideo.image_path suffix='_120' max_width=90 max_height=70 class='js_mp_fix_width' title=$aMiniVideo.title}</a>
	</div>
	<div style="margin-left:100px;">
		<a href="{permalink module='videochannel' id=$aMiniVideo.video_id title=$aMiniVideo.title}" class="row_sub_link" title="{$aMiniVideo.title}">{$aMiniVideo.title|clean|shorten:'50':'...'}</a>
		<div class="extra_info_link">
			{phrase var='videochannel.by_lowercase'} {$aMiniVideo|user|shorten:20:'...'|split:20}<br />
			{if $aMiniVideo.total_view <= 1}
				{phrase var='videochannel.1_view'}
		      {else}
				{$aMiniVideo.total_view|number_format} {phrase var='videochannel.views'}
		      {/if}
		</div>
	</div>
	<div class="clear"></div>
</div>