<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:33 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Profile
 * @version 		$Id: pic.html.php 4710 2012-09-21 08:59:25Z Raymond_Benc $
 * @description		This template is used to display the Like/Join link in Pages with Timeline.
 */
 
 


?>


<?php if (! Phpfox ::getUserBy('profile_page_id') && Phpfox ::isUser()): ?>
<?php if (isset ( $this->_aVars['aPage'] ) && $this->_aVars['aPage']['reg_method'] == '2' && ! isset ( $this->_aVars['aPage']['is_invited'] ) && $this->_aVars['aPage']['page_type'] == '1'): ?>
<?php else: ?>
<?php if (isset ( $this->_aVars['aPage'] ) && isset ( $this->_aVars['aPage']['is_reg'] ) && $this->_aVars['aPage']['is_reg']): ?>
<?php else: ?>
			
<?php if (isset ( $this->_aVars['aPage'] ) && ! isset ( $this->_aVars['aPage']['is_liked'] ) && $this->_aVars['aPage']['is_liked'] != true): ?>
<?php if (! isset ( $this->_aVars['aUser'] ) || ! isset ( $this->_aVars['aUser']['use_timeline'] )): ?><span id="pages_like_join_position"<?php if ($this->_aVars['aPage']['is_liked']): ?> style="display:none;"<?php endif; ?>> <?php endif; ?>
					<a href="#" id="pages_like_join" <?php if (isset ( $this->_aVars['aUser'] ) && isset ( $this->_aVars['aUser']['use_timeline'] ) && $this->_aVars['aUser']['use_timeline']): ?>style=""<?php endif; ?>onclick="$(this).parent().hide(); $('#js_add_pages_unlike').show(); <?php if ($this->_aVars['aPage']['page_type'] == '1' && $this->_aVars['aPage']['reg_method'] == '1'): ?> $.ajaxCall('pages.signup', 'page_id=<?php echo $this->_aVars['aPage']['page_id']; ?>'); <?php else: ?>$.ajaxCall('like.add', 'type_id=pages&amp;item_id=<?php echo $this->_aVars['aPage']['page_id']; ?>');<?php endif; ?> return false;">
<?php if ($this->_aVars['aPage']['page_type'] == '1'): ?>
<?php echo Phpfox::getPhrase('pages.join'); ?>
<?php else: ?>
<?php echo Phpfox::getPhrase('pages.like'); ?>
<?php endif; ?>
					</a>
<?php if (! isset ( $this->_aVars['aUser'] ) || ! isset ( $this->_aVars['aUser']['use_timeline'] )): ?></span><?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endif;  endif; ?>


