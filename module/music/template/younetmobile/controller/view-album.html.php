<div class="item_view">

    <div class="ym-info">
		{$aAlbum.time_stamp|convert_time} {phrase var='music.by_lowercase'} {$aAlbum|user:'':'':50}
    </div>
    
	{if $aAlbum.view_id != 0}
	<div class="message js_moderation_off" id="js_approve_message">
		{phrase var='music.album_is_pending_approval'}
	</div>
	{/if}    

	{if
		((($aAlbum.user_id == Phpfox::getUserId() && Phpfox::getUserParam('music.can_edit_own_albums')) || Phpfox::getUserParam('music.can_edit_other_music_albums')))
		|| ((($aAlbum.user_id == Phpfox::getUserId() && Phpfox::getUserParam('music.can_edit_own_albums'))))
		|| ($aAlbum.view_id == 0 && Phpfox::getUserParam('music.can_feature_music_albums'))
		|| (Phpfox::getUserParam('music.can_sponsor_album'))
		|| (Phpfox::getUserParam('music.can_purchase_sponsor_album') && !$aAlbum.is_sponsor && ($aAlbum.user_id == Phpfox::getUserId()))
		|| ((($aAlbum.user_id == Phpfox::getUserId() && Phpfox::getUserParam('music.can_delete_own_music_album')) || Phpfox::getUserParam('music.can_delete_other_music_albums')))
	}
	<div class="item_bar">
		<div class="item_bar_action_holder">	
			<a href="#" class="item_bar_action"><span>{phrase var='music.actions'}</span></a>		
			<ul>
				{template file='music.block.menu-album'}
			</ul>			
		</div>		
	</div>    
	{/if}
    <div class="ym-album-content">
     {$aAlbum.text|parse}
     {module name="music.track"}
    </div>
	
	<div {if $aAlbum.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
		{module name='feed.comment'}
	</div>
</div>