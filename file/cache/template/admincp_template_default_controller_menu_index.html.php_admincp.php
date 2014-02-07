<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 6, 2014, 9:55 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Admincp
 * @version 		$Id: index.html.php 2826 2011-08-11 19:41:03Z Raymond_Benc $
 */
 
 

?>
<form method="post" action="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.menu', array('parent' => $this->_aVars['iParentId'])); ?>">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
	<table cellpadding="0" cellspacing="0">
<?php if ($this->_aVars['iParentId'] === 0): ?>
	<tr>
		<td colspan="5" class="table_header">
<?php echo Phpfox::getPhrase('admincp.menu_block'); ?>
		</td>
	</tr>	
<?php if (count((array)$this->_aVars['aMenus'])):  foreach ((array) $this->_aVars['aMenus'] as $this->_aVars['sType'] => $this->_aVars['aMenusSub']): ?>
	<tr>
		<td colspan="5" class="table_header2">
<?php echo $this->_aVars['sType']; ?>
		</td>
	</tr>
	<tr>
		<th style="width:60px;"><?php echo Phpfox::getPhrase('admincp.order'); ?></th>
		<th><?php echo Phpfox::getPhrase('admincp.menu'); ?></th>
		<th><?php echo Phpfox::getPhrase('admincp.location'); ?></th>
		<th style="width:60px;"><?php echo Phpfox::getPhrase('admincp.active'); ?></th>
		<th><?php echo Phpfox::getPhrase('admincp.actions'); ?></th>
	</tr>	
<?php if (count((array)$this->_aVars['aMenusSub'])):  foreach ((array) $this->_aVars['aMenusSub'] as $this->_aVars['iKey'] => $this->_aVars['aMenu']): ?>
	<?php
						Phpfox::getLib('template')->getBuiltFile('admincp.block.menu.entry');						
						?>
<?php endforeach; endif; ?>
<?php endforeach; endif; ?>
<?php endif; ?>
	<tr>
		<td colspan="5" class="table_header">
<?php if ($this->_aVars['iParentId'] === 0): ?>
<?php echo Phpfox::getPhrase('admincp.modules'); ?>
<?php else: ?>
			Children: <?php echo Phpfox::getPhrase(''.$this->_aVars['aParentMenu']['module_id'].'.'.$this->_aVars['aParentMenu']['var_name'].''); ?>
<?php endif; ?>
		</td>
	</tr>
<?php if (count((array)$this->_aVars['aModules'])):  foreach ((array) $this->_aVars['aModules'] as $this->_aVars['sModule'] => $this->_aVars['aMenusSub']): ?>
<?php if ($this->_aVars['iParentId'] === 0): ?>
	<tr>
		<td colspan="5" class="table_header2">
<?php echo $this->_aVars['sModule']; ?>
		</td>
	</tr>
<?php endif; ?>
	<tr>
		<th style="width:60px;"><?php echo Phpfox::getPhrase('admincp.order'); ?></th>
		<th><?php echo Phpfox::getPhrase('admincp.menu'); ?></th>
		<th><?php echo Phpfox::getPhrase('admincp.location'); ?></th>
		<th style="width:60px;"><?php echo Phpfox::getPhrase('admincp.active'); ?></th>
		<th><?php echo Phpfox::getPhrase('admincp.actions'); ?></th>
	</tr>	
<?php if (count((array)$this->_aVars['aMenusSub'])):  foreach ((array) $this->_aVars['aMenusSub'] as $this->_aVars['iKey'] => $this->_aVars['aMenu']): ?>
	<?php
						Phpfox::getLib('template')->getBuiltFile('admincp.block.menu.entry');						
						?>
<?php endforeach; endif; ?>
<?php endforeach; endif; ?>
	</table>
	<div class="table_bottom table_hover_action">
		<input type="submit" value="<?php echo Phpfox::getPhrase('admincp.update'); ?>" class="button" />
	</div>

</form>


