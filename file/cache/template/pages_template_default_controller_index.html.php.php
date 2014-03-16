<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:32 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: index.html.php 3990 2012-03-09 15:28:08Z Raymond_Benc $
 */
 
 

 if (count ( $this->_aVars['aPages'] )):  if ($this->_aVars['sView'] == 'my' && Phpfox ::getUserBy('profile_page_id')): ?>
<div class="message">
<?php echo Phpfox::getPhrase('pages.note_that_pages_displayed_here_are_pages_created_by_the_page', array('global_full_name' => Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['sGlobalUserFullName']),'profile_full_name' => Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aGlobalProfilePageLogin']['full_name']))); ?>
</div>
<?php endif;  if (count((array)$this->_aVars['aPages'])):  $this->_aPhpfoxVars['iteration']['pages'] = 0;  foreach ((array) $this->_aVars['aPages'] as $this->_aVars['aPage']):  $this->_aPhpfoxVars['iteration']['pages']++; ?>

<div id="js_pages_<?php echo $this->_aVars['aPage']['page_id']; ?>" class="js_pages_parent <?php if (is_int ( $this->_aPhpfoxVars['iteration']['pages'] / 2 )): ?>row1<?php else: ?>row2<?php endif;  if ($this->_aPhpfoxVars['iteration']['pages'] == 1 && ! PHPFOX_IS_AJAX): ?> row_first<?php endif; ?>">		
		<div class="row_title">	
			<div class="row_title_image">
				<a href="<?php echo $this->_aVars['aPage']['link']; ?>"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('server_id' => $this->_aVars['aPage']['profile_server_id'],'title' => $this->_aVars['aPage']['title'],'path' => 'core.url_user','file' => $this->_aVars['aPage']['profile_user_image'],'suffix' => '_50_square','max_width' => '50','max_height' => '50','is_page_image' => true)); ?></a>						
				
<?php if (Phpfox ::getUserParam('pages.can_moderate_pages') || $this->_aVars['aPage']['user_id'] == Phpfox ::getUserId()): ?>
				<div class="row_edit_bar_parent">
					<div class="row_edit_bar_holder">
						<ul>
							<?php
						Phpfox::getLib('template')->getBuiltFile('pages.block.link');						
						?>
						</ul>			
					</div>
					<div class="row_edit_bar">				
							<a href="#" class="row_edit_bar_action"><span><?php echo Phpfox::getPhrase('pages.actions'); ?></span></a>							
					</div>
				</div>				
<?php endif; ?>
				
<?php if (Phpfox ::getUserParam('pages.can_moderate_pages')): ?>
				<a href="#<?php echo $this->_aVars['aPage']['page_id']; ?>" class="moderate_link" rel="pages"><?php echo Phpfox::getPhrase('pages.moderate'); ?></a>
<?php endif; ?>
			</div>
			<div class="row_title_info">
				<a href="<?php echo $this->_aVars['aPage']['link']; ?>" class="link"><?php echo Phpfox::getLib('phpfox.parse.output')->split(Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aPage']['title']), 55, '...'), 40); ?></a>			
				<div class="extra_info">
					<ul class="extra_info_middot"><li><?php echo Phpfox::getLib('locale')->convert($this->_aVars['aPage']['category_name']); ?></li><?php if ($this->_aVars['aPage']['page_type'] == '1'): ?><li><span>&middot;</span></li><li><?php if ($this->_aVars['aPage']['total_like'] > 1):  echo Phpfox::getPhrase('pages.total_members', array('total' => number_format($this->_aVars['aPage']['total_like'])));  elseif ($this->_aVars['aPage']['total_like'] == 1):  echo Phpfox::getPhrase('pages.1_member');  else:  echo Phpfox::getPhrase('pages.no_members');  endif; ?></li><?php endif; ?></ul>
				</div>
<?php if ($this->_aVars['aPage']['page_type'] == '0'): ?>
<?php Phpfox::getBlock('feed.comment', array('aFeed' => $this->_aVars['aPage']['aFeed'])); ?>
<?php endif; ?>
			</div>					
		</div>	
</div>
<?php endforeach; endif;  if (Phpfox ::getUserParam('pages.can_moderate_pages')):  Phpfox::getBlock('core.moderation');  endif; ?>

<?php if (!isset($this->_aVars['aPager'])): Phpfox::getLib('pager')->set(array('page' => Phpfox::getLib('request')->getInt('page'), 'size' => Phpfox::getLib('search')->getDisplay(), 'count' => Phpfox::getLib('search')->getCount())); endif;  $this->getLayout('pager');  else: ?>
<div class="extra_info">
<?php echo Phpfox::getPhrase('pages.no_pages_found'); ?>
</div>
<?php endif; ?>
