<?php 

defined('PHPFOX') or exit('NO DICE!'); 
// if Phpfox::getParam('videochannel.allow_videochannel_uploading')
?>
{if Phpfox::getParam('videochannel.allow_videochannel_uploading')}
<div id="js_upload_video_file" class="page_section_menu_holder">
	{if isset($sErrorFFMPEG) && !empty($sErrorFFMPEG)}
		<div class="error_message">{$sErrorFFMPEG}</div>
	{else}
		{module name='videochannel.file'}
	{/if}
</div>
{/if}
<div id="js_upload_video_url" class="page_section_menu_holder"{if Phpfox::getParam('videochannel.allow_videochannel_uploading')} style="display:none;"{/if}>
	{module name='videochannel.url'}
</div>