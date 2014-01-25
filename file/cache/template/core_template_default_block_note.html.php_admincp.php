<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: January 25, 2014, 12:32 am */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: block.html.php 3325 2011-10-20 08:33:09Z Miguel_Espinoza $
 */
 
 

 if (isset ( $this->_aVars['sHeader'] ) && ( ! PHPFOX_IS_AJAX || isset ( $this->_aVars['bPassOverAjaxCall'] ) )): ?>
<div class="block<?php if (( defined ( 'PHPFOX_IN_DESIGN_MODE' ) && PHPFOX_IN_DESIGN_MODE ) || ( Phpfox ::getService('theme')->isInDnDMode())): ?> js_sortable<?php endif; ?>"<?php if (isset ( $this->_aVars['sBlockBorderJsId'] )): ?> id="js_block_border_<?php echo $this->_aVars['sBlockBorderJsId']; ?>"<?php endif;  if (defined ( 'PHPFOX_IN_DESIGN_MODE' ) && Phpfox ::getLib('module')->blockIsHidden('js_block_border_' . $this->_aVars['sBlockBorderJsId'] . '' )): ?> style="display:none;"<?php endif; ?>>
<?php if (! empty ( $this->_aVars['sHeader'] )): ?>
		<div class="title js_sortable_header">		
<?php if (isset ( $this->_aVars['sBlockTitleBar'] )): ?>
<?php echo $this->_aVars['sBlockTitleBar']; ?>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aEditBar'] )): ?>
			<div class="js_edit_header_bar">
				<a href="#" title="<?php echo Phpfox::getPhrase('admincp.edit_this_block'); ?>" onclick="$.ajaxCall('<?php echo $this->_aVars['aEditBar']['ajax_call']; ?>', 'block_id=<?php echo $this->_aVars['sBlockBorderJsId'];  if (isset ( $this->_aVars['aEditBar']['params'] )):  echo $this->_aVars['aEditBar']['params'];  endif; ?>'); return false;"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_edit.png','alt' => '','class' => 'v_middle')); ?></a>				
			</div>
<?php endif; ?>
<?php if (isset ( $this->_aVars['sDeleteBlock'] )): ?>
			<div class="js_edit_header_bar js_edit_header_hover" style="display:none;">
				<a href="#" onclick="if (confirm('Are you sure?')) { $(this).parents('.block:first').remove(); $.ajaxCall('core.hideBlock', 'type_id=<?php echo $this->_aVars['sDeleteBlock']; ?>&amp;block_id=' + $(this).parents('.block:first').attr('id')); } return false;" title="<?php echo Phpfox::getPhrase('admincp.remove_this_block'); ?>">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_delete.png','alt' => '','class' => 'v_middle')); ?>
				</a>
			</div>
<?php endif; ?>
<?php echo $this->_aVars['sHeader']; ?>
		</div>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aEditBar'] )): ?>
	<div id="js_edit_block_<?php echo $this->_aVars['sBlockBorderJsId']; ?>" class="edit_bar" style="display:none;"></div>
<?php endif; ?>
	<div class="content"<?php if (isset ( $this->_aVars['sBlockJsId'] )): ?> id="js_block_content_<?php echo $this->_aVars['sBlockJsId']; ?>"<?php endif; ?>>
<?php endif; ?>
		<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: note.html.php 2826 2011-08-11 19:41:03Z Raymond_Benc $
 */
 
 

?>
<div class="t_center">
	<div style="position:absolute; right:0; margin-right:20px; margin-top:2px; display:none;" id="js_save_note">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/small.gif')); ?>
	</div>
	<textarea id="js_admincp_note" name="admincp_note" cols="60" rows="8" style="width:98%;" onfocus="$('#js_share_user_status').show();"><?php echo $this->_aVars['sAdminNote']; ?></textarea>
	<div class="p_4 t_right" id="js_share_user_status" style="display:none;">
		<input type="button" value="<?php echo Phpfox::getPhrase('admincp.save'); ?>" class="button" onclick="$('#js_share_user_status').hide(); $('#js_save_note').show(); $('#js_admincp_note').ajaxCall('core.admincp.updateNote'); return false;" />
		<input type="button" name="null" value="<?php echo Phpfox::getPhrase('admincp.cancel'); ?>" onclick="$('#js_share_user_status').hide(); return false;" class="button" />
	</div>	
</div>
<?php if (isset ( $this->_aVars['sHeader'] ) && ! PHPFOX_IS_AJAX): ?>
	</div>
<?php if (isset ( $this->_aVars['aFooter'] ) && count ( $this->_aVars['aFooter'] )): ?>
	<div class="bottom">
	<ul>
<?php if (count((array)$this->_aVars['aFooter'])):  $this->_aPhpfoxVars['iteration']['block'] = 0;  foreach ((array) $this->_aVars['aFooter'] as $this->_aVars['sPhrase'] => $this->_aVars['sLink']):  $this->_aPhpfoxVars['iteration']['block']++; ?>

			<li id="js_block_bottom_<?php echo $this->_aPhpfoxVars['iteration']['block']; ?>"<?php if ($this->_aPhpfoxVars['iteration']['block'] == 1): ?> class="first"<?php endif; ?>><a href="<?php echo $this->_aVars['sLink']; ?>" id="js_block_bottom_link_<?php echo $this->_aPhpfoxVars['iteration']['block']; ?>"><?php echo $this->_aVars['sPhrase']; ?></a></li>
<?php endforeach; endif; ?>
	</ul>
	</div>
<?php endif; ?>
</div>
<?php unset($this->_aVars['sHeader'], $this->_aVars['sModule'], $this->_aVars['sComponent'], $this->_aVars['aFooter'], $this->_aVars['sBlockBorderJsId'], $this->_aVars['bBlockDisableSort'], $this->_aVars['bBlockCanMove'], $this->_aVars['aEditBar'], $this->_aVars['sDeleteBlock'], $this->_aVars['sBlockTitleBar']);  endif; ?>
