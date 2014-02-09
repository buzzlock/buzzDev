<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 9, 2014, 5:51 am */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Feed
 * @version 		$Id: display.html.php 2284 2011-02-01 15:58:18Z Raymond_Benc $
 */
 
 

?>

	

<?php if ($this->_aVars['bIsInPage']): ?>

<div class="global_attachment_holder_section" id="global_attachment_advancedphoto">
	<div><input type="hidden" name="val[group_id]" value="<?php if (isset ( $this->_aVars['aFeedCallback']['item_id'] )):  echo $this->_aVars['aFeedCallback']['item_id'];  else: ?>0<?php endif; ?>" /></div>			
	<div><input type="hidden" name="val[action]" value="upload_photo_via_share" /></div>
<?php if (Phpfox ::getLib('request')->isIOS()): ?>
			<input type="button" name="FiledataOriginal" id="FiledataOriginal" value="Choose photo" style="display:none;">
<?php else: ?>
			<div id="divFileInput"><input type="file" name="image[]" id="global_attachment_advancedphoto_file_input" value="" onchange="$bButtonSubmitActive = true; $('.activity_feed_form_button .button').removeClass('button_not_active');" /></div>
			<div class="extra_info">
<?php echo Phpfox::getPhrase('advancedphoto.select_a_photo_to_attach'); ?>
			</div>						
<?php endif; ?>
</div>

	<?php echo '
	<script type="text/javascript">
		$Behavior.ynadvancedphotoChangeToPhotoInPage = function () {
			$(\'.activity_feed_form_attach .activity_feed_link_form\').each(function(i) { 
				var href = $(this).html();
				if(href.search(\'advancedphoto/frame\') != -1)
				{
					$(this).html(href.replace(\'advancedphoto/frame\', \'photo/frame\'));
				}
			});
		};
	</script>
	'; ?>

<?php else: ?>
	<div class="global_attachment_holder_section" id="global_attachment_advancedphoto">
	<div><input type="hidden" name="val[group_id]" value="<?php if (isset ( $this->_aVars['aFeedCallback']['item_id'] )):  echo $this->_aVars['aFeedCallback']['item_id'];  else: ?>0<?php endif; ?>" /></div>			
	<div><input type="hidden" name="val[action]" value="upload_advancedphoto_via_share" /></div>
<?php if (Phpfox ::getLib('request')->isIOS()): ?>
			<input type="button" name="FiledataOriginal" id="FiledataOriginal" value="Choose photo" style="display:none;">
<?php else: ?>
			<div id="divFileInput"><input type="file" name="image[]" id="global_attachment_advancedphoto_file_input" value="" onchange="$bButtonSubmitActive = true; $('.activity_feed_form_button .button').removeClass('button_not_active');" /></div>
			<div class="extra_info">
<?php echo Phpfox::getPhrase('advancedphoto.select_a_photo_to_attach'); ?>
			</div>						
<?php endif; ?>
	</div>	

	<?php echo '
	<script type="text/javascript">
    $Behavior.FphotoResetForm = function() {
    	$ActivityFeedCompleted.resetYnadvancedphotoForm = function()
    	{
    		$bButtonSubmitActive = true;
    		$(\'#global_attachment_advancedphoto_file_input\').val(\'\');
    	}
    }
	</script>
	'; ?>


<?php endif; ?>
