<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:36 pm */ ?>
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
 * @version 		$Id: active-admin.html.php 2826 2011-08-11 19:41:03Z Raymond_Benc $
 */
 
 

 if (count((array)$this->_aVars['aActiveAdmins'])):  $this->_aPhpfoxVars['iteration']['admins'] = 0;  foreach ((array) $this->_aVars['aActiveAdmins'] as $this->_aVars['aActiveAdmin']):  $this->_aPhpfoxVars['iteration']['admins']++; ?>

	<div class="<?php if (is_int ( $this->_aPhpfoxVars['iteration']['admins'] / 2 )): ?>row1<?php else: ?>row2<?php endif;  if ($this->_aPhpfoxVars['iteration']['admins'] == 1): ?> row_first<?php endif; ?>">
		<div style="position:absolute; left:0px;" class="t_center">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aActiveAdmin'],'suffix' => '_50_square','max_width' => 50,'max_height' => 50)); ?>
		</div>
		<div style="margin-left:52px;">
<?php echo '<span class="user_profile_link_span" id="js_user_name_link_' . $this->_aVars['aActiveAdmin']['user_name'] . '"><a href="' . Phpfox::getLib('phpfox.url')->makeUrl('profile', array($this->_aVars['aActiveAdmin']['user_name'], ((empty($this->_aVars['aActiveAdmin']['user_name']) && isset($this->_aVars['aActiveAdmin']['profile_page_id'])) ? $this->_aVars['aActiveAdmin']['profile_page_id'] : null))) . '">' . Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getService('user')->getCurrentName($this->_aVars['aActiveAdmin']['user_id'], $this->_aVars['aActiveAdmin']['full_name']), Phpfox::getParam('user.maximum_length_for_full_name')) . '</a></span>'; ?>
			<div class="extra_info">
<?php echo Phpfox::getTime(Phpfox::getParam('core.extended_global_time_stamp'), $this->_aVars['aActiveAdmin']['in_admincp']); ?><br />
<?php echo $this->_aVars['aActiveAdmin']['location']; ?><br />
<?php echo $this->_aVars['aActiveAdmin']['ip_address']; ?>
			</div>
		</div>		
	</div>
<?php endforeach; endif; ?>

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
