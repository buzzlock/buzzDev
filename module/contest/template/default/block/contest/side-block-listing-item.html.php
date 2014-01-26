<?php

defined('PHPFOX') or exit('NO DICE!');
?>
<!-- Main entry -->
<div class="yc_small_item">
	<div class="item_left" style="background-image:url({img return_url=true server_id=$aItem.server_id path='core.url_pic' file="contest/".$aItem.image_path suffix='_100'});">
		<!--a href="{permalink module='contest' id=$aItem.contest_id title=$aItem.contest_name}" title="{$aItem.contest_name|clean}">
           {img server_id=$aItem.server_id path='core.url_pic' file="contest/".$aItem.image_path suffix='_100' max_width='100' max_height='100' class='js_mp_fix_width'}
		</a-->
	</div>
	<div class="item_right">
		<p>
			<a class="small_title" href="{permalink module='contest' id=$aItem.contest_id title=$aItem.contest_name}" title="{$aItem.contest_name|clean}">
				{$aItem.contest_name|clean|shorten:16:'...'|split:20}
			</a>
			<div class="extra_info">
				<p>{phrase var='contest.participants'}: {$aItem.total_participant}</p>
				<p>{phrase var='contest.entries'}: {$aItem.total_entry}</p>
				<p>{phrase var='contest.end'}: {$aItem.end_time_parsed}</p>
			</div>
		</p>
	</div>
</div>
