<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 6, 2014, 9:55 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Admincp
 * @version 		$Id: entry.html.php 1300 2009-12-07 00:39:10Z Raymond_Benc $
 */
 
 

?><tr class="checkRow<?php if (is_int ( $this->_aVars['iKey'] / 2 )): ?> tr<?php else:  endif; ?>">
	<td class="t_center"><input type="text" name="val[<?php echo $this->_aVars['aMenu']['menu_id']; ?>][ordering]" value="<?php echo $this->_aVars['aMenu']['ordering']; ?>" size="3" class="t_center" /></td>
	<td><?php echo $this->_aVars['aMenu']['name']; ?></td>
	<td><?php echo $this->_aVars['aMenu']['url_value']; ?></td>
	<td class="t_center"><input type="checkbox" name="val[<?php echo $this->_aVars['aMenu']['menu_id']; ?>][is_active]" value="1" <?php if ($this->_aVars['aMenu']['is_active']): ?>checked="checked" <?php endif; ?>/></td>
	<td>
		<select name="action" class="goJump" style="width:140px;">
			<option value=""><?php echo Phpfox::getPhrase('language.select_action'); ?></option>		
			<option value="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.menu.add.', array('id' => $this->_aVars['aMenu']['menu_id'])); ?>"><?php echo Phpfox::getPhrase('admincp.edit'); ?></option>
<?php if ($this->_aVars['aMenu']['total_children'] > 0): ?>
			<option value="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.menu', array('parent' => $this->_aVars['aMenu']['menu_id'])); ?>"><?php echo Phpfox::getPhrase('admincp.manage_children_total_children', array('total_children' => $this->_aVars['aMenu']['total_children'])); ?></option>
<?php endif; ?>
			<option value="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.menu.', array('delete' => $this->_aVars['aMenu']['menu_id'])); ?>" style="color:red;"><?php echo Phpfox::getPhrase('admincp.delete'); ?></option>
		</select>
	</td>
</tr>
