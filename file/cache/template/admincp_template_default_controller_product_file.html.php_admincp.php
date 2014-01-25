<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: January 25, 2014, 8:22 pm */ ?>
<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Admincp
 * @version 		$Id: file.html.php 1149 2009-10-07 10:14:46Z Raymond_Benc $
 */



?>
	<div class="table_header">
		Manual Install
	</div>
<?php if (count ( $this->_aVars['aNewProducts'] )): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th>Product</th>
			<th>Version</th>
			<th style="width:100px;">Action</th>
		</tr>
<?php if (count((array)$this->_aVars['aNewProducts'])):  foreach ((array) $this->_aVars['aNewProducts'] as $this->_aVars['iKey'] => $this->_aVars['aProduct']): ?>
		<tr class="checkRow<?php if (is_int ( $this->_aVars['iKey'] / 2 )): ?> tr<?php else:  endif; ?>">
			<td>
<?php if (! empty ( $this->_aVars['aProduct']['url'] )): ?><a href="<?php echo $this->_aVars['aProduct']['url']; ?>" target="_blank"><?php endif;  echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aProduct']['title']);  if (! empty ( $this->_aVars['aProduct']['url'] )): ?></a><?php endif; ?>
<?php if (! empty ( $this->_aVars['aProduct']['description'] )): ?>
				<div class="extra_info">
<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aProduct']['description']); ?>
				</div>
<?php endif; ?>
			</td>
			<td class="t_center"><?php if (empty ( $this->_aVars['aProduct']['version'] )): ?>N/A<?php else:  echo $this->_aVars['aProduct']['version'];  endif; ?></td>			
			<td class="t_center">
				<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.product.file', array('install' => $this->_aVars['aProduct']['product_id'])); ?>" title="Click to install this product.">Install</a>
			</td>
		</tr>
<?php endforeach; endif; ?>
	</table>
<?php else: ?>
	<div class="table">
		<div class="message">
			Nothing new to install.
		</div>	
	</div>	
<?php endif; ?>
	<div class="table_clear"></div>
	<br />

