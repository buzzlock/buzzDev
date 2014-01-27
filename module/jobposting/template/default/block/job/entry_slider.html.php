<div class="ync-feature">
	<div class="ync-feature-info">
		<div class = "ync-left">
			<a href="javascript:void(0);" title="">					
				<span style="background:url({img return_url=true server_id=$item.image_server_id path='core.url_pic' file="jobposting/".$item.image_path suffix='_240' max_width=241 max_height=150});background-repeat:no-repeat;background-position: center center;display:block;height:161px;width:240px;text-indent:-99999px">
				</span>
			</a>			
		</div>
		<div class = "ync-feature-content">
			<p id="js_coupon_edit_title[id]" class="ync-title">
				<a href="{permalink module='jobposting' id=$item.job_id title=$item.title}" id="js_coupon_edit_inner_title[id]" class="link ajax_link">{$item.title|clean|shorten:55:'...'|split:50}</a>
			</p>
			<p class="ynjp_featureContent_coName">{$item.name}</p>
			<p>{$item.location}</p>
			<p class="ynjp_featureContent_industry">
				{if isset($item.industrial_phrase) && $item.industrial_phrase!=""}{$item.industrial_phrase}{else}N/A (Industry){/if}
			</p>
			<p>{phrase var='jobposting.expire_on'}: {$item.time_expire_phrase}</p>		
		</div>
		<p class="ynjp_featureDesc">
			{$item.description_parsed_phrase|clean|shorten:150:'...'|split:50}		
		</p>
		<a href="{permalink module='jobposting' id=$item.job_id title=$item.title}" class="ynjp_viewmore">{phrase var='jobposting.view_more'}...</a>
	</div>
</div>               