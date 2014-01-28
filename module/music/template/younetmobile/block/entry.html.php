<div id="js_controller_music_track_{$aSong.song_id}" class="js_music_track js_song_parent {if isset($aSong.is_sponsor) && $aSong.is_sponsor}row_sponsored {/if}{if $aSong.is_featured}row_featured {/if} {if isset($phpfox.iteration.songs) && is_int($phpfox.iteration.songs/2)}row1{else}row2{/if}{if isset($phpfox.iteration.songs) && $phpfox.iteration.songs == 1} row_first{/if}{if $aSong.view_id == '1' && !isset($bIsInPendingMode)} row_moderate{/if}">

	{if $aSong.view_id == '1' && !isset($bIsInPendingMode)}
	<div class="row_moderate_info">
		{phrase var='music.pending_approval'}
	</div>
	{/if}

	<div class="row_title">	

		<div class="row_title_image">
			
			{if isset($sView) && $sView == 'featured'}
			{else}
			<div id="js_featured_phrase_{$aSong.song_id}" class="js_featured_song row_featured_link"{if !$aSong.is_featured} style="display:none;"{/if}>
				{phrase var='music.featured'}
			</div>					
			{/if}	
			<div id="js_sponsor_phrase_{$aSong.song_id}" class="js_sponsor_event row_sponsored_link"{if !$aSong.is_sponsor} style="display:none;"{/if}>
				{phrase var='music.sponsored'}
			</div>
			
			{img user=$aSong suffix='_50_square' max_width=50 max_height=50}
			
		</div>
		<div class="row_title_info">    
	
			<div id="js_controller_music_play_{$aSong.song_id}">
				
				<div class="music_title_link">
					<a href="{permalink module='music' id=$aSong.song_id title=$aSong.title}" class="link" title="{$aSong.title|clean}" {if defined('PHPFOX_IS_POPUP')} onclick="window.opener.location.href=this.href; return false;"{/if}>{$aSong.title|clean|shorten:35:'...'|split:20}</a>
					{if !empty($aSong.album_name)}
					<div class="extra_info">
						<a href="{permalink module='music.album' id=$aSong.album_id title=$aSong.album_name}" title="{$aSong.album_name|clean}">{$aSong.album_name|clean|shorten:55:'...'|split:40}</a>
					</div>
					{/if}
				</div>			
				<div class="clear"></div>
			</div>
			
			{if isset($aUser) && $aUser.user_id != $aSong.user_id || !defined('PHPFOX_IS_USER_PROFILE')}
			<div class="extra_info">
				<ul class="extra_info_middot"><li>{$aSong.time_stamp|convert_time} {phrase var='music.by_lowercase'} {$aSong|user:'':'':50}</li>{if $aSong.total_play > 1}<li><span>&middot;</span></li><li>{phrase var='music.total_plays' total=$aSong.total_play}</li>{/if}</ul>
			</div>
			{/if}
			
			
		</div>		
	</div>
</div>