<div class="entry_content_photo">
{img server_id=$aPhotoEntry.server_id path='core.url_pic' file=$aPhotoEntry.image_path suffix='_500' max_width='500' max_height='500' class='js_mp_fix_width'}

{if !$bIsPreview}
<div class="photo_action">
    <a target="_blank" class="large_item_image no_ajax_link" href="{img return_url=true path='core.url_pic' file=$aPhotoEntry.image_path suffix=$sSuffix}" title="{phrase var='contest.view_full_size'}">{phrase var='contest.view_full_size'}</a> |
    <a target="_blank" class="large_item_image no_ajax_link" href="{$core_path}module/contest/static/download.php?entry_id={$aPhotoEntry.entry_id}" title="{phrase var='contest.download'}">{phrase var='contest.download'}</a>
</div>
{/if}
</div>