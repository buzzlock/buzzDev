<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:33 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: controller.html.php 64 2009-01-19 15:05:54Z Raymond_Benc $
 */
 
 

?>

<?php if ($this->_aVars['aPage']['view_id'] == '1'): ?>
	<div class="message js_moderation_off" id="js_approve_message">
<?php echo Phpfox::getPhrase('pages.this_page_is_pending_an_admins_approval_before_it_can_be_displayed_publicly'); ?>
	</div>
<?php endif;  if (! Phpfox ::isMobile() && ( Phpfox ::getUserParam('pages.can_moderate_pages') || $this->_aVars['aPage']['is_admin'] )): ?>
	<div class="item_bar">
		<div class="item_bar_action_holder">
<?php if ($this->_aVars['aPage']['view_id'] == '1' && Phpfox ::getUserParam('pages.can_moderate_pages')): ?>
				<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif')); ?>
				</a>			
				<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('pages.approve', 'page_id=<?php echo $this->_aVars['aPage']['page_id']; ?>'); return false;">
<?php echo Phpfox::getPhrase('pages.approve'); ?>
				</a>
<?php endif; ?>
			<a href="#" class="item_bar_action">
				<span>
<?php echo Phpfox::getPhrase('pages.actions'); ?>
				</span>
			</a>		
			<ul>
				<?php
						Phpfox::getLib('template')->getBuiltFile('pages.block.link');						
						?>
			</ul>			
		</div>		
	</div>
<?php endif; ?>

<?php if ($this->_aVars['bCanViewPage']): ?>
<?php if (isset ( $this->_aVars['aWidget'] )): ?>
		<div class="item_view_content">
<?php echo Phpfox::getLib('phpfox.parse.output')->parse($this->_aVars['aWidget']['text']); ?>
		</div>
<?php elseif ($this->_aVars['sCurrentModule'] == 'info' && ! $this->_aVars['iViewCommentId']): ?>
		<div class="item_view_content">
<?php echo Phpfox::getLib('phpfox.parse.output')->parse($this->_aVars['aPage']['text']); ?>
		</div>
<?php elseif ($this->_aVars['sCurrentModule'] == 'pending'): ?>
<?php if (count ( $this->_aVars['aPendingUsers'] )): ?>
<?php if (count((array)$this->_aVars['aPendingUsers'])):  $this->_aPhpfoxVars['iteration']['pendingusers'] = 0;  foreach ((array) $this->_aVars['aPendingUsers'] as $this->_aVars['aPendingUser']):  $this->_aPhpfoxVars['iteration']['pendingusers']++; ?>

				<div id="js_pages_user_entry_<?php echo $this->_aVars['aPendingUser']['signup_id']; ?>" class="row1<?php if ($this->_aPhpfoxVars['iteration']['pendingusers'] == 1): ?> row_first<?php endif; ?>">
					<div class="go_left" style="width:50px;">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aPendingUser'],'suffix' => '_50_square','max_width' => '50','max_height' => '50')); ?>
						<a href="#<?php echo $this->_aVars['aPendingUser']['signup_id']; ?>" class="moderate_link" rel="pages"><?php echo Phpfox::getPhrase('pages.moderate'); ?></a>
					</div>
					<div style="margin-left:55px">
						<span class="row_title_link"><?php echo Phpfox::getLib('phpfox.parse.output')->shorten('<span class="user_profile_link_span" id="js_user_name_link_' . $this->_aVars['aPendingUser']['user_name'] . '"><a href="' . Phpfox::getLib('phpfox.url')->makeUrl('profile', array($this->_aVars['aPendingUser']['user_name'], ((empty($this->_aVars['aPendingUser']['user_name']) && isset($this->_aVars['aPendingUser']['profile_page_id'])) ? $this->_aVars['aPendingUser']['profile_page_id'] : null))) . '">' . Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getService('user')->getCurrentName($this->_aVars['aPendingUser']['user_id'], $this->_aVars['aPendingUser']['full_name']), Phpfox::getParam('user.maximum_length_for_full_name')) . '</a></span>', 50, '...'); ?></span>
					</div>
					<div class="clear"></div>
				</div>
<?php endforeach; endif; ?>
<?php Phpfox::getBlock('core.moderation'); ?>
<?php else: ?>
<?php endif; ?>
<?php else: ?>
<?php if ($this->_aVars['bHasPermToViewPageFeed']): ?>
			
<?php else: ?>
<?php echo Phpfox::getPhrase('pages.unable_to_view_this_section_due_to_privacy_settings'); ?>
<?php endif; ?>
<?php endif;  else: ?>
	<div class="message">
<?php if (isset ( $this->_aVars['aPage']['is_invited'] ) && $this->_aVars['aPage']['is_invited']): ?>
<?php echo Phpfox::getPhrase('pages.you_have_been_invited_to_join_this_community'); ?>
<?php else: ?>
<?php echo Phpfox::getPhrase('pages.due_to_privacy_settings_this_page_is_not_visible'); ?>
<?php if ($this->_aVars['aPage']['page_type'] == '1' && $this->_aVars['aPage']['reg_method'] == '2'): ?>
<?php echo Phpfox::getPhrase('pages.this_page_is_also_invite_only'); ?>
<?php endif; ?>
<?php endif; ?>
	</div>
<?php endif; ?>


