{template file='advancedphoto.block.photo-entry'}
{if $bIsLoadMore}
	<div id="yn_loadmore_phrase_{$iYear}" class="advancedphoto-viewmore">
		<div id ="yn_loadmore_phrase_waiting_icon_{$iYear}" style='display:none'> {img theme='ajax/add.gif'} </div>	
		<a href="#" onclick='ynphoto.loadMorePhotos({$iYear}, {$iNextPage}, {$iMaxPhotosPerLoad}); return false;'> {phrase var='advancedphoto.load_more'} </a>
	</div>
{/if}
