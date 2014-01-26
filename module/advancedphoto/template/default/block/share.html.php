<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Feed
 * @version 		$Id: display.html.php 2284 2011-02-01 15:58:18Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

	

{if $bIsInPage}

<div class="global_attachment_holder_section" id="global_attachment_advancedphoto">
	<div><input type="hidden" name="val[group_id]" value="{if isset($aFeedCallback.item_id)}{$aFeedCallback.item_id}{else}0{/if}" /></div>			
	<div><input type="hidden" name="val[action]" value="upload_photo_via_share" /></div>
	{if Phpfox::getLib('request')->isIOS()}
			<input type="button" name="FiledataOriginal" id="FiledataOriginal" value="Choose photo" style="display:none;">
		{else}
			<div id="divFileInput"><input type="file" name="image[]" id="global_attachment_advancedphoto_file_input" value="" onchange="$bButtonSubmitActive = true; $('.activity_feed_form_button .button').removeClass('button_not_active');" /></div>
			<div class="extra_info">
				{phrase var='advancedphoto.select_a_photo_to_attach'}
			</div>						
	{/if}
</div>

	{literal}
	<script type="text/javascript">
		$Behavior.ynadvancedphotoChangeToPhotoInPage = function () {
			$('.activity_feed_form_attach .activity_feed_link_form').each(function(i) { 
				var href = $(this).html();
				if(href.search('advancedphoto/frame') != -1)
				{
					$(this).html(href.replace('advancedphoto/frame', 'photo/frame'));
				}
			});
		};
	</script>
	{/literal}
{else}
	<div class="global_attachment_holder_section" id="global_attachment_advancedphoto">
	<div><input type="hidden" name="val[group_id]" value="{if isset($aFeedCallback.item_id)}{$aFeedCallback.item_id}{else}0{/if}" /></div>			
	<div><input type="hidden" name="val[action]" value="upload_advancedphoto_via_share" /></div>
	{if Phpfox::getLib('request')->isIOS()}
			<input type="button" name="FiledataOriginal" id="FiledataOriginal" value="Choose photo" style="display:none;">
		{else}
			<div id="divFileInput"><input type="file" name="image[]" id="global_attachment_advancedphoto_file_input" value="" onchange="$bButtonSubmitActive = true; $('.activity_feed_form_button .button').removeClass('button_not_active');" /></div>
			<div class="extra_info">
				{phrase var='advancedphoto.select_a_photo_to_attach'}
			</div>						
	{/if}
	</div>	

	{literal}
	<script type="text/javascript">
    $Behavior.FphotoResetForm = function() {
    	$ActivityFeedCompleted.resetYnadvancedphotoForm = function()
    	{
    		$bButtonSubmitActive = true;
    		$('#global_attachment_advancedphoto_file_input').val('');
    	}
    }
	</script>
	{/literal}

{/if}