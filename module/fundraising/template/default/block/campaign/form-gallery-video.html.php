<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{if Phpfox::getUserParam('fundraising.can_upload_video')}
<h3>{phrase var='fundraising.video_gallery'}</h3>
{if $aVideo}
	{img server_id=$aVideo.server_id path='core.url_pic' file=$aVideo.image_path suffix='_120' max_width='120' max_height='120' class='js_mp_fix_width'}
{/if}
<div class="extra_info">
    {phrase var='fundraising.please_enter_a_youtube_video_url'}
</div>
<input type="text" class="url" name="val[video_url]" size="100" >
<div id="js_submit_upload_video" class="table_clear">
   
</div>

{/if}