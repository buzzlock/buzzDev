<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:33 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: menu.html.php 4871 2012-10-10 05:51:05Z Raymond_Benc $
 */
 
 

?>
<div class="pages_view_sub_menu" id="pages_menu">
	<ul>
<?php if ($this->_aVars['aPage']['is_admin']): ?>
			<li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('pages.add', array('id' => $this->_aVars['aPage']['page_id'])); ?>"><?php echo Phpfox::getPhrase('pages.edit_page'); ?></a></li>		
<?php endif; ?>
<?php Phpfox::getBlock('share.link', array('type' => 'pages','url' => $this->_aVars['aPage']['link'],'title' => $this->_aVars['aPage']['title'],'display' => 'menu','sharefeedid' => $this->_aVars['aPage']['page_id'],'sharemodule' => 'pages')); ?>
<?php if (! Phpfox ::getUserBy('profile_page_id')): ?>
			<li id="js_add_pages_unlike" <?php if (! $this->_aVars['aPage']['is_liked']): ?> style="display:none;"<?php endif; ?>><a href="#" onclick="$(this).parent().hide(); $('#pages_like_join_position').show(); $.ajaxCall('like.delete', 'type_id=pages&amp;item_id=<?php echo $this->_aVars['aPage']['page_id']; ?>'); return false;"><?php if ($this->_aVars['aPage']['page_type'] == '1'):  echo Phpfox::getPhrase('pages.remove_membership');  else:  echo Phpfox::getPhrase('pages.unlike');  endif; ?></a></li>
<?php endif; ?>
<?php if (! $this->_aVars['aPage']['is_admin'] && Phpfox ::getUserParam('pages.can_claim_page') && empty ( $this->_aVars['aPage']['claim_id'] )): ?>
			<li>
				<a href="#?call=contact.showQuickContact&amp;height=600&amp;width=600&amp;page_id=<?php echo $this->_aVars['aPage']['page_id']; ?>" class="inlinePopup js_claim_page" title="<?php echo Phpfox::getPhrase('pages.claim_page'); ?>">
<?php echo Phpfox::getPhrase('pages.claim_page'); ?>
				</a>
			</li>
<?php endif; ?>
	</ul>
</div>
