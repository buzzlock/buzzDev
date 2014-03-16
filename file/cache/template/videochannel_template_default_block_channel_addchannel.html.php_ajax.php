<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:31 pm */ ?>
<?php 
 
 

?>
<form id="channel_add" method="post" action="#" onsubmit="$(this).ajaxCall('videochannel.channel.saveChannel','id=<?php echo $this->_aVars['aForms']['channel_id']; ?>'); $('#img_action').show(); $('.btn_submit').hide(); return false;">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
<div class="channel_edit_row <?php if (phpfox ::isMobile()): ?>mobile-add-channel<?php endif; ?>">
	<!-- Channel Information -->
	<div id="channel_info" <?php if ($this->_aVars['act'] == "yes"): ?> style="display: none" <?php endif; ?> >
		<div class="channel_edit_holder">
			<div class="t_center">
<?php if (! empty ( $this->_aVars['aForms']['img'] )): ?>
				<img width="120" height="90" class="js_mp_fix_width photo_holder" alt="<?php echo $this->_aVars['aForms']['title']; ?>" src="<?php echo $this->_aVars['aForms']['img']; ?>"/>
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'noimage/item.png')); ?>
<?php endif; ?>
			</div>
			
<?php if (( $this->_aVars['sModule'] != 'pages' )): ?>
			<div class="p_4">
<?php if (Phpfox ::isModule('privacy') && Phpfox ::getUserParam('videochannel.can_set_allow_list_on_videos')): ?>
				<div class="table">
					<div class="table_left">
<?php echo Phpfox::getPhrase('videochannel.privacy'); ?>:
					</div>
					<div class="table_right">
<?php Phpfox::getBlock('privacy.form', array('privacy_name' => 'privacy','privacy_info' => 'videochannel.control_who_can_view_this_channel','privacy_no_custom' => true)); ?>
					</div>
				</div>
<?php endif; ?>
				
<?php if (Phpfox ::isModule('comment') && Phpfox ::isModule('privacy') && Phpfox ::getUserParam('videochannel.can_control_comments_on_videos')): ?>
				<div class="table">
					<div class="table_left">
<?php echo Phpfox::getPhrase('videochannel.comment_privacy'); ?>:
					</div>
					<div class="table_right">										
<?php Phpfox::getBlock('privacy.form', array('privacy_name' => 'privacy_comment','privacy_info' => 'videochannel.control_who_can_comment_all_videos_on_this_channel','privacy_no_custom' => true)); ?>
						
					</div>			
				</div>
<?php endif; ?>
			</div>
<?php endif; ?>
		</div>
		
		<div class="channel_edit_info">
			<div><input type="hidden" name="val[site_id]" value="<?php echo $this->_aVars['aForms']['site_id']; ?>" /></div>
			<div><input type="hidden" name="val[url]" value="<?php echo $this->_aVars['aForms']['url']; ?>" /></div>
			<div class="table">
				<div class="table_left">
<?php if (Phpfox::getParam('core.display_required')): ?><span class="required"><?php echo Phpfox::getParam('core.required_symbol'); ?></span><?php endif;  echo Phpfox::getPhrase('videochannel.title'); ?>:
				</div>
				<div class="table_right">
					<input type="text" value="<?php echo $this->_aVars['aForms']['title']; ?>" name="val[title]"/>
				</div>
			</div>
			<div class="table">
				<div class="table_left">
<?php if (Phpfox::getParam('core.display_required')): ?><span class="required"><?php echo Phpfox::getParam('core.required_symbol'); ?></span><?php endif;  echo Phpfox::getPhrase('videochannel.category'); ?>:
				</div>
				<div class="table_right">				
<?php echo $this->_aVars['aForms']['aCategories']; ?>
				</div>
			</div>
			<div class="table">
				<div class="table_left">
<?php echo Phpfox::getPhrase('videochannel.summary'); ?>:
				</div>
				<div class="table_right">
					<textarea cols="35" rows="4" name="val[description]">
<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aForms']['summary']); ?>
					</textarea>
					
				</div>
			</div>
		</div>
	</div>
	<!-- End Channel Information -->
	<div class="clear"></div>	
	<!-- Videos list -->	
	<div id="video_list_action" class="brd_bottom clear">
		<h1 style="float: left"><?php echo Phpfox::getPhrase('videochannel.videos_list'); ?></h1>
<?php if ($this->_aVars['act'] != 'no'): ?>
			<a href="javascript:void(0);" class="selectall" onclick="selectAllVideo(this); return false;"><?php echo Phpfox::getPhrase('core.select_all'); ?></a>
			<a href="javascript:void(0);" class="unselectall" onclick="selectAllVideo(this); return false;" style="display: none;" ><?php echo Phpfox::getPhrase('core.un_select_all'); ?></a>
<?php endif; ?>
	</div>
	<div class="table" id="channel_video_list">
<?php if ($this->_aVars['act'] == 'no'): ?>
			<script type="text/javascript"> activeId = 0; </script>
			<?php
						Phpfox::getLib('template')->getBuiltFile('videochannel.block.channel.videolist');						
						?> 	
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif')); ?>
<?php endif; ?>
	</div>
	<!-- End Videos list -->
</div>
<div class="clear"></div>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif','id' => 'img_action','style' => 'display: none'));  if (isset ( $this->_aVars['sShowCategory'] )): ?>
<?php if ($this->_aVars['act'] == 'yes'): ?>
		<script>loadVideoList("<?php echo $this->_aVars['aForms']['url_encode']; ?>");</script>
<?php endif; ?>
	<input id='js_channel_btn_update' class="button btn_submit" type="submit" name="val[action]" value="<?php echo Phpfox::getPhrase('core.update'); ?>"/>
<?php if ($this->_aVars['act'] == 'no'): ?>
<?php if (isset ( $this->_aVars['aVideos'] ) && count ( $this->_aVars['aVideos'] ) && ( Phpfox ::getUserParam('videochannel.can_delete_own_video') || Phpfox ::getUserParam('videochannel.can_delete_other_video'))): ?>
		<input id='js_channel_btn_deleteall' class="button btn_submit" type="button" value="<?php echo Phpfox::getPhrase('videochannel.delete_all'); ?>" onclick="if(confirm('<?php echo Phpfox::getPhrase('videochannel.delete_all_videos_belong_to_this_channel'); ?>')) deleteAllVideos(<?php echo $this->_aVars['aForms']['channel_id']; ?>); return false;" />
<?php endif; ?>
<?php endif; ?>
<?php echo $this->_aVars['sShowCategory'];  else: ?>
	<input id='js_channel_btn_add' class="button btn_submit" type="submit" name="val[action]" value="<?php echo Phpfox::getPhrase('core.add'); ?>"/>
	<script>loadVideoList("<?php echo $this->_aVars['aForms']['url_encode']; ?>");</script>
<?php endif; ?>

        <div><input type="hidden" name="val[callback_module]" value="<?php echo $this->_aVars['sModule']; ?>" /></div>

        <div><input type="hidden" name="val[callback_item_id]" value="<?php echo $this->_aVars['iItem']; ?>" /></div>
		<div><input type="hidden" name="iIndex" value="<?php echo $this->_aVars['iIndex']; ?>" /></div>


</form>

