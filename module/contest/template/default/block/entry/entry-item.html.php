<div class="yc large_item image_hover_holder yncontest_add_entry_item" id='yncontest_entry_item_{$aItem.item_id}' entry_item_id='{$aItem.item_id}'>
	<div class="yc_view_image {if $aContest.type==1 || $aContest.type==4}item_small_image{else}item_large_image{/if}" style="background-image:url({img return_url=true server_id=$aItem.server_id path='core.url_pic' file=$aItem.image_path})">
	</div>
	<div class="large_item_info">
		<p>
			<a class="small_title" href="#" onclick="return false;" title="{$aItem.title|clean}">
				{$aItem.title|clean|shorten:25:'...'}
			</a>
		</p>
	</div>
</div>