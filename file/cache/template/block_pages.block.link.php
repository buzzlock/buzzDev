<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:32 pm */ ?>
<li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('pages.add', array('id' => $this->_aVars['aPage']['page_id'])); ?>"><?php echo Phpfox::getPhrase('pages.manage'); ?></a></li>
<?php if (Phpfox ::getUserParam('pages.can_design_pages') && isset ( $this->_aVars['aPage']['is_admin'] ) && $this->_aVars['aPage']['is_admin']): ?>
	<li>
		<a href="<?php echo $this->_aVars['aPage']['link']; ?>designer/" class="no_ajax_link">
<?php echo Phpfox::getPhrase('pages.customize_design'); ?>
		</a>
	</li>
<?php endif;  if (Phpfox ::getUserParam('pages.can_moderate_pages') || $this->_aVars['aPage']['user_id'] == Phpfox ::getUserId()): ?>
	<li class="item_delete">
		<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('pages', array('delete' => $this->_aVars['aPage']['page_id'])); ?>" onclick="return confirm('<?php echo Phpfox::getPhrase('pages.are_you_sure'); ?>');" class="no_ajax_link">
<?php echo Phpfox::getPhrase('pages.delete'); ?>
		</a>
	</li>
<?php endif;  if (Phpfox ::getUserParam('pages.can_add_cover_photo_pages')): ?>
<li>
	<a href="#" onclick="$(this).parent().find('.cover_section_menu_drop:first').toggle(); event.cancelBubble = true; if (event.stopPropagation) event.stopPropagation();return false;">
<?php if (empty ( $this->_aVars['aPage']['cover_photo_id'] )): ?>
<?php echo Phpfox::getPhrase('user.add_a_cover'); ?>
<?php else: ?>
<?php echo Phpfox::getPhrase('user.change_cover'); ?>
<?php endif; ?>
	</a>
	<div class="cover_section_menu_drop" style="display: none;">
		<ul style="display:block">
			<li>
				<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('pages.'.$this->_aVars['aPage']['page_id']); ?>photo">
<?php echo Phpfox::getPhrase('user.choose_from_photos'); ?>
				</a>
			</li>
			<li>
				<a href="#" onclick="$(this).parent().find('.cover_section_menu_drop:first').hide(); $Core.box('profile.logo', 500, 'page_id=<?php echo $this->_aVars['aPage']['page_id']; ?>'); return false;">
<?php echo Phpfox::getPhrase('user.upload_photo'); ?>
				</a>
			</li>
<?php if (! empty ( $this->_aVars['aPage']['cover_photo_id'] )): ?>
				<li>
					<a href="<?php echo $this->_aVars['aPage']['link']; ?>coverupdate_1">
<?php echo Phpfox::getPhrase('user.reposition'); ?>
					</a>
				</li>
				<li>
					<a href="#" onclick="$(this).parent().find('.cover_section_menu_drop:first').hide(); $.ajaxCall('pages.removeLogo', 'page_id=<?php echo $this->_aVars['aPage']['page_id']; ?>'); return false;">
<?php echo Phpfox::getPhrase('user.remove'); ?>
					</a>
				</li>
<?php endif; ?>
		</ul>
	</div>
</li>
<?php endif; ?>
