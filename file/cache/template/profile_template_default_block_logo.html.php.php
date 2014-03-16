<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:32 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Profile
 * @version 		$Id: logo.html.php 4914 2012-10-22 07:52:17Z Raymond_Benc $
 */
 
 

?>

<?php if ($this->_aVars['bRefreshPhoto']): ?>
	<div class="cover_photo_link">
<?php else: ?>
	<a href="<?php echo Phpfox::permalink('photo', $this->_aVars['aCoverPhoto']['photo_id'], $this->_aVars['aCoverPhoto']['title'], false, null, (array) array (
)); ?>userid_<?php echo $this->_aVars['aCoverPhoto']['user_id']; ?>/" class="thickbox photo_holder_image cover_photo_link" rel="<?php echo $this->_aVars['aCoverPhoto']['photo_id']; ?>">
<?php endif; ?>

<?php if (isset ( $this->_aVars['bNoPrefix'] ) && $this->_aVars['bNoPrefix'] == true): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('id' => 'js_photo_cover_position','server_id' => $this->_aVars['aCoverPhoto']['server_id'],'path' => 'photo.url_photo','file' => $this->_aVars['aCoverPhoto']['destination'],'suffix' => '','width' => 980,'title' => $this->_aVars['aCoverPhoto']['title'],'style' => 'position:absolute; top:'.$this->_aVars['sLogoPosition'].'px; left:0px;','time_stamp' => true));  else: ?>
<?php if ($this->_aVars['bRefreshPhoto'] || $this->_aVars['bNewCoverPhoto']): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('id' => 'js_photo_cover_position','server_id' => $this->_aVars['aCoverPhoto']['server_id'],'path' => 'photo.url_photo','file' => $this->_aVars['aCoverPhoto']['destination'],'suffix' => '_1024','width' => 980,'title' => $this->_aVars['aCoverPhoto']['title'],'style' => 'position:absolute; top:'.$this->_aVars['sLogoPosition'].'px; left:0px;','time_stamp' => true)); ?>
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('id' => 'js_photo_cover_position','server_id' => $this->_aVars['aCoverPhoto']['server_id'],'path' => 'photo.url_photo','file' => $this->_aVars['aCoverPhoto']['destination'],'suffix' => '_1024','width' => 980,'title' => $this->_aVars['aCoverPhoto']['title'],'style' => 'position:absolute; top:'.$this->_aVars['sLogoPosition'].'px; left:0px;')); ?>
<?php endif;  endif;  if ($this->_aVars['bRefreshPhoto']): ?>
	</div>
<?php else: ?>
	</a>
<?php endif;  if ($this->_aVars['bRefreshPhoto']): ?>
	<?php echo '
		<style type="text/css">
			#js_photo_cover_position
			{
				cursor:move;
			}
		</style>
		<script type="text/javascript">
		var sCoverPosition = \'0\';	
		$Behavior.positionCoverPhoto = function(){			
			$(\'#js_photo_cover_position\').draggable(\'destroy\').draggable({
				axis: \'y\',
				cursor: \'move\',	
				stop: function(event, ui){
					var sStop = $(this).position();
					sCoverPosition = sStop.top;			
				}
			});	
		}
		</script>
	'; ?>

<?php endif;  if ($this->_aVars['bRefreshPhoto']): ?>
	<div class="cover_photo_change">
<?php echo Phpfox::getPhrase('user.drag_to_reposition_cover'); ?>
		<form method="post" action="#">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
			<ul class="table_clear_button">
				<li id="js_cover_update_loader_upload" style="display:none;"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif','class' => 'v_middle')); ?></li>		
				<li class="js_cover_update_li"><div><input type="button" class="button button_off" value="<?php echo Phpfox::getPhrase('user.cancel_cover_photo'); ?>" name="cancel" onclick="window.location.href='<?php if ($this->_aVars['bIsPages']):  echo $this->_aVars['sPagesLink'];  else:  echo Phpfox::getLib('phpfox.url')->makeUrl('profile');  endif; ?>';" /></div></li>
				<li class="js_cover_update_li"><div><input type="button" class="button" value="<?php echo Phpfox::getPhrase('user.save_changes'); ?>" name="save" onclick="$('.js_cover_update_li').hide(); $('#js_cover_update_loader_upload').show(); $.ajaxCall('<?php echo $this->_aVars['sAjaxModule']; ?>.updateCoverPosition', 'position=' + sCoverPosition<?php if ($this->_aVars['sAjaxModule'] == 'pages'): ?> + '&page_id=<?php echo $this->_aVars['aPage']['page_id']; ?>'<?php endif; ?>); return false;" /></div></li>
			</ul>
			<div class="clear"></div>
		
</form>

	</div>
<?php endif; ?>
