<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:33 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Feed
 * @version 		$Id: timeline.html.php 5458 2013-02-28 14:54:14Z Miguel_Espinoza $
 */
 
 

?>
<div class="timeline_holder js_parent_feed_entry" id="js_item_feed_<?php echo $this->_aVars['aFeed']['feed_id']; ?>">
	
<?php if (! Phpfox ::isMobile() && ( ( defined ( 'PHPFOX_FEED_CAN_DELETE' ) ) || ( Phpfox ::getUserParam('feed.can_delete_own_feed') && $this->_aVars['aFeed']['user_id'] == Phpfox ::getUserId()) || Phpfox ::getUserParam('feed.can_delete_other_feeds'))): ?>
			<div class="feed_delete_link"><a href="#" class="action_delete js_hover_title" onclick="$.ajaxCall('feed.delete', 'id=<?php echo $this->_aVars['aFeed']['feed_id'];  if (isset ( $this->_aVars['aFeedCallback']['module'] )): ?>&amp;module=<?php echo $this->_aVars['aFeedCallback']['module']; ?>&amp;item=<?php echo $this->_aVars['aFeedCallback']['item_id'];  endif; ?>', 'GET'); return false;"><span class="js_hover_info"><?php echo Phpfox::getPhrase('feed.delete_this_feed'); ?></span></a></div>
<?php endif; ?>
	
	<div>
		<div style="float:left;">
<?php if (! isset ( $this->_aVars['aFeed']['feed_mini'] )): ?>
<?php if (isset ( $this->_aVars['aFeed']['is_custom_app'] ) && $this->_aVars['aFeed']['is_custom_app'] && ( ( isset ( $this->_aVars['aFeed']['view_id'] ) && $this->_aVars['aFeed']['view_id'] == 7 ) || ( isset ( $this->_aVars['aFeed']['gender'] ) && $this->_aVars['aFeed']['gender'] < 1 ) )): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('server_id' => 0,'path' => 'app.url_image','file' => $this->_aVars['aFeed']['app_image_path'],'suffix' => '_square','max_width' => 32,'max_height' => 32)); ?>
<?php else: ?>
<?php if (isset ( $this->_aVars['aFeed']['user_name'] ) && ! empty ( $this->_aVars['aFeed']['user_name'] )): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aFeed'],'suffix' => '_50_square','max_width' => 32,'max_height' => 32)); ?>
<?php else: ?>
<?php if (! empty ( $this->_aVars['aFeed']['parent_user_name'] )): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aFeed'],'suffix' => '_50_square','max_width' => 32,'max_height' => 32,'href' => $this->_aVars['aFeed']['parent_user_name'])); ?>
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aFeed'],'suffix' => '_50_square','max_width' => 32,'max_height' => 32,'href' => '')); ?>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
		</div>
		<div style="margin-left:36px; overflow:hidden; width:85%;" class="timeline_name_and_date_wrapper">
<?php echo '<span class="user_profile_link_span" id="js_user_name_link_' . $this->_aVars['aFeed']['user_name'] . '"><a href="' . Phpfox::getLib('phpfox.url')->makeUrl('profile', array($this->_aVars['aFeed']['user_name'], ((empty($this->_aVars['aFeed']['user_name']) && isset($this->_aVars['aFeed']['profile_page_id'])) ? $this->_aVars['aFeed']['profile_page_id'] : null))) . '">' . Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getService('user')->getCurrentName($this->_aVars['aFeed']['user_id'], $this->_aVars['aFeed']['full_name']), 25, '...') . '</a></span>';  if ($this->_aVars['aFeed']['parent_feed_id'] > 0): ?> <?php echo Phpfox::getPhrase('feed.shared');  else:  if (isset ( $this->_aVars['aFeed']['parent_user'] )): ?> <?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'layout/arrow.png','class' => 'v_middle')); ?> <?php echo '<span class="user_profile_link_span" id="js_user_name_link_' . $this->_aVars['aFeed']['parent_user']['parent_user_name'] . '"><a href="' . Phpfox::getLib('phpfox.url')->makeUrl('profile', array($this->_aVars['aFeed']['parent_user']['parent_user_name'], ((empty($this->_aVars['aFeed']['parent_user']['parent_user_name']) && isset($this->_aVars['aFeed']['parent_user']['parent_profile_page_id'])) ? $this->_aVars['aFeed']['parent_user']['parent_profile_page_id'] : null))) . '">' . Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getService('user')->getCurrentName($this->_aVars['aFeed']['parent_user']['parent_user_id'], $this->_aVars['aFeed']['parent_user']['parent_full_name']), 25, '...') . '</a></span>'; ?> <?php endif;  if (! empty ( $this->_aVars['aFeed']['feed_info'] )): ?> <?php echo $this->_aVars['aFeed']['feed_info'];  endif;  endif; ?>
			<div class="extra_info timeline_date_1">
<?php echo Phpfox::getLib('date')->convertTime($this->_aVars['aFeed']['time_stamp'], 'feed.feed_display_time_stamp'); ?>
<?php if ($this->_aVars['aFeed']['privacy'] > 0 && ( $this->_aVars['aFeed']['user_id'] == Phpfox ::getUserId() || Phpfox ::getUserParam('core.can_view_private_items'))): ?>
				<div class="js_hover_title"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'layout/privacy_icon.png','alt' => $this->_aVars['aFeed']['privacy'])); ?><span class="js_hover_info"><?php if (Phpfox ::isModule('privacy')):  echo Phpfox::getService('privacy')->getPhrase($this->_aVars['aFeed']['privacy']);  else: ?>Privacy <?php echo $this->_aVars['aFeed']['privacy']; ?> <?php endif; ?></span></div>
<?php endif; ?>
			</div>
		</div>
		
		<div class="clear"></div>
				
	<?php
						Phpfox::getLib('template')->getBuiltFile('feed.block.content');						
						?>
		
	</div>		
</div>
<?php if (! PHPFOX_IS_AJAX && is_int ( $this->_aPhpfoxVars['iteration']['iFeed'] / 2 )): ?>
<div class="clear"></div>
<?php endif; ?>
