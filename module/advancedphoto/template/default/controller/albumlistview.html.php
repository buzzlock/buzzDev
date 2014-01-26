
{template file='advancedphoto.block.photo-entry'}
{if Phpfox::getUserParam('advancedphoto.can_approve_photos') || Phpfox::getUserParam('advancedphoto.can_delete_other_photos')}
	{moderation}
{/if}
	<div class="extra_info" id="ynadvphoto_album_in_this_album_holder"  {if !$sJsAlbumTagContent} style="display:none" {/if}>
		<b>{phrase var='advancedphoto.in_this_album'}: </b> <span id="ynadvphoto_album_in_this_album"> {$sJsAlbumTagContent} </span>
	</div>	
<div class="clear"></div>
<div {if $aForms.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
	{module name='advancedphoto.yncomment'}
</div>